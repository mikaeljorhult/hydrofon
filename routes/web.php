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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::get('home', 'AppController@index')->name('home');

Route::get('calendar/{date?}', 'CalendarController@index')->name('calendar');
Route::post('calendar', 'CalendarController@store');

Route::get('profile', 'ProfileController@index')->name('profile');

Route::resources([
    'bookings'   => 'BookingController',
    'categories' => 'CategoryController',
    'objects'    => 'ObjectController',
    'users'      => 'UserController',
]);

Route::resource('checkins', 'CheckinController', ['only' => ['store', 'destroy']]);
Route::resource('checkouts', 'CheckoutController', ['only' => ['store', 'destroy']]);
