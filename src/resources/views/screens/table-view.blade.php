@extends('adminlte::page')
@php
    /**@var \Jeanderson\modeladministrator\Models\ModelConfig $modelConfig*/
    /**@var \Jeanderson\modeladministrator\Models\Element $element*/
    /**@var \Jeanderson\modeladministrator\Models\CustomModel[] $models*/
    /**@var \Illuminate\Http\Request $request*/
    if(is_null($custom_query)){
        $custom_query = null;
    }
    $createHtml = new \Jeanderson\modeladministrator\Utils\CreateHTML($modelConfig);
    $models = \Jeanderson\modeladministrator\Utils\Filter::filter_function($modelConfig,$request,$custom_query);
    $route = $createHtml->getRoutesForType("show");
    $route_search = $createHtml->getRoutesForType("searchinput");
    $route_edit = $createHtml->getRoutesForType("edit");
    $colspan = $createHtml->getElements()->count();
@endphp
@section("css")
    @includeIf("modeladmin::css.AppCss")
    @include("modeladmin::css.icheck-bootstrap")
@endsection
@section("js")
    {{--    <script src="https://rawgit.com/RickStrahl/jquery-resizable/master/src/jquery-resizable.js"></script>--}}
    <script>
        let count_expand_table = 0;

        function expand_table_tr(tr, id) {
            if ($(tr).is(":visible")) {
                count_expand_table--;
                $(tr).hide();
                focusInDiv(false);
            } else {
                $(tr).show();
                show_information(id);
                focusInDiv(true);
                count_expand_table++;
            }
        }

        function show_edit_form(id, url) {
            @if($route_edit->checkIfUserHaspermission())
            if (!url) {
                url = "./{{$route_edit->url}}?id=" + id;
            }
            $("#show-content-edit").hide();
            $("#show-loading-edit").show();
            $.ajax({
                type: "GET",
                url: url,
                contentType: "application/json; charset=utf-8",
                cache: false,
                success: function (response) {
                    if (response.success) {
                        $("#show-content-edit").html("");
                        $("#show-content-edit").append(response.html);
                        $("#show-content-edit").show();
                        $("#show-loading-edit").hide();
                        register_functions_in_edit();
                    } else {
                        Swal.fire({title: "Erro", text: "Houve um erro! Erro: " + response.error, type: "error"});
                    }
                },
                error: function () {
                    Swal.fire({title: "Erro", text: "Houve um erro!", type: "error"});
                }
            });
            @else
            Swal.fire("Sem Permissão", "Você não permissão para editar", "warning");
            @endif
        }

        function show_information(id, url) {
            if (!url) {
                url = "./{{$route->url}}?id=" + id;
            }
            $("#show-loading-" + id).show();
            $.ajax({
                type: "GET",
                url: url,
                contentType: "application/json; charset=utf-8",
                cache: false,
                success: function (response) {
                    if (response.success) {
                        $("#show-content-" + id).html("");
                        $("#show-content-" + id).append(response.html);
                        $("#show-content-" + id).show();
                        $("#show-loading-" + id).hide();
                    } else {
                        Swal.fire({title: "Erro", text: "Houve um erro! Erro: " + response.error, type: "error"});
                    }
                },
                error: function () {
                    Swal.fire({title: "Erro", text: "Houve um erro!", type: "error"});
                }
            });
        }
    </script>
    <script>
        function observeSelectTableSearch(selector) {
            $(selector).change(function () {
                var input_search = $(this).children("option:selected").val();
                var url = "{{$route_search->url}}?input_search=" + input_search;
                $.ajax({
                    type: "GET",
                    url: url,
                    contentType: "application/json; charset=utf-8",
                    cache: true,
                    success: function (response) {
                        if (response.success) {
                            var tr = $(selector).parent().parent();
                            var td = tr.find("td")[2];
                            $(td).html(response.html);
                            var select = $(td).find("select");
                            var function_element = select.attr("execute-script");
                            if (function_element) {
                                window[function_element](select);
                            }
                            var td2 = tr.find("td")[1];
                            if (response.relationable) {
                                $(td2).html($("#operator_relation_element").val());
                                $(".js-select-relation").select2({width: '100%'});
                            } else {
                                $(td2).html($("#operator_normal").val());
                                $(".js-select").select2({width: '100%'});
                            }
                        } else {
                            Swal.fire({title: "Erro", text: "Houve um erro! Erro: " + response.error, type: "error"});
                        }
                    },
                    error: function () {
                        Swal.fire({title: "Erro", text: "Houve um erro!", type: "error"});
                    }
                });
            });
        }

        function observeSelectTableSearchMobile(selector) {
            $(selector).change(function () {
                let input_search = $(this).children("option:selected").val();
                let url = "{{$route_search->url}}?input_search=" + input_search;
                $.ajax({
                    type: "GET",
                    url: url,
                    contentType: "application/json; charset=utf-8",
                    cache: true,
                    success: function (response) {
                        if (response.success) {
                            var tr = $(selector).parent().parent();
                            let td = tr.next().next().find("td");
                            td.html(response.html);
                            let select = td.find("select");
                            var function_element = select.attr("execute-script");
                            if (function_element) {
                                window[function_element](select);
                            }
                            let td2 = tr.next().find("td");
                            if (response.relationable) {
                                td2.html($("#operator_relation_element").val());
                                $(".js-select-relation").select2({width: '100%'});
                            } else {
                                td2.html($("#operator_normal").val());
                                $(".js-select").select2({width: '100%'});
                            }
                        } else {
                            Swal.fire({title: "Erro", text: "Houve um erro! Erro: " + response.error, type: "error"});
                        }
                    },
                    error: function () {
                        Swal.fire({title: "Erro", text: "Houve um erro!", type: "error"});
                    }
                });
            });
        }

        observeSelectTableSearch("#table_search > tbody > tr > td:first-child > select");
        observeSelectTableSearchMobile("#field_search");
    </script>
    @includeIf("modeladmin::js.functions")
    @includeIf("modeladmin::js.jquery-resizable")
