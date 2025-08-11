<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fornecedores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nome', 150);
            $table->string('documento', 32)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('telefone', 50)->nullable();
            $table->unsignedBigInteger('endereco_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('endereco_id')->references('id')->on('enderecos');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fornecedores');
    }
};
