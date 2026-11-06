<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buzon_mensajes', function (Blueprint $table) {
            $table->id();
            $table->string('remitente', 150)->default('Konex <no-reply@konex.local>');
            $table->string('correo', 150);
            $table->string('asunto', 180);
            $table->string('codigo', 8);
            $table->text('cuerpo');
            $table->boolean('leido')->default(false);
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buzon_mensajes');
    }
};
