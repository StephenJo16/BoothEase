<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function show()
    {
        $user = User::findOrFail(Auth::id());

        // Fetch categories from database
        $categories = Category::orderBy('name')->get();

        return view('profile.index', [
            'user' => $user,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request)
    {
        $user = User::findOrFail(Auth::id());

        $validated = $request->validate([
            'full_name'                 => ['required', 'string', 'max:255', Rule::unique('users', 'display_name')->ignore($user->id)],
            'business_name'             => ['required', 'string', 'max:255', Rule::unique('users', 'name')->ignore($user->id)],
            'mobile_number'             => ['required', 'string', 'max:20'],
            'category_id'               => ['required', 'exists:categories,id'],
        ]);

        // Normalize phone number
        $phone = '+62' . ltrim($validated['mobile_number'], '0');

        // Check if phone number is already taken by another user
        $existingPhone = User::where('phone_number', $phone)
            ->where('id', '!=', $user->id)
            ->exists();

        if ($existingPhone) {
            return back()->withErrors(['mobile_number' => 'The mobile number has already been taken.'])->withInput();
        }

        $user->display_name      = $validated['full_name'];
        $user->name              = $validated['business_name'];
        $user->phone_number      = $phone;
        $user->category_id       = $validated['category_id'];
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
