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
        Schema::create('hair_styles', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->string('hoten')->nullable();
            $table->string('link_anh')->nullable();
            $table->text('mota')->nullable();
            $table->string('id_salon');
            $table->foreign('id_salon')
                ->references('id')
                ->on('salons')
                ->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hair_styles');
    }
};
