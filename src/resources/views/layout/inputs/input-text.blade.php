@php
    /**@var \Jeanderson\modeladministrator\Models\Element $element*/
@endphp
<label for="{{$element->fillable_var}}">{{$element->label}}:</label>
<input type="{{$element->type_input}}" name="{{$element->fillable_var}}" id="{{$element->fillable_var}}"
       value="{{old($element->fillable_var,$element->value)}}" class="form-control {{$element->class_field}}"
       placeholder="{{$element->placeholder}}"@foreach(explode(";",$element->attributes) as $attribute) {{$attribute}} @endforeach>
@if($errors ?? false)
    @if($errors->has($element->fillable_var))
        <div class="alert-danger" style="border-radius: .25rem"><p style="margin-left: 5px">{{ $errors->first($element->fillable_var)}}</p></div>
    @endif
@endif
