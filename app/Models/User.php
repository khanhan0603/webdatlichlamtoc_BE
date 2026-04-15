<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens,HasUuids;

    protected $table='users';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'hoten',
        'email',
        'sodienthoai',
        'matkhau',
        'ngaysinh',
        'role',
        'loai'
    ];
    //ẩn mật khẩu khi trả JSON
    protected $hidden = [
        'password'
    ];
    //cast birthday
    protected $casts = [
        'ngaysinh' => 'date'
    ];
}
