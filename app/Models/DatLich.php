<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class DatLich extends Model
{
    use HasUuids;

    protected $table = 'dat_liches';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_khachhang',
        'id_stylist',
        'id_salon',
        'thoigian_hen',
        'id_khuyenmai',
        'tongtien',
        'trangthai',
    ];

    public function users(){
        return $this->belongsTo(User::class,'id_khachhang');
    }
    public function hairstyles(){
        return $this->belongsTo(HairStyle::class,'id_hairstyle');
    }
    public function salons(){
        return $this->belongsTo(Salon::class,'id_salon');
    }
    public function khuyen_mais(){
        return $this->belongsTo(KhuyenMai::class,'id_khuyenmai');
    }
}
