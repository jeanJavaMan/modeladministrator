{{--<table id="table_search" class="table table-dark table-bordered">--}}
{{--    <tr>--}}
{{--        <th>{{__("modeladminlang::default.field")}}</th>--}}
{{--        <td>--}}
{{--            <select class="js-select" name="field_search[]" id="field_search">--}}
{{--                <option value=""></option>--}}
{{--                @foreach($modelConfig->elements_cache() as $element)--}}
{{--                    @if(request()->has("c"))--}}
{{--                        @if(!is_null(request()->get($element->fillable_var)))--}}
{{--                            <option value="{{$element->fillable_var}}">{{$element->label}}</option>--}}
{{--                        @endif--}}
{{--                    @else--}}
{{--                        @if($element->show_in_table)--}}
{{--                            <option value="{{$element->fillable_var}}">{{$element->label}}</option>--}}
{{--                        @endif--}}
{{--                    @endif--}}
{{--                @endforeach--}}
{{--            </select>--}}
{{--        </td>--}}
{{--    </tr>--}}
{{--    <tr>--}}
{{--        <th>{{__("modeladminlang::default.operator")}}</th>--}}
{{--        <td>--}}
{{--            <select class="js-select" name="operator[]" id="operator">--}}
{{--                @foreach(\Jeanderson\modeladministrator\Utils\OperationValues::getOperatorsValues() as $key => $value)--}}
{{--                    <option value="{{$value}}">{{$key}}</option>--}}
{{--                @endforeach--}}
{{--            </select>--}}
{{--        </td>--}}
{{--    </tr>--}}
{{--    <tr>--}}
{{--        <th>{{__("modeladminlang::default.value")}}</th>--}}
{{--    <td>--}}
{{--        <input class="form-control" name="value_search[]" id="value_search">--}}
{{--    </td>--}}
{{--    </tr>--}}
{{--    <tr>--}}
{{--        <th>{{__("modeladminlang::default.relational_operator")}}</th>--}}
{{--        <td>--}}

{{--        </td>--}}
{{--    </tr>--}}
{{--    <tr>--}}
{{--        <th>{{__("modeladminlang::default.add")}}</th>--}}
{{--    <td>--}}
{{--        <button onclick="add_search_field(this)"--}}
{{--                title="{{__("modeladminlang::default.click_for_add_fields")}}" class="btn btn-success"--}}
{{--                type="button"><i class="fas fa-plus"></i></button>--}}
{{--    </td>--}}
{{--    </tr>--}}
{{--</table>--}}
<div class="form-group">
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal_mobile">Criar Filtros</button>
</div>
@component("modeladmin::layout.components.modal",["id"=>"modal_mobile","title"=>"Monte seu Filtro"])
    <table id="table_search" class="table table-dark table-bordered">
        <tbody>
        <tr>
            <th>{{__("modeladminlang::default.field")}}</th>
            <td>
                <select class="js-select" name="field_search[]" id="field_search">
                    <option value=""></option>
                    @foreach($modelConfig->elements_cache() as $element)
                        @if(request()->has("c"))
                            @if(!is_null(request()->get($element->fillable_var)))
                                <option value="{{$element->fillable_var}}">{{$element->label}}</option>
                            @endif
                        @else
                            @if($element->show_in_table)
                                <option value="{{$element->fillable_var}}">{{$element->label}}</option>
                            @endif
                        @endif
                    @endforeach
                </select>
            </td>
        </tr>
        <tr>
            <th>{{__("modeladminlang::default.operator")}}</th>
            <td>
                <select class="js-select" name="operator[]" id="operator">
                    @foreach(\Jeanderson\modeladministrator\Utils\OperationValues::getOperatorsValues() as $key => $value)
                        <option value="{{$value}}">{{$key}}</option>
                    @endforeach
                </select>
            </td>
        </tr>
        <tr>
            <th>{{__("modeladminlang::default.value")}}</th>
        <td>
            <input class="form-control" name="value_search[]" id="value_search">
        </td>
        </tr>
        <tr>
            <th>{{__("modeladminlang::default.add")}}</th>
        <td>
            <button onclick="add_search_field_mobile(this)"
                    title="{{__("modeladminlang::default.click_for_add_fields")}}" class="btn btn-success"
                    type="button"><i class="fas fa-plus"></i></button>
        </td>
        </tr>
        </tbody>
    </table>
@endcomponent
