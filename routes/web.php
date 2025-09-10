<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;
use App\Http\Resources\UserResource;
use App\Models\User;
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

// Route::redirect('/', '/events', 301);

// Midtrans payment routes (public endpoints)
Route::prefix('payment')->name('payment.')->group(function () {
    Route::post('/notification', [PaymentController::class, 'notification'])->name('notification');
    Route::get('/finish', [PaymentController::class, 'finish'])->name('finish');
    Route::get('/unfinish', [PaymentController::class, 'unfinish'])->name('unfinish');
    Route::get('/error', [PaymentController::class, 'error'])->name('error');
});

// Legacy checkout endpoint for backward compatibility
Route::post('/checkout', [PaymentController::class, 'legacyCheckout']);

Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event:ulid}', [EventController::class, 'show'])->name('events.show');

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Booking routes (now require authentication)
    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::post('/events/{event:ulid}', [BookingController::class, 'store'])->name('store');
        Route::get('/{booking:ulid}', [BookingController::class, 'show'])->name('show');
        Route::get('/{booking:ulid}/payment', [BookingController::class, 'payment'])->name('payment');
        Route::post('/{booking:ulid}/upload-proof', [BookingController::class, 'uploadProof'])->name('upload-proof');
        Route::post('/{booking:ulid}/confirm', [BookingController::class, 'confirm'])->name('confirm');
        Route::get('/{booking:ulid}/status-check', [BookingController::class, 'statusCheck'])->name('status-check');
    });

    // Payment routes
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::post('/{booking:ulid}/checkout', [PaymentController::class, 'checkout'])->name('checkout');
        Route::get('/{transaction:ulid}/status', [PaymentController::class, 'status'])->name('status');
        //        Route::get('/{booking:ulid}/instructions', [PaymentController::class, 'instructions'])->name('instructions');
        //        Route::post('/{booking:ulid}/upload-proof', [PaymentController::class, 'uploadProof'])->name('upload-proof');
        //        Route::post('/{booking:ulid}/verify', [PaymentController::class, 'verify'])->name('verify');
        //        Route::post('/events/{event:ulid}/generate-qr', [PaymentController::class, 'generateQR'])->name('generate-qr');
    });

    // Ticket routes (require authentication)
    Route::prefix('tickets')->name('tickets.')->group(function () {
        Route::get('/{booking:ulid}', [TicketController::class, 'show'])->name('show');
        Route::get('/{booking:ulid}/download', [TicketController::class, 'download'])->name('download');
        Route::post('/{booking:ulid}/generate-qr', [TicketController::class, 'generateQR'])->name('generate-qr');
        Route::get('/scanner', [TicketController::class, 'scanner'])->name('scanner');
    });

    Route::post('/events/{event:ulid}/book', [BookingController::class, 'store'])->name('events.book');
});

Route::get('/api/user/{username}', function (string $id) {
    return new UserResource(User::findOrFail($id));
});

//Route::get('/api/tickets', function () {
//    return
//}
Route::get('/api/event', function () {
    return new \App\Http\Resources\EventResource(\App\Models\Event::find(1));
});

Route::post('/tickets/validate', [TicketController::class, 'validateTicket'])->name('tickets.validate');

/*
|--------------------------------------------------------------------------
| Fallback Routes
|--------------------------------------------------------------------------
|
| These routes handle authentication fallbacks and 404 errors
|
*/

// Authentication fallback route
Route::get('/auth', function () {
    return view('auth');
})->name('auth.fallback');

// 404 fallback route - must be at the end
Route::fallback(function () {
    // Check if the request expects JSON (API calls)
    if (request()->expectsJson()) {
        return response()->json([
            'message' => 'The requested resource was not found.',
            'error' => 'Not Found',
        ], 404);
    }

    // Return the custom 404 view for web requests
    return response()->view('errors.404', [], 404);
});

require __DIR__.'/auth.php';

// Include test routes in development
if (app()->environment(['local', 'testing'])) {
    require __DIR__.'/test.php';
}
