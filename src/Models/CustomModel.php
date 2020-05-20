<?php


namespace Jeanderson\modeladministrator\Models;


use Bnb\Laravel\Attachments\HasAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CustomModel extends \Eloquent
{
    use HasAttachment;
    /**
     * @var bool Informa se ira utilizar o método custom_query_search para pesquisar personalizada.
     */
    public $use_custom_query_search = false;

    /**
     * Retorna um texto para exibição dos valores.
     * @return string
     */
    public function toView()
    {
        return __("modeladminlang::default.warning_implement_toview");
    }

    /**
     * Preenche os campos com dados.
     * @param array $data
     */
    public function fillInFields(array $data)
    {
        foreach ($data as $fillable => $value) {
            $element = ModelConfig::getElementForFillableCache("\\".get_called_class(), $fillable);
            if ($element->is_relationable && Str::contains($element->relationship_type_function, "One")) {
                $this->setFillable($fillable, $value, $element);
            } else {
                $this->setFillable($fillable, $value, $element);
            }
        }
    }

    /**
     * Atualiza os campos corretamente.
     * @param array $data
     */
    public function fieldsUpdate(array $data)
    {
        $this->fillInFields($data);
//        $elements_checkbox = ModelConfig::getModelConfigWithCache("\\".get_called_class())->elements_cache()->filter(function ($item) {
//            return $item->type_input == "checkbox";
//        });
//        foreach ($elements_checkbox as $element) {
//            if (!key_exists($element->fillable_var, $data)) {
//                $fillable = $element->fillable_var;
//                $this->$fillable = false;
//            }
//        }
    }

    /**
     * Altera o valor do campo de acordo com o seu tipo.
     * @param $fillable
     * @param $value
     * @param Element $element
     */
    private function setFillable($fillable, $value, $element)
    {
        if(!empty($value) || !is_null($value)){
            if($element->type_input == "password"){
                $this->$fillable = \Hash::make($value);
            }else{
                $this->$fillable = $value;
            }
        }
    }

    /**
     * Query customizada para ser usada em pesquisas! Para que ela funcione é necessário que o atributo use_custom_query_search esteja true.
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function customQuerySearch(Request $request)
    {
        return CustomModel::query();
    }

    /**
     * Retorna uma array com estilo customizados para cada coluna da tabela.
     * @return array
     */
    public function getColumnCustomStyle(){
        return [];
    }

    /**
     * Retorna array de enum referente ao campo
     * @param $fillable
     * @return array
     */
    public static function getEnumsValues($fillable){
        return [];
    }
}
