<?php
namespace Jeanderson\modeladministrator;

use Illuminate\Support\ServiceProvider;

class ModelAdministratorProvider extends ServiceProvider
{
    public function boot(){
        $this->mergeConfigFrom(__DIR__."/config/modeladmin.php","modeladmin");
        if(!config("modeladmin.disable_migration")) {
            $this->loadMigrationsFrom(__DIR__ . "/database/migrations");
        }
        $this->loadViewsFrom(__DIR__."/resources/views","modeladmin");
        $this->loadTranslationsFrom(__DIR__."/resources/lang","modeladminlang");
        $this->loadRoutesFrom(__DIR__."/routes/web.php");
    }

    public function register()
    {
        parent::register();
    }
}
