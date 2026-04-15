<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Salon extends Model
{
    use HasUuids;

    protected $table='salons';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'diachi',
    ];
}
