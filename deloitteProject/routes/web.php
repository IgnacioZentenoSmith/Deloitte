<?php

use Illuminate\Support\Facades\Route;

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


Auth::routes();

// Email Verification Routes...
Route::get('email/verify', 'Auth\VerificationController@show')->name('verification.notice');
Route::get('email/verify/{id}', 'Auth\VerificationController@verify')->name('verification.verify');
Route::get('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');
Route::put('email/{id}/setPassword', 'Auth\VerificationController@setPassword')->name('email.setPassword')->middleware('auth');


//Rutas de la carpeta home
Route::resource('home', 'HomeController')->middleware('auth');
//Redirigir a home cuando no haya postdominio
Route::get('/', 'HomeController@index')->name('home');
//Rutas de la carpeta admin
Route::resource('admin', 'AdminController')->middleware('auth');
Route::get('/admin/{id}/editPermisos', 'AdminController@editPermisos')->name('admin.editPermisos')->middleware('auth');
Route::put('/admin/{id}/updatePermisos', 'AdminController@updatePermisos')->name('admin.updatePermisos')->middleware('auth');
Route::post('/admin/{id}/resend', 'AdminController@resendVerification')->name('admin.resendVerification')->middleware('auth');
//Rutas de la carpeta dashboard
Route::post('/dashboard/ajax', 'DashboardController@ajaxRequest')->name('dashboard.ajax')->middleware('auth');
Route::resource('dashboard', 'DashboardController')->middleware('auth');

//Rutas de datos
Route::get('/datos/importarExcel', 'DatosController@getImportarExcel')->name('datos.importarExcel')->middleware('auth');
Route::get('/datos/eliminarExcel', 'DatosController@getEliminarExcel')->name('datos.eliminarExcel')->middleware('auth');
Route::post('/datos/postImportarExcel', 'DatosController@postImportarExcel')->name('datos.postImportarExcel')->middleware('auth');
Route::delete('/datos/postEliminarExcel/{id}', 'DatosController@postEliminarExcel')->name('datos.postEliminarExcel')->middleware('auth');

//Rutas de historiales
Route::get('/historiales/pagos', 'HistorialesController@getPagosIndex')->name('historiales.pagos')->middleware('auth');
Route::get('/historiales/retenciones', 'HistorialesController@getRetencionesIndex')->name('historiales.retenciones')->middleware('auth');
Route::post('/historiales/ajaxPagos', 'HistorialesController@ajaxPagos')->name('historiales.ajaxPagos')->middleware('auth');
Route::post('/historiales/ajaxRetenciones', 'HistorialesController@ajaxRetenciones')->name('historiales.ajaxRetenciones')->middleware('auth');

//Rutas de bitacoras
Route::get('/bitacora/index', 'BitacoraController@index')->name('bitacora.index')->middleware('auth');
Route::post('/bitacora/ajaxBitacoras', 'BitacoraController@ajaxBitacoras')->name('bitacora.ajaxBitacoras')->middleware('auth');
