<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        // Validate the request data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        // Attempt to authenticate the user
        if (Auth::attempt($credentials)) {
            // Authentication successful
            $user = Auth::user();
            // Generate an API token for the user (if using Laravel's built-in token-based authentication)
            $token = $user->createToken('API Token')->plainTextToken;

            // Return a response with the authenticated user and token
            return response()->json([
                // 'user' => $user,
                'token' => $token,
                'message' => 'Welcome back '. $user->firstname,
            ], 200);
        } else {
            // Authentication failed
            return response()->json(['message' => 'Incorrect Email or Password'], 401);
        }
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->user();

        // Implement your logic to create or authenticate the user
        // You can access the Google user data using the $googleUser variable

        // Example: Authenticate the user
        $user = User::where('email', $googleUser->email)->first();

        if (!$user) {
            // User does not exist, create a new user
            $user = User::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'password' => '', // You can generate a random password or use a dummy value
            ]);
        }

        Auth::login($user);

        // Redirect to your desired success page or return a JSON response
        return response()->json(['message' => 'Google login successful'], 200);
    }

    public function redirectToApple()
    {
        return Socialite::driver('apple')->redirect();
    }

    public function handleAppleCallback()
    {
        // Handle Apple login callback logic
    }
}
