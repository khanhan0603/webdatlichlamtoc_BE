<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DatLichController;
use App\Http\Controllers\DichVuController;
use App\Http\Controllers\HairStyleController;
use App\Http\Controllers\KhuyenMaiController;
use App\Http\Controllers\LoaiDichVuController;
use App\Http\Controllers\PhuongTienDichVuController;
use App\Http\Controllers\SalonController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

//Đăng Nhập
Route::prefix('auth')
    ->controller(AuthController::class)->group(function(){
        //Chỉ cho phép đăng nhập 5 lần trong 1 phút
        Route::post('login','login')->middleware('throttle:5,1');
        Route::middleware('auth:sanctum')->post('logout','logout');
        //Route::post('refresh','refresh');
        //Route::get('me','me');
});

Route::prefix('users')
    ->controller(UserController::class)
    ->group(function(){
        Route::middleware(['auth:sanctum','role:ADMIN,STAFF'])->group(function(){
            Route::get('','index');
            Route::get('/user','showUser');
        });
        Route::middleware(['auth:sanctum','role:USER'])->group(function(){
            Route::get('/{id}','show');
            Route::post('','getOrCreateUserByPhone');
            Route::put('/{id}','update');
        });
    Route::post('/register','register');
});

Route::prefix('types')
    ->controller(LoaiDichVuController::class)
    ->group(function(){
        Route::middleware(['auth:sanctum','role:ADMIN'])->group(function(){
            Route::post('','store');
            Route::delete('/{id}','delete');
            Route::put('/{id}','update');
        });
    Route::get('','index');
    Route::get('/{id}','show');
});

Route::prefix('services')
    ->controller(DichVuController::class)
    ->group(function(){
        Route::middleware(['auth:sanctum','role:ADMIN'])->group(function(){
            Route::post('','store');
            Route::delete('/{id}','delete');
            Route::put('/{id}','update');
        });
    Route::get('','index');
    //Xem chi tiết dich vụ
    Route::get('/{id}','show');
    //Lọc dịch vụ theo loại dịch vụ(**)
    Route::post('/type','indexByType');
    //Tìm kiếm dịch vụ(**)
    Route::post('/search','search');
});


Route::prefix('hair-styles')
    ->controller(HairStyleController::class)
    ->group(function(){
        Route::get('','index');
        Route::get('/{id}','show');
        //Lọc nhân viên theo chi nhánh(**)
        Route::post('/salon','indexBySalon');
        Route::middleware(['auth:sanctum','role:ADMIN'])->group(function(){
            Route::post('','store');
            Route::delete('/{id}','delete');
            Route::put('/{id}','update');
        });
    });

Route::prefix('khuyen-mais')
    ->controller(KhuyenMaiController::class)
    ->group(function(){
        Route::get('','index');
        Route::get('/{id}','show');
        Route::middleware(['auth:sanctum','role:ADMIN'])->group(function(){
            Route::post('','store');
            Route::delete('/{id}','delete');
            Route::put('/{id}','update');
        });
    });

Route::prefix('phuong-tiens')
    ->controller(PhuongTienDichVuController::class)
    ->group(function(){
        Route::get('/{id_dich_vu}','index');
        Route::middleware(['auth:sanctum','role:ADMIN'])->group(function(){
            Route::post('','store');
            Route::delete('/{id}','delete');
            Route::put('/{id}','update');
            //Hiển thị phương tiện theo dich vụ
            Route::post('/dich-vu','indexPhuongTienByDichVu');
        });
    });
Route::prefix('salon')
    ->controller(SalonController::class)
    ->group(function(){
        Route::get('','index');
        Route::get('/{id}','show');
        //Coi các nhân viên tư vấn tại salon đó
        Route::get('/hair-style/{id}','showHairStyle');
        Route::middleware(['auth:sanctum','role:ADMIN'])->group(function(){
            Route::post('','store');
            Route::delete('/{id}','delete');
            Route::put('/{id}','update');
        });
    });

Route::prefix('booking')
    ->controller(DatLichController::class)
    ->group(function(){
        //Coi slot thời gian còn lại của 1 nhân viên tư vấn tại salon đó
        Route::post('/slot','showSlot');
        Route::middleware(['auth:sanctum','role:USER'])->group(function(){
            //Chọn mã khuyến mãi (**)
            Route::post('/khuyen-mai','showKhuyenMai');
            //Đặt lịch
            Route::post('','store');
        });
        Route::middleware(['auth:sanctum','role:ADMIN'])->group(function(){
            //Xem tất cả danh sách đặt lịch
            Route::get('','index');
            Route::get('/{id}','show');
        });
    });
