@php
    /**@var ModelConfig $modelConfig*/use Jeanderson\modeladministrator\Models\CustomModel;use Jeanderson\modeladministrator\Models\Element;use Jeanderson\modeladministrator\Models\ModelConfig;
    /**@var Element $element*/
    /**@var CustomModel $model*/
    /**@var \Jeanderson\modeladministrator\Models\Route $route*/
    $elements = $modelConfig->elements_cache();
    $route_delete = $modelConfig->routes_cache()->first(function ($route_element){ return $route_element->type == "delete";});
    $route_pdf = $modelConfig->routes_cache()->first(function ($route_element){ return $route_element->type == "pdf";});
@endphp
<div class="form-group">
    <div class="form-group">
        @component("modeladmin::layout.components.card-collapse",["title"=>__("modeladminlang::default.options"),"class"=>"card-success","id"=>"collapse-".$model->id])
            <form method="post" id="form-delete" action="./{{$route_delete->url}}">
                @csrf
                <input type="hidden" name="id" value="{{Crypt::encrypt($model->id)}}">
                <div class="form-group">
                    <button onclick="show_edit_form({{$model->id}})" type="button" data-toggle="modal"
                            data-target="#modal-edit"
                            class="btn bg-gradient-warning"><i
                            class="fas fa-edit"> {{__("modeladminlang::default.edit")}}</i></button>
                    <a target="_blank" href="./{{$route_pdf->url}}?id={{$model->id}}" class="btn bg-gradient-primary"><i
                            class="fas fa-file-pdf"></i> PDF</a>

                    <button onclick="delete_item()" class="btn bg-gradient-danger" type="button"><i
                            class="fas fa-trash"> {{__("modeladminlang::default.delete")}}</i></button>
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
                @php
                    $function = $element->relationship_function;
                    $modelConfigRelation = \Jeanderson\modeladministrator\Models\ModelConfig::getModelConfigWithCache($element->relationable_with_class);
                    $results = $model->$function()->paginate(15,['*'],$modelConfigRelation->title);
                @endphp
                @if($results->total() > 0)
                    <div class="form-group">
                        <label>{{$modelConfigRelation->title}}:</label>
                        @component("modeladmin::layout.components.table")
                            @slot("table_head")
                                <tr style='background:#535353;color:white;'>
                                    @foreach($modelConfigRelation->elements_cache() as $element)
                                        @if($element->show_in_table)
                                            <th>{{$element->label}}</th>
                                        @endif
                                    @endforeach
                                </tr>
                            @endslot
                            @slot("table_body")
                                @foreach($results as $result)
                                    <tr>
                                        @foreach($modelConfigRelation->elements_cache() as $element)
                                            @php($fillable = $element->fillable_var)
                                            @if($element->show_in_table)
                                                <td>{{$result->$fillable ?? ""}}</td>
                                            @endif
                                        @endforeach
                                    </tr>
                                @endforeach
                            @endslot
                        @endcomponent
                        <div>{{$results->appends(["id"=>$model->id,$modelConfigRelation->title => $results->currentPage()])->links()}}</div>
                        <hr>
                    </div>
                @endif
            @else
                @php($fillable = $element->fillable_var)
                <strong>{{$element->label}}:</strong>
                @if($element->type_input == "textarea")
                    <textarea readonly class="form-control">{{$model->$fillable}}</textarea>
                @elseif($element->type_input == "file")
                    @includeIf("modeladmin::screens.show-attachments")
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
    function delete_item() {
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
                $("#form-delete").submit();
            }
        })
    }
</script>
