<?php

use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\ProfilesController;
use App\Http\Controllers\RepliesController;
use App\Http\Controllers\ThreadsController;
use App\Http\Controllers\ThreadSubscriptionsController;
use App\Http\Controllers\UserNotificationsController;
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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');



Route::get('/threads', [ThreadsController::class, 'index'])->name('all.thread');
Route::get('/threads/create', [ThreadsController::class, 'create'])->name('thread.create');
Route::get('/threads/{channel}/{thread}', [ThreadsController::class, 'show'])->name('show.thread');
Route::delete('/threads/{channel}/{thread}', [ThreadsController::class, 'destroy'])->name('delete.thread');

Route::post('/threadds/store', [ThreadsController::class, 'store'])->name('thread.store');

Route::post('/threads/{channel}/{thread}/replies', [RepliesController::class, 'store'])->name('reply.store');
Route::get('/threads/{channel}', [ThreadsController::class, 'index']);

Route::post('/replies/{reply}/favorites', [FavoritesController::class, 'store']);
Route::delete('/replies/{reply}/favorites', [FavoritesController::class, 'destroy']);

Route::delete('/replies/{reply}', [RepliesController::class, 'destroy']);
Route::patch('/replies/{reply}', [RepliesController::class, 'update']);
Route::get('/profiles/{user}', [ProfilesController::class, 'show'])->name('profile');

Route::post('/threads/{channel}/{thread}/subscriptions', [ThreadSubscriptionsController::class,'store'])->middleware('auth');
Route::delete('/threads/{channel}/{thread}/subscriptions', [ThreadSubscriptionsController::class,'destroy'])->middleware('auth');


Route::delete('/profiles/{user}/notifications/{notification}', [UserNotificationsController::class, 'destroy']);
Route::get('/profiles/{user}/notifications', [UserNotificationsController::class, 'index']);
