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

    /**
     * IMPORTANTE:
     * Esto indica a Laravel que el campo para login NO es "email",
     * sino "email_viajero".
     */
    public function getAuthIdentifierName()
    {
        return 'email_viajero';
    }

    protected $fillable = [
        'nombre',
        'apellido1',
        'apellido2',
        'direccion',
        'codigoPostal',
        'ciudad',
        'pais',
        'email_viajero',
        'password',
    ];

    protected $hidden = [
        'password',
    ];
}
