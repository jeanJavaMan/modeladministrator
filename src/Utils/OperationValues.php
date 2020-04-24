<?php


namespace Jeanderson\modeladministrator\Utils;


class OperationValues
{
    public static function getOperatorsValues(){
        return [
            "Igual" => "=",
            "Menor que" => "<",
            "Maior que" => ">",
            "Menor ou Igual" => "<=",
            "Maior ou Igual" => ">=",
            "Menor ou Maior" => "<>",
            "Diferente de" => "!=",
            "Contém" => "like",
            "Não contém" => "not like"
        ];
    }

//    public static function getOperatorWhere(){
//        return [
//            "Entre" => "Between",
//            "Onde a data é" => "Date",
//            "Onde o mês é" => "Month",
//            "Onde o dia é" => "Day",
//            "Onde o ano é" => "Year"
//        ];
//    }

    public static function getComparatorWhere(){
        return [
          "E" => "",
          "Ou" => "or"
        ];
    }

    public static function getOrderFunction(){
        return [
            __("modeladminlang::default.asc") => "asc",
            __("modeladminlang::default.desc") => "desc"
        ];
    }
}
