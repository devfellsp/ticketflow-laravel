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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('titulo', 120);
            $table->text('descricao');
            $table->enum('status', ['ABERTO', 'EM_ANDAMENTO', 'RESOLVIDO'])->default('ABERTO')->index();
            $table->enum('prioridade', ['BAIXA', 'MEDIA', 'ALTA'])->index();
            $table->foreignId('solicitante_id')->constrained('users');
            $table->foreignId('responsavel_id')->nullable()->constrained('users');
            $table->dateTime('resolved_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};