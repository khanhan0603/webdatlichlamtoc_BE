<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateKhuyenMaiRequest;
use App\Models\KhuyenMai;
use Illuminate\Http\Request;

class KhuyenMaiController extends Controller
{
    function index(){
        $khuyen_mais=KhuyenMai::all();
        return response()->json([
            'status'=>true,
            'data'=>$khuyen_mais
        ]);
    }

    function show(String $id){
        $khuyenmai=KhuyenMai::where('id',$id)->first();

        if(!$khuyenmai){
            return response()->json([
                'status'=>false,
                'data'=>'Không tìm thấy thông tin phù hợp!'
            ],404);
        }
        else{
            return response()->json([
                'status'=>true,
                'data'=>$khuyenmai
            ]);
        }
    }

    function store(CreateKhuyenMaiRequest $request){
        $khuyenmai=new KhuyenMai();

        $khuyenmai->tenkhuyenmai=$request->tenkhuyenmai;
        $khuyenmai->thoigian_apdung=$request->thoigian_apdung;
        $khuyenmai->thoigian_ketthuc=$request->thoigian_ketthuc;
        $khuyenmai->giatri=$request->giatri;
        $khuyenmai->loai=$request->loai;
        $khuyenmai->mota=$request->mota;

        $khuyenmai->save();

        return response()->json([
            'status'=>true,
            'message'=>'Thêm khuyến mãi thành công!',
            'data'=>$khuyenmai
        ]);
    }
    function delete (String $id){
        $result=KhuyenMai::where('id','=',$id)->first();

        if(!$result){
            return response()->json([
                'status'=>false,
                'message'=>'Không tìm thấy thông tin phù hợp!'
            ],404);
        }
        else{
            $result->delete();
            return response()->json([
                'status'=>true,
                'message'=>'Xóa khuyến mãi thành công!'
            ]);
        }
    }
    function update(String $id,CreateKhuyenMaiRequest $request){
        $result=KhuyenMai::where('id','=',$id)->first();

        if(!$result){
            return response()->json([
                'status'=>false,
                'message'=>'Không tìm thấy thông tin phù hợp!'
            ],404);
        }
        else{
            $result->tenkhuyenmai=$request->tenkhuyenmai;
            $result->thoigian_apdung=$request->thoigian_apdung;
            $result->thoigian_ketthuc=$request->thoigian_ketthuc;
            $result->giatri=$request->giatri;
            $result->loai=$request->loai;
            $result->mota=$request->mota;

            $result->save();
            return response()->json([
                'status'=>true,
                'message'=>'Cập nhật khuyến mãi thành công!',
                'data'=>$result
            ]);
        }
    }
}
