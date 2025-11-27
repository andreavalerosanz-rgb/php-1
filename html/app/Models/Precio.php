<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Precio extends Model
{
    use HasFactory;

    protected $table = 'transfer_precios';
    protected $primaryKey = 'id_precios';
    public $timestamps = false; 

    protected $fillable = [
        'id_vehiculo',
        'id_hotel',
        'Precio',
    ];

    // Relación con el vehículo
    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class, 'id_vehiculo');
    }
    
    // Relación con el hotel
    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'id_hotel');
    }
}