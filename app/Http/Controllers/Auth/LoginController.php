<?php
 
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class LoginController extends Controller
{

    /**
     * Display a login form.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect('/');
        } else {
            return view('auth.login');
        }
    }

    /**
     * Handle an authentication attempt.
     */
    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
 
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
 
            //return redirect()->route('products.list');
            return auth()->user()->isAdmin() ? redirect()->route('admin.dashboard') : redirect()->route('products.list');
        }
 
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Log out the user from application.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')
            ->withSuccess('You have logged out successfully!');
    } 

    public function recoverForm(Request $request)
    {
        return view('auth.recover-form');
    }

    public function newPasswordForm(Request $request)
    {
        return view('auth.new-password-form', ['email' => $request->email]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:4|confirmed',
        ]);

        User::where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);

        return redirect()->route('login')->with('success', 'Password reseted.');
    }

    public function googleLogin(){
        return Socialite::driver('google')->redirect();
    }

    public function googleAuthentication(){
        $googleUser = Socialite::driver('google')->stateless()->user();
        
        $user = User::where('email', $googleUser->email)->first();
        
        if($user){
            Auth::login($user);
            return redirect()->route('products.list');
        } else{
            $userdata = User::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'password' => Hash::make('olameudeus'),
                'google_id' => $googleUser->id,
            ]);

            if($userdata){
                Auth::login($userdata);
                return redirect()->route('products.list');
            }
        }
        
    }
}
