<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleSignUpController extends Controller
{
    public function signUp(Request $request)
    {
        // Get the user details from Google
        if($googleUser = Socialite::driver('google')->stateless()->userFromToken($request->input('access_token'))){
            return response()->json(['msg' => 'yes']);
        }

        // Check if the user already exists
        $user = User::where('email', $googleUser->email)->first();

        if (!$user) {
            // User does not exist, create a new user
            $user = User::create([
                'firstname' => $googleUser->givenName,
                'lastname' => $googleUser->familyName,
                'email' => $googleUser->email,
                'provider' => 'google',
                'provider_id' => $googleUser->id,
                'password' => '',
            ]);
        }

        // Generate a new API token for the user
        $token = $user->createToken('google-signup')->plainTextToken;

        return response()->json(['token' => $token], 201);
    }
}
