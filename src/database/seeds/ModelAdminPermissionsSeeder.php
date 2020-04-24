<?php


namespace Jeanderson\modeladministrator\database\seeds;


use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class ModelAdminPermissionsSeeder extends Seeder
{
    public function run(){
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => __("modeladminlang::default.edit")]);
        Permission::create(['name' => __("modeladminlang::default.delete")]);
        Permission::create(['name' => __("modeladminlang::default.save")]);
        Permission::create(['name' => __("modeladminlang::default.create_pdf")]);
        Permission::create(['name' => __("modeladminlang::default.create_excel")]);
        Permission::create(['name' => __("modeladminlang::default.create_permissions")]);

        $role = Role::create(['name' => 'model-admin']);
        $role->givePermissionTo(Permission::all());
    }
}
