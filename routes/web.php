<?php

use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BucketController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckinController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DataRequestController;
use App\Http\Controllers\DeskController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImpersonationController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\ProfileBookingsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\ResourceIdentifierController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserIdentifierController;
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

Route::get('/', [HomeController::class, 'index']);

Auth::routes();
Route::redirect('home', '/')->name('home');

Route::get('profile', ProfileController::class)->name('profile');
Route::get('profile/bookings', ProfileBookingsController::class)->name('profile.bookings');

Route::get('notifications', NotificationsController::class)->name('notifications');

Route::get('calendar/{date?}', [CalendarController::class, 'index'])->name('calendar');
Route::post('calendar', [CalendarController::class, 'store']);

Route::get('desk/{search?}', [DeskController::class, 'index'])->name('desk');
Route::post('desk', [DeskController::class, 'store']);

Route::post('impersonation', [ImpersonationController::class, 'store'])->name('impersonation');
Route::delete('impersonation', [ImpersonationController::class, 'destroy']);

Route::resources([
    'bookings'   => BookingController::class,
    'buckets'    => BucketController::class,
    'categories' => CategoryController::class,
    'groups'     => GroupController::class,
    'resources'  => ResourceController::class,
    'users'      => UserController::class,
]);

Route::resource('checkins', CheckinController::class)->only(['store', 'destroy']);
Route::resource('checkouts', CheckoutController::class)->only(['store', 'destroy']);

Route::resource('approvals', ApprovalController::class)->only(['index', 'store', 'destroy']);

Route::resource('resources.identifiers', ResourceIdentifierController::class);
Route::resource('users.identifiers', UserIdentifierController::class)->except(['show']);

Route::resource('subscriptions', SubscriptionController::class)->only(['store', 'destroy']);
Route::get('feeds/{feed}', [SubscriptionController::class, 'show'])->name('feed');

Route::resource('datarequests', DataRequestController::class)->only(['store']);