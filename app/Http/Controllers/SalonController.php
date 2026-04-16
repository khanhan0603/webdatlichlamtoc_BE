<?php

namespace App\Http\Controllers;

use App\Models\HairStyle;
use App\Models\Salon;
use Illuminate\Http\Request;

class SalonController extends Controller
{
    public function index(){
        try{
            $salons=Salon::all();
            return response()->json([
                'status'=>true,
                'data'=>$salons
            ],200);
        }
        catch(\Exception $e){
            return response()->json([
                'status'=>false,
                'message'=>$e->getMessage()
            ],500);
        }
    }

    public function show(String $id){
        try{
            $salon=Salon::find($id);
            return response()->json([
                'status'=>true,
                'data'=>$salon
            ],200);
        }
        catch(\Exception $e){
            return response()->json([
                'status'=>false,
                'message'=>$e->getMessage()
            ],500);
        }
    }

    public function store(Request $request){
            $request->validate([
                'diachi'=>'required',
            ],
            [
                'diachi.required'=>'Địa chỉ salon không được để trống!'
            ]);

            $salon=new Salon();
            $salon->diachi=$request->diachi;

            try{
                $salon->save();
                return response()->json([
                    'status'=>true,
                    'message'=>'Thêm thông tin thành công!',
                    'data'=>$salon
                ],200);
            }
            catch(\Exception $e){
                return response()->json([
                    'status'=>false,
                    'message'=>$e->getMessage()
                ],500);
            }
    }

    public function delete(String $id){
        $salon=Salon::where('id','=',$id)->first();
        $hair_style=HairStyle::where('id_salon','=',$id)->first();

        if($hair_style){
            return response()->json([
                'status'=>false,
                'message'=>'Không thể xóa salon vì có chứa thông tin nhân viên tư vấn!'
            ],404);
        }
        else{
            try{
                $salon->delete();
                return response()->json([
                    'status'=>true,
                    'message'=>'Xóa thành công!'
                ],200);
            }
            catch(\Exception $e){
                return response()->json([
                    'status'=>false,
                    'message'=>$e->getMessage()
                ],500);
            }
        }
    }

    public function update(Request $request,String $id){
        $request->validate([
            'diachi'=>'required',
        ],[
            'diachi.required'=>'Địa chỉ salon không được để trống!'
        ]);

        $salon=Salon::where('id','=',$id)->first();
        if(!$salon){
            return response()->json([
                'status'=>false,
                'message'=>'Không tìm thấy thông tin salon!'
            ],404);
        }
        try{
            $salon->diachi=$request->diachi;
            $salon->save();
            return response()->json([
                'status'=>true,
                'message'=>'Cập nhật thành công!',
                'data'=>$salon
            ],200);
        }
        catch(\Exception $e){
            return response()->json([
                'status'=>false,
                'message'=>$e->getMessage()
            ],500);
        }
    }

    public function showHairStyle(String $id){
        try{
            $hair_style=HairStyle::where('id_salon','=',$id)->get();
            return response()->json([
                'status'=>true,
                'data'=>$hair_style
            ],200);
        }
        catch(\Exception $e){
            return response()->json([
                'status'=>false,
                'message'=>$e->getMessage()
            ],500);
        }
    }
}
