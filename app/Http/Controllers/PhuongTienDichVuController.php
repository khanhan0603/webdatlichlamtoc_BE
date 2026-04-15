<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePhuongTienDichVuRequest;
use App\Models\PhuongTienDichVu;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Request;

class PhuongTienDichVuController extends Controller
{
    function index(String $id_dichvu){
        $phuong_tien = PhuongTienDichVu::where('id_dichvu','=',$id_dichvu)
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

        // lấy thứ tự lớn nhất hiện tại của dịch vụ
        $maxThuTu = PhuongTienDichVu::where('id_dichvu',$request->id_dichvu)
            ->max('thutu');

        $nextThuTu = (int)$maxThuTu + 1;
        dd($maxThuTu,$nextThuTu);
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
                $phuong_tien->thutu = $nextThuTu++; // fallback tự tăng
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
                $phuong_tien->thutu = $nextThuTu++;; // fallback tự tăng
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
    public function indexPhuongTienByDichVu(Request $request){
        $request->validate([
            'id_dichvu'=>'required|exists:dich_vus,id'
        ],[
            'id_dichvu.required'=>'Mã dịch vụ không được để trống!',
            'id_dichvu.exists'=>'Mã dịch vụ không tìm thấy!'
        ]);
        
        $id_dichvu=$request->id_dichvu;

        $phuongtiendichvus=PhuongTienDichVu::where('id_dichvu','=',$id_dichvu)->get();
        if($phuongtiendichvus->isEmpty()){
            return response()->json([
                'status'=>false,
                'message'=>'Không tìm thấy phương tiện dịch vụ!'
            ],404);
        }
        return response()->json([
            'status'=>true,
            'data'=>$phuongtiendichvus
        ]);
    }

    public function delete(String $id){
        $phuongtien = PhuongTienDichVu::find($id);

        if(!$phuongtien){
            return response()->json([
                'status'=>false,
                'message'=>'Không tìm thấy phương tiện dịch vụ!'
            ],404);
        }

        if($phuongtien->link){

            $url = $phuongtien->link;

            $path = parse_url($url, PHP_URL_PATH);

            $path = ltrim($path, '/');

            $path = str_replace('storage/', '', $path);

            Storage::disk('s3')->delete($path);
        }

        $phuongtien->delete();

        return response()->json([
            'status'=>true,
            'message'=>'Xóa phương tiện dịch vụ thành công!'
        ]);
    }

    public function update(Request $request,String $id){
            $request->validate([
                'loai'=>'required',
                'id_dichvu'=>'required|exists:dich_vus,id',
                'thutu'=>'required|numeric'
            ],[
                'loai.required'=>'Loại phương tiện dịch vụ được để trống!',
                'id_dichvu.required'=>'Mã dịch vụ được để trống!',
                'id_dichvu.exists'=>'Mã dịch vụ không tìm thấy!',
                'thutu.required'=>'Số thứ tự dịch vụ được để trống!',
                'thutu.numeric'=>'Số thứ tự dịch vụ phải là số!'
            ]);

            $result = PhuongTienDichVu::find($id);
        
            if(!$result){
                return response()->json([
                    'status'=>false,
                    'message'=>'Không tìm thấy phương tiện dịch vụ!'
                ],404);
            }
        
            if($request->hasFile('anh')){
        
                if($result->link){
                    //debug
                    //dd($path);

                    $url = $result->link;

                    $path = ltrim(parse_url($url, PHP_URL_PATH), '/');

                    Storage::disk('s3')->delete($path);
                }
        
                $anh = $request->file('anh');
        
                $name = time().'_'.uniqid().'.'.$anh->getClientOriginalExtension();
        
                $path = Storage::disk('s3')->putFileAs(
                    'phuong_tien_dich_vu',
                    $anh,
                    $name
                );
        
                $result->link = Storage::disk('s3')->url($path);
            }
        
            $result->loai = $request->loai;
            $result->id_dichvu = $request->id_dichvu;
            $result->thutu = $request->thutu;
        
            $result->save();
        
            return response()->json([
                'status'=>true,
                'message'=>'Cập nhật phương tiện dịch vụ thành công!',
                'data'=>$result
            ]);
    }
}