<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateHairStyleRequest;
use App\Http\Requests\UpdateHairStyleRequest;
use App\Models\HairStyle;
use Illuminate\Support\Facades\Storage;

class HairStyleController extends Controller
{
    function index(){
        $hair_style=HairStyle::all();
        return response()->json([
            'status'=>true,
            'data'=>$hair_style
        ]);
    }

    function store(CreateHairStyleRequest $request){
        try{
            $anh=$request->file('anh');

            $anh_name=time().'_'.uniqid().'.'.$anh->getClientOriginalExtension();

            $path=Storage::disk('s3')->putFileAs(
                'hair_style',
                $anh,
                $anh_name
            );

            if(!$path){
                return response()->json([
                    'status'=>false,
                    'message'=>'Upload hình ảnh thất bại!'
                ],500);
            }
            
            $url=Storage::disk('s3')->url($path);

            $hair_style=new HairStyle();
            $hair_style->hoten=$request->hoten;
            $hair_style->link_anh=$url;
            $hair_style->mota=$request->mota;
            $hair_style->id_salon=$request->id_salon;

            $hair_style->save();

            return response()->json([
                'status'=>true,
                'message'=>'Thêm thành công!',
                'data'=>$hair_style
            ]);
        }
        catch(\Exception $e){
            return response()->json([
                'status'=>false,
                'message'=>$e->getMessage()
            ],500);
        }
    }

    function show(String $id){
        $result=HairStyle::where('id',$id)->first();

        if(!$result){
            return response()->json([
                'status'=>false,
                'message'=>'Không tìm thấy thông tin phù hợp!'
            ],404);
        }
        else{
            return response()->json([
                'status'=>true,
                'data'=>$result
            ]);
        }
    }

    function delete(String $id){
        $result=HairStyle::where('id','=',$id)->first();

        if(!$result){
            return response()->json([
                'status'=>false,
                'message'=>'Không tìm thấy thông tin cần xóa!'
            ],404);
        }
        else{
            // Xóa ảnh
            $oldPath = parse_url($result->link_anh, PHP_URL_PATH);
            $oldPath = ltrim($oldPath, '/');

            Storage::disk('s3')->delete($oldPath);
            $result->delete();
            return response()->json([
                'status'=>true,
                'message'=>'Xóa thông tin thành công!'
            ]);
        }
    }

    function update(UpdateHairStyleRequest $request,String $id){
        $result=HairStyle::where('id','=',$id)->first();

        if(!$result){
            return response()->json([
                'status'=>false,
                'message'=>'Không tìm thấy thông tin cần cập nhật!'
            ],404);
        }
        else{
            if($request->hasFile('anh')){
                $anh=$request->file('anh');

                $anh_name=time().'_'.uniqid().'.'.$anh->getClientOriginalExtension();

                $path=Storage::disk('s3')->putFileAs(
                    'hair_style',
                    $anh,
                    $anh_name
                );

                if(!$path){
                    return response()->json([
                        'status'=>false,
                        'message'=>'Upload hình ảnh thất bại!'
                    ],500);
                }
                
                $url=Storage::disk('s3')->url($path);

                //Xóa ảnh cũ
                if($result->link_anh){
                    $oldPath = parse_url($result->link_anh, PHP_URL_PATH);
                    $oldPath = ltrim($oldPath, '/');

                    Storage::disk('s3')->delete($oldPath);
                }

                //Update link
                $result->link_anh=$url;
            }
            $result->hoten=$request->hoten;
            $result->mota=$request->mota;
            $result->id_salon=$request->id_salon;

            $result->save();

            return response()->json([
                 'status'=>true,
                'message'=>'Cập nhật thông tin thành công!',
                'data'=>$result
            ]);
        }
    }
}
