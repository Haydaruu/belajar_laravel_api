<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $check_login = auth()->attempt($request->only('email', 'password'));

        if (! $check_login) {
            return response()->json([
                'status' => false,
                'message' => 'data yang anda masukan salah',
                'data' => [],
            ], Response::HTTP_UNAUTHORIZED);
        }

        return response()->json([
            'status' => true,
            'message' => 'Login Berhasil',
            'data' => [
                'token' => $check_login,
            ],
        ]);

    }
}
