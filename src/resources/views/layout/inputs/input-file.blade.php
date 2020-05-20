@php
    /**@var \Jeanderson\modeladministrator\Models\Element $element*/
    $is_multiple = \Illuminate\Support\Str::contains($element->attributes,"multiple");
@endphp
<label for="{{$element->fillable_var}}">{{$element->label}}:</label>
<input type="{{$element->type_input}}" name="{{$element->fillable_var}}{{$is_multiple ? "[]":""}}" id="{{$element->fillable_var}}"
       class="{{$element->class_field}}"
       placeholder="{{$element->placeholder}}"@foreach(explode(";",$element->attributes) as $attribute) {!! $attribute !!}  @endforeach>
@if($errors ?? false)
    @if($errors->has($element->fillable_var))
        <div class="alert-danger" style="border-radius: .25rem"><p style="margin-left: 5px">{{ $errors->first($element->fillable_var)}}</p></div>
    @endif
@endif
