<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'email' => 'required|email|string',
            'password' => 'required|string'
        ]);

        if ($validated->fails()) {
            return response()->json($validated->errors(), 403);
        }

        $creaentials = ['email' => $request->email, 'password' => $request->password];

        if (!Auth::attempt($creaentials)) {
            return response()->json(['error' => 'username หรือ password ไม่ถูกต้อง.'], 401);
        }

        $user = User::where('email', $request->email)->first();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'data' => $user
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->delete();
        DB::setDefaultConnection('main');
        return response()->json([
            'message' => 'ออกจากระบบเรียบร้อยแล้ว.'
        ]);
    }
}
