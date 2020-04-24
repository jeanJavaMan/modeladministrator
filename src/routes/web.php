<?php
Route::any("/protected/{slug}", "\Jeanderson\modeladministrator\Http\Controllers\ParentController@directionControl")
    ->where("slug", "([A-Za-z0-9\-\/]+)")->middleware(["web","auth"])->name("modeladmin");
Route::get("/modeladmin","\Jeanderson\modeladministrator\Http\Controllers\AdminController@index")->middleware(["web","auth"]);
Route::group(["prefix"=>"modeladmin","middleware"=>["web","auth"]],function (){
    Route::post("/savedata","\Jeanderson\modeladministrator\Http\Controllers\AdminController@saveData")->name("modeladmin.savedata");
});

