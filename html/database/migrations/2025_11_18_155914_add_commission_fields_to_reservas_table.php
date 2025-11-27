<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transfer_reservas', function (Blueprint $table) {
            // El precio total del trayecto, obtenido de transfer_precios
            $table->decimal('precio_total', 10, 2)->nullable()->after('id_vehiculo');

            // Comisión ganada por el hotel para esta reserva específica
            // Se calcula como precio_total * (Comision / 100)
            $table->decimal('comision_ganada', 10, 2)->nullable()->after('precio_total');

            // Estado para calcular la liquidación mensual (opcional, pero útil)
            $table->boolean('comision_liquidada')->default(false)->after('comision_ganada');
            
            // Campo para saber qué usuario (viajero) creó la reserva (para el panel particular)
            $table->unsignedBigInteger('id_viajero')->nullable()->after('email_cliente'); 
        });
    }

    public function down(): void
    {
        Schema::table('transfer_reservas', function (Blueprint $table) {
            $table->dropColumn(['precio_total', 'comision_ganada', 'comision_liquidada', 'id_viajero']);
        });
    }
};