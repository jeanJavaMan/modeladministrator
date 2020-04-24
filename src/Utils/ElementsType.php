<?php


namespace Jeanderson\modeladministrator\Utils;


class ElementsType
{
    public const TYPES = ["text","number","textarea","date","checkbox","select"];
    public const ATTRIBUTES_PROPERTY = ["text" => "string", "number" => "int", "checkbox" => "bool", "select" => "int", "date" => "string", "textarea" => "string"];
}
