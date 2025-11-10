<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BoothController;
use App\Http\Controllers\EventController;

use App\Models\User;
use App\Models\Event;

Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        // Redirect based on user role
        if ($user->role->name === 'tenant') {
            return redirect()->route('events');
        } elseif ($user->role->name === 'event_organizer') {
            return redirect()->route('my-events.index');
        }
    }

    // Get top 3 published events sorted by booking count
    // Only show events where registration deadline hasn't closed
    $topEvents = Event::where('status', Event::STATUS_PUBLISHED)
        ->where('registration_deadline', '>=', now()->toDateString())
        ->withCount('bookings')
        ->with(['category', 'booths'])
        ->orderBy('bookings_count', 'desc')
        ->limit(3)
        ->get();

    return view('landingpage.index', compact('topEvents'));
})->name('home');

// --- GRUP ROUTE UNTUK USER YANG BELUM LOGIN (GUEST) ---
Route::middleware('guest')->group(function () {
    // Menampilkan halaman signup
    Route::get('/signup', [AuthController::class, 'showSignupForm'])->name('signup');
    // Memproses data dari form signup
    Route::post('/signup', [AuthController::class, 'signup']);

    // Menampilkan halaman login
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    // Memproses data dari form login
    Route::post('/login', [AuthController::class, 'login']);


    Route::get('/auth/google/redirect', [AuthController::class, 'googleRedirect'])->name('google.redirect');
    Route::get('/auth/google/callback', [AuthController::class, 'googleCallback'])->name('google.callback');
});


// --- ROUTE UNTUK LOGOUT (HARUS SUDAH LOGIN) ---
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/profile', [UserController::class, 'show'])->name('profile');
    Route::put('/profile', [UserController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [UserController::class, 'updatePassword'])->name('profile.password');


    //onboarding after first oauth login
    Route::get('/onboarding', [AuthController::class, 'showOnboarding'])->name('onboarding.show');
    Route::post('/onboarding', [AuthController::class, 'saveOnboarding'])->name('onboarding.save');
});


// --- Route lainnya yang sudah ada ---
Route::get('/faq', function () {
    return view('faq.index');
})->name('faq');

Route::get('/events', [EventController::class, 'publicIndex'])->name('events');

Route::get('/events/{event}', [EventController::class, 'publicShow'])->name('events.show');

Route::get('/events/{event}/booths', [EventController::class, 'showBooths'])->name('booths.index');

Route::get('/booths/{booth}/details', [EventController::class, 'showBoothDetails'])->name('booths.details');

Route::get('/my-bookings', [BookingController::class, 'index'])->name('my-bookings');

Route::get('/my-bookings/{booking}', [BookingController::class, 'show'])->name('my-booking-details');

Route::get('/my-bookings/{booking}/invoice', [BookingController::class, 'downloadInvoice'])->name('booking.invoice')->middleware('auth');

Route::get('/my-events/details', function () {
    return view('my-events.details');
})->name('my-event-details');

Route::get('/my-events/edit', function () {
    return view('my-events.edit');
})->name('my-event-edit');

Route::middleware('auth')->prefix('my-events')->name('my-events.')->group(function () {
    Route::get('/', [EventController::class, 'index'])->name('index');
    Route::get('/create', [EventController::class, 'create'])->name('create');
    Route::post('/', [EventController::class, 'store'])->name('store');
    Route::get('/{event}', [EventController::class, 'show'])->name('show');
    Route::get('/{event}/edit', [EventController::class, 'edit'])->name('edit');
    Route::put('/{event}', [EventController::class, 'update'])->name('update');
    Route::post('/{event}/publish', [EventController::class, 'publish'])->name('publish');
    Route::delete('/{event}', [EventController::class, 'destroy'])->name('destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/request-refund/{booking}', [\App\Http\Controllers\RefundRequestController::class, 'create'])->name('request-refund');
    Route::post('/request-refund/{booking}', [\App\Http\Controllers\RefundRequestController::class, 'store'])->name('refund-request.store');
});

