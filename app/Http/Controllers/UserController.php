<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //Danh sách các user
    function index(){
        $users=User::get();
        return response()->json([
            'status'=>true,
            'data_users'=>$users
        ]);
    }

    public function showUser(){
        $users=User::where('role','=','USER')->get();
        if(!$users){
            return response()->json([
                'status'=>false,
                'message'=>'Không tìm thấy thông tin khách hàng!'
            ],404);
        }
        return response()->json([
            'status'=>true,
            'data_users'=>$users
        ]);
    }

    //Tìm kiếm user
    function show(String $id){
        $result = User::where('id', $id)->first();
        if(!$result){
            return response()->json([
                'status'=>false,
                'message'=>'Không tìm thấy thông tin khách hàng!'
            ],404);
        }
        return response()->json([
            'status'=>true,
            'data_users'=>$result
        ]);
    }

    //Tạo user
    function getOrCreateUserByPhone(CreateUserRequest $request){
        //Tìm kiếm khách hàng bằng số điện thoại
        $sodienthoai=$request->sodienthoai;
        $result=User::where('sodienthoai','=',$sodienthoai)->first();

         //Khách hàng nhập sdt nếu chưa có trong hệ thống thì tạo mới
        if(!$result){
            $user=new User();
            $user->sodienthoai=$request->sodienthoai;
            $user->role='USER';
            $user->loai='NORMAL';

            $user->save();

            return response()->json([
                'status'=>true,
                'message'=>'Tạo mới khách hàng thành công!',
                'data' => $user
            ]);
        }
        else{
            return response()->json([
                'status'=>true,
                'message'=>'Khách hàng đã tồn tại!',
                'data'=>$result
            ]);
        }
    }

    //Đăng ký khách hàng
    function register(CreateUserRequest $request){
        //Tìm kiếm khách hàng bằng số điện thoại
        $result=User::where('sodienthoai','=',$request->sodienthoai)->first();

        //Nếu không tìm thấy thì tạo mới khách hàng
        if(!$result){
            $user=new User();
            $user->hoten=$request->hoten;
            $user->email=$request->email;
            $user->sodienthoai=$request->sodienthoai;
            $user->matkhau=Hash::make($request->matkhau);
            $user->ngaysinh=$request->ngaysinh;
            $user->role='USER';
            $user->loai='NORMAL';

            $user->save();

            return response()->json([
               'status'=>true,
               'message'=>'Đăng ký khách hàng thành công!',
               'data'=>$user
            ]);
        }

        //Nếu tìm thấy khách hàng thì update khách hàng
        else{
            $result->hoten=$request->hoten;
            $result->email=$request->email;
            $result->sodienthoai=$request->sodienthoai;
            $result->matkhau=bcrypt($request->matkhau);
            $result->ngaysinh=$request->ngaysinh;
            $result->save();

            return response()->json([
                'status'=>true,
                'message'=>'Đăng ký khách hàng thành công!',
                'data'=>$result
            ]);
        }
        
    }

    //Cập nhật thông tin khách hàng
    function update(CreateUserRequest $request,String $id){
        $result=User::where('id','=',$id)->first();

        if($result){
            $result->hoten=$request->hoten;
            $result->email=$request->email;
            $result->sodienthoai=$request->sodienthoai;
            $result->ngaysinh=$request->ngaysinh;
            $result->save();

            return response()->json([
                'status'=>true,
                'message'=>'Cập nhật thông tin khách hàng thành công!',
                'data'=>$result
            ]);
        }
        else{
            return response()->json([
                'status'=>false,
                'message'=>'Không tìm thấy khách hàng!'
            ],404);
        }
    }

    //Hủy tài khoản
    function destroy(String $id){
        $result=User::where('id','=',$id)->first();
        if($result){
            $result->delete();
            return response()->json([
                'status'=>true,
                'message'=>'Hủy tài khoản khách hàng thành công!'
            ]);
        }
        else{
            return response()->json([
                'status'=>false,
                'message'=>'Không tìm thấy khách hàng!'
            ],404);
        }
    }
}
