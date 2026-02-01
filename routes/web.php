<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BoothController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PaymentController;

use App\Models\User;
use App\Models\Event;
use App\Models\City;
use App\Models\District;
use App\Models\Subdistrict;

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
        ->where('registration_deadline', '>=', now())
        ->withCount('bookings')
        ->with(['category', 'booths'])
        ->orderBy('bookings_count', 'desc')
        ->limit(3)
        ->get();

    return view('landingpage.index', compact('topEvents'));
})->name('home');

use Illuminate\Foundation\Auth\EmailVerificationRequest;

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
});

// Google OAuth routes (outside guest middleware to allow callback after authentication)
Route::get('/auth/google/redirect', [AuthController::class, 'googleRedirect'])->name('google.redirect');
Route::get('/auth/google/callback', [AuthController::class, 'googleCallback'])->name('google.callback');


// Email Verification Notice
Route::get('/email/verify', function () {
    return view('verify-email.index');
})->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (Request $request, $id, $hash) {
    $user = User::findOrFail($id);

    if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        abort(403);
    }

    if (! $user->hasVerifiedEmail()) {
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }
    }

    Auth::login($user);

    return redirect('/events')->with('success', 'Email verified successfully!');
})->middleware(['signed'])->name('verification.verify');

// --- ROUTE UNTUK LOGOUT (HARUS SUDAH LOGIN) ---   
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    // GET route as fallback for expired CSRF tokens
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout.get');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Verification link sent!');
    })->middleware('throttle:6,1')->name('verification.send');

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

Route::get('/events', [EventController::class, 'viewAllEvents'])->name('events');

Route::get('/events/{event}', [EventController::class, 'viewEventDetails'])->name('events.show');

Route::get('/events/{event}/booths', [EventController::class, 'showBooths'])->name('booths.index');

Route::get('/booths/{booth}/details', [EventController::class, 'showBoothDetails'])->name('booths.details');

Route::middleware(['auth', 'verified', 'role:tenant'])->group(function () {
    Route::get('/my-bookings', [BookingController::class, 'viewMyBookings'])->name('my-bookings');
    Route::get('/my-bookings/{booking}', [BookingController::class, 'viewMyBookingDetails'])->name('my-booking-details');
    Route::get('/my-bookings/{booking}/invoice', [BookingController::class, 'downloadInvoice'])->name('booking.invoice');
});

Route::middleware(['auth', 'verified', 'role:event_organizer'])->group(function () {
    Route::get('/my-events/details', function (Request $request) {
        if ($request->has('event_id')) {
            $event = Event::findOrFail($request->query('event_id'));
            if ($event->user_id !== Auth::id()) {
                abort(403);
            }
        }
        return view('my-events.details');
    })->name('my-event-details');

    Route::get('/my-events/edit', function (Request $request) {
        if ($request->has('event_id')) {
            $event = Event::findOrFail($request->query('event_id'));
            if ($event->user_id !== Auth::id()) {
                abort(403);
            }
        }
        return view('my-events.edit');
    })->name('my-event-edit');

    Route::prefix('my-events')->name('my-events.')->group(function () {
        Route::get('/', [EventController::class, 'viewMyEvents'])->name('index');
        Route::get('/create', [EventController::class, 'newMyEvents'])->name('create');
        Route::post('/', [EventController::class, 'createEvent'])->name('store');
        Route::get('/{event}', [EventController::class, 'show'])->name('show');
        Route::get('/{event}/edit', [EventController::class, 'editMyEvents'])->name('edit');
        Route::put('/{event}', [EventController::class, 'editEvents'])->name('update');
        Route::post('/{event}/publish', [EventController::class, 'publishEvents'])->name('publish');
        Route::delete('/{event}', [EventController::class, 'deleteEvent'])->name('destroy');
    });
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/request-refund/{booking}', [\App\Http\Controllers\RefundRequestController::class, 'create'])->name('request-refund');
    Route::post('/request-refund/{booking}', [\App\Http\Controllers\RefundRequestController::class, 'createRefundRequest'])->name('refund-request.store');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/events/{event}/refund-requests', [\App\Http\Controllers\RefundRequestController::class, 'viewRefundList'])->name('refund-requests');
    Route::get('/events/{event}/refund-requests/{refundRequest}', [\App\Http\Controllers\RefundRequestController::class, 'viewRefundDetails'])->name('refund-requests.show');
    Route::patch('/events/{event}/refund-requests/{refundRequest}/approve', [\App\Http\Controllers\RefundRequestController::class, 'approveRefund'])->name('refund-requests.approve');
    Route::patch('/events/{event}/refund-requests/{refundRequest}/reject', [\App\Http\Controllers\RefundRequestController::class, 'rejectRefund'])->name('refund-requests.reject');
});

