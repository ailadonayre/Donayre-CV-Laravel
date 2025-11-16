<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Try to authenticate with username
        if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password']], $request->filled('remember'))) {
            $request->session()->regenerate();
            
            // Update last login
            /** @var User $user */
            $user = Auth::user();
            $user->last_login = now();
            $user->save();

            return redirect()->intended(route('home'))
                ->with('success', 'Login successful!');
        }

        // Try with email if username failed
        if (Auth::attempt(['email' => $credentials['username'], 'password' => $credentials['password']], $request->filled('remember'))) {
            $request->session()->regenerate();
            
            // Update last login
            /** @var User $user */
            $user = Auth::user();
            $user->last_login = now();
            $user->save();

            return redirect()->intended(route('home'))
                ->with('success', 'Login successful!');
        }

        throw ValidationException::withMessages([
            'username' => 'Invalid username or password. Please try again.',
        ]);
    }
}