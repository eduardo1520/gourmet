<?php

use App\Http\Controllers\GameGourmetController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\StartController;
use App\Http\Controllers\PlateController;
use App\Http\Controllers\CategoryController;

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

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/',[GameGourmetController::class, 'index'])->name('home');
Route::get('/start', [StartController::class, 'index'])->name('start');
//Route::get('/category', [CategoryController::class, 'index'])->name('category');

Route::resource('/category', CategoryController::class)->names([
   'index',
    'store',
]);

Route::get('/plate/category/{id}', [PlateController::class,'show'])->name('plate.show');
Route::get('/plate/{id}/edit', [PlateController::class,'edit'])->name('plate.edit');
//Route::get('/plate', [PlateController::class,'index'])->name('plate.index');
Route::get('/plate', [PlateController::class,'create'])->name('plate.create');


//Route::resource('/plate', PlateController::class)->names([
//    'index',
//    'create',
//    'edit',
//    'store',
//    'show',
//]);

