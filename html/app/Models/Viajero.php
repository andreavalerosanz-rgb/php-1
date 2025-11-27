<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Viajero extends Authenticatable
{
    use HasFactory;

    protected $table = 'transfer_viajeros';
    protected $primaryKey = 'id_viajero';
    
    public $timestamps = false; 
    
    public function getAuthIdentifierName()
    {
        return 'email_viajero';
    }

    protected $fillable = [
        'nombre',
        'email_viajero',
        'password',
        'apellido1',
        'apellido2',
        'direccion',
        'codigoPostal',
        'ciudad',
        'pais',
    ];

    protected $hidden = [
        'password',
    ];
}