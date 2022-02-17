<?php

use TCG\Voyager\Voyager;
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

Route::get('/network-install', function () {
    $id = 6;

    $install = Page::where('id','=', $id)->first();

    return view('pages.network-install',compact('install'));
})->name('page.install');

Route::get('/it-support', function () {
    $id = 5;

    $support = Page::where('id','=', $id)->first();

    return view('pages.it-support',compact('support'));
})->name('page.support');


Route::get('/network-maintenance', function () {
    $id = 4;

    $main = Page::where('id','=', $id)->first();

    return view('pages.network-maintance',compact('main'));
})->name('page.maintenance');

Route::get('/telephone-systems', function () {

    $id = 7;

    $telephone = Page::where('id','=', $id)->first();

    return view('pages.telephone-systems',compact('telephone'));
})->name('page.phone');

Route::get('/cloud-solutions', function () {
     
    $id = 8;

    $cloud = Page::where('id','=', $id)->first();

    

    return view('pages.cloud-solutions',compact('cloud'));

})->name('page.cloud');



Route::get('/cctv-solutions', function() {
    $id = 3;
     $cctv = Page::where('id','=', $id)->first();
        return view('pages.cctv',compact('cctv'));
    })->name('page.cctv');


Route::get('/contact-page', function(){
    return view('pages.contact');
})->name('page.contact');    



//contact route
Route::get('contact',[ContactController::class,'contact']);
Route::post('contact',[ContactController::class,'contactPost'])->name('contact.store');    


//pages
Route::get('page/{id}',[PagesController::class,'show'])->name('page.show');


Route::group(['prefix' => 'fuzzy'], function () {
    Voyager::routes();
});
