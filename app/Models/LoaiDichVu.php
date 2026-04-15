<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class LoaiDichVu extends Model
{
    use HasUuids;

    protected $table='loai_dich_vus';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['tenloai'];
}
