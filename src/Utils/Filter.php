<?php


    namespace Jeanderson\modeladministrator\Utils;


    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Http\Request;
    use Illuminate\Support\Str;
    use Jeanderson\modeladministrator\Models\Element;
    use Jeanderson\modeladministrator\Models\ModelConfig;

    class Filter
    {
        protected $modelConfig, $request;
        /**
         * Lista de classes que já foi feito o join
         * @var array
         */
        private $class_relations_join = [];
        /**
         * Contém a query em forma de texto para mostrar ao usuário
         * @var
         */
        private $query_text;

        /**
         * Filter constructor.
         * @param ModelConfig $modelConfig
         * @param Request $request
         */
        public function __construct(ModelConfig $modelConfig, Request $request)
        {
            $this->modelConfig = $modelConfig;
            $this->request = $request;
        }

        /**
         * @param $custom_query
         * @param $custom_filter_query
         * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
         */
        public function filter($custom_query,$custom_filter_query)
        {
            if ($this->request->has("filter")) {
                return $this->create_query($custom_filter_query);
            } else {
                if(!is_null($custom_query)){
                    return $custom_query;
                }else{
                    $modelClass = $this->modelConfig->model_class;
                    return  $modelClass::paginate(30);
                }
            }
        }

        private function create_query($custom_filter_query)
        {
            /**@var Builder $query */
            if(!is_null($custom_filter_query)){
                $query = $custom_filter_query;
            }else{
                $query = $this->modelConfig->model_class::query();
            }
            for ($i = 0; $i < count($this->request->post("field_search")); $i++) {
                $field = $this->request->post("field_search")[$i];
                $operator = $this->request->post("operator")[$i] ?? $this->request->post("operator_relation")[$i];
                $value = $this->request->post("value_search")[$i];
                $this->mountQuery($query, $field, $operator, $value, $i);
            }
            $this->checkGroupBy($query);
            $this->checkOrderBy($query);
            $this->request->request->add(["query_text"=>$this->query_text]);
            return $query->paginate($this->request->post("show_for_page"), [$this->modelConfig->table . ".*"]);
        }

        private function mount_text_query($field,$operator,$value, $is_relation = false,$relationFunction = ""){
            $element = $this->getElementForField($field);
            if($element){
                if($is_relation){
                    $this->query_text .= " <strong style='color: #ff8b20'>".$relationFunction."</strong> ".$element->label." <strong style='color: deepskyblue'>".array_search($operator,OperationValues::getOperatorsValues())."</strong> <strong>".$value."</strong> ";
                }else{
                    $this->query_text .= $element->label." <strong style='color: deepskyblue'>".array_search($operator,OperationValues::getOperatorsValues())."</strong> <strong>".$value."</strong> ";
                }
            }
        }

        private function checkGroupBy(Builder &$query){
            if($this->request->post("groupby")){
                $groupby = $this->modelConfig->table.".".$this->request->post("groupby");
                $label_groupby = $this->getElementForField($this->request->post("groupby"))->label;
                $this->query_text .= " <strong style='color: #ff8b20'>".__("modeladminlang::default.groupby")."</strong> ".$label_groupby;
                $query = $query->groupBy($groupby);
            }
        }

        private function checkOrderBy(Builder &$query){
            if($this->request->post("orderby")){
                $function = $this->request->post("order_by_func");
                $orderby = $this->modelConfig->table.".".$this->request->post("orderby");
                $this->query_text .= " <strong style='color: #ff8b20'>".__("modeladminlang::default.orderby")."</strong> ".$this->request->post("orderby")." <strong style='color: #ff8b20'>".__("modeladminlang::default.in_order")."</strong> ".array_search($function,OperationValues::getOrderFunction());
                $query = $query->orderBy($orderby,$function);
            }
        }

        private function mountQuery(Builder &$query, $field, $operator, $value, $i)
        {
            if ($this->request->has("operator_relation") && $i > 0) {
                $operator_relation = $this->request->post("operator_relation")[$i - 1];
                if ($operator_relation) {
                    $function = $operator_relation . "Where";
                    $this->mount_text_query($field,$operator,$value, true,array_search($operator_relation,OperationValues::getComparatorWhere()));
                    $this->checkTypeQuery($query, $field, $operator, $value, $function);
                } else {
                    $this->mount_text_query($field,$operator,$value,true,array_search($operator_relation,OperationValues::getComparatorWhere()));
                    $this->checkTypeQuery($query, $field, $operator, $value);
                }
            } else {
                $this->mount_text_query($field,$operator,$value);
                $this->checkTypeQuery($query, $field, $operator, $value);
            }
        }

        private function checkTypeQuery(Builder &$query, $field, $operator, $value, $function = "where")
        {
            if($field){
                $element = $this->getElementForField($field);
                if ($element->is_relationable && $element->relationship_type_function !== "hasOne") {
                    if($element->relationship_type_function == "hasMany"){
                        $this->createQueryRelationHasMany($query,$element,$function,$operator,$value);
                    }else{
                        $this->createQueryRelationManyToMany($query,$element,$function,$operator,$value);
                    }
                } else {
                    if ($operator === "like" || $operator === "not like") {
                        $query = $query->$function($field, $operator, "%" . $value . "%");
                    } else {
                        $query = $query->$function($field, $operator, $value);
                    }
                }
            }
        }

        private function createQueryRelationHasMany(&$query, $element, $function,$operator,$value){
            $modelConfigRelation = ModelConfig::getModelConfigWithCache($element->relationable_with_class);
            $foreingField = $this->getForeingField($modelConfigRelation);
            if(!in_array($modelConfigRelation->table,$this->class_relations_join)){
                $query = $query->leftJoin($modelConfigRelation->table, $this->modelConfig->table . ".id", "=", $modelConfigRelation->table . ".".$foreingField->fillable_var);
                $this->class_relations_join[] = $modelConfigRelation->table;
            }
            $query = $query->$function($modelConfigRelation->table . ".id", $operator, $value);
        }

        private function createQueryRelationManyToMany(&$query,$element, $function,$operator,$value){
            /**@var Element $element*/
            /**@var Builder $query*/
            $table_relationable = $element->table_relation_many_to_many;
            if(!in_array($table_relationable,$this->class_relations_join)){
                $table_singular = Str::singular($this->modelConfig->table);
                $query = $query->leftJoin($table_relationable,$this->modelConfig->table.".id","=",$table_relationable.".".$table_singular."_id");
                $this->class_relations_join[] = $table_relationable;
            }
            $query = $query->$function($table_relationable.".".$element->fillable_var, $operator, $value);
        }

        /**
         * @param ModelConfig $modelConfigRelation
         * @return Element
         */
        private function getForeingField(ModelConfig $modelConfigRelation){
            $thisClass = $this->modelConfig->model_class;
            return $modelConfigRelation->elements_cache()->first(function ($item) use($thisClass){
                /**@var Element $item*/
                return $item->relationable_with_class === $thisClass;
            });
        }

        /**
         * @param $field
         * @return Element
         */
        private function getElementForField($field)
        {
            return $this->modelConfig->elements_cache()->first(function ($item) use ($field) {
                return $item->fillable_var === $field;
            });
        }

        /**
         * @param ModelConfig $modelConfig
         * @param Request $request
         * @param $custom_query
         * @param $custom_filter_query
         * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
         */
        public static function filter_function(ModelConfig $modelConfig, Request $request,$custom_query,$custom_filter_query)
        {
            $filter = new Filter($modelConfig, $request);
            return $filter->filter($custom_query,$custom_filter_query);
        }


    }
