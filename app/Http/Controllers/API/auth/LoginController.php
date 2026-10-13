<?php

namespace App\Http\Controllers\API\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $user = User::Where('email', $request->email) -> first();
        if (!$user || !Hash::check($request->password, $user->password)){
            return response()-> json([
            'message' => 'invalid credentials'], 401);
        }

        $token =$user->createToken('ap_token')->plainTextToken;
        return response()->json([
            'token' => $token,
            'user'=> $user
        ], Response::HTTP_OK);
    }
}
