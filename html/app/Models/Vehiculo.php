<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    use HasFactory;

    protected $table = 'transfer_vehiculos';
    protected $primaryKey = 'id_vehiculo';
    public $timestamps = false; 

    protected $fillable = [
        'descripcion',
        'email_conductor',
        'password',
        'activo',
        'precio',
    ];

    protected $appends = ['precio_final'];

    public function reservas()
    {
        return $this->hasMany(\App\Models\Reserva::class, 'id_vehiculo', 'id_vehiculo');
    }

    /**
     * Precio final del vehículo
     * - Si viene de join con transfer_precios → usar Precio
     * - Si no → usar precio base del vehículo
     */
    public function getPrecioFinalAttribute()
    {
        return isset($this->Precio)
            ? (float) $this->Precio
            : (float) $this->precio;
    }
}
