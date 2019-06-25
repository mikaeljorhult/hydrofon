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

Route::get('/', 'HomeController@index');

Auth::routes();
Route::redirect('home', '/')->name('home');
Route::get('profile', 'ProfileController@index')->name('profile');

Route::get('calendar/{date?}', 'CalendarController@index')->name('calendar');
Route::post('calendar', 'CalendarController@store');

Route::get('desk/{search?}', 'DeskController@index')->name('desk');
Route::post('desk', 'DeskController@store');

Route::post('impersonation', 'ImpersonationController@store')->name('impersonation');
Route::delete('impersonation', 'ImpersonationController@destroy');

Route::resources([
    'bookings'   => 'BookingController',
    'buckets'    => 'BucketController',
    'categories' => 'CategoryController',
    'groups'     => 'GroupController',
    'resources'  => 'ResourceController',
    'users'      => 'UserController',
]);

Route::resource('checkins', 'CheckinController')->only(['store', 'destroy']);
Route::resource('checkouts', 'CheckoutController')->only(['store', 'destroy']);

Route::resource('resources.identifiers', 'ResourceIdentifierController')->except(['show']);
Route::resource('users.identifiers', 'UserIdentifierController')->except(['show']);

Route::resource('subscriptions', 'SubscriptionController')->only(['store', 'destroy']);
Route::get('feeds/{feed}', 'SubscriptionController@show')->name('feed');

Route::prefix('api')->namespace('Api')->name('api.')->group(function () {
    Route::apiResource('bookings', 'BookingController');
    Route::apiResource('users', 'UserController');
});
