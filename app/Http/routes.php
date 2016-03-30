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

/**
 * Recurso de las acciones de pesaje
 */
Route::get('weighing/printTicket/{weighing}', [
	'as'	=>	'weighing.printTicket',
	'uses'	=>	'WeighingController@printTicket'
	]);
Route::resource('weighing', 'WeighingController');
/**
 * Recurso de configuraciones del sistema
 */
Route::resource('setting', 'SettingController');

/**
 * Empleados
 */
// index de empleados
Route::get('employee', [
	'as'	=>	'employee.index',
	'uses'	=>	'EmployeeController@index'
	]);
// formulario para importar datos de empleados
Route::get('employee/importData', [
	'as'	=>	'employee.importDataForm',
	'uses'	=>	'EmployeeController@importDataForm'
	]);
Route::post('employee/importData', [
	'as'	=>	'employee.postImportDataForm',
	'uses'	=>	'EmployeeController@postImportDataForm'
	]);


/**
 * Este recurso es para propositos de testeo, con los cuales
 * se prueban los procesos de importación de datos al sistema.
 */
Route::get('/test/get_three_employees', [
	'as'	=>	'test.get_three_employees',
	'uses'	=>	'TestController@returnThreeEmployees'
	]);

Route::get('/test/get_invalid_id_employees_data', [
	'as'	=>	'test.get_invalid_id_employees_data',
	'uses'	=>	'TestController@returnInvalidIdEmployeesData'
	]);


