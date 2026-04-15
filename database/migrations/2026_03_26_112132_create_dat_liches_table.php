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
        Schema::create('dat_liches', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('id_khachhang');
            $table->string('id_hairstyle')->nullable();
            $table->string('id_salon');
            $table->dateTime('thoigian_hen');
            $table->string('id_khuyenmai')->nullable();
            $table->double('tongtien');
            $table->enum('trangthai',['BOOKED','ARRIVED','CONSULTED','CANCELLED']);

            $table->foreign('id_khachhang')
                ->references('id')
                ->on('users')
                ->cascadeOnUpdate();
            $table->foreign('id_hairstyle')
                ->references('id')
                ->on('hair_styles')
                ->cascadeOnUpdate();
            $table->foreign('id_salon')
                ->references('id')
                ->on('salons')
                ->cascadeOnUpdate();
            $table->foreign('id_khuyenmai')
                ->references('id')
                ->on('khuyen_mais')
                ->cascadeOnUpdate();

            $table->unique(['id_hairstyle','thoigian_hen']);
            $table->index('id_khachhang');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dat_liches');
    }
};
