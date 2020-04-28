@extends('adminlte::page')

@section('title', 'ModelAdmin')

@section('content_header')
    <h1 class="m-0 text-dark">Painel Administrativo</h1>
@stop

@section('content')
    @component("modeladmin::layout.components.card",["title"=>"Opções"])
        <div class="form-group">
            <a href="{{route("modeladmin.crud")}}"   style="color: white" class="btn btn-primary">Criar Crud Completo</a>
        </div>
        <div class="form-group">
            <a href="{{route("modeladmin.create.route")}}" style="color: white" class="btn btn-success">Criar Rotas Customizadas</a>
        </div>
    @endcomponent
@stop

