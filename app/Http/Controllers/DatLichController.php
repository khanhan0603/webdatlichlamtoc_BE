<?php

namespace App\Http\Controllers;

use App\Models\ChiTietDatLich;
use App\Models\DatLich;
use App\Models\DichVu;
use App\Models\HairStyle;
use App\Models\KhuyenMai;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DatLichController extends Controller
{

    public function showSlot(Request $request)
    {
        $request->validate([
            'salon_id' => 'required|exists:salons,id',
            'date' => 'required|date|after_or_equal:today',
        ],[
            'salon_id.required' => 'Mã salon không được để trống!',
            'salon_id.exists' => 'Mã salon không tồn tại!',
            'date.required' => 'Ngày đặt lịch không được để trống!',
            'date.date' => 'Ngày đặt lịch không hợp lệ!',
            'date.after_or_equal' => 'Không thể đặt lịch trong quá khứ!'
        ]);

        $salonId = $request->salon_id;
        $hairStyleId = $request->id_hair_style;
        $date = $request->date;

        if($hairStyleId==null){
            //Random nhân viên tư vấn
            //Lấy danh sách nhân viên tư vấn
            $hairStyles = HairStyle::where('id_salon', $salonId)->get();
            $hairStyleId = $hairStyles->random()->id;
        }

        // Danh sách khung giờ làm việc
        $slots = [
            "07:00","08:00","09:00","10:00","11:00",
            "12:00","13:00","14:00","15:00","16:00",
            "17:00","18:00","19:00","20:00"
        ];

        // Query lịch đã đặt
        $query = DatLich::whereDate('thoigian_hen', $date)
            ->where('id_salon', $salonId);

        if ($hairStyleId) {
            $query->where('id_hairstyle', $hairStyleId);
        }

        // Lấy danh sách giờ đã đặt
        $bookedSlots = $query->pluck('thoigian_hen')
            ->map(function ($time) {
                return Carbon::parse($time)->format('H:i');
            })
            ->toArray();

        // Tính khung giờ còn trống
        $availableSlots = array_values(array_diff($slots, $bookedSlots));

        return response()->json([
            'salon_id' => $salonId,
            'staff_id' => $hairStyleId,
            'date' => $date,
            'booked_slots' => $bookedSlots,
            'available_slots' => $availableSlots
        ]);
    }

    public function showKhuyenMai(Request $request){
        $request->validate([
            'khuyenmai_id' => 'exists:khuyen_mais,id',
        ],[
            'khuyenmai_id.exists' => 'Mã khuyên mãi không tìm thấy!'
        ]);
        $khuyenmai=KhuyenMai::where('id',$request->khuyenmai_id)->first();
        if($khuyenmai->thoigian_apdung>Carbon::now()){
            return response()->json([
                'status'=>false,
                'message'=>'Khuyến mãi chưa được áp dụng!'
            ]);
        }
        if($khuyenmai->thoigian_ketthuc<Carbon::now()){
            return response()->json([
                'status'=>false,
                'message'=>'Khuyến mãi đã hết hạn sử dụng!'
            ]);
        }
        return response()->json([
            'status'=>true,
            'data'=>$khuyenmai
        ]);
    }
    
    //Đặt lịch có áp khuyến mãi
    public function store(Request $request)
    {
        $request->validate([
            'salon_id' => 'required|exists:salons,id',
            'staff_id' => 'required|exists:hair_styles,id',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|date_format:H:i',
            'services' => 'required|array',
            'services.*' => 'exists:dich_vus,id',
            'id_khuyenmai' => 'exists:khuyen_mais,id',
        ],[
            'salon_id.required' => 'Mã salon không được để trống!',
            'salon_id.exists' => 'Mã salon không tồn tại!',
            'staff_id.required' => 'Mã nhân viên tư vấn không được để trống!',
            'staff_id.exists' => 'Mã nhân viên tư vấn không tồn tại!',
            'date.required' => 'Ngày đặt lịch không được để trống!',
            'date.date' => 'Ngày đặt lịch không hợp lệ!',
            'date.after_or_equal' => 'Không thể đặt lịch trong quá khứ!',
            'time.required' => 'Giờ đặt lịch không được để trống!',
            'time.date_format' => 'Giờ đặt lịch không hợp lệ!',
            'services.required' => 'Danh sách dịch vụ được để trống!',
            'services.*.exists' => 'Mã dịch vụ không tìm thấy!',
            'id_khuyenmai.exists' => 'Mã khuyến mãi không tồn tại!',
        ]);

        $userId = $request->user()->id;

        $dateTime = Carbon::parse($request->date.' '.$request->time);

        $khuyenMai = KhuyenMai::find($request->id_khuyenmai);

        // check slot
        $exists = DatLich::where('id_salon',$request->salon_id)
            ->where('id_hairstyle',$request->staff_id)
            ->where('thoigian_hen',$dateTime)
            ->exists();

        if($exists){
            return response()->json([
                'status'=>false,
                'message'=>'Khung giờ này đã được đặt!'
            ],400);
        }

        DB::beginTransaction();

        try{

            $tongTien = 0;

            $datLich = DatLich::create([
                'id_khachhang'=>$userId,
                'id_salon'=>$request->salon_id,
                'id_hairstyle'=>$request->staff_id,
                'thoigian_hen'=>$dateTime,
                'id_khuyenmai'=>$khuyenMai->id,
                'tongtien'=>0,
                'trangthai'=>'BOOKED'
            ]);

            foreach($request->services as $serviceId){

                $dichVu = DichVu::find($serviceId);

                ChiTietDatLich::create([
                    'id_datlich'=>$datLich->id,
                    'id_dichvu'=>$serviceId,
                    'dongia'=>$dichVu->dongia,
                    'thanhtien'=>$dichVu->dongia
                ]);
                $tongTien += $dichVu->dongia;
            }
            if($khuyenMai->loai=='FIXED'){
                $tongTien=$tongTien-$khuyenMai->giatri;
            }
            else if($khuyenMai->loai=='PERCENT'){
                $tongTien=$tongTien-($tongTien*$khuyenMai->giatri/100);
            }
            
            $datLich->tongtien = $tongTien;
            $datLich->save();

            DB::commit();

            return response()->json([
                'status'=>true,
                'message'=>'Đặt lịch thành công!',
                'data'=>$datLich
            ]);

        }catch(QueryException $e){

            DB::rollBack();

            return response()->json([
                'status'=>false,
                'message'=>$e->getMessage()
            ],500);
        }
    }

    public function index(){
        $dat_lichs=DatLich::all();
        try{
            return response()->json([
                'status'=>true,
                'data'=>$dat_lichs
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
        $dat_lich=DatLich::find($id);
        try{
            return response()->json([
                'status'=>true,
                'data'=>$dat_lich
            ],200);
        }
        catch(\Exception $e){
            return response()->json([
                'status'=>false,
                'message'=>$e->getMessage()
            ],500);
        }
    }

    public function indexByUserId(Request $request){
        $request->validate([
            'user_id'=>'required|exists:users,id'
        ],[
            'user_id.required'=>'Khách hàng không được để trống!',
            'user_id.exists'=>'Khách hàng không tìm thấy!'
        ]);

        $dat_lichs=DatLich::where('id_khachhang','=',$request->user_id)->get();
        try{
            return response()->json([
                'status'=>true,
                'data'=>$dat_lichs
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
