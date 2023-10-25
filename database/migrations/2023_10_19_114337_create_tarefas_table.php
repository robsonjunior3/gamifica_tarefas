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
        Schema::create('tarefas', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->text('descricao');
            $table->integer('pontuacao');
            $table->unsignedBigInteger('criador_id');
            $table->unsignedBigInteger('responsavel_id')->nullable();
            $table->boolean('concluida')->default(false);
            $table->timestamps();

            $table->foreign('criador_id')->references('id')->on('usuarios')->cascadeOnDelete();//->onDelete('cascade');;
            $table->foreign('responsavel_id')->references('id')->on('usuarios')->nullOnDelete();//->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarefas');
    }
};