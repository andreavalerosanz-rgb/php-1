<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Hotel;
use App\Models\Viajero;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;


class Reserva extends Model
{
    use HasFactory;

    protected $table = 'transfer_reservas';

    protected $primaryKey = 'id_reserva';
    
    public $timestamps = false; 

    // Campos que se pueden asignar masivamente (todos los campos que se llenarán)
    protected $fillable = [
    'localizador',
    'id_tipo_reserva',
    'email_cliente',

    // OWNER
    'id_owner',
    'tipo_owner',

    // CREADOR REAL
    'created_by_type',
    'created_by_id',

    // FECHAS
    'fecha_reserva',
    'fecha_modificacion',

    // HOTEL / DESTINO
    'id_hotel',
    'id_destino',

    // VIAJE
    'fecha_entrada',
    'hora_entrada',
    'numero_vuelo_entrada',
    'origen_vuelo_entrada',

    'fecha_vuelo_salida',
    'hora_vuelo_salida',
    'numero_vuelo_salida',
    'origen_vuelo_salida',
    'hora_recogida_hotel',

    // VEHÍCULO / PRECIO
    'num_viajeros',
    'id_vehiculo',
    'precio_total',
    'comision_ganada',
    'comision_liquidada',

    // ESTADO
    'estado',
];

    

     /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function hotel()
    {
        return $this->belongsTo(\App\Models\Hotel::class, 'id_hotel', 'id_hotel');
    }

    public function zona()
{
    return $this->hasOneThrough(
        Zona::class,
        Hotel::class,
        'id_hotel', // FK en hoteles
        'id_zona',  // PK en zonas
        'id_hotel', // FK en reservas
        'id_zona'   // FK en hoteles
    );
}

    public function owner()
    {
        return $this->belongsTo(\App\Models\Viajero::class, 'id_owner', 'id_viajero');
    }

    public function vehiculo()
    {
        return $this->belongsTo(\App\Models\Vehiculo::class, 'id_vehiculo', 'id_vehiculo');
    }

    // ==========================
// CREADOR DE LA RESERVA
// ==========================

// Si la crea un ADMIN
public function adminCreador()
{
    return $this->belongsTo(Admin::class, 'created_by_id', 'id_admin');
}

// Si la crea un USUARIO (viajero)
public function userCreador()
{
    return $this->belongsTo(Viajero::class, 'created_by_id', 'id_viajero');
}

// Si la crea un HOTEL
public function hotelCreador()
{
    return $this->belongsTo(Hotel::class, 'created_by_id', 'id_hotel');
}

public function getCreadorAttribute()
{
    return match ($this->created_by_type) {
        'hotel' => $this->hotelCreador,
        'admin' => $this->adminCreador,
        'user'  => $this->userCreador,
        default => null,
    };
}

public function getCreadorEtiquetaAttribute(): string
{
    return match ($this->created_by_type) {
        'hotel' => 'Hotel',
        'admin' => 'Administrador',
        'user'  => 'Viajero',
        default => 'Desconocido',
    };
}

public function getCreadorNombreAttribute(): ?string
{
    if (!$this->creador) {
        return null;
    }

    return match ($this->created_by_type) {
        'hotel' => $this->creador->nombre ?? null,
        'user'  => $this->creador->nombre ?? null,
        'admin' => $this->creador->nombre_admin
                    ?? $this->creador->nombre
                    ?? $this->creador->email
                    ?? null,
        default => null,
    };
}


public function fechaLimite()
{
    if ($this->id_tipo_reserva == 1) {
        return \Carbon\Carbon::parse($this->fecha_entrada);
    }

    if ($this->id_tipo_reserva == 2) {
        return \Carbon\Carbon::parse($this->fecha_vuelo_salida);
    }

    if ($this->id_tipo_reserva == 3) {
        return \Carbon\Carbon::parse($this->fecha_entrada);
    }

    return null;
}


//Descriptores para mostrar tipo de traslado en lugar de ID's
public function getTipoTrasladoNombreAttribute()
{
    return match($this->id_tipo_reserva) {
        1 => 'Aeropuerto → Hotel',
        2 => 'Hotel → Aeropuerto',
        3 => 'Ida y Vuelta',
        default => 'Desconocido'
    };
}

public function puedeSerModificadaPor(string $rol): bool
{
    // Si está anulada → nadie puede
    if ($this->estado === 'anulada') {
        return false;
    }

    $fechaTraslado = $this->fechaLimite();

    // Si no hay fecha → por seguridad no permitir
    if (!$fechaTraslado) {
        return false;
    }

    // Si ya pasó → finalizada → nadie puede
    if ($fechaTraslado->isPast()) {
        return false;
    }

    // Admin siempre puede mientras no esté finalizada
    if ($rol === 'admin') {
        return true;
    }

    // Hotel y viajero → mínimo 48h antes
    return now()->diffInHours($fechaTraslado, false) > 48;
}

// App\Models\Reserva.php
public function fechaFinTraslado(): ?\Carbon\Carbon
{
    return match ($this->id_tipo_reserva) {
        1 => $this->fecha_entrada && $this->hora_entrada
            ? \Carbon\Carbon::parse($this->fecha_entrada.' '.$this->hora_entrada)
            : null,

        2, 3 => $this->fecha_vuelo_salida && $this->hora_recogida_hotel
            ? \Carbon\Carbon::parse($this->fecha_vuelo_salida.' '.$this->hora_recogida_hotel)
            : null,

        default => null,
    };
}

public static function sincronizarReservasFinalizadas(): void
{
    DB::statement("
        UPDATE transfer_reservas
        SET
            estado = 'finalizada',
            comision_liquidada = comision_ganada,
            fecha_modificacion = NOW()
        WHERE
            estado = 'confirmada'
        AND
        (
            (
                id_tipo_reserva IN (1,3)
                AND DATE_ADD(
                    TIMESTAMP(fecha_entrada, hora_entrada),
                    INTERVAL -1 HOUR
                ) < NOW()
            )
            OR
            (
                id_tipo_reserva = 2
                AND DATE_ADD(
                    TIMESTAMP(fecha_vuelo_salida, hora_recogida_hotel),
                    INTERVAL -1 HOUR
                ) < NOW()
            )
        )
    ");
}

}