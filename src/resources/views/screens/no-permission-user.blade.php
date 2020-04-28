@extends('adminlte::page')
@section("content")
    @component("modeladmin::layout.components.card",["title"=>"Sem permissão de acesso","icon"=>"fas fa-lock"])
        {{__("modeladminlang::default.no_permission_message")}}
    @endcomponent
@stop
