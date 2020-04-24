<?php
namespace Jeanderson\modeladministrator\Models\view;

use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Jeanderson\modeladministrator\Models\ModelConfig;

/**
 * Class Table
 * @package Jeanderson\modeladministrator\Models\view
 */
class Table
{
    /**
     * @param ModelConfig $modelConfig
     * @param Request $request
     * @return Factory|View
     */
    private static function show(ModelConfig $modelConfig, Request $request)
    {
        return view("modeladmin::screens.table-view")->with(["modelConfig" => $modelConfig,"request"=>$request]);
    }

    /**
     * @param $modelClass
     * @param Request $request
     * @return Factory|View
     */
    public static function create($modelClass, Request $request)
    {
        return self::show(ModelConfig::getModelConfigWithCache($modelClass),$request);
    }
}
