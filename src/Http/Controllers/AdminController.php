<?php


    namespace Jeanderson\modeladministrator\Http\Controllers;


    use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
    use Illuminate\Foundation\Bus\DispatchesJobs;
    use Illuminate\Foundation\Validation\ValidatesRequests;
    use Illuminate\Http\Request;
    use Illuminate\Routing\Controller;
    use Illuminate\Support\Facades\Artisan;
    use Jeanderson\modeladministrator\Models\Element;
    use Jeanderson\modeladministrator\Models\functions\CreateStub;
    use Jeanderson\modeladministrator\Models\ModelConfig;
    use Jeanderson\modeladministrator\Models\Route;
    use RealRashid\SweetAlert\Facades\Alert;

    class AdminController extends Controller
    {
        use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

        public function index()
        {
            return view("modeladmin::admin.home");
        }

        public function create_crud()
        {
            return view("modeladmin::admin.crud");
        }

        public function create_route()
        {
            return view("modeladmin::admin.custom-routes");
        }

        public function save_route(Request $request)
        {
            try {
                $route = new Route();
                $route->modelconfigs_id = $request->post("modelconfigs_id");
                $route->url = $request->post("url");
                $route->functions = $request->post("functions");
                $route->method = $request->post("method");
                $route->type = $request->post("type");
                $route->visible_to_everyone = $request->post("visible_to_everyone");
                $route->permissions = $request->post("permissions");
                $route->save();
                dump($route);
                Artisan::call("cache:clear");
                \alert()->success("Salvo", "Salvo com sucesso");
            } catch (\Throwable $ex) {
                \Log::error($ex);
                \alert()->error("Erro", $ex->getMessage())->autoClose(0);
            }
            return redirect()->back();
        }

        public function saveData(Request $request)
        {
//            dump($request->all());
            $model_class = $request->post("model_class");
            $title = $request->post("title");
            $table = $request->post("table");
            $modelConfig = new ModelConfig();
            $modelConfig->title = $title;
            $modelConfig->model_class = "\App\Models\\" . $model_class;
            $modelConfig->table = $table;
            $modelConfig->save();
            for ($i = 0; $i < count($request->post("fillable_var")); $i++) {
                $this->saveElements($request, $i, $modelConfig);
            }
            for ($index = 0; $index < count($request->post("url")); $index++) {
                if (!empty($request->post("url")[$index])) {
                    $route = new Route();
                    $route->modelconfigs_id = $modelConfig->id;
                    $route->url = $request->post("url")[$index];
                    $route->functions = $request->post("functions")[$index];
                    $route->method = $request->post("method")[$index];
                    $route->type = $request->post("type")[$index];
                    $route->visible_to_everyone = $request->post("visible_to_everyone")[$index];
                    $route->permissions = $request->post("permissions")[$index];
                    $route->save();
                }
            }
            $createStubs = new CreateStub();
            $createStubs->createModel($model_class, $table, $request->all());
            Alert::success("Sucesso", "Salvo com sucesso");
            Artisan::call("cache:clear");
            return redirect()->back();
        }

        private function saveElements(Request $request, $i, $modelConfig)
        {
            $element = new Element();
            $element->position_order = ($i + 1);
            $element->modelconfigs_id = $modelConfig->id;
            $element->fillable_var = $request->post("fillable_var")[$i];
            $element->type_input = $request->post("type_input")[$i];
            $element->label = $request->post("label")[$i];
            $element->rules = $request->post("rules")[$i];
            $element->placeholder = $request->post("placeholder")[$i];
            $element->class_field = $request->post("class_field")[$i];
            $element->attributes = $request->post("attributes")[$i];
            $element->show_in_form = $request->post("show_in_form")[$i];
            $element->show_in_table = $request->post("show_in_table")[$i];
            $element->show_in_edit = $request->post("show_in_edit")[$i];
            $element->is_relationable = $request->post("is_relationable")[$i];
            $element->relationable_with_class = $request->post("relationable_with_class")[$i];
            $element->relationship_function = $request->post("relationship_function")[$i];
            $element->relationship_type_function = $request->post("relationship_type_function")[$i];
            $element->table_relation_many_to_many = $request->post("table_relation_many_to_many")[$i];
            $element->save();
        }
    }
