<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDichVuRequest;
use App\Http\Requests\CreatePhuongTienDichVuRequest;
use App\Models\PhuongTienDichVu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhuongTienDichVuController extends Controller
{
    function index(String $id_dichvu){
        $phuong_tien = PhuongTienDichVu::table('phuong_tien_dich_vus')
            ->where('id_dich_vu','=',$id_dichvu)
            ->get();
        return response()->json(
            [
                'status'=>true,
                'data'=>$phuong_tien
            ]
        );
    }
    function store(CreatePhuongTienDichVuRequest $request){
        $uploadedItems = [];
        $errors = [];

        // Xử lý ảnh (tối đa 3)
        if($request->hasFile('anh')){
            foreach($request->file('anh') as $index => $anh){
                $anh_name = time().'_'.uniqid().'.'.$anh->getClientOriginalExtension();
                $path = Storage::disk('s3')->putFileAs('phuong_tien_dich_vu', $anh, $anh_name);
                
                if(!$path){
                    $errors[] = 'Upload ảnh '.$anh->getClientOriginalName().' thất bại!';
                    continue;
                }

                $phuong_tien = new PhuongTienDichVu();
                $phuong_tien->loai = 'IMAGE';
                $phuong_tien->link = Storage::disk('s3')->url($path);
                $phuong_tien->id_dichvu = $request->id_dichvu;
                $phuong_tien->thutu = $request->thutu_anh[$index] ?? ($index + 1); // fallback tự tăng
                $phuong_tien->save();

                $uploadedItems[] = $phuong_tien;
            }
        }

        // Xử lý video (tối đa 2)
        if($request->hasFile('video')){
            foreach($request->file('video') as $index => $video){
                $video_name = time().'_'.uniqid().'.'.$video->getClientOriginalExtension();
                $path = Storage::disk('s3')->putFileAs('phuong_tien_dich_vu', $video, $video_name);
                
                if(!$path){
                    $errors[] = 'Upload video '.$video->getClientOriginalName().' thất bại!';
                    continue;
                }

                $phuong_tien = new PhuongTienDichVu();
                $phuong_tien->loai = 'VIDEO';
                $phuong_tien->link = Storage::disk('s3')->url($path);
                $phuong_tien->id_dichvu = $request->id_dichvu;
                $phuong_tien->thutu = $request->thutu_anh[$index] ?? ($index + 1); // fallback tự tăng
                $phuong_tien->save();

                $uploadedItems[] = $phuong_tien;
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Thêm phương tiện dịch vụ thành công!',
            'data' => $uploadedItems,
            'errors' => $errors // file nào upload thất bại
        ]);
    }
}
