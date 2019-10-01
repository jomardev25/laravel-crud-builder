<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

include('moduleroutes.php');

Route::get('/', function () {

});

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/core/modules', ['as' => 'core.modules.index', 'uses' => 'Core\ModuleController@index']);
Route::get('/core/modules/create', ['as' => 'core.modules.create', 'uses' => 'Core\ModuleController@create']);
Route::post('/core/modules/store', ['as' => 'core.modules.store', 'uses' => 'Core\ModuleController@store']);
Route::get('/core/modules/edit/{$module_id}', ['as' => 'core.modules.edit', 'uses' => 'Core\ModuleController@edit']);

Route::get('/core/modules/field-template', [
    'as' => 'core.modules.fieldtemplate', 'uses' => 'Core\ModuleController@fieldTemplate'
]);

Route::get('/core/modules/rebuild/{module_id}', [
    'as' => 'core.modules.rebuild', 'uses' => 'Core\ModuleController@rebuild'
]);

Route::get('/core/modules/table/{module_id}', ['as' => 'core.modules.table', 'uses' => 'Core\ModuleController@table']);

Route::patch(
            '/core/modules/table/update/{module_id}', 
            ['as' => 'core.modules.table.update', 'uses' => 'Core\ModuleController@updateTable']
);

Route::get('/dashboard', [
    'as' => 'core.dashboard', 'uses' => 'Core\DashboardController@index'
]);

Route::get('/maintenance/user-management/users', [
    'as' => 'users.index', 'uses' => 'Modules\Maintenance\UserController@index'
]);







