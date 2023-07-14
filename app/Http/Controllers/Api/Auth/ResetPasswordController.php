<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ForgotPasswordMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    public function sendResetCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        $user = User::where('email', $request->email)->first();

        $code = Str::random(6); // Generate a random 6-digit code
        $user->update(['reset_password_code' => $code]);        
        Mail::to($user->email)->send(new ForgotPasswordMail($user, $code));

        return response()->json(['message' => 'Reset code '. $code .' has been sent to your email.'], 200);
    }



    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'code' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::where('email', $request->email)->where('reset_password_code', $request->code)->first();

        if (!$user) {
            return response()->json(['message' => 'Invalid email or code.'], 400);
        }

        $user->update([
            'password' => Hash::make($request->password),
            'reset_password_code' => null,
        ]);

        return response()->json(['message' => 'Password has been reset successfully.'], 200);
    }
}
