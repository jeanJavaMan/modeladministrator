@php
    /**@var \Jeanderson\modeladministrator\Models\Element $element*/
@endphp
<select class="js-select form-control"
        name="value_search[]">
    @foreach($element->options_cache() as $option)
        <option value="{{$option->value}}">{{$option->value}}</option>
    @endforeach
</select>
