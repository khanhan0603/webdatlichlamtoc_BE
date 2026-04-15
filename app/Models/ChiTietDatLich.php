<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ChiTietDatLich extends Model
{
    use HasUuids;

    protected $table = 'chi_tiet_dat_liches';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_dichvu',
        'dongia',
        'thanhtien',
        'id_datlich'
    ];

    public function dichvus(){
        return $this->belongsTo(DichVu::class,'id_dichvu');
    }

    public function dat_liches(){
        return $this->belongsTo(DatLich::class,'id_datlich');
    }
}
