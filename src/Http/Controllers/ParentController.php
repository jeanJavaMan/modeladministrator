<?php

    namespace Jeanderson\modeladministrator\Http\Controllers;


    use Carbon\Carbon;
    use Cassandra\Custom;
    use http\Client\Response;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
    use Illuminate\Foundation\Bus\DispatchesJobs;
    use Illuminate\Foundation\Validation\ValidatesRequests;
    use Illuminate\Http\Request;
    use Illuminate\Routing\Controller;
    use Illuminate\Support\Str;
    use Jeanderson\modeladministrator\Models\CustomModel;
    use Jeanderson\modeladministrator\Models\Element;
    use Jeanderson\modeladministrator\Models\Route;
    use Jeanderson\modeladministrator\Models\view\Form;
    use Jeanderson\modeladministrator\Models\view\Table;
    use RealRashid\SweetAlert\Facades\Alert;
    use PDF;

    /**
     * Class ParentController
     * @property Route route
     * @package Jeanderson\modeladministrator\Http\Controllers
     */
    class ParentController extends Controller
    {
        use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

        protected $route;

        public function directionControl($url, Request $request)
        {
            try {
                $this->route = Route::getRouteCache($url);
                if ($this->route->visible_to_everyone || $this->route->checkIfUserHaspermission()) {
                    $function = $this->route->functions;
                    return $this->$function($request);
                }
                return view("modeladmin::screens.no-permission-user");
            } catch (\Throwable $ex) {
                \Log::error($ex);
                return abort(500);
            }
        }

        public function directionControlProtected($url, Request $request)
        {
            $path_url = $request->path();
            if (isset(config("modeladmin.block_routes")[$path_url])) {
                \Log::info("Tentativa de acesso a rota protegida: " . $path_url);
                return abort(404);
            } else {
                return $this->directionControl($url, $request);
            }
        }

        protected function no_permission_user()
        {
            return view("modeladmin::screens.no-permission-user");
        }

        protected function check_user_permission($permission)
        {
            return auth()->user()->hasPermissionTo($permission);
        }

        public function list_data(Request $request, $include_custom_table = "", $includes = [])
        {
            return Table::create($this->route->modelConfig_cache()->model_class, $request, $include_custom_table, $custom_query = null, $custom_filter_query = null, $includes);
        }

        public function create(Request $request, $include_form = "")
        {
            $request->flush();
            return Form::create($this->route->modelConfig_cache()->model_class, $include_form);
        }

        public function store(Request $request)
        {
            try {
                $validator = $this->validate_ajax($request);
                if ($validator->fails()) {
                    return \response()->json(array("success" => false, "error" => $validator->errors()->all()));
                } else {
                    $this->saveModel($this->route->modelConfig_cache()->model_class, $request);
                    return \response()->json(array("success" => true));
                }
            } catch (\Throwable $ex) {
                \Log::error($ex);
                return \response()->json(array("success" => false, "error" => ["msg" => $ex->getMessage()]));
            }
        }

        public function show(Request $request, $include_show = "", $include_options = "")
        {
            try {
                if ($request->has("id")) {
                    $model = $this->route->modelConfig_cache()->model_class::find($request->get("id"));
                    $view = view("modeladmin::screens.show-view-ajax")
                        ->with(["model" => $model, "modelConfig" => $this->route->modelConfig_cache(), "include_show" => $include_show, "include_options" => $include_options])
                        ->render();
                    return response()->json(array('success' => true, 'html' => $view));
                }
            } catch (\Throwable $ex) {
                \Log::error($ex);
                return response()->json(array('success' => false, 'html' => "", "error" => $ex->getMessage()));
            }
        }

        public function edit(Request $request, $include_edit = "")
        {
            try {
                if ($request->has("id")) {
                    $model = $this->route->modelConfig_cache()->model_class::find($request->get("id"));
                    $this->getAttributesWithValues($request, $model);
                    $view = view("modeladmin::screens.edit-view")
                        ->with(["model" => $model, "modelConfig" => $this->route->modelConfig_cache(), "include_edit" => $include_edit])
                        ->render();
                    return response()->json(array('success' => true, 'html' => $view));
                }
            } catch (\Throwable $ex) {
                \Log::error($ex);
                return response()->json(array('success' => false, 'html' => "", "error" => $ex->getMessage()));
            }
        }

        public function update(Request $request)
        {
            if ($request->has("id")) {
                try {
                    $validator = $this->validate_ajax($request);
                    if ($validator->fails()) {
                        return \response()->json(array("success" => false, "error" => $validator->errors()->all()));
                    } else {
                        $model = $this->route->modelConfig_cache()->model_class::find($request->get("id"));
                        $elements = $this->route->modelConfig_cache()->elements_cache();
                        $model->fieldsUpdate($request->only($model->getFillable()));
                        $model->save();
                        $this->saveModelWithFile($request, $model, $elements);
                        $this->saveModelWithRelation($request, $model, $elements, true);
                        return \response()->json(array("success" => true));
                    }
                } catch (\Throwable $ex) {
                    \Log::error($ex);
                    return \response()->json(array("success" => false, "error" => ["msg" => $ex->getMessage()]));
                }
            } else {
                return \response()->json(array("success" => false, "error" => ["msg" => "Without ID"]));
            }
        }

        public function delete(Request $request)
        {
            if ($request->has("id")) {
                try {
                    $id = \Crypt::decrypt($request->post("id"));
                    $model_class = $this->route->modelConfig_cache()->model_class;
                    $model = $model_class::find($id);
                    if($model->secureDelete()){
                        Alert::success("Sucesso", "Deletado com sucesso!");
                    }else{
                        Alert::warning("Item em uso", "Não foi possível excluir, pois já se encontra em uso!")->autoClose(0);
                    }
                } catch (\Throwable $ex) {
                    \Log::error($ex);
                    Alert::error("Erro", "Houve um erro! " . $ex->getMessage())->autoClose(0);
                }

            }
            return redirect()->back();
        }

        public function pdf(Request $request)
        {
            if ($request->has("id")) {
                set_time_limit(0);
                $data["modelConfig"] = $this->route->modelConfig_cache();
                $data["model"] = $this->route->modelConfig_cache()->model_class::find($request->get("id"));
                $pdf = PDF::loadView('modeladmin::layout.pdf.pdf-view', $data);
                return $pdf->stream();
            }
        }

        public function attachment_delete(Request $request)
        {
            if ($request->has("key")) {
                try {
                    $model = $this->route->modelConfig_cache()->model_class::find($request->get("id"));
                    $key = $request->get("key");
                    /**@var CustomModel $model */
                    $attachment = $model->attachment($key);
                    $attachment->delete();
                    Alert::success("Sucesso", "Excluido com sucesso");
                } catch (\Throwable $ex) {
                    \Log::error($ex);
                    Alert::error("Erro", "Houve um erro: " . $ex->getMessage());
                }
            }
            return redirect()->back();
        }

        /**
         * @param Request $request
         * @return \Illuminate\Contracts\Validation\Validator
         */
        public function validate_ajax(Request $request)
        {
            $rules = $this->route->modelConfig_cache()->getAllRulesForElements();
            return \Validator::make($request->all(), $rules);
        }

        /**
         * Faz uma query em todas as colunas e onde encontrar primeiro, retorna uma resposta Json
         * @param Request $request
         * @return \Illuminate\Http\JsonResponse
         */
        public function find_ajax(Request $request)
        {
            $modelClass = $this->route->modelConfig_cache()->model_class;
            /**@var \Illuminate\Database\Eloquent\Builder $query */
            $fillableSearch = $modelClass::$fillableSearch;
            if ($modelClass::$use_custom_query_search) {
                $query = $modelClass::customQuerySearch($request);
            } else {
                $query = $modelClass::query();
                $query = $query->orWhere("id",$request->get("search"));
                foreach ($fillableSearch as $fillable) {
                    $query = $query->orWhere($fillable, 'LIKE', "%" . $request->get("search") . "%");
                }
            }
            $json_response = [];
            $fillableSearch[] = "id";
            $results = $query->paginate(7,$fillableSearch);
            /**@var CustomModel $result */
            foreach ($results as $result) {
                $json_response[] = ["id" => $result->id, "text" => $result->toView()];
            }
            return response()->json([
                'items' => $json_response, 'pagination' => $results->nextPageUrl() ? true : false
            ]);
        }

        /**
         * Retorna um HTML com os campos para cada tipo de campo do modelo.
         * @param Request $request
         * @return \Illuminate\Http\JsonResponse
         */
        public function getInputForSearch(Request $request)
        {
            try {
                $modelConfig = $this->route->modelConfig_cache();
                $input_search = $request->get("input_search");
                /**@var Element $element */
                $element = $modelConfig->elements_cache()->first(function ($item) use ($input_search) {
                    /**@var Element $item */
                    return $item->fillable_var == $input_search;
                });
                if ($element->is_relationable) {
                    $html = view("modeladmin::layout.inputs.search.input-relationable")->with(["modelConfig" => $modelConfig, "element" => $element])->render();
                } else {
                    $html = view("modeladmin::layout.inputs.search.input-" . $element->type_input)->with(["modelConfig" => $modelConfig, "element" => $element])->render();
                }
                return response()->json(array('success' => true, 'html' => $html, 'relationable' => $element->is_relationable));
            } catch (\Throwable $ex) {
                return response()->json(array('success' => false, 'html' => "", "error" => $ex->getMessage()));
            }

        }

        /**
         * Função para que quando seja editado, ou retornado com error as mensagens de validação os campos que são para relacionamentos sejam preenchidos corretamente com os valores dos campos relacionáveis.
         * @param Request $request
         * @param $model
         */
        protected function getAttributesWithValues(Request $request, $model)
        {
            $elements = $this->route->modelConfig_cache()->elements_cache();
            $data = $model->getAttributes();
            $elements_relationable = $elements->filter(function ($element) {
                /**@var Element $element */
                return Str::contains($element->relationship_type_function, "Many");
            });
            foreach ($elements_relationable as $element) {
                $function = $element->relationship_function;
                $relationship = $model->$function()->get();
                $values = [];
                foreach ($relationship as $relationModel) {
                    $values[] = $relationModel->id;
                }
                $data[$element->fillable_var] = $values;
            }
            $request->request->add($data);
            $request->flash();
        }


        /**
         * Executa o processo de salvar uma classe de modelo no banco de dados.
         * @param $modelClass
         * @param Request $request
         * @return bool
         */
        protected function saveModel($modelClass, Request $request)
        {
            $elements = $this->route->modelConfig_cache()->elements_cache();
            /**@var CustomModel $model */
            $model = new $modelClass();
            $model->fillInFields($request->only($model->getFillable()));
            $model->save();
            $this->saveModelWithFile($request, $model, $elements);
            return $this->saveModelWithRelation($request, $model, $elements);
        }

        /**
         * @param Request $request
         * @param CustomModel $model
         * @param $elements
         * @throws \Exception
         */
        protected function saveModelWithFile(Request $request, &$model, $elements)
        {
            $file_elements = $elements->filter(function ($element) {
                return $element->type_input == "file";
            });
            /**@var Element $file_element */
            foreach ($file_elements as $file_element) {
                if ($request->hasFile($file_element->fillable_var)) {
                    $date = Carbon::now()->format("dmYHi");
                    $files_or_file = $request->file($file_element->fillable_var);
                    if (Str::contains($file_element->attributes, "multiple")) {
                        foreach ($files_or_file as $file) {
                            $name = explode(".", $file->getClientOriginalName())[0];
                            $model->attach($file, ["title" => $name . "-" . $date]);
                        }
                    } else {
                        $name = explode(".", $files_or_file->getClientOriginalName())[0];
                        $model->attach($files_or_file, ["title" => $name . "-" . $date]);
                    }
                }
            }
        }

        /**
         * @param Request $request
         * @param $model
         * @param $elements
         * @param bool $is_update
         * @return bool
         */
        protected function saveModelWithRelation(Request $request, &$model, $elements, $is_update = false)
        {
            $elements_relationables = $elements->filter(function ($element) {
                /**@var Element $element */
                return $element->is_relationable && !Str::contains($element->relationship_type_function, "One");
            });
            /**@var Element $element */
            foreach ($elements_relationables as $element) {
                if ($request->has($element->fillable_var)) {
                    switch ($element->relationship_type_function) {
                        case "hasMany":
                            $this->saveHasMany($request, $model, $element);
                            break;
                        case "belongsToMany":
                            $this->saveBelongsToMany($request, $model, $element, $is_update);
                            break;
                    }
                }
            }
            return true;
        }

        /**
         * @param Request $request
         * @param \Eloquent $model
         * @param Element $element
         */
        private function saveHasMany(Request $request, &$model, $element)
        {
            $model_ids = $request->get($element->fillable_var);
            $methodRelation = $element->relationship_function;
            $class_related = $element->relationable_with_class;
            $models = $class_related::find($model_ids);
            $model->$methodRelation()->saveMany($models);
        }

        /**
         * @param Request $request
         * @param \Eloquent $model
         * @param Element $element
         * @param bool $is_update
         */
        private function saveBelongsToMany(Request $request, &$model, $element, $is_update = false)
        {
            $model_ids = $request->get($element->fillable_var);
            $methodRelation = $element->relationship_function;
            if ($is_update) {
                $model->$methodRelation()->sync($model_ids);
            } else {
                $model->$methodRelation()->attach($model_ids);
            }

        }

    }
