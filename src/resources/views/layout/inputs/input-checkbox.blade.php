@php
    /**@var \Jeanderson\modeladministrator\Models\Element $element*/
@endphp
<div class="custom-control custom-checkbox">
    <input class="custom-control-input {{$element->class_field}}" type="{{$element->type_input}}"
           id="{{$element->fillable_var}}" name="{{$element->fillable_var}}"
           {{old($element->fillable_var) !== null ? "checked":""}} @foreach(explode(";",$element->attributes) as $attribute) {{$attribute}} @endforeach>
    <label for="{{$element->fillable_var}}" class="custom-control-label">{{$element->label}}</label>
</div>
@if($errors ?? false)
    @if($errors->has($element->fillable_var))
        <div class="alert-danger" style="border-radius: .25rem"><p style="margin-left: 5px">{{ $errors->first($element->fillable_var)}}</p></div>
    @endif
@endif
