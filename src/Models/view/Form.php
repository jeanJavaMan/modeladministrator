<?php


namespace Jeanderson\modeladministrator\Models\view;

use Cache;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;
use Jeanderson\modeladministrator\Models\ModelConfig;

/**
 * Class Form
 * @package Jeanderson\modeladministrator\Models\view
 */
class Form
{
    /**
     * @param ModelConfig $modelConfig
     * @return Factory|View
     */
    private static function show(ModelConfig $modelConfig)
    {
        return view("modeladmin::screens.form-view")->with("modelConfig", $modelConfig);
    }

    /**
     * @param $modelClass
     * @return Factory|View
     */
    public static function create($modelClass)
    {
        return self::show(ModelConfig::getModelConfigWithCache($modelClass));
    }

}
