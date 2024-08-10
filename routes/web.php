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
use App\Http\Controllers\ProfileUpdateController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\ResourceIdentifierController;
use App\Http\Controllers\ResourceStatusController;
use App\Http\Controllers\SettingsController;
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
Route::redirect('home', '/')->name('home');

Route::middleware('auth')->group(function () {
    Route::resource('bookings', BookingController::class);

    Route::name('profile.')
        ->prefix('profile')
        ->group(function () {
            Route::get('/', ProfileController::class)->name('index');
            Route::put('/', ProfileUpdateController::class)->name('update');
            Route::get('bookings', ProfileBookingsController::class)->name('bookings');
        });

    Route::resource('datarequests', DataRequestController::class)->only(['store']);

    Route::get('notifications', NotificationsController::class)->name('notifications');

    Route::get('calendar/{date?}', [CalendarController::class, 'index'])->name('calendar');
    Route::post('calendar', [CalendarController::class, 'store']);

    Route::controller(ApprovalController::class)
        ->name('approvals.')
        ->prefix('approvals')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::delete('{booking}', 'destroy')->name('destroy');
        });

    Route::resource('resources.statuses', ResourceStatusController::class)->only(['store', 'destroy']);

    Route::delete('impersonation', [ImpersonationController::class, 'destroy']);

    Route::resource('subscriptions', SubscriptionController::class)->only(['store', 'destroy']);
});

Route::middleware('admin')->group(function () {
    Route::get('desk/{search?}', [DeskController::class, 'index'])->name('desk');
    Route::post('desk', [DeskController::class, 'store']);

    Route::resource('checkins', CheckinController::class)->only(['store']);
    Route::resource('checkouts', CheckoutController::class)->only(['store']);

    Route::resources([
        'buckets' => BucketController::class,
        'categories' => CategoryController::class,
        'groups' => GroupController::class,
        'resources' => ResourceController::class,
        'users' => UserController::class,
    ]);

    Route::resource('resources.identifiers', ResourceIdentifierController::class);
    Route::resource('users.identifiers', UserIdentifierController::class)->except(['show']);

    Route::controller(SettingsController::class)
        ->name('settings.')
        ->prefix('settings')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'update')->name('update');
        });

    Route::post('impersonation', [ImpersonationController::class, 'store'])->name('impersonation');
});

Route::get('feeds/{feed}', [SubscriptionController::class, 'show'])->name('feed');
