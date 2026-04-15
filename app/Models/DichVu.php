<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class DichVu extends Model
{
    use HasUuids;

    protected $table='dich_vus';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'tendichvu',
        'dongia',
        'mota',
        'id_loaidichvu',
    ];

    public function loaiDichVu()
    {
        return $this->belongsTo(LoaiDichVu::class, 'id_loaidichvu', 'id');
    }
}
