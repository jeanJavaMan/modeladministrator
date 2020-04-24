@extends('adminlte::page')
@php
    /**@var \Jeanderson\modeladministrator\Models\ModelConfig $modelConfig*/
    /**@var \Jeanderson\modeladministrator\Models\Element $element*/
    /**@var \Jeanderson\modeladministrator\Models\Route $route*/
    $createHtml = new \Jeanderson\modeladministrator\Utils\CreateHTML($modelConfig);
    $route = $createHtml->getRoutesForType("store");
@endphp
@section("content")
    <form method="post" id="form" action="../{{$route->url}}" enctype="multipart/form-data">
        @csrf
        @component("modeladmin::layout.components.card",["title"=>__("modeladminlang::default.register")." ".$modelConfig->title])
            @foreach($modelConfig->elements_cache() as $element)
                <div class="form-group">
                    @if($element->show_in_form)
                        @if($element->is_relationable)
                            @includeIf("modeladmin::layout.inputs.input-relationable")
                        @else
                            @includeIf("modeladmin::layout.inputs.input-".$element->type_input)
                        @endif
                    @endif
                </div>
            @endforeach
            <hr>
            <div class="form-group">
                <div id="print_error_msg" class="alert alert-danger" style="display:none">
                    <ul></ul>
                </div>
            </div>
            <div class="form-group">
                <button onclick="validateAndSubmit('../{{$route->url}}','#form', this)" style="min-width: 100px;" type="button"
                        class="btn bg-gradient-success">{{__("modeladminlang::default.save")}}</button>
            </div>
        @endcomponent
    </form>
@stop
@section("js")
    @includeIf("modeladmin::js.functions")
    @includeIf("modeladmin::js.jquery-mask")
@stop
