<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/    
Route::get('/employee/export_to_excel', [
    'as'    =>  'employee.export_to_excel',
    'uses'  =>  '\llstarscreamll\EmployeesModule\app\Http\Controllers\EmployeeController@exportToExcel'
]);
Route::post('/employee/import', [
    'as'    =>  'employee.post_import',
    'uses'  =>  '\llstarscreamll\EmployeesModule\app\Http\Controllers\EmployeeController@post_import'
]);
Route::get('/employee/prepare_import', [
    'as'    =>  'employee.prepare_import',
    'uses'  =>  '\llstarscreamll\EmployeesModule\app\Http\Controllers\EmployeeController@prepare_import'
]);
Route::put('employee/status/{status}', [
    'as'    =>  'employee.status',
    'uses'  =>  '\llstarscreamll\EmployeesModule\app\Http\Controllers\EmployeeController@status'
]);
Route::get('employee/{id}/restore', [
    'as'    =>  'employee.restore',
    'uses'  =>  '\llstarscreamll\EmployeesModule\app\Http\Controllers\EmployeeController@restore'
]);
Route::resource('employee', '\llstarscreamll\EmployeesModule\app\Http\Controllers\EmployeeController');
