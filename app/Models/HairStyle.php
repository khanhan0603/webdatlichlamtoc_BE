<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class HairStyle extends Model
{
     use HasUuids;

    protected $table='hair_styles';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'hoten',
        'link_anh',
        'mota',
        'id_salon'
    ];

    public function salon(){
        return $this->belongsTo(Salon::class,'id_salon');
    }
}
