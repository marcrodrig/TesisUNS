<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DatasetController;
use App\Http\Controllers\DetectorController;
use App\Http\Controllers\WidgetController;
use App\Http\Controllers\BotometerController;

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
    return view('dashboard');
});

Route::get('/datos', [DatasetController::class,'index'])->name('data');

Route::get('/clasificacion/detector', [DetectorController::class,'index'])->name('clasificacion');
Route::post('/clasificacion', [DetectorController::class,'check']);

Route::get('/clasificacion/botometer', [BotometerController::class,'index'])->name('botometer');
Route::post('/clasificacion/botometer', [BotometerController::class,'botometer']);
Route::get('/clasificacion/botometer/{username}', [BotometerController::class,'resultado'])->name('resultadoBotometer');

Route::get('/widgets', [WidgetController::class,'widgetTweet']);