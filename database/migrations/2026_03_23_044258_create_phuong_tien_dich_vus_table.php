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
        Schema::create('phuong_tien_dich_vus', function (Blueprint $table) {
            $table->id()->autoIncrement()->primary();
            $table->string('id_dichvu');
            $table->enum('loai',['IMAGE','VIDEO']);
            $table->string('link')->nullable();
            $table->integer('thutu')->nullable();
            //Không cho xóa bản ghi dịch vụ khi các bản ghi trong phương tiện chưa xóa hết
            $table->foreign('id_dichvu')
                ->references('id')
                ->on('dich_vus')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
            $table->timestamps();   
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phuong_tien_dich_vus');
    }
};
