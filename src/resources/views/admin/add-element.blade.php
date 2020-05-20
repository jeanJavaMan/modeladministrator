@extends('adminlte::page')

@section('title', 'ModelAdmin')

@section('content_header')
    <h1 class="m-0 text-dark">Painel Administrativo</h1>
@stop

@section('content')
    <form action="{{route("modeladmin.save.element")}}" method="post">
        @csrf
        @component("modeladmin::layout.components.card",["title"=>"Add Elementos"])
            <div class="form-group">
                <label>Model Config</label>
                <select name="modelconfigs_id" required class="form-control">
                    @foreach(\Jeanderson\modeladministrator\Models\ModelConfig::all() as $modelConfig)
                        <option value="{{$modelConfig->id}}">{{$modelConfig->model_class}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Posição</label>
                <input type="number" name="position_order" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Nome Variável</label>
                <input class="form-control" required name="fillable_var">
            </div>
            <div class="form-group">
                <label>Tipo</label>
                <select required name="type_input[]" class="form-control">
                    @foreach(\Jeanderson\modeladministrator\Utils\ElementsType::TYPES as $type)
                        <option value="{{$type}}">{{$type}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Nome visivel</label>
                <input class="form-control" required name="label">
            </div>
            <div class="form-group">
                <label>Regras</label>
                <input class="form-control" name="rules">
            </div>
            <div class="form-group">
                <label>Placeholder</label>
                <input class="form-control" name="placeholder">
            </div>
            <div class="form-group">
                <label>Classe Customizada</label>
                <input class="form-control" name="class_field">
            </div>
            <div class="form-group">
                <label>Atributos</label>
                <input class="form-control" placeholder="separados por ;" name="attributes[]">
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-3">
                        <label>Show In Form</label>
                        <select class="form-control" name="show_in_form">
                            <option value="1" selected>SIM</option>
                            <option value="0">NÃO</option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label>Show In Table</label>
                        <select class="form-control" name="show_in_table">
                            <option value="1" selected>SIM</option>
                            <option value="0">NÃO</option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label>Show In Edit</label>
                        <select class="form-control" name="show_in_edit">
                            <option value="1" selected>SIM</option>
                            <option value="0">NÃO</option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label>Relacionável</label>
                        <select class="form-control" name="is_relationable">
                            <option value="1">SIM</option>
                            <option value="0" selected>NÃO</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Relacionável com a classe</label>
                <input class="form-control" name="relationable_with_class">
            </div>
            <div class="form-group">
                <label>Nome da Função de relacionamento</label>
                <input class="form-control" name="relationship_function">
            </div>
            <div class="form-group">
                <label>Tipo de Relação</label>
                <select class="form-control" name="relationship_type_function">
                    @foreach(\Jeanderson\modeladministrator\Utils\RelationsTypes::TYPES as $type)
                        <option value="{{$type}}">{{$type}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Tabela de Relação muitos para muitos</label>
                <input class="form-control" name="table_relation_many_to_many">
            </div>
        @endcomponent
    </form>
@stop

