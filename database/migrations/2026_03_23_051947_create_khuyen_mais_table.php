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
        Schema::create('khuyen_mais', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('tenkhuyenmai')->nullable();
            $table->dateTime('thoigian_apdung')->nullable();
            $table->dateTime('thoigian_ketthuc')->nullable();
            $table->double('giatri')->nullable();
            $table->enum('loai',['PERCENT','FIXED'])->nullable();
            $table->text('mota')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('khuyen_mais');
    }
};
