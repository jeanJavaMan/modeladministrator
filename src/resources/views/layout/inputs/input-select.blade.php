@php
    /**@var \Jeanderson\modeladministrator\Models\Element $element*/
@endphp
<label style="width: 100%" for="{{$element->fillable_var}}">
    {{$element->label}}:
    <select class="js-select form-control" name="{{$element->fillable_var}}"
            id="{{$element->fillable_var}}" @foreach(explode(";",$element->attributes) as $attribute) {!! $attribute !!}  @endforeach>
        @foreach($element->options_cache() as $option)
            <option @if(old($element->fillable_var,$element->value) == $element->value) selected
                    @endif value="{{$option->value}}">{{$option->value}}</option>
        @endforeach
    </select>
</label>
@if($errors ?? false)
    @if($errors->has($element->fillable_var))
        <div class="alert-danger" style="border-radius: .25rem"><p style="margin-left: 5px">{{ $errors->first($element->fillable_var)}}</p></div>
    @endif
@endif