Route::get('/refund-requests/details', function () {
    return view('refund-requests.details');
})->name('refund-requests-details');

// Booking requests routes (for event organizers)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/events/{event}/booking-requests', [BookingController::class, 'viewBookings'])
        ->name('booking-requests');

    Route::get('/events/{event}/booking-requests/{booking}', [BookingController::class, 'viewBookingDetails'])
        ->name('booking-request-details');

    Route::post('/events/{event}/booking-requests/{booking}/confirm', [BookingController::class, 'approveBooking'])
        ->name('booking-requests.confirm');

    Route::post('/events/{event}/booking-requests/{booking}/reject', [BookingController::class, 'rejectBookingRequest'])
        ->name('booking-requests.reject');
});

Route::post('/bookings', [BookingController::class, 'createBooking'])->name('bookings.store');

// Payment routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/bookings/{booking}/payment', [\App\Http\Controllers\PaymentController::class, 'create'])->name('payment.create');
    Route::post('/bookings/{booking}/payment/initiate', [\App\Http\Controllers\PaymentController::class, 'makePayment'])->name('payment.initiate');
    Route::post('/bookings/{booking}/payment/check-status', [\App\Http\Controllers\PaymentController::class, 'checkPaymentStatus'])->name('payment.check-status');
    Route::get('/bookings/{booking}/payment/success', [\App\Http\Controllers\PaymentController::class, 'handleSuccess'])->name('payment.success');
    Route::get('/bookings/{booking}/payment/pending', [\App\Http\Controllers\PaymentController::class, 'pending'])->name('payment.pending');
    Route::get('/bookings/{booking}/payment/error', [\App\Http\Controllers\PaymentController::class, 'error'])->name('payment.error');
});

// Midtrans callback (no auth required)
Route::post('/payment/callback', [\App\Http\Controllers\PaymentController::class, 'processCallback'])->name('payment.callback');

// Rating routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/bookings/{booking}/rating', [\App\Http\Controllers\RatingController::class, 'rateOrganizer'])->name('rating.store');
    Route::get('/bookings/{booking}/rating/check', [\App\Http\Controllers\RatingController::class, 'checkRating'])->name('rating.check');

    // Organizer rating tenant routes
    Route::get('/events/{event}/attendants/{booking}', [BookingController::class, 'showAttendant'])->name('attendant.details');
    Route::post('/events/{event}/attendants/{booking}/rating', [\App\Http\Controllers\RatingController::class, 'rateTenant'])->name('attendant.rating.store');
    Route::get('/events/{event}/attendants/{booking}/rating/check', [\App\Http\Controllers\RatingController::class, 'checkOrganizerRating'])->name('attendant.rating.check');
});

Route::get('/booth-layout/data/{event}', [BoothController::class, 'viewLayout'])->name('booth-layout.data');
Route::get('/booth-layout/floors/{event}', [BoothController::class, 'getFloors'])->name('booth-layout.floors');

// Protected booth layout editing (Organizer only)
Route::middleware(['auth', 'verified', 'role:event_organizer'])->group(function () {
    Route::get('/booth-layout', function (Request $request) {
        $eventId = $request->query('event_id');
        if ($eventId) {
            $event = Event::findOrFail($eventId);
            if ($event->user_id !== Auth::id()) {
                abort(403);
            }
        }
        return view('booth-layout.index');
    })->name('booth-layout');

    Route::get('/booth-layout/view', function (Request $request) {
        $eventId = $request->query('event_id');
        if ($eventId) {
            $event = Event::findOrFail($eventId);
            if ($event->user_id !== Auth::id()) {
                abort(403);
            }
        }
        return view('booth-layout.view', [
            'eventId' => $eventId,
        ]);
    })->name('booth-layout.view');

    Route::post('/booth-layout/save', [BoothController::class, 'saveLayout'])->name('booth-layout.save');
    Route::delete('/booth-layout/floors/{event}/{floor}', [BoothController::class, 'deleteFloor'])->name('booth-layout.deleteFloor');
});

// Location API routes for cascading dropdowns
Route::get('/api/cities', function (Request $request) {
    $cities = City::where('province_id', $request->province_id)
        ->orderBy('name')
        ->get(['id', 'name']);
    return response()->json($cities);
});

Route::get('/api/districts', function (Request $request) {
    $districts = District::where('city_id', $request->city_id)
        ->orderBy('name')
        ->get(['id', 'name']);
    return response()->json($districts);
});

Route::get('/api/subdistricts', function (Request $request) {
    $subdistricts = Subdistrict::where('district_id', $request->district_id)
        ->orderBy('name')
        ->get(['id', 'name']);
    return response()->json($subdistricts);
});

Route::post('/payment/notification', [PaymentController::class, 'handleNotification'])->name('payment.notification');
