<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'id_owner',
        'tipo_owner',
        'fecha_reserva',
        'fecha_modificacion',
        'id_hotel',
        'id_destino',
        'num_viajeros',
        'id_vehiculo',
        'precio_total',
        'comision_ganada',
        'comision_liquidada',
        'fecha_entrada',
        'hora_entrada',
        'numero_vuelo_entrada',
        'origen_vuelo_entrada',
        'hora_vuelo_salida',
        'fecha_vuelo_salida',
        'numero_vuelo_salida',
        'origen_vuelo_salida',
        'hora_recogida_hotel',  
    ];
}