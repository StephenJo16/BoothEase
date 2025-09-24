<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule; // Import Rule

class AuthController extends Controller
{
    // --- SIGN UP ---
    public function showSignupForm()
    {
        return view('signup.index');
    }

    public function signup(Request $request)
    {
        // FIX: Menyesuaikan validasi dengan role name yang benar
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'business_name' => 'required|string|max:255|unique:users,name',
            'email' => 'required|string|email|max:255|unique:users,email',
            'country_code' => 'required|string',
            'mobile_number' => 'required|string|max:20',
            'password' => 'required|string|min:8',
            // FIX: Mengubah 'organizer' menjadi 'event_organizer'
            'user_type' => ['required', 'string', Rule::in(['tenant', 'event_organizer'])],
            'business_category' => 'required|string',
            'custom_business_category' => 'nullable|string|required_if:business_category,other|max:255',
        ]);

        $fullPhoneNumber = $request->country_code . $request->mobile_number;
        $validator->after(function ($validator) use ($fullPhoneNumber) {
            if (User::where('phone_number', $fullPhoneNumber)->exists()) {
                $validator->errors()->add('mobile_number', 'The mobile number has already been taken.');
            }
        });

        if ($validator->fails()) {
            return redirect()->route('signup')->withErrors($validator)->withInput();
        }

        // FIX: Menghapus logika terjemahan, langsung menggunakan value dari request
        $role = Role::where('name', $request->user_type)->firstOrFail();

        $category = ($request->business_category === 'other')
                        ? $request->custom_business_category
                        : $request->business_category;

        $user = User::create([
            'role_id' => $role->id,
            'display_name' => $request->full_name,
            'name' => $request->business_name,
            'email' => $request->email,
            'phone_number' => $fullPhoneNumber,
            'business_category' => $category,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);
        
        // Logika redirect sudah benar
        if ($user->role->name === 'tenant') {
            return redirect()->route('events');
        } elseif ($user->role->name === 'event_organizer') {
            return redirect()->route('my-events');
        }

        return redirect()->route('home');
    }

    // Method login dan logout Anda sudah benar, tidak perlu diubah
    // --- SIGN IN ---
    public function showLoginForm()
    {
        return view('login.index');
    }

    public function login(Request $request)
    {
        $request->validate([
            'country_code' => 'required|string',
            'mobile_number' => 'required|string',
            'password' => 'required|string',
        ]);

        $fullPhoneNumber = $request->country_code . $request->mobile_number;
        
        // Coba login menggunakan phone_number
        if (Auth::attempt(['phone_number' => $fullPhoneNumber, 'password' => $request->password])) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            if ($user->role->name === 'tenant') {
                return redirect()->intended(route('events'));
            } elseif ($user->role->name === 'event_organizer') {
                return redirect()->intended(route('my-events'));
            }
            
            return redirect()->route('home');
        }

        return back()->withErrors([
            'mobile_number' => 'The provided credentials do not match our records.',
        ])->onlyInput('mobile_number');
    }

    // --- LOGOUT ---
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/'); 
    }
}