<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Hotel extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'transfer_hoteles';
    protected $primaryKey = 'id_hotel';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'id_zona',
        'email_hotel',
        'Comision',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    // ======================================================
    //  Helpers pensados para el módulo de PERFILES
    // ======================================================

    /**
     * Nombre que mostraremos en la cabecera del perfil.
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->nombre;
    }

    /**
     * Email "genérico" del perfil (equivalente a email_admin / email_viajero).
     */
    public function getProfileEmailAttribute(): string
    {
        return $this->email_hotel;
    }

    /**
     * Campos que el hotel puede editar desde "Mi perfil".
     */
    public static function profileFillable(): array
    {
        return ['nombre', 'email_hotel', 'password'];
    }

    /**
     * Reglas de validación para actualizar el perfil de un hotel.
     */
    public static function profileRules(int $id): array
    {
        return [
            'nombre'      => ['required', 'string', 'max:100'],
            'email_hotel' => [
                'required',
                'email',
                'max:100',
                // unique en transfer_hoteles, ignorando al propio hotel
                'unique:transfer_hoteles,email_hotel,' . $id . ',id_hotel',
            ],
            // password opcional, solo si la cambia
            'password'    => ['nullable', 'string', 'min:6', 'confirmed'],
        ];
    }
}
