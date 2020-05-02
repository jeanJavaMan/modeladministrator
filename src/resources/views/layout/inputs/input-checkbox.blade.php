@php
    /**@var \Jeanderson\modeladministrator\Models\Element $element*/
@endphp
<label for="{{$element->fillable_var}}">{{$element->label}}:</label>
<select class="js-select form-control" id="{{$element->fillable_var}}"
        name="{{$element->fillable_var}}" @foreach(explode(";",$element->attributes) as $attribute) {!! $attribute !!} @endforeach>
    <option {{old($element->fillable_var,"1") == "1" ? "selected":""}} value="1">{{__("modeladminlang::default.yes")}}</option>
    <option {{old($element->fillable_var,"1") == "0" ? "selected":""}} value="0">{{__("modeladminlang::default.no")}}</option>
</select>
@if($errors ?? false)
    @if($errors->has($element->fillable_var))
        <div class="alert-danger" style="border-radius: .25rem"><p
                style="margin-left: 5px">{{ $errors->first($element->fillable_var)}}</p></div>
    @endif
@endif
