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
        Schema::create('chi_tiet_dat_liches', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('id_dichvu');
            $table->double('dongia');
            $table->double('thanhtien');
            $table->string('id_datlich');
            $table->foreign('id_dichvu')
                ->references('id')
                ->on('dich_vus')
                ->cascadeOnUpdate();
            $table->foreign('id_datlich')
                ->references('id')
                ->on('dat_liches')
                ->onDelete('cascade')
                ->cascadeOnUpdate();
            $table->timestamps();

            $table->index('id_datlich');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chi_tiet_dat_liches');
    }
};
