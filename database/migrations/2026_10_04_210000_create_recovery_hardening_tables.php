<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->boolean('activo')->default(true)->after('bio');
        });

        Schema::create('codigos_otp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_usuario')->constrained('usuarios', 'id_usuario')->cascadeOnDelete();
            $table->char('codigo_hmac', 64);
            $table->timestamp('vence_en');
            $table->unsignedTinyInteger('intentos')->default(0);
            $table->unsignedTinyInteger('max_intentos')->default(5);
            $table->timestamp('usado_en')->nullable();
            $table->timestamp('anulado_en')->nullable();
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('codigos_recuperacion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_usuario')->constrained('usuarios', 'id_usuario')->cascadeOnDelete();
            $table->uuid('lote');
            $table->char('codigo_hmac', 64);
            $table->timestamp('usado_en')->nullable();
            $table->timestamp('anulado_en')->nullable();
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('archivos_recuperacion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_usuario')->unique()->constrained('usuarios', 'id_usuario')->cascadeOnDelete();
            $table->char('secreto_hmac', 64);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });

        Schema::create('eventos_recuperacion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_usuario')->nullable()->constrained('usuarios', 'id_usuario')->nullOnDelete();
            $table->string('evento', 60);
            $table->string('ip', 45)->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eventos_recuperacion');
        Schema::dropIfExists('archivos_recuperacion');
        Schema::dropIfExists('codigos_recuperacion');
        Schema::dropIfExists('codigos_otp');

        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropColumn('activo');
        });
    }
};
