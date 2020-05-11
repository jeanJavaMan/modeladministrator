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
         * @param $include_custom_table
         * @param $custom_query
         * @param $custom_filter_query
         * @return Factory|View
         */
        private static function show(ModelConfig $modelConfig, Request $request,$include_custom_table,$custom_query,$custom_filter_query)
        {
            return view("modeladmin::screens.table-view")->with(["modelConfig" => $modelConfig,"request"=>$request,"include_custom_table"=>$include_custom_table,"custom_query"=>$custom_query,"custom_filter_query"=>$custom_filter_query]);
        }

        /**
         * @param $modelClass
         * @param Request $request
         * @param $include_custom_table
         * @param $custom_query
         * @param $custom_filter_query
         * @return Factory|View
         */
        public static function create($modelClass, Request $request,$include_custom_table,$custom_query,$custom_filter_query = null)
        {
            return self::show(ModelConfig::getModelConfigWithCache($modelClass),$request,$include_custom_table,$custom_query,$custom_filter_query);
        }
    }
