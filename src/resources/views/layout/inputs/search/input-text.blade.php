@php
    /**@var \Jeanderson\modeladministrator\Models\Element $element*/
 /**@var \Jeanderson\modeladministrator\Models\ModelConfig $modelConfig*/
$enums_values = $modelConfig->model_class::getEnumsValues($element->fillable_var);
@endphp
@if(!empty($enums_values))
    <select class="js-select form-control"
            name="value_search[]">
        @foreach($enums_values as $value)
            <option value="{{$value}}">{{$value}}</option>
        @endforeach
    </select>
@else
    <input type="{{$element->type_input}}" name="value_search[]" class="form-control"
           placeholder="{{$element->placeholder}}">
@endif

