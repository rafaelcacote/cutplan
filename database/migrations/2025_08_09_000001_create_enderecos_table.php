<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enderecos', function (Blueprint $table) {
            $table->id();
            $table->string('linha1', 150);
            $table->string('linha2', 150)->nullable();
            $table->string('cidade', 100);
            $table->string('estado', 100);
            $table->string('cep', 20)->nullable();
            $table->string('pais', 100)->default('Brasil');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enderecos');
    }
};
