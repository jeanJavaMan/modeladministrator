<?php


    namespace Jeanderson\modeladministrator\Models\view;

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
         * @param $include_form
         * @return Factory|View
         */
        private static function show(ModelConfig $modelConfig, $include_form)
        {
            return view("modeladmin::screens.form-view")->with(["modelConfig"=>$modelConfig,"include_form"=>$include_form]);
        }

        /**
         * @param $modelClass
         * @param $include_form
         * @return Factory|View
         */
        public static function create($modelClass, $include_form)
        {
            return self::show(ModelConfig::getModelConfigWithCache($modelClass),$include_form);
        }

    }
