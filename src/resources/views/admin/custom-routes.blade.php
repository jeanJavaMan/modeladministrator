@extends('adminlte::page')

@section('title', 'ModelAdmin')

@section('content_header')
    <h1 class="m-0 text-dark">Painel Administrativo</h1>
@stop

@section('content')
    <form action="{{route("modeladmin.save.route")}}" method="post">
        @csrf
        @component("modeladmin::layout.components.card",["title"=>"Rotas Customizadas"])
            <div class="form-group">
                <label>Model Config</label>
                <select name="modelconfigs_id" required class="form-control">
                    @foreach(\Jeanderson\modeladministrator\Models\ModelConfig::all() as $modelConfig)
                        <option value="{{$modelConfig->id}}">{{$modelConfig->model_class}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Tipo</label>
                <input class="form-control" name="type" value="">
            </div>
            <div class="form-group">
                <label>URL</label>
                <input class="form-control"
                       name="url"
                       value="classname/url">
            </div>
            <div class="form-group">
                <label>Função</label>
                <input class="form-control" name="functions" value="funcao_name">
            </div>
            <div class="form-group">
                <label>Método</label>
                <input class="form-control" name="method" value="GET">
            </div>
            <div class="form-group">
                <label>Visivel para todos</label>
                <select name="visible_to_everyone" class="form-control">
                    <option value="1">SIM</option>
                    <option value="0" selected>NÃO</option>
                </select>
            </div>
            <div class="form-group">
                <label>Permissões</label>
                <input name="permissions" class="form-control">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-success">Adicionar</button>
            </div>
        @endcomponent
    </form>
@stop

