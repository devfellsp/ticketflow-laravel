<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // SQLite não suporta ALTER COLUMN em enum
        // Solução: Recriar a tabela
        
        // 1. Criar tabela temporária com estrutura correta
        Schema::create('tickets_temp', function (Blueprint $table) {
            $table->id();
            $table->string('titulo', 120);
            $table->text('descricao');
            $table->enum('status', ['ABERTO', 'EM_ANDAMENTO', 'RESOLVIDO'])->default('ABERTO')->index();
            $table->enum('prioridade', ['BAIXA', 'MEDIA', 'ALTA', 'CRITICA'])->index();
            $table->foreignId('solicitante_id')->constrained('users');
            $table->foreignId('responsavel_id')->nullable()->constrained('users');
            $table->dateTime('resolved_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        // 2. Copiar dados
        DB::statement('INSERT INTO tickets_temp SELECT * FROM tickets');

        // 3. Dropar tabela antiga
        Schema::dropIfExists('tickets');

        // 4. Renomear temp para tickets
        Schema::rename('tickets_temp', 'tickets');
    }

    public function down(): void
    {
        // Reverter para 3 valores
        Schema::create('tickets_temp', function (Blueprint $table) {
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

        DB::statement('INSERT INTO tickets_temp SELECT * FROM tickets');
        Schema::dropIfExists('tickets');
        Schema::rename('tickets_temp', 'tickets');
    }
};