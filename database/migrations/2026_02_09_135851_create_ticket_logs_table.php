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
        Schema::create('ticket_logs', function (Blueprint $table) {
            $table->id();
            // Referência ao Ticket (Equivalente à FK no Java/Hibernate)
            $table->foreignId('ticket_id')->constrained()->onDelete('cascade'); 
            
            $table->string('de'); // Status anterior
            $table->string('para'); // Novo status
            
            // Usuário que realizou a alteração
            $table->foreignId('user_id')->constrained(); 
            
            $table->timestamps(); // Cria 'created_at' e 'updated_at'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_logs');
    }
};