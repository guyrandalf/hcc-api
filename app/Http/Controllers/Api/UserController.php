<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function loginUser(Request $request)
    {
        $input = $request->all();
        Auth::attempt($input);
        $user = Auth::user();
        $token = $user->createToken('hcc_user')->accessToken;
        return response(['status' => 200, 'token' => $token], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function getUserDetails(Request $request)
    {
        if (Auth::guard('api')->check()) {
            $user = Auth::guard('api')->user();
            return Response(['data' => $user], 200);
        }
        return Response(['data' => 'Unauthorized Access'], 401);
    }

    /**
     * Display the specified resource.
     */
    public function userLogout(User $user)
    {
        if (Auth::guard('api')->check()) {
            $accessToken = Auth::guard('api')->user()->token();

            \DB::table('oauth_refresh_tokens')
                ->where('access_token_id', $accessToken->id)
                ->update(['revoked' => true]);

            $accessToken->revoke();

            return Response(['data' => 'Unauthorized Access', 'message' => 'User Successfully Logged Out']);
        }
        return Response(['data' => 'Unauthorized Access'], 401);
    }
}
