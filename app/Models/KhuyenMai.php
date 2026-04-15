<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class KhuyenMai extends Model
{
    use HasUuids;
    protected $table = 'khuyen_mais';

    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    
    protected $fillable = [
        'tenkhuyenmai',
        'thoigian_apdung',
        'thoigian_ketthuc',
        'giatri',
        'loai',
        'mota',
    ];
}
