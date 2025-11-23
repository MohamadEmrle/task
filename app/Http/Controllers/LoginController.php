<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Resources\AdminRecource;
use App\Models\Admin;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        $data = $request->all();
        $admin = Admin::where('email', $data['email'])->first();
        if (!$admin) {
            return response()->json(['message' =>  'البريد الإلكتروني غير صحيح'], 400);
        }
        $token = $admin->createToken('auth_token')->plainTextToken;
        return response()->json([
            'message' => 'تم تسجيل الدخول بنجاح',
            'token' => $token,
            'admin' => new AdminRecource($admin)
        ]);
    }
}
