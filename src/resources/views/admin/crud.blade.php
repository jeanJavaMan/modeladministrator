@extends('adminlte::page')

@section('title', 'ModelAdmin')

@section('content_header')
    <h1 class="m-0 text-dark">Criar CRUD padrão</h1>
@stop

@section('content')
    <form action="{{route("modeladmin.savedata")}}" method="post">
        @csrf
        @component("modeladmin::layout.components.card",["title"=>"Create CRUD"])
            @component("modeladmin::layout.components.card-collapse",["title"=>"Configurações Iniciais","open"=>true])
                <div class="form-group">
                    <label>Classe</label>
                    <input class="form-control" name="model_class">
                </div>
                <div class="form-group">
                    <label>Título da classe</label>
                    <input class="form-control" name="title">
                </div>
                <div class="form-group">
                    <label>Tabela</label>
                    <input class="form-control" name="table">
                </div>
            @endcomponent
            @component("modeladmin::layout.components.card-collapse",["title"=>"Atributos","id"=>"collapse3","open"=>false])
                <div class="form-groups">
                    <div id="content_attributes" class="form-group">
                        <div class="form-group">
                            <label>Ordem da posição</label>
                            <input class="form-control" type="number" name="position_order[]">
                        </div>
                        <div class="form-group">
                            <label>Nome Variável</label>
                            <input class="form-control" required name="fillable_var[]">
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
                            <input class="form-control" required name="label[]">
                        </div>
                        <div class="form-group">
                            <label>Regras</label>
                            <input class="form-control" name="rules[]">
                        </div>
                        <div class="form-group">
                            <label>Placeholder</label>
                            <input class="form-control" name="placeholder[]">
                        </div>
                        <div class="form-group">
                            <label>Classe Customizada</label>
                            <input class="form-control" name="class_field[]">
                        </div>
                        <div class="form-group">
                            <label>Atributos</label>
                            <input class="form-control" placeholder="separados por ;" name="attributes[]">
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-3">
                                    <label>Show In Form</label>
                                    <select class="form-control" name="show_in_form[]">
                                        <option value="1" selected>SIM</option>
                                        <option value="0">NÃO</option>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <label>Show In Table</label>
                                    <select class="form-control" name="show_in_table[]">
                                        <option value="1" selected>SIM</option>
                                        <option value="0">NÃO</option>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <label>Show In Edit</label>
                                    <select class="form-control" name="show_in_edit[]">
                                        <option value="1" selected>SIM</option>
                                        <option value="0">NÃO</option>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <label>Relacionável</label>
                                    <select class="form-control" name="is_relationable[]">
                                        <option value="1">SIM</option>
                                        <option value="0" selected>NÃO</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Relacionável com a classe</label>
                            <input class="form-control" name="relationable_with_class[]">
                        </div>
                        <div class="form-group">
                            <label>Nome da Função de relacionamento</label>
                            <input class="form-control" name="relationship_function[]">
                        </div>
                        <div class="form-group">
                            <label>Tipo de Relação</label>
                            <select class="form-control" name="relationship_type_function[]">
                                @foreach(\Jeanderson\modeladministrator\Utils\RelationsTypes::TYPES as $type)
                                    <option value="{{$type}}">{{$type}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Tabela de Relação muitos para muitos</label>
                            <input class="form-control" name="table_relation_many_to_many[]">
                        </div>
                        <hr>
                    </div>
                    <div id="recebe_attributes">

                    </div>
                    <div class="form-group">
                        <button onclick="add_atributos();" type="button" class="btn btn-success">Adicionar</button>
                    </div>
                </div>
            @endcomponent
            @component("modeladmin::layout.components.card-collapse",["title"=>"Rotas","id"=>"collapse2","open"=>false])
                <div class="form-groups">
                    @foreach(\Jeanderson\modeladministrator\Utils\RoutesInfo::$route_info as $route_info)
                        <div class="form-group">
                            <label>Tipo</label>
                            <input disabled class="form-control" name="type[]" value="{{$route_info["type"]}}">
                        </div>
                        <div class="form-group">
                            <label>URL</label>
                            <input class="form-control"
                                   routetype="{{$route_info["type"] == "index" ? "":"/".$route_info["type"]}}"
                                   name="url[]"
                                   value="classname/{{$route_info["type"]}}">
                        </div>
                        <div class="form-group">
                            <label>Função</label>
                            <input class="form-control" name="functions[]" value="{{$route_info["functions"]}}">
                        </div>
                        <div class="form-group">
                            <label>Método</label>
                            <input class="form-control" name="method[]" value="{{$route_info["method"]}}">
                        </div>
                        <div class="form-group">
                            <label>Visivel para todos</label>
                            <select name="visible_to_everyone[]" class="form-control">
                                @if($route_info["visible_to_everyone"])
                                    <option selected value="1">SIM</option>
                                    <option value="0">NÃO</option>
                                @else
                                    <option value="1">SIM</option>
                                    <option value="0" selected>NÃO</option>
                                @endif
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Permissões</label>
                            <input name="permissions[]" class="form-control">
                        </div>
                        <hr>
                    @endforeach
                </div>
            @endcomponent
            <div class="form-group">
                <button type="submit" class="btn btn-success">Salvar</button>
            </div>
        @endcomponent
    </form>
@stop
@section("js")
    <script>
        function alter_route_url(classname) {
            $("input[name='url[]']").each(function (index, element) {
                let e = $(element);
                e.val(classname.toLowerCase() + e.attr("routetype"));
            });
        }

        $("input[name='model_class']").keyup(function (event) {
            alter_route_url(this.value);
            $("input[name='table']").val(this.value.toLowerCase() + "s");
        });

        function add_atributos() {
            $("#recebe_attributes").append($("#content_attributes").html());
        }
    </script>
@stop
