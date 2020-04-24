@php
    /**@var \Jeanderson\modeladministrator\Models\ModelConfig $modelConfig*/
    /**@var \Jeanderson\modeladministrator\Models\CustomModel $model*/
    /**@var \Jeanderson\modeladministrator\Models\Element $element*/
@endphp
    <!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>

        .table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 20px;
        }

        table {
            background-color: transparent;
        }

        table {
            border-spacing: 0;
            border-collapse: collapse;
        }

        * {
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }

        table {
            display: table;
            margin-top: -17px;
            /*border-collapse: separate;*/
            /*border-spacing: 2px;*/
            border-color: black;
        }

        .table-bordered > thead > tr > th, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > th, .table-bordered > thead > tr > td, .table-bordered > tbody > tr > td, .table-bordered > tfoot > tr > td {
            border: 1px solid black;
        }

        .bordado {
            border: 1px solid black;
            /*border-collapse: separate;*/
            /*border-spacing: 2px;*/
            /*padding: 0.01em 5px;*/
        }

        .text-center {
            text-align: center;
        }

        .ajusta-img {
            max-width: 10%;
            alignment: center;
        }

        .div-ajusta {
            margin-top: -16.9px;
            padding-left: 5px;
            padding-right: 5px;
            /*margin-bottom: -1px;*/
            margin-bottom: 15.9px;
        }

        .separador {
            margin-top: 30px;
            width: 35%;
            border-top: 1px solid #000;
            list-style-type: none;
        }

    </style>
    <title>{{$modelConfig->title}}</title>
</head>
<body>
<div><p class="bordado text-center">{{__("modeladminlang::default.information")}} {{$modelConfig->title}}
        ID {{$model->id}} </p></div>
@foreach($modelConfig->elements_cache() as $element)
    @if($element->show_in_table)
        @if($element->is_relationable)
            @php
                $function = $element->relationship_function;
                $modelConfigRelation = \Jeanderson\modeladministrator\Models\ModelConfig::getModelConfigWithCache($element->relationable_with_class);
                $results = $model->$function()->get();
            @endphp
            @if($results->count() > 0)
                <div style="margin-top: 10px;" class="bordado div-ajusta text-center">{{$element->label}}</div>
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        @foreach($modelConfigRelation->elements_cache() as $element)
                            @if($element->show_in_table)
                                <th>{{$element->label}}</th>
                            @endif
                        @endforeach
                    </tr>
                    @foreach($results as $result)
                        <tr>
                            @foreach($modelConfigRelation->elements_cache() as $element)
                                @php($fillable = $element->fillable_var)
                                <td>{{$result->$fillable ?? ""}}</td>
                            @endforeach
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        @else
            @php($fillable = $element->fillable_var)
            <div class="bordado div-ajusta">
                <strong>{{$element->label}}: </strong>{{$model->$fillable ?? ""}}
            </div>
        @endif
    @endif

@endforeach
<div class="bordado div-ajusta">
    <strong>{{__("modeladminlang::default.printing_date")}}: </strong> {{\Carbon\Carbon::now()->format("d/m/yy h:s")}}
</div>
</body>
</html>
