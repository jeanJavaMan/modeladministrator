@php
    /**@var \Jeanderson\modeladministrator\Models\ModelConfig $modelConfig*/
    /**@var \Jeanderson\modeladministrator\Models\ModelConfig[] $modelConfig_relation_array*/
    /**@var \Jeanderson\modeladministrator\Models\CustomModel $model*/
    $modelConfig_relation_array = [];
@endphp
<html>
<head>
</head>
<body>
<table>
    <thead>
    <tr>
        @foreach($modelConfig->elements_cache() as $element)
            @if($element->show_in_table)
                @if($element->is_relationable)
                    @php
                        $modelConfig_relation = \Jeanderson\modeladministrator\Models\ModelConfig::getModelConfigWithCache($element->relationable_with_class);
                        $count_elements = $modelConfig_relation->elements_cache()->filter(function ($item){
                            return $item->show_in_table;
                        })->count();
                        $function = $element->relationship_function;
                        $count_results = $model->$function()->count();
                        if($count_results > 0){
                             $modelConfig_relation_array[$element->fillable_var] = ["modelConfig"=>$modelConfig_relation,"count"=>$count_results];
                        }
                        $total_espace = $count_elements + $count_results;
                        $is_par = ($total_espace % 2 == 0);
                    @endphp
                    @if($count_results > 0)
                        @if($is_par)
                            <th style="text-align: center"
                                colspan="{{$total_espace}}">{{$modelConfig_relation->title}}</th>
                        @else
                            <th style="text-align: center"
                                colspan="{{$count_elements}}">{{$modelConfig_relation->title}}</th>
                        @endif
                        {{--                        @for($i = 0; $i < $count_results; $i++)--}}
                        {{--                            <th style="text-align: center"--}}
                        {{--                                colspan="{{$count_elements}}">{{$modelConfig_relation->title}}</th>--}}
                        {{--                        @endfor--}}
                    @endif
                @else
                    <th style="text-align: center" rowspan="2">{{$element->label}}</th>
                @endif
            @endif
        @endforeach
    </tr>
    <tr>
        @foreach($modelConfig_relation_array as $modelConfig_relation)
            @for($i = 0; $i < $modelConfig_relation["count"]; $i++)
                @foreach($modelConfig_relation["modelConfig"]->elements_cache() as $element_relation)
                    <th style="text-align: center">{{$element_relation->label}}</th>
                @endforeach
            @endfor
        @endforeach
    </tr>
    </thead>
    <tbody>
    <tr>
        @foreach($modelConfig->elements_cache() as $element)
            @if($element->show_in_table)
                @if($element->is_relationable)
                    @if(key_exists($element->fillable_var,$modelConfig_relation_array))
                        @php
                            $modelConfig_relation = $modelConfig_relation_array[$element->fillable_var]["modelConfig"];
                            $function = $element->relationship_function;
                            $results = $model->$function()->get();
                        @endphp
                        @if($results->count() > 0)
                            @foreach($results as $result)
                                @foreach($modelConfig_relation->elements_cache() as $element_relation)
                                    @php($fillable = $element_relation->fillable_var)
                                    <td>{{$result->$fillable ?? ""}}</td>
                                @endforeach
                            @endforeach
                        @endif
                    @endif
                @else
                    @php($fillable = $element->fillable_var)
                    <td>{{$model->$fillable ?? ""}}</td>
                @endif
            @endif
        @endforeach
    </tr>
    </tbody>
</table>
</body>
</html>