Route::middleware('auth')->group(function () {
    Route::get('refund-requests', [\App\Http\Controllers\RefundRequestController::class, 'index'])->name('refund-requests');
    Route::get('/refund-requests/{refundRequest}', [\App\Http\Controllers\RefundRequestController::class, 'show'])->name('refund-requests.show');
    Route::patch('/refund-requests/{refundRequest}/approve', [\App\Http\Controllers\RefundRequestController::class, 'approve'])->name('refund-requests.approve');
    Route::patch('/refund-requests/{refundRequest}/reject', [\App\Http\Controllers\RefundRequestController::class, 'reject'])->name('refund-requests.reject');
});

Route::get('/refund-requests/details', function () {
    return view('refund-requests.details');
})->name('refund-requests-details');

// Booking requests routes (for event organizers)
Route::middleware('auth')->group(function () {
    Route::get('/events/{event}/booking-requests', [BookingController::class, 'bookingRequests'])
        ->name('booking-requests');

    Route::get('/events/{event}/booking-requests/{booking}', [BookingController::class, 'bookingRequestDetails'])
        ->name('booking-request-details');

    Route::post('/events/{event}/booking-requests/{booking}/confirm', [BookingController::class, 'confirmBookingRequest'])
        ->name('booking-requests.confirm');

    Route::post('/events/{event}/booking-requests/{booking}/reject', [BookingController::class, 'rejectBookingRequest'])
        ->name('booking-requests.reject');
});

Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');

// Payment routes
Route::middleware('auth')->group(function () {
    Route::get('/bookings/{booking}/payment', [\App\Http\Controllers\PaymentController::class, 'create'])->name('payment.create');
    Route::post('/bookings/{booking}/payment/initiate', [\App\Http\Controllers\PaymentController::class, 'initiate'])->name('payment.initiate');
    Route::post('/bookings/{booking}/payment/check-status', [\App\Http\Controllers\PaymentController::class, 'checkStatus'])->name('payment.check-status');
    Route::get('/bookings/{booking}/payment/success', [\App\Http\Controllers\PaymentController::class, 'success'])->name('payment.success');
    Route::get('/bookings/{booking}/payment/pending', [\App\Http\Controllers\PaymentController::class, 'pending'])->name('payment.pending');
    Route::get('/bookings/{booking}/payment/error', [\App\Http\Controllers\PaymentController::class, 'error'])->name('payment.error');
});

// Midtrans callback (no auth required)
Route::post('/payment/callback', [\App\Http\Controllers\PaymentController::class, 'callback'])->name('payment.callback');

// Rating routes
Route::middleware('auth')->group(function () {
    Route::post('/bookings/{booking}/rating', [\App\Http\Controllers\RatingController::class, 'store'])->name('rating.store');
    Route::get('/bookings/{booking}/rating/check', [\App\Http\Controllers\RatingController::class, 'checkRating'])->name('rating.check');

    // Organizer rating tenant routes
    Route::get('/events/{event}/attendants/{booking}', [BookingController::class, 'showAttendant'])->name('attendant.details');
    Route::post('/events/{event}/attendants/{booking}/rating', [\App\Http\Controllers\RatingController::class, 'storeOrganizerRating'])->name('attendant.rating.store');
    Route::get('/events/{event}/attendants/{booking}/rating/check', [\App\Http\Controllers\RatingController::class, 'checkOrganizerRating'])->name('attendant.rating.check');
});


Route::get('/booth-layout', function (Request $request) {
    return view('booth-layout.index', [
        'eventId' => $request->query('event_id'),
    ]);
})->name('booth-layout');

Route::get('/booth-layout/edit', function () {
    return view('booth-layout.edit');
})->name('booth-layout.edit');

Route::post('/booth-layout/save', [BoothController::class, 'store'])->name('booth-layout.save');
Route::get('/booth-layout/view', function (Request $request) {
    return view('booth-layout.view', [
        'eventId' => $request->query('event_id'),
    ]);
})->name('booth-layout.view');
Route::get('/booth-layout/data/{event}', [BoothController::class, 'show'])->name('booth-layout.data');
Route::get('/booth-layout/floors/{event}', [BoothController::class, 'getFloors'])->name('booth-layout.floors');
Route::delete('/booth-layout/floors/{event}/{floor}', [BoothController::class, 'deleteFloor'])->name('booth-layout.deleteFloor');
