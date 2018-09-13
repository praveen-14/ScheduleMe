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

//Route::get('/schedule/{projectID}', 'algorithm@schedule');
//Route::get('/', 'algorithm@test');
Route::get('/createProject', 'ManagerController@createProject');
Route::get('/updateProject/{projectID}', 'ManagerController@showUpdateProject');
Auth::routes();
Route::get('/register', 'HomeController@showRegister');
Route::get('/signin', 'HomeController@showLogin');
Route::get('/projectManagerHome', 'HomeController@showProjectManagerHome');
Route::get('/developerHome', 'HomeController@showDeveloperHome');
Route::post('/signin',[
    'uses' =>'HomeController@doLogin',
    'as' =>'login']);
Route::post('/createProject',[
    'uses' => 'ManagerController@doCreateProject',
    'as' =>'createProject']);
Route::post('/removeProject',[
    'uses' => 'ManagerController@removeProject',
    'as' =>'removeProject']);
Route::post('/updateProject',[
    'uses' => 'ManagerController@updateProject',
    'as' =>'updateProject']);
Route::post('/schedule',[
    'uses' => 'ManagerController@showSchedule',
    'as' =>'schedule']);
Route::post('/updateTask',[
    'uses' => 'DeveloperController@updateTask',
    'as' =>'updateTask']);


//Route::post('/register',[
//    'uses' =>'HomeController@doRegister',
//    'as' =>'register']);
//Route::post('/login',[
//    'uses' =>'HomeController@doLogin',
//    'as' =>'login']);

