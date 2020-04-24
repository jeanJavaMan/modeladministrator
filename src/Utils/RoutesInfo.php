<?php


namespace Jeanderson\modeladministrator\Utils;


class RoutesInfo
{
    public static $route_info = [
        [
            "type" => "index",
            "functions" => "list_data",
            'visible_to_everyone' =>false,
            "method" => "GET"
        ],
        [
            "type" => "create",
            "functions" => "create",
            'visible_to_everyone' =>false,
            "method" => "GET"
        ],
        [
            "type" => "delete",
            "functions" => "delete",
            'visible_to_everyone' =>false,
            "method" => "POST"
        ],
        [
            "type" => "show",
            "functions" => "show",
            'visible_to_everyone' =>false,
            "method" => "GET"
        ],
        [
            "type" => "searchinput",
            "functions" => "getInputForSearch",
            'visible_to_everyone' =>true,
            "method" => "GET"
        ],
        [
            "type" => "update",
            "functions" => "update",
            'visible_to_everyone' =>false,
            "method" => "POST"
        ],
        [
            "type" => "edit",
            "functions" => "edit",
            'visible_to_everyone' =>false,
            "method" => "GET"
        ],
        [
            "type" => "search",
            "functions" => "find_ajax",
            'visible_to_everyone' =>false,
            "method" => "GET"
        ],
        [
            "type" => "pdf",
            "functions" => "pdf",
            'visible_to_everyone' =>false,
            "method" => "GET"
        ],
        [
            "type" => "attachment_delete",
            "functions" => "attachment_delete",
            'visible_to_everyone' =>false,
            "method" => "GET"
        ],
        [
            "type" => "store",
            "functions" => "store",
            'visible_to_everyone' =>false,
            "method" => "POST"
        ]
    ];
}
