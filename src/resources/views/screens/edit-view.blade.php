@php
    /**@var \Jeanderson\modeladministrator\Models\ModelConfig $modelConfig*/
    /**@var \Jeanderson\modeladministrator\Models\Element $element*/
    /**@var \Jeanderson\modeladministrator\Models\CustomModel $model*/
    $createHtml = new \Jeanderson\modeladministrator\Utils\CreateHTML($modelConfig);
    $route = $createHtml->getRoutesForType("update");
@endphp
<div class="form-group">
    <div class="form-group">
        <form id="form-edit" method="post" action="./{{$route->url}}?id={{$model->id}}" enctype="multipart/form-data">
            @csrf
            @foreach($modelConfig->elements_cache() as $element)
                <div class="form-group">
                    @if($element->show_in_edit)
                        @if($element->is_relationable)
                            @includeIf("modeladmin::layout.inputs.input-relationable")
                        @else
                            @includeIf("modeladmin::layout.inputs.input-".$element->type_input)
                        @endif
                    @endif
                </div>
            @endforeach
            @if(!empty($include_edit))
                @includeIf($include_edit,["modelConfig"=>$modelConfig,"model"=>$model])
            @endif
            <hr>
            <div class="form-group">
                <div id="print_error_msg" class="alert alert-danger" style="display:none">
                    <ul></ul>
                </div>
            </div>
            <div class="form-group">
                <button onclick="validateAndSubmit('./{{$route->url}}?id={{$model->id}}','#form-edit',this,false,true)"
                        style="min-width: 100px;" type="button"
                        class="btn bg-gradient-success">{{__("modeladminlang::default.save")}}</button>
            </div>
        </form>
    </div>
</div>
@includeIf("modeladmin::js.functions")
@includeIf("modeladmin::js.jquery-mask")
