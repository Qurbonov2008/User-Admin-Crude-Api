<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $registerUser = User::where('username' , $request->username)->first();
        if($registerUser)
        {
            return response()->json(["message" => 'Bunday User bor']);
        }
        $user = User::create([
            'full_name' => $request-> full_name,
            'username'=> $request-> username,
            "password" => Hash::make($request->qassword)
        ]);
        return response()->json(['message' => 'Foydalanuvchi muvaffaqiyatli qo\'shildi']);
    }
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');
        $user=User::where('username',$request->username)->first();
        if (!$user) {
            return response()->json(['message'=> 'Kechirasiz User topilmadi'],404);
        }
        if (password_verify($credentials['password'], $user->password)) {
            $token = $user->createToken('auth-token')->plainTextToken;
            return response()->json([
                'message'=>'foydalanuvchi topildi',
                'token' => $token
            ], 200);
        }else {
            return response()->json(["message" => "Parol notog'ri"],404);
        }
    }
}
