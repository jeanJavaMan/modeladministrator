@php
    /**@var \Jeanderson\modeladministrator\Models\Element $element*/
    use Jeanderson\modeladministrator\Models\ModelConfig;
    $routes = ModelConfig::getModelConfigWithCache($element->relationable_with_class)->routes_cache();
    $route = $routes->first(function ($item){return $item->type == "search";});
    $multiple = \Illuminate\Support\Str::contains($element->relationship_type_function,"Many");

@endphp
    <select class="form-control" execute-script="execute_ajax_relation" name="value_search[]">
    </select>
<script>
    function execute_ajax_relation(element){
        $(element).select2({
            width: "100%",
            ajax: {
                url: "./{{$route->url}}?s=1",
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
                cache: true,
                delay: 250
            },
            placeholder: "{{__("modeladminlang::default.write_for_search")}}",
//                minimumInputLength: 2,
            multiple: false
        });
    }
</script>
