<?php

    use App\Http\Controllers\TestController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/create',[TestController::class,'create']);

Route::get('/worker_cabinet',[TestController::class,'workerCabinet']);

Route::get('/worker_flor/{id}',[TestController::class,'workerFlor']);

Route::get('/max_salary/{id}',[TestController::class,'maxSalary']);

Route::get('/capacity/{type}',[TestController::class,'capacity']);

Route::get('/search-files/{workerid}',[TestController::class,'searchFiles']);

Route::get('/vk-photo/{id}',[TestController::class,'vkPhoto']);

Route::get('/test',[TestController::class,'testget']);