<?php

use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Models\Page;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\ContactController;

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
    return view('pages.home');
})->name('home');

Route::get('/contact-page', function(){
    return view('pages.contact');
})->name('page.contact');  

Route::get('/about-us', function(){
    return view('pages.about-us');
})->name('page.about'); 



//contact route
Route::get('contact',[ContactController::class,'contact']);
Route::post('contact',[ContactController::class,'contactPost'])->name('contact.store');    


//pages
Route::get('page/{id}',[PagesController::class,'show'])->name('page.show');


Route::group(['prefix' => 'fuzzy'], function () {
    Voyager::routes();
});
