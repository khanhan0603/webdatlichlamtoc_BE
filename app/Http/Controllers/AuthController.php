<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //Dang Nhap
    function login (Request $request){
        $request->validate([
                'email'=>'required|email',
                'matkhau'=>'required'
            ],
            [
                'email.required'=>'Email là bắt buộc',
                'email.email'=>'Định dạng email không hợp lệ',
                'matkhau.required'=>'Mật khẩu là bắt buộc'
            ]
        );

        $user=User::where('email','=',$request->email)->first();

        if(!$user||!Hash::check($request->matkhau,$user->matkhau)){
            return response()->json([
                'status'=>false,
                'message'=>'Email hoặc mật khẩu không đúng!'
            ],401);
        }

        //Tạo token Sanctum
        $token=$user->createToken('auth-token')->plainTextToken;
        return response()->json([
            'status'=>true,
            'message'=>'Đăng nhập thành công!',
            'token'=>$token,
            'user' => new UserResource($user)
        ]);
    }

    //Đăng xuất
    function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'status'=>true,
            'message'=>'Đăng xuất thành công!'
        ]);
    }
}
