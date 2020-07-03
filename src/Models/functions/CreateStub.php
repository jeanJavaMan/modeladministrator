<?php


    namespace Jeanderson\modeladministrator\Models\functions;


    use Carbon\Carbon;
    use Jeanderson\modeladministrator\Utils\ElementsType;
    use Jeanderson\modeladministrator\Utils\RelationsTypes;

    class CreateStub
    {
        public function createModel($class, $table, array $attributes)
        {
            $template = str_replace(["{{className}}", "{{tableName}}"], [$class, $table],
                $this->getStubs("Model"));
            $this->createModelFillables($template, $attributes["fillable_var"])
                ->createRelations($template, $attributes)
                ->createModelProperty($template, $attributes)
                ->createModelToView($template, $attributes["fillable_var"][0])
                ->createFillableSearch($template,$attributes["fillable_var"][0]);

            if (!file_exists(app_path("/Models"))) {
                mkdir(app_path("/Models"), 0777, true);
            }
            file_put_contents(app_path("/Models/$class.php"), $template);
        }

        public function createFillableSearch(&$template,$fillable_var){
            $fillableSearch = "['".$fillable_var."']";
            $template = str_replace("{{fillableSearch}}", $fillableSearch, $template);
            return $this;
        }

//        public function createMigration(array $attributes)
//        {
//            $table_name = $attributes["table"];
//            $migrationName = "Create" . ucfirst($table_name)."Table";
//            $migrationNameFile = Carbon::now()->format("Y_m_d_hsi") . "_create_" . $table_name . "_table";
//            $schema = $this->createMigrationSchema($attributes);
//            $template = str_replace(["{{migrationName}}", "{{tableName}}", "{{migrationSchema}}"],
//                [$migrationName, $table_name, $schema], $this->getStubs("Migration"));
//            file_put_contents(database_path("/migrations/$migrationNameFile.php"), $template);
//        }

//        private function createMigrationSchema(array $attributes)
//        {
//            $schema = "\$table->bigIncrements('id');\n";
//            foreach ($this->dataModel["fillables"] as $key => $fillable) {
//                if ($fillable['isrelation'] && in_array($fillable['type_relation'], RelationsType::multiTables())) {
//                    $this->createTableRelationManyToMany($key, $fillable);
//                } else {
//                    $extra_function = $fillable['required'] == true ? "" : "->nullable()";
//                    $schema .= "\$table->".$fillable["type"]."('".$key."')$extra_function;\n";
//                }
//            }
//            $schema .= "\$table->timestamps();";
//            return $schema;
//        }

        private function createModelProperty(&$template, array $attributes)
        {
            $type_fill_property = ElementsType::ATTRIBUTES_PROPERTY;
            $property = "";
            for ($i = 0; $i < count($attributes["fillable_var"]); $i++) {
                $property .= "* @property " . $type_fill_property[$attributes["type_input"][$i]] . " " . $attributes["fillable_var"][$i] . "\n";
            }
            $template = str_replace("{{property}}", $property, $template);
            return $this;
        }

        private function createModelToView(&$template, $fillable_var)
        {
            $toView = "\$this->$fillable_var";
            $toView = rtrim($toView, ".");
            $template = str_replace("{{toView}}", $toView, $template);
            return $this;
        }

        private function createModelFillables(&$template, array $fillables_var)
        {
            $f = "['" . implode("','", $fillables_var) . "']";
            $template = str_replace("{{fillable}}", $f, $template);
            return $this;
        }

        private function createRelations(&$template, array $attributes)
        {
            $relations = "";
            for ($index = 0; $index < count($attributes["is_relationable"]); $index++) {
                if ($attributes["is_relationable"][$index]) {
                    $myclass = $attributes["model_class"];
                    $class = $attributes["relationable_with_class"][$index];
                    $method_name = $attributes["relationship_function"][$index];
                    $function_type = $attributes["relationship_type_function"][$index];
                    $function = "public function $method_name(){ \nreturn ";
                    switch ($function_type) {
                        case "hasOne":
                            $function .= $this->createHasOne($class);
                            break;
                        case "hasMany":
                            $function .= $this->createHasMany($class);
                            break;
                        case "belongsToMany":
                            $function .= $this->createManyToMany($class, $myclass, $attributes["table_relation_many_to_many"][$index]);
                            break;
                    }
                    $function .= "}\n";
                    $relations .= $function;
                }
            }
            $template = str_replace("{{relationFunctions}}", $relations, $template);
            return $this;
        }

        private function createHasOne($class_foreing)
        {
            $key = strtolower($class_foreing) . "_id";
            return "\$this->hasOne($class_foreing::class,'id','$key');\n";
        }

        private function createHasMany($class)
        {
            $key = strtolower($class) . "_id";
            return "\$this->hasMany($class::class,'$key','id');\n";
        }

        private function createManyToMany($class_foreing, $class, $table_name)
        {
            $foreing_key = strtolower($class) . "_id";
            $key = strtolower($class_foreing) . "_id";
            return "\$this->belongsToMany($class_foreing::class,'$table_name','$foreing_key','$key');\n";
        }

        protected function getStubs($name)
        {
            return file_get_contents(__DIR__ . "/../../stubs/$name.stub");
        }
    }
