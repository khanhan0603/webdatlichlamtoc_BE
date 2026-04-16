<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDichVuRequest;
use App\Models\DichVu;
use App\Models\PhuongTienDichVu;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Request;

use function PHPUnit\Framework\isEmpty;

class DichVuController extends Controller
{
    function index(){
        $dichvu=DichVu::all();
        return response()->json([
            'status'=>true,
            'data'=>$dichvu
        ]);
    }

    function show(String $id){
        $result=DichVu::where('id','=',$id)->first();
        if(!$result){
            return response()->json([
                'status'=>false,
                'message'=>'Không tìm thấy dịch vụ phù hợp!'
            ],404);
        }
        else{
            return response()->json([
                'status'=>true,
                'data'=>$result
            ]);
        }
    }

    function store(CreateDichVuRequest $request){
        $dichvu=new DichVu();
        $dichvu->tendichvu=$request->tendichvu;
        $dichvu->dongia=$request->dongia;
        $dichvu->mota=$request->mota;
        $dichvu->id_loaidichvu=$request->id_loaidichvu;

        $dichvu->save();

        return response()->json([
            'status'=>true,
            'message'=>'Thêm mới dịch vụ thành công!'
        ]);
    }

    function delete(String $id){
        $dichvu=DichVu::where('id','=',$id)->first();

        if(!$dichvu){
            return response()->json([
                'status'=>false,
                'message'=>'Không tìm thấy dịch vụ!'
            ],404);
        }
        else{
            //Kiểm tra có phương tiện dịch vụ ko, nếu có thì xóa phương tiện
            $phuongtiendichvus=PhuongTienDichVu::where('id_dichvu',$id)->get();
            
            if($phuongtiendichvus->isNotEmpty()){
                foreach($phuongtiendichvus as $pt){
                    $url=$pt->link;
                    $path=parse_url($url,PHP_URL_PATH);
                    $path=ltrim($path,'/');
                    $path=str_replace('storage/','',$path);
                    Storage::disk('s3')->delete($path);

                    $pt->delete();
                }
            }
            $dichvu->delete();
            return response()->json([
                'status'=>true,
                'message'=>'Xóa dịch vụ thành công!'
            ]);
        }
    }

    public function update(CreateDichVuRequest $request,String $id){
        $result=DichVu::where('id',$id)->first();

        if(!$result){
            return response()->json([
                'status'=>false,
                'message'=>'Không tìm thấy dịch vụ!'
            ],404);
        }
        else{
            $result->tendichvu=$request->tendichvu;
            $result->dongia=$request->dongia;
            $result->mota=$request->mota;
            $result->id_loaidichvu=$request->id_loaidichvu;

            $result->save();

            return response()->json([
                'status'=>true,
                'message'=>'Cập nhật dịch vụ thành công!'
            ]);
        }
    }

    //Tìm kiếm dịch vụ
    public function search(Request $request){
        $tendichvu=$request->tendichvu;

        $dichvu=DichVu::where('tendichvu','like','%'.$tendichvu.'%')->get();

        if($dichvu->isEmpty()){
            return response()->json([
                'status'=>false,
                'message'=>'Không tìm thấy dịch vụ phù hợp!'
            ],404);
        }

        return response()->json([
            'status'=>true,
            'data'=>$dichvu
        ]);
    }

    //Lọc dịch vụ theo loại dịch vụ
    public function indexByType(Request $request){
       $request->validate([
            'id_loaidichvu'=>'required|exists:loai_dich_vus,id'
        ],[
            'id_loaidichvu.required'=>'Mã loại dịch vụ không được để trống!',
            'id_loaidichvu.exists'=>'Mã loại dịch vụ không tìm thấy!'
        ]);

        $id_loaidichvu = $request->id_loaidichvu;

        $dichvu = DichVu::where('id_loaidichvu',$id_loaidichvu)->get();

        if($dichvu->isEmpty()){
            return response()->json([
                'status'=>false,
                'message'=>'Không tìm thấy dịch vụ phù hợp!'
            ],404);
        }

        return response()->json([
            'status'=>true,
            'data'=>$dichvu
        ]);
    }
}
