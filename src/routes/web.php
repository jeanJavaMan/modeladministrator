<?php
Route::any("/protected/{slug}", "\Jeanderson\modeladministrator\Http\Controllers\ParentController@directionControlProtected")
    ->where("slug", "([A-Za-z0-9\-\/]+)")->middleware(["web","auth"])->name("modeladmin");
Route::get("/modeladmin","\Jeanderson\modeladministrator\Http\Controllers\AdminController@index")->middleware(["web","auth"]);
Route::group(["prefix"=>"modeladmin","middleware"=>["web","auth"]],function (){
    Route::post("/savedata","\Jeanderson\modeladministrator\Http\Controllers\AdminController@saveData")->name("modeladmin.savedata");
    Route::get("/crud","\Jeanderson\modeladministrator\Http\Controllers\AdminController@create_crud")->name("modeladmin.crud");
    Route::get("/routes","\Jeanderson\modeladministrator\Http\Controllers\AdminController@create_route")->name("modeladmin.create.route");
    Route::post("/routes/save","\Jeanderson\modeladministrator\Http\Controllers\AdminController@save_route")->name("modeladmin.save.route");
    Route::post("/element/save","\Jeanderson\modeladministrator\Http\Controllers\AdminController@save_element")->name("modeladmin.save.element");
    Route::get("/element","\Jeanderson\modeladministrator\Http\Controllers\AdminController@add_element")->name("modeladmin.add.element");
});

