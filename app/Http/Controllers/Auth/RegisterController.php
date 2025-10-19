<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserCreated;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\View\View;

use App\Models\User;

class RegisterController extends Controller
{
    /**
     * Display a login form.
     */
    public function showRegistrationForm()
    {
        if (Auth::check()) {
            return redirect('/');
        }

        return view('auth.register');
    }

    /**
     * Register a new user.
     */
    public function register(Request $request){
        $request->validate([
            'name' => 'required|string|max:250',
            'country_code' => 'required|integer|min:111|max:999',
            'phone_number' => 'required|integer|min:111111111|max:999999999',
            'email' => 'required|email|max:250|unique:users',
            'password' => 'required|min:8|confirmed'
        ]);

        $full_phone_number = '+' . $request->country_code . $request->phone_number;

        $user = User::create([
            'name' => $request->name,
            'phone_number' => $full_phone_number, // Store full phone number here
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $credentials = $request->only('email', 'password');
        Auth::attempt($credentials);
        $request->session()->regenerate();

        event(new UserCreated($user));

        return redirect()->route('login')
            ->withSuccess('You have successfully registered & logged in!');
    }
}
