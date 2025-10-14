<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // --- SIGN UP ---
    public function showSignupForm()
    {
        return view('signup.index');
    }

    private function normalizeIndoPhone(?string $input): ?string
    {
        if ($input === null || trim($input) === '') return null;
        $digits = preg_replace('/\D+/', '', $input);
        if ($digits === '') return null;

        if (str_starts_with($digits, '0')) {
            $digits = '62' . substr($digits, 1);
        } elseif (str_starts_with($digits, '62')) {
            $digits = $digits;
        } else {
            $digits = '62' . $digits;
        }
        return $digits;
    }

    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'business_name' => 'required|string|max:255|unique:users,name',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone_number' => 'required|string|max:20',
            'password' => 'required|string|min:8',
            'user_type' => ['required', 'string', Rule::in(['tenant', 'event_organizer'])],
            'business_category' => 'required|string',
            'custom_business_category' => 'nullable|string|required_if:business_category,other|max:255',
        ]);

        $normalizedPhone = $this->normalizeIndoPhone($request->phone_number);
        if (!$normalizedPhone) {
            $validator->errors()->add('phone_number', 'Invalid phone number format.');
        }

        $validator->after(function ($validator) use ($normalizedPhone) {
            if ($normalizedPhone && User::where('phone_number', $normalizedPhone)->exists()) {
                $validator->errors()->add('phone_number', 'The mobile number has already been taken.');
            }
        });

        if ($validator->fails()) {
            return redirect()->route('signup')->withErrors($validator)->withInput();
        }

        $role = Role::where('name', $request->user_type)->firstOrFail();

        $category = ($request->business_category === 'other')
            ? $request->custom_business_category
            : $request->business_category;

        $user = User::create([
            'role_id' => $role->id,
            'display_name' => $request->full_name,
            'name' => $request->business_name,
            'email' => $request->email,
            'phone_number' => $normalizedPhone,
            'business_category' => $category,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        // UPDATED: Menambahkan pesan selamat datang setelah sign up
        $welcomeMessage = 'Registration successful. Welcome, ' . $user->display_name . '!';

        if ($user->role->name === 'tenant') {
            return redirect()->route('events')->with('success', $welcomeMessage);
        } elseif ($user->role->name === 'event_organizer') {
            return redirect()->route('my-events.index')->with('success', $welcomeMessage);
        }

        return redirect()->route('events')->with('success', $welcomeMessage);
    }

    // --- SIGN IN ---
    public function showLoginForm()
    {
        return view('login.index');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate();

            $user = Auth::user();
            $welcomeMessage = 'Login successful. Welcome back, ' . $user->display_name . '!';

            if ($user->role->name === 'tenant') {
                return redirect()->route('events')->with('success', $welcomeMessage);
            } elseif ($user->role->name === 'event_organizer') {
                return redirect()->route('my-events.index')->with('success', $welcomeMessage);
            }

            return redirect()->route('events')->with('success', $welcomeMessage);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    // --- LOGOUT ---
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        // UPDATED: Menambahkan pesan sukses setelah logout
        return redirect('/')->with('success', 'You have been successfully logged out.');
    }

    public function googleRedirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function showOnboarding()
    {
        $user = Auth::user();
        return view('onboarding.index', compact('user'));
    }

    public function saveOnboarding(Request $request)
    {
        $request->validate([
            'business_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'business_category' => 'required|string',
            'custom_business_category' => 'nullable|string|required_if:business_category,other|max:255',
            'user_type' => ['required', 'string', Rule::in(['tenant', 'event_organizer'])],
        ]);

        $userId = Auth::id();
        if (!$userId) return redirect()->route('login');

        $user = User::findOrFail($userId);
        $normalizedPhone = $this->normalizeIndoPhone($request->phone_number);

        if (!$normalizedPhone) {
            return back()->withErrors(['phone_number' => 'Invalid phone number format.'])->withInput();
        }

        $existingUser = User::where('phone_number', $normalizedPhone)
            ->where('id', '!=', $userId)
            ->first();

        if ($existingUser) {
            return back()->withErrors(['phone_number' => 'The mobile number has already been taken.'])->withInput();
        }

        $category = $request->business_category === 'other'
            ? $request->custom_business_category
            : $request->business_category;

        $roleName = $request->input('user_type', 'tenant');
        $roleId = Role::where('name', $roleName)->value('id') ?? $user->role_id;

        $user->name = $request->business_name;
        $user->phone_number = $normalizedPhone;
        $user->business_category = $category;
        $user->role_id = $roleId;
        $user->save();

        if ($roleName === 'tenant') {
            return redirect()->route('events');
        } elseif ($roleName === 'event_organizer') {
            return redirect()->route('my-events.index');
        }
        return redirect()->route('events');
    }

    public function googleCallback()
    {
        try {
            $google = Socialite::driver('google')->user();
        } catch (\Throwable $e) {
            return redirect()->route('login')->with('error', 'Google login failed. Try again.');
        }

        $user = User::where('provider', 'google')
            ->where('provider_id', $google->getId())
            ->first();

        if (!$user && $google->getEmail()) {
            $user = User::where('email', $google->getEmail())->first();
        }

        if (!$user) {
            $roleId = Role::where('name', 'tenant')->value('id') ?? 2;
            $displayName = $google->getName() ?: ($google->getNickname() ?: 'Google User');
            $businessName = 'Google-' . ($google->getEmail() ?: $google->getId());

            $user = User::create([
                'role_id' => $roleId,
                'display_name' => $displayName,
                'name' => $businessName,
                'email' => $google->getEmail(),
                'phone_number' => null,
                'business_category' => null,
                'password' => Hash::make(Str::random(32)),
                'provider' => 'google',
                'provider_id' => $google->getId(),
                'avatar' => $google->getAvatar(),
            ]);

            // Eager load the role relationship for newly created users
            $user->load('role');
        } else {
            $user->update([
                'provider' => 'google',
                'provider_id' => $google->getId(),
                'avatar' => $google->getAvatar(),
            ]);

            // Ensure role relationship is loaded
            if (!$user->relationLoaded('role')) {
                $user->load('role');
            }
        }

        Auth::login($user, remember: true);

        $welcomeMessage = 'Successfully logged in with Google. Welcome, ' . $user->display_name . '!';

        $needsOnboarding = empty($user->phone_number) || empty($user->business_category);
        if ($needsOnboarding) {
            return redirect()->route('onboarding.show')->with('success', 'Welcome! Please complete your profile.');
        }

        if ($user->role->name === 'tenant') {
            return redirect()->route('events')->with('success', $welcomeMessage);
        } elseif ($user->role->name === 'event_organizer') {
            return redirect()->route('my-events.index')->with('success', $welcomeMessage);
        }
        return redirect()->route('events')->with('success', $welcomeMessage);
    }
}