@endsection
@section("content")
    @component("modeladmin::layout.components.card",["title"=>__("modeladminlang::default.list_of_models")." ".$modelConfig->title])
        @component("modeladmin::layout.components.card-collapse",["title"=>"Opções","icon"=>"fas fa-cogs","id"=>"collpase-all",])
            @includeIf("modeladmin::layout.filters.filter-search")
            @includeIf("modeladmin::layout.filters.fields-show")
        @endcomponent
        @if(!empty($include_custom_table))
            <div style="margin-top: -17px;" id="table_container">
                @include($include_custom_table)
            </div>
        @else
            @desktop
            <div style="margin-top: -17px;" id="table_container">
                @component("modeladmin::layout.components.table")
                    @slot("table_head")
                        <tr class="tr-head">
                            {!! $createHtml->prepareTableColumns() !!}
                        </tr>
                    @endslot
                    @slot("table_body")
                        @foreach($models as $model)
                            <tr onclick="expand_table_tr('#tr-{{$model->id}}',{{$model->id}})"
                                title="{{__("modeladminlang::default.click_to_expand")}}" class="tr-select">
                                {!! $createHtml->getTableColumnDataForRow($model) !!}
                            </tr>
                            <tr id="tr-{{$model->id}}" class="tr-expand">
                                <td colspan="{{$colspan}}">
                                    <div id="show-loading-{{$model->id}}" class="text-center">
                                        <div class="form-group">
                                            <i class="fas fa-2x fa-sync fa-spin"></i>
                                        </div>
                                        <div class="form-group">
                                            <p>{{__("modeladminlang::default.loading")}}...</p>
                                        </div>
                                    </div>
                                    <div id="show-content-{{$model->id}}" style="display: none;">

                                    </div>
                                </td>
                            </tr>
                            <tr hidden></tr>
                        @endforeach
                    @endslot
                @endcomponent
                <table id="header-fixed"></table>
            </div>
            @elsedesktop
            <div style="margin-top: -17px;" id="table_container">
                @php
                    $data_th = $createHtml->prepareTableColumnsMobile();
                    $count_td = 0;
                @endphp
                <table class="table table-bordered table-striped">
                    <tbody>
                    @foreach($models as $model)
                        @php
                            $data_td = $createHtml->getTableColumnDataForRowMobile($model);
                            $data_count = 0;
                        @endphp
                        @for($i = 0; $i < count($data_td); $i++)
                            <tr>
                                {!! $data_th[$data_count] !!}
                                {!! $data_td[$i] !!}
                            </tr>
                            @php($data_count++)
                        @endfor
                        <tr>
                            <th class="tr-head">Ver/Opções</th>
                            <td>
                                <button onclick="expand_table_tr('#tr-{{$model->id}}',{{$model->id}})" type="button"
                                        title="Clique para ver" class="btn btn-success btn-block"><i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        <tr id="tr-{{$model->id}}" class="tr-expand">
                            <td colspan="{{$colspan}}">
                                <div id="show-loading-{{$model->id}}" class="text-center">
                                    <div class="form-group">
                                        <i class="fas fa-2x fa-sync fa-spin"></i>
                                    </div>
                                    <div class="form-group">
                                        <p>{{__("modeladminlang::default.loading")}}...</p>
                                    </div>
                                </div>
                                <div id="show-content-{{$model->id}}" style="display: none;">

                                </div>
                            </td>
                        </tr>
                        <tr style="height: 10px"></tr>
                    @endforeach
                    </tbody>
                </table>
                <table id="header-fixed"></table>
            </div>
            @enddesktop
        @endif
        <hr>
        <div class="text-center">
            {{$models->links()}}
        </div>
    @endcomponent
    @component("modeladmin::layout.components.modal", ["title"=>__("modeladminlang::default.edit"),"id"=>"modal-edit","modalDialogClass"=>"modal-xl",'class'=>''])
        <div id="show-loading-edit" class="text-center">
            <div class="form-group">
                <i class="fas fa-2x fa-sync fa-spin"></i>
            </div>
            <div class="form-group">
                <p>{{__("modeladminlang::default.loading")}}...</p>
            </div>
        </div>
        <div id="show-content-edit" style="display: none;">

        </div>
    @endcomponent
@stop
