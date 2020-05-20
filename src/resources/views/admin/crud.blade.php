@extends('adminlte::page')

@section('title', 'ModelAdmin')

@section('content_header')
    <h1 class="m-0 text-dark">Criar CRUD padrão</h1>
@stop
@section("css")
    @include("modeladmin::css.AppCss")
@endsection

@section('content')
    <form id="form" action="{{route("modeladmin.savedata")}}" method="post">
        @component("modeladmin::layout.components.card",["title"=>"Create CRUD"])
            @component("modeladmin::layout.components.card-collapse",["title"=>"Configurações Iniciais","open"=>true])
                @csrf
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
                    <div class="row">
                        <div id="content_dados" class="col-md-5">
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
                        <div id="receive_content" class="col-md-5">
                            <div class="form-group"><label>Adicionados</label></div>
                        </div>
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
                            <input type="hidden" class="form-control" name="type_route[]"
                                   value="{{$route_info["type"]}}">
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
                <button onclick="enviar_dados()" type="button" class="btn btn-success">Salvar</button>
            </div>
        @endcomponent
    </form>
@stop
@section("js")
    @include("modeladmin::js.jquery-fix-clone")
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
            let variavel = $("#content_dados").find("input[name='fillable_var[]']").val();
            $("#receive_content").append("<div id='div_content_"+variavel+"' class='form-group'><div class='row'><div class='col'><input readonly value='"+variavel+"' class='form-control' ></div><div class='col-md-2'><button onclick=\"remover_div('#div_content_"+variavel+"')\" class='btn btn-danger' type='button'>Remover</button></div></div><div id='"+variavel+"' style='display: none'></div></div>");
            $("#content_dados").children().clone().appendTo("#"+variavel);
            $("#content_dados").find("input").val("");
        }

        function remover_div(div) {
            $(div).remove();
        }

        function enviar_dados() {
            $("#content_dados").find("input").removeAttr("name");
            $("#content_dados").find("select").removeAttr("name");
            $("#form").submit();
        }
    </script>
@stop
