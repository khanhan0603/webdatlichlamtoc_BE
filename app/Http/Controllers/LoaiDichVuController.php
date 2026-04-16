<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLoaiDichVuRequest;
use App\Models\DichVu;
use App\Models\LoaiDichVu;

class LoaiDichVuController extends Controller
{
    //Danh sách loại dịch vụ
    function index(){
        $types=LoaiDichVu::all();
        return response()->json([
            'status'=>true,
            'data'=>$types
        ]);
    }

    //Thêm loại dịch vụ
    function store(CreateLoaiDichVuRequest $request){
        $type=new LoaiDichVu();

        $type->tenloai=$request->tenloai;

        $type->save();
        return response()->json([
            'status'=>true,
            'message'=>'Tạo loại dịch vụ thành công!',
            'data'=>$type
        ]);
    }

    //Tìm kiếm 1 loại dịch vụ
    function show(String $id){
        $result=LoaiDichVu::where('id','=',$id)->first();
        if(!$result){
            return response()->json([
                'status'=>false,
                'messenge'=>'Không tìm thấy loại dịch vụ!'
            ],404);
        }
        else{
            return response()->json([
                'status'=>true,
                'data'=>$result
            ]);
        }
    }

    //Xóa loại dịch vụ theo id
    function delete(String $id){
        $result=LoaiDichVu::where('id','=',$id)->first();

        if(!$result){
            return response()->json([
                'status'=>false,
                'message'=>'Không tìm thấy loại dịch vụ!'
            ],404);
        }
        else{
            //tìm kiếm dịch vụ thuộc loại dịch vụ
            $dichvus=DichVu::where('id_loaidichvu','=',$id)->get();

            if($dichvus->isNotEmpty()){
                foreach($dichvus as $dv){
                    $dv->delete();
                }
            }
            $result->delete();
            return response()->json([
                'status'=>true,
                'message'=>'Xóa loại dịch vụ thành công!'
            ]);
        }
    }

    //Cập nhật loại dịch vụ
    function update(CreateLoaiDichVuRequest $request,String $id){
         $result=LoaiDichVu::where('id','=',$id)->first();

        if(!$result){
            return response()->json([
                'status'=>false,
                'message'=>'Không tìm thấy loại dịch vụ!'
            ],404);
        }

        $result->tenloai=$request->tenloai;
        $result->save();

        return response()->json([
            'status'=>true,
            'message'=>'Cập nhật loại dịch vụ thành công!',
            'data'=>$result
        ]);
    }
}
