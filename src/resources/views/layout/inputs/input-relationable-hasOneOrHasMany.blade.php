@php
    /**@var \Jeanderson\modeladministrator\Models\Element $element*/
    use Jeanderson\modeladministrator\Models\ModelConfig;
    $class_relationable = $element->relationable_with_class;
    $routes = ModelConfig::getModelConfigWithCache($class_relationable)->routes_cache();
    $route = $routes->first(function ($item){return $item->type == "search";});
    $multiple = \Illuminate\Support\Str::contains($element->relationship_type_function,"Many");
    $options = null;
    if(old($element->fillable_var)){
        $options = $class_relationable::find(old($element->fillable_var));
    }
@endphp
<label style="width: 100%" for="{{$element->fillable_var}}">
    {{$element->label}} {{$multiple ? " (".__("modeladminlang::default.multiple_selection").")":""}}:
    <select register-function="true" execute-script="execute_ajax_relation_{{$element->id}}" class="form-control" name="{{$element->fillable_var}}{{$multiple ?"[]":""}}" {{$multiple ? "multiple=multiple":""}}
            id="{{$element->fillable_var}}" @foreach(explode(";",$element->attributes) as $attribute) {!! $attribute !!}  @endforeach>
        @if($options)
            @if($options instanceof Illuminate\Database\Eloquent\Collection)
                @foreach($options->all() as $option)
                    <option value="{{$option->id}}" selected>{{$option->toView()}}</option>
                @endforeach
            @else
                <option value="{{$options->id}}" selected>{{$options->toView()}}</option>
            @endif
        @endif
    </select>
</label>
<script>
    window.addEventListener('load', function () {
        $("#{{$element->fillable_var}}").select2({
            width: "100%",
            ajax: {
                url: "../{{$route->url}}",
                data: function (params) {
                    return {
                        search: params.term,
                        page: params.page || 1
                    };
                },
                dataType: 'json',
                processResults: function (data) {
                    data.page = data.page || 1;
                    return {
                        results: data.items.map(function (item) {
                            return {
                                id: item.id,
                                text: item.text
                            };
                        }),
                        pagination: {
                            more: data.pagination
                        }
                    }
                },
                cache: false,
                delay: 250
            },
            placeholder: "{{__("modeladminlang::default.write_for_search")}}",
//                minimumInputLength: 2,
            multiple: {!! $multiple ? 'true':'false' !!}
        });
    });
    function execute_ajax_relation_{{$element->id}}(element){
        $(element).select2({
            width: "100%",
            ajax: {
                url: "./{{$route->url}}",
                data: function (params) {
                    return {
                        search: params.term,
                        page: params.page || 1
                    };
                },
                dataType: 'json',
                processResults: function (data) {
                    data.page = data.page || 1;
                    return {
                        results: data.items.map(function (item) {
                            return {
                                id: item.id,
                                text: item.text
                            };
                        }),
                        pagination: {
                            more: data.pagination
                        }
                    }
                },
                cache: false,
                delay: 250
            },
            placeholder: "{{__("modeladminlang::default.write_for_search")}}",
//                minimumInputLength: 2,
            multiple: {!! $multiple ? 'true':'false' !!}
        });
    }
</script>
