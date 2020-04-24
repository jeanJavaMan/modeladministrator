<?php

namespace Jeanderson\modeladministrator\Models;

use Cache;
use Eloquent;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Element
 * @property int id
 * @property int modelconfigs_id
 * @property string fillable_var
 * @property string type_input
 * @property string label
 * @property string desc
 * @property boolean show_in_form
 * @property boolean show_in_table
 * @property boolean show_in_edit
 * @property boolean is_relationable
 * @property string relationable_with_class
 * @property string relationship_function
 * @property string relationship_type_function
 * @property string table_relation_many_to_many
 * @property string rules
 * @property string placeholder
 * @property string class_field
 * @property string value
 * @property string attributes
 * @property int position_order
 * @package jeanderson\modeladministrator\Models
 */
class Element extends Eloquent
{
    protected $fillable = ['modelconfigs_id', 'fillable_var', 'type_input', 'label', 'desc', 'show_in_form', 'show_in_table','show_in_edit', 'is_relationable', 'relationable_with_class', 'relationship_function','relationship_type_function','table_relation_many_to_many', 'rules', 'placeholder', 'class_field', 'value', 'attributes','position_order'];
    protected $table = "elements";

    /**
     * @return BelongsTo
     */
    public function modelconfig(){
        return $this->belongsTo(ModelsConfig::class);
    }

    /**
     * @return BelongsToMany
     */
    public function options(){
        return $this->belongsToMany(Option::class,'elements_options','element_id','option_id');
    }

    /**
     * @return Option[]|mixed
     */
    public function options_cache(){
        return Cache::rememberForever("element-id-".$this->id,function (){
           return $this->options()->get();
        });
    }
    public function save(array $options = [])
    {
        $result = parent::save($options);
        $this->generatePositionOrder();
        return $result;
    }
    private function generatePositionOrder(){
        if($this->position_order == null){
            $count_elements = ModelConfig::find($this->modelconfigs_id)->elements()->count();
            $this->position_order = $count_elements;
            $this->update();
        }
    }
}
