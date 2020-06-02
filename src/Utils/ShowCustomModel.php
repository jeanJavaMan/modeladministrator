<?php


    namespace Jeanderson\modeladministrator\Utils;


    use Jeanderson\modeladministrator\Models\CustomModel;

    class ShowCustomModel
    {
        public static function showData($model, $msg = "Dado Nulo ou Excluído"){
            try{
                if($model){
                    return $model->toView();
                }else{
                    return $msg;
                }
            }catch (\Throwable $ex){
                \Log::error($ex);
                return "Não foi possível recuperar dado!";
            }
        }
    }
