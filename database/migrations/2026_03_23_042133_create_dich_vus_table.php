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
        Schema::create('dich_vus', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('tendichvu',100)->nullable();
            $table->double('dongia')->nullable();
            $table->text('mota')->nullable();
            $table->string('id_loaidichvu');
            $table->foreign('id_loaidichvu')
                    ->references('id')
                    ->on('loai_dich_vus')
                    ->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dich_vus');
    }
};
