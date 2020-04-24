@php
    /**@var \Jeanderson\modeladministrator\Models\ModelConfig $modelConfig*/
@endphp
@component("modeladmin::layout.components.card-collapse",["title"=>__("modeladminlang::default.filters"),"icon"=>"fas fa-filter"])
    @if($request->has("filter"))
        @slot("header")
            <a href="" title="{{__("modeladminlang::default.click_for_clear")}}" style="float: right;"
               class="btn btn-secondary"><i class="fas fa-circle"
                                            style="color: greenyellow"></i> {{__("modeladminlang::default.filtered")}}
            </a>
        @endslot
    @endif
    <form method="post" action="">
        @csrf
        <input type="hidden" name="filter" value="1">
        <div class="form-group">
            @if($request->has("query_text"))
                <label>{{__("modeladminlang::default.filter")}}:</label>
                <p class="alert-dark">{!!$request->post("query_text") !!}<br><small>Obs: comparação com campos
                        relacionáveis é exibido somente o ID do campo</small></p>

            @endif
        </div>
        <div class="form-group">
            <table id="table_search" class="table table-dark table-bordered">
                <thead>
                <tr>
                    <th>{{__("modeladminlang::default.field")}}</th>
                    <th>{{__("modeladminlang::default.operator")}}</th>
                    <th>{{__("modeladminlang::default.value")}}</th>
                    <th>{{__("modeladminlang::default.relational_operator")}}</th>
                    <th>{{__("modeladminlang::default.add")}}</th>
                </tr>
                </thead>
                <tbody id="tbody_search">
                <tr>
                    <td>
                        <select class="js-select" name="field_search[]" id="field_search">
                            <option value=""></option>
                            @foreach($modelConfig->elements_cache() as $element)
                                @if(request()->has("c"))
                                    @if(!is_null(request()->get($element->fillable_var)))
                                        <option value="{{$element->fillable_var}}">{{$element->label}}</option>
                                    @endif
                                @else
                                    @if($element->show_in_table)
                                        <option value="{{$element->fillable_var}}">{{$element->label}}</option>
                                    @endif
                                @endif
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select class="js-select" name="operator[]" id="operator">
                            @foreach(\Jeanderson\modeladministrator\Utils\OperationValues::getOperatorsValues() as $key => $value)
                                <option value="{{$value}}">{{$key}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input class="form-control" name="value_search[]" id="value_search">
                    </td>
                    <td>

                    </td>
                    <td>
                        <button onclick="add_search_field(this)"
                                title="{{__("modeladminlang::default.click_for_add_fields")}}" class="btn btn-success"
                                type="button"><i class="fas fa-plus"></i></button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="form-group">
            <label>{{__("modeladminlang::default.groupby")}}:</label>
            <div class="col-sm-3">
                <select name="groupby" class="form-control js-select">
                    <option></option>
                    @foreach($modelConfig->elements_cache() as $element)
                        @if($element->relationship_type_function === "hasOne" || !$element->is_relationable)
                            <option value="{{$element->fillable_var}}">{{$element->label}}</option>
                        @endif
                    @endforeach
                </select>
            </div>

        </div>
        <div class="form-group">
            <label>{{__("modeladminlang::default.orderby")}}:</label>
            <div class="col-sm-3">
                <select name="orderby" class="form-control js-select">
                    <option></option>
                    @foreach($modelConfig->elements_cache() as $element)
                        @if($element->relationship_type_function === "hasOne" || !$element->is_relationable)
                            <option value="{{$element->fillable_var}}">{{$element->label}}</option>
                        @endif
                    @endforeach
                </select>
                <label>{{__("modeladminlang::default.in_order")}}:</label>
                <select name="order_by_func" class="form-control">
                    @foreach(\Jeanderson\modeladministrator\Utils\OperationValues::getOrderFunction() as $key => $value)
                        <option value="{{$value}}">{{$key}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group">
            <label>Exibir por pagina:</label>
            <div class="col-sm-1">
                <select name="show_for_page" class="form-control">
                    <option value="15">15</option>
                    <option selected value="30">30</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="200">200</option>
                    <option value="300">300</option>
                    <option value="400">400</option>
                    <option value="500">500</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <button class="btn bg-gradient-success" type="submit">{{__("modeladminlang::default.filter_run")}}</button>
            <a href="" class="btn btn-primary">{{__("modeladminlang::default.clear_filters")}}</a>
        </div>
    </form>
    <div hidden>
        <div>
            <textarea id="operator_relation">
                 <select class="js-select" name="operator_relation[]">
                @foreach(\Jeanderson\modeladministrator\Utils\OperationValues::getComparatorWhere() as $key => $value)
                         <option value="{{$value}}">{{$key}}</option>
                     @endforeach
            </select>
            </textarea>
            <textarea id="operator_relation_element">
                 <select class="js-select-relation" name="operator_relation[]">
                    <option value="=">Igual</option>
                     <option value="!=">Diferente de</option>
                    </select>
            </textarea>
            <textarea id="operator_normal">
                <select class="js-select" name="operator[]">
                        @foreach(\Jeanderson\modeladministrator\Utils\OperationValues::getOperatorsValues() as $key => $value)
                        <option value="{{$value}}">{{$key}}</option>
                    @endforeach
                    </select>
            </textarea>
        </div>
        <div>
            <textarea id="tr_copy">
                <tr>
                <td>
                    <select class="js-select" name="field_search[]">
                        <option value=""></option>
                        @foreach($modelConfig->elements_cache() as $element)
                            @if(request()->has("c"))
                                @if(!is_null(request()->get($element->fillable_var)))
                                    <option value="{{$element->fillable_var}}">{{$element->label}}</option>
                                @endif
                            @else
                                @if($element->show_in_table)
                                    <option value="{{$element->fillable_var}}">{{$element->label}}</option>
                                @endif
                            @endif
                        @endforeach
                    </select>
                </td>
                <td>
                    <select class="js-select" name="operator[]">
                        @foreach(\Jeanderson\modeladministrator\Utils\OperationValues::getOperatorsValues() as $key => $value)
                            <option value="{{$value}}">{{$key}}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input class="form-control" name="value_search[]">
                </td>
                <td>

                </td>
                <td>
                    <button onclick="remove_search_field(this)"
                            title="{{__("modeladminlang::default.click_for_remove_fields")}}" class="btn btn-danger"
                            type="button"><i class="fas fa-times"></i></button>
                </td>
            </tr>
            </textarea>
        </div>
    </div>
@endcomponent
