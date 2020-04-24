<?php

namespace Jeanderson\modeladministrator\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

/**
 * Class ModelConfig
 * @property int id
 * @property string model_class
 * @property string title
 * @property string table
 * @package jeanderson\modeladministrator\Models
 */
class ModelConfig extends Eloquent
{
    protected $fillable = ['model_class','title','table'];
    protected $table = "modelconfigs";

    /**
     * @return HasMany
     */
    public function elements(){
        return $this->hasMany(Element::class,"modelconfigs_id");
    }

    /**
     * @return HasMany
     */
    public function routes(){
        return $this->hasMany(Route::class,"modelconfigs_id");
    }

    /**
     * @return Element[]|mixed|Collection
     */
    public function elements_cache(){
        return Cache::rememberForever("modelconfig-elements-".$this->id,function (){
            return $this->elements()->orderBy("position_order","asc")->get();
        });
    }

    /**
     * @return Route[]|mixed|Collection
     */
    public function routes_cache(){
        return Cache::rememberForever("modelconfig-routes-".$this->id,function (){
           return $this->routes()->get();
        });
    }

    /**
     * @param $modelClass
     * @return ModelConfig
     */
    public static function getModelConfigWithCache($modelClass){
        /**@var ModelConfig $modelConfig */
        $modelConfig = Cache::rememberForever($modelClass,function () use ($modelClass) {
            return ModelConfig::where("model_class", $modelClass)->first();
        });
        return $modelConfig;
    }

    /**
     * @return array
     */
    public function getAllRulesForElements(){
        $elements = $this->elements_cache();
        $rules = [];
        foreach ($elements as $element){
            if(!empty($element->rules)){
                $rules[$element->fillable_var] = $element->rules;
            }
        }
        return $rules;
    }

    /**
     * @param $modelClass
     * @param $fillable
     * @return Element
     */
    public static function getElementForFillableCache($modelClass,$fillable){
        /**@var Element $element*/
        $element = Cache::rememberForever($modelClass.$fillable,function () use ($modelClass,$fillable) {
            return ModelConfig::getModelConfigWithCache($modelClass)->elements()->where("fillable_var",$fillable)->first();
        });
        return $element;
    }
}
