<?php

/*
|--------------------------------------------------------------------------
| Backpack\PermissionManager Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are
| handled by the Backpack\PermissionManager package.
|
*/

Route::group([
            'namespace'  => 'Backpack\PermissionManager\app\Http\Controllers',
            'prefix'     => config('backpack.base.route_prefix', 'admin'),
            'middleware' => ['web', 'admin','checkPermission'],
    ], function () {
        CRUD::resource('permission', 'PermissionCrudController');
        CRUD::resource('role', 'RoleCrudController');
        CRUD::resource('user', 'UserCrudController');
    Route::group(['prefix'=>'export-excel'],function (){

        Route::get('users','UserCrudController@ExportExcelAction');

    });
    });
