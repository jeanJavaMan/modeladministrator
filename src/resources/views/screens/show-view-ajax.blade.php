@php
    /**@var ModelConfig $modelConfig*/use Jeanderson\modeladministrator\Models\CustomModel;use Jeanderson\modeladministrator\Models\Element;use Jeanderson\modeladministrator\Models\ModelConfig;
    /**@var Element $element*/
    /**@var CustomModel $model*/
    /**@var \Jeanderson\modeladministrator\Models\Route $route*/
    /**@var \Jeanderson\modeladministrator\Models\Route $route_delete*/
    /**@var \Jeanderson\modeladministrator\Models\Route $route_edit*/
    /**@var CustomModel $result*/
    $elements = $modelConfig->elements_cache();
    $createHtml = new \Jeanderson\modeladministrator\Utils\CreateHTML($modelConfig);
    $route_delete = $createHtml->getRoutesForType("delete");
    $route_pdf = $createHtml->getRoutesForType("pdf");
    $route_edit = $createHtml->getRoutesForType("edit");
@endphp
<div class="form-group">
    <div class="form-group">
        @component("modeladmin::layout.components.card-collapse",["title"=>__("modeladminlang::default.options"),"class"=>"card-success","id"=>"collapse-".$model->id])
            <form method="post" id="form-delete-{{$model->id}}" action="./{{$route_delete->url}}">
                @csrf
                <input type="hidden" name="id" value="{{Crypt::encrypt($model->id)}}">
                <div class="form-group">
                    <div class="row">
                        @if($route_edit->checkIfUserHaspermission())
                            <div style="margin-bottom: 15px;" class="col-md-2">
                                <button onclick="show_edit_form({{$model->id}})" type="button" data-toggle="modal"
                                        data-target="#modal-edit"
                                        class="btn bg-gradient-warning"><i
                                        class="fas fa-edit"> {{__("modeladminlang::default.edit")}}</i></button>
                            </div>
                        @endif
                        @if($route_pdf->checkIfUserHaspermission())
                            <div style="margin-bottom: 15px;" class="col-md-2">
                                <a target="_blank" href="./{{$route_pdf->url}}?id={{$model->id}}"
                                   class="btn bg-gradient-primary"><i
                                        class="fas fa-file-pdf"></i> PDF</a>
                            </div>
                        @endif
                        @if($route_delete->checkIfUserHaspermission())
                            <div style="margin-bottom: 15px;" class="col-md-2">
                                <button onclick="delete_item('{{$model->id}}')" class="btn bg-gradient-danger"
                                        type="button"><i
                                        class="fas fa-trash"> {{__("modeladminlang::default.delete")}}</i></button>
                            </div>
                        @endif
                    </div>
                </div>
            </form>
            @if(!empty($include_options))
                @includeIf($include_options,["modelConfig"=>$modelConfig,"model"=>$model])
            @endif
        @endcomponent
    </div>
    @foreach($elements as $element)
        <div class="form-group">
            @if($element->is_relationable)
                @if($element->relationship_type_function == "hasOne")
                    <div class="form-group">
                        @php
                            $function = $element->relationship_function;
                            $result_hasOne = $model->$function()->first();
                        @endphp
                        @if($result_hasOne)
                            <strong>{{$element->label ?? \Jeanderson\modeladministrator\Models\ModelConfig::getModelConfigWithCache($element->relationable_with_class)->title}}
                                :</strong> {{\Jeanderson\modeladministrator\Utils\ShowCustomModel::showData($result_hasOne)}}
                        @endif
                        <hr>
                    </div>
                @else
                    @php
                        $function = $element->relationship_function;
                        $modelConfigRelation = \Jeanderson\modeladministrator\Models\ModelConfig::getModelConfigWithCache($element->relationable_with_class);
                        $results = $model->$function()->paginate(15,['*'],$modelConfigRelation->title);
                    @endphp
                    @if($results->total() > 0)
                        <div class="form-group">
                            <label>{{$element->label ?? $modelConfigRelation->title}}:</label>
                            <ul>
                                @foreach($results as $result)
                                    <li>
                                        {{\Jeanderson\modeladministrator\Utils\ShowCustomModel::showData($result)}}
                                    </li>
                                @endforeach
                            </ul>
                            <div>{{$results->appends(["id"=>$model->id,$modelConfigRelation->title => $results->currentPage()])->links()}}</div>
                            <hr>
                        </div>
                    @endif
                @endif
            @else
                @php($fillable = $element->fillable_var)
                <strong>{{$element->label}}:</strong>
                @if($element->type_input == "textarea")
                    <textarea readonly class="form-control">{{$model->$fillable}}</textarea>
                @elseif($element->type_input == "file")
                    @includeIf("modeladmin::screens.show-attachments")
                @elseif($element->type_input == "password")
                    **************
                @else
                    {{$model->$fillable}}
                @endif
                <hr>
            @endif
        </div>
    @endforeach
    @if(!empty($include_show))
        @includeIf($include_show,["modelConfig"=>$modelConfig,"model"=>$model])
    @endif
</div>
<script>
    function delete_item(id) {
        Swal.fire({
            title: "{{__("modeladminlang::default.delete")}}",
            text: "{{__("modeladminlang::default.delete_message")}}",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#08dd32',
            confirmButtonText: "{{__("modeladminlang::default.confirm_delete_button")}}",
            cancelButtonText: "{{__("modeladminlang::default.cancel_button")}}"
        }).then((result) => {
            if (result.value) {
                $("#form-delete-" + id).submit();
            }
        })
    }
</script>
