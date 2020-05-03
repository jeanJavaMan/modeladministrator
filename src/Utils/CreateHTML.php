<?php


    namespace Jeanderson\modeladministrator\Utils;


    use Jeanderson\modeladministrator\Models\CustomModel;
    use Jeanderson\modeladministrator\Models\Element;
    use Jeanderson\modeladministrator\Models\ModelConfig;

    class CreateHTML
    {
        /**
         * @var ModelConfig
         */
        protected $modelConfig;

        /**
         * @var array
         */
        protected $routesData = [];

        /**
         * CreateHTML constructor.
         * @param ModelConfig $modelConfig
         */
        public function __construct(ModelConfig $modelConfig)
        {
            $this->modelConfig = $modelConfig;
            $this->prepareRoutes();
        }

        protected function prepareRoutes()
        {
            foreach ($this->modelConfig->routes_cache() as $route) {
                $this->routesData['routes'][$route->type] = $route;
            }
        }

        /**
         * @return \Illuminate\Database\Eloquent\Collection|Element[]|mixed
         */
        public function getElements()
        {
            return $this->modelConfig->elements_cache();
        }

        /**
         * Return HTML elements th
         * @return string
         */
        public function prepareTableColumns()
        {
            $html = "";
            foreach ($this->modelConfig->elements_cache() as $element) {
                if (request()->has("c")) {
                    if (!is_null(request()->get($element->fillable_var))) {
                        $html .= "<th>" . $element->label . "</th>";
                    }
                } else {
                    if ($element->show_in_table) {
                        $html .= "<th>" . $element->label . "</th>";
                    }
                }
            }
            return $html;
        }

        /**
         * @return array
         */
        public function prepareTableColumnsMobile()
        {
            $html = [];
            foreach ($this->modelConfig->elements_cache() as $element) {
                if (request()->has("c")) {
                    if (!is_null(request()->get($element->fillable_var))) {
                        $html[] = "<th class='tr-head'>" . $element->label . "</th>";
                    }
                } else {
                    if ($element->show_in_table) {
                        $html[] = "<th class='tr-head'>" . $element->label . "</th>";
                    }
                }
            }
            return $html;
        }

        /**
         * Return HTML elements td
         * @param CustomModel $model
         * @return string
         */
        public function getTableColumnDataForRow(CustomModel $model)
        {
            $html = "";
            foreach ($this->modelConfig->elements_cache() as $element) {
                if (request()->has("c")) {
                    if (!is_null(request()->get($element->fillable_var))) {
                        $this->prepareTableDataForRow($html, $element, $model);
                    }
                } else {
                    if ($element->show_in_table) {
                        $this->prepareTableDataForRow($html, $element, $model);
                    }
                }
            }
            return $html;
        }

        public function getTableColumnDataForRowInShowView(CustomModel $model)
        {
            $html = "";
            foreach ($this->modelConfig->elements_cache() as $element) {
                if ($element->show_in_table) {
                    $this->prepareTableDataForRow($html, $element, $model);
                }
            }
            return $html;
        }

        public function getTableColumnDataForRowMobile(CustomModel $model)
        {
            $html = [];
            foreach ($this->modelConfig->elements_cache() as $element) {
                if (request()->has("c")) {
                    if (!is_null(request()->get($element->fillable_var))) {
                        $this->prepareTableDataForRowMobile($html, $element, $model);
                    }
                } else {
                    if ($element->show_in_table) {
                        $this->prepareTableDataForRowMobile($html, $element, $model);
                    }
                }
            }
            return $html;
        }

        private function prepareTableDataForRowMobile(&$html, Element $element, CustomModel $model)
        {
            if ($element->is_relationable) {
                $function = $element->relationship_function;
                $result = $model->$function()->first();
                $html[] = "<td>" . ($result ? $result->toView() : "") . "</td>";
            } else {
                $fillable = $element->fillable_var;
                if (key_exists($fillable, $model->getColumnCustomStyle())) {
                    $html[] = "<td class='" . $model->getColumnCustomStyle()[$fillable]["class"] . "' style='" . $model->getColumnCustomStyle()[$fillable]["style"] . "'>" . $model->$fillable . "</td>";
                } else {
                    $html[] = "<td>" . $model->$fillable . "</td>";
                }
            }
        }

        private function prepareTableDataForRow(&$html, Element $element, CustomModel $model)
        {
            if ($element->is_relationable) {
                $function = $element->relationship_function;
                $result = $model->$function()->first();
                $html .= "<td>" . ($result ? $result->toView() : "") . "</td>";
            } else {
                $fillable = $element->fillable_var;
                if (key_exists($fillable, $model->getColumnCustomStyle())) {
                    $html .= "<td class='" . $model->getColumnCustomStyle()[$fillable]["class"] . "' style='" . $model->getColumnCustomStyle()[$fillable]["style"] . "'>" . $model->$fillable . "</td>";
                } else {
                    $html .= "<td>" . $model->$fillable . "</td>";
                }
            }
        }

        public function getRoutesForType($type = "show")
        {
            return $this->routesData["routes"][$type];
        }

    }
