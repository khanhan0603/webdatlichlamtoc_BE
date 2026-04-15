<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhuongTienDichVu extends Model
{
    protected $table = 'phuong_tien_dich_vus';

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_dichvu',
        'loai',
        'link',
        'thutu',
    ];

    public function dichvu()
    {
        return $this->belongsTo(DichVu::class, 'id_dichvu', 'id');
    }
}
