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

//Admin Route
Route::prefix('app')->middleware([\App\Http\Middleware\AdminCheck::class])->group(function (){
    //Tag Route
    Route::post('/create_tag','App\Http\Controllers\AdminController@addTag');
    Route::get('/get_tag','App\Http\Controllers\AdminController@getTag');
    Route::post('/edit_tag','App\Http\Controllers\AdminController@editTag');
    Route::post('/delete_tag','App\Http\Controllers\AdminController@deleteTag');

    //Upload
    Route::post('/upload','App\Http\Controllers\AdminController@upload');


    //Cat Route
    Route::post('/create_cat','App\Http\Controllers\AdminController@addCat');
    Route::post('/delete_cat','App\Http\Controllers\AdminController@deleteCat');
    Route::post('/edit_cat','App\Http\Controllers\AdminController@editCat');
    Route::get('/get_cat','App\Http\Controllers\AdminController@getCat');



    //Delete Image
    Route::post('/delete_image','App\Http\Controllers\AdminController@deleteImage');

    //AdminUser
    Route::post('/create_adminUser','App\Http\Controllers\AdminController@addAdminUser');
    Route::get('/get_adminUser','App\Http\Controllers\AdminController@getAminUser');
    Route::post('/delete_adminUser','App\Http\Controllers\AdminController@deleteAminUser');
    Route::post('/edit_adminUser','App\Http\Controllers\AdminController@editAdminUser');

    //Admin Login
    Route::post('/admin_login','App\Http\Controllers\AdminController@adminLogin');


    //Roles
    Route::get('/get_roles','App\Http\Controllers\AdminController@getAllRole');
    Route::post('/create_role','App\Http\Controllers\AdminController@createRole');
    Route::post('/delete_role','App\Http\Controllers\AdminController@deleteRole');
    Route::post('/edit_role','App\Http\Controllers\AdminController@editRole');
    Route::post('/assign_role','App\Http\Controllers\AdminController@assignRole');



    //blog Routes
    Route::post('/create_blog','App\Http\Controllers\AdminController@createBlog');
    Route::post('/delete_blog','App\Http\Controllers\AdminController@deleteBlog');
    Route::get('/get_blogs','App\Http\Controllers\AdminController@getBlogs');
    Route::post('/edit_blog/{id}','App\Http\Controllers\AdminController@editBlogs');
    Route::get('/get_blog_data/{id}','App\Http\Controllers\AdminController@getBlogData');
});

//upload BLog Image
Route::post('/createBlog','App\Http\Controllers\AdminController@imageBlog');

Route::get('/','App\Http\Controllers\AdminController@index');
Route::get('/logout','App\Http\Controllers\AdminController@logout');
Route::any('/{slug}/{id}','App\Http\Controllers\AdminController@index');
Route::any('/{slug}/','App\Http\Controllers\AdminController@index');

//Route for slug
Route::get('/slug','App\Http\Controllers\AdminController@slug');

//Route::get('/', function () {
//    return view('welcome');
//});
//Route::any('{slug}',function () {
//    return view('welcome');
//});
