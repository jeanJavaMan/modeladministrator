@php
    /**@var \Jeanderson\modeladministrator\Models\Element $element*/
    /**@var \Jeanderson\modeladministrator\Models\ModelConfig $modelConfig*/
$enums_values = $modelConfig->model_class::getEnumsValues($element->fillable_var);
@endphp
<label for="{{$element->fillable_var}}">{{$element->label}}:</label>
@if(!empty($enums_values))
    <select class="js-select form-control" name="{{$element->fillable_var}}"
            id="{{$element->fillable_var}}" @foreach(explode(";",$element->attributes) as $attribute) {!! $attribute !!}  @endforeach>
        @foreach($enums_values as $value)
            <option value="{{$value}}">{{$value}}</option>
        @endforeach
    </select>
@else
    <input type="{{$element->type_input}}" name="{{$element->fillable_var}}" id="{{$element->fillable_var}}"
           value="{{$element->type_input == "password" ? "":old($element->fillable_var,$element->value)}}"
           class="form-control {{$element->class_field}}"
           placeholder="{{$element->placeholder}}"@foreach(explode(";",$element->attributes) as $attribute) {!! $attribute !!}  @endforeach>
@endif
@if($errors ?? false)
    @if($errors->has($element->fillable_var))
        <div class="alert-danger" style="border-radius: .25rem"><p
                style="margin-left: 5px">{{ $errors->first($element->fillable_var)}}</p></div>
    @endif
@endif
