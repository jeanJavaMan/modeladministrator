@php
    /**@var \Jeanderson\modeladministrator\Models\ModelConfig $modelConfig*/
@endphp
@component("modeladmin::layout.components.card-collapse",["title"=>"Exibir campos personalizados","icon"=>"fas fa-eye","id"=>"collpase-2","class"=>"card-secondary","divClass"=>"colapse-junto"])
    <div class="form-group">
        <form method="get" action="">
            <input type="hidden" name="c" value="1">
            <div class="form-group">
                @foreach($modelConfig->elements_cache() as $element)
                    @component("modeladmin::layout.components.checkbox",["id"=>$element->fillable_var,"name"=>$element->fillable_var])
                        {{$element->label}}
                    @endcomponent
                @endforeach
            </div>
            <div class="form-group">
                <button class="btn btn-success" type="submit">Exibir Campos Selecionados</button>
                <a href="{{\Illuminate\Support\Facades\Request::url()}}" class="btn btn-primary">Retornar Padrão</a>
            </div>
        </form>
    </div>
@endcomponent
