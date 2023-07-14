<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AppleSignUpController extends Controller
{
    public function signUp()
    {
        // Redirect the user to the Apple sign-in page
        $redirectUrl = ''; // Generate the redirect URL for Apple sign-in
        return redirect($redirectUrl);
    }

    public function callback(Request $request)
    {
        // Verify the identity token received from Apple
        $identityToken = $request->input('identity_token');

        // Decode the identity token to extract user information
        $jwtParts = explode('.', $identityToken);
        $payload = json_decode(base64_decode($jwtParts[1]), true);

        // Extract the user data from the decoded payload
        $appleUserName = $payload['name'];
        $appleUserEmail = $payload['email'];

        // Implement your logic to create or authenticate the user
        // You can access the Apple user data and email using the extracted variables

        // Example: Authenticate the user
        $user = User::where('email', $appleUserEmail)->first();

        if (!$user) {
            // User does not exist, create a new user
            $user = User::create([
                'name' => $appleUserName,
                'email' => $appleUserEmail,
                'password' => '', // You can generate a random password or use a dummy value
            ]);
        }

        Auth::login($user);

        // Redirect to your desired success page or return a JSON response
        return response()->json(['message' => 'Apple sign-up successful'], 200);
    }
}
