<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('hoten')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('sodienthoai',10)->unique();
            $table->string('matkhau')->nullable();
            $table->date('ngaysinh')->nullable();
            $table->enum('role',['USER','ADMIN','STAFF'])->default('USER');
            $table->enum('loai',['NORMAL','LOYAL','VIP'])->default('NORMAL')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
