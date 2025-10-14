<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function show()
    {
        $user = User::findOrFail(Auth::id());

        $categories = [
            'technology', 'healthcare', 'education', 'retail', 'food-beverage',
            'automotive', 'real-estate', 'finance', 'entertainment', 'other'
        ];

        return view('profile.index', [
            'user' => $user,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request)
    {
        $user = User::findOrFail(Auth::id());

        $validated = $request->validate([
            'full_name'                 => ['required', 'string', 'max:255'],
            'business_name'             => ['required', 'string', 'max:255', Rule::unique('users', 'name')->ignore($user->id)],
            'mobile_number'             => ['required', 'string', 'max:20'],
            'business_category'         => ['required', 'string'],
            'custom_business_category'  => ['nullable', 'string', 'max:255'],
        ]);

        $phone = '+62' . ltrim($validated['mobile_number'], '0');

        $category = $validated['business_category'] === 'other'
            ? ($validated['custom_business_category'] ?? 'other')
            : $validated['business_category'];

        $user->display_name      = $validated['full_name'];
        $user->name              = $validated['business_name'];
        $user->phone_number      = $phone;
        $user->business_category = $category;
        $user->save();

        // Use 'success' to match the notification code
        return back()->with('success', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $user = User::findOrFail(Auth::id());

        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'new_password'     => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->password = Hash::make($validated['new_password']);
        $user->save();

        // Use 'success' to match the notification code
        return back()->with('success', 'Password updated successfully!');
    }
}