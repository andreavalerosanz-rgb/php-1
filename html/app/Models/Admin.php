<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'transfer_admin';
    protected $primaryKey = 'id_admin';
    public $timestamps = false;

    /**
     * Campo usado por Laravel para "login".
     */
    public function getAuthIdentifierName()
    {
        return 'email_admin';
    }

    /**
     * Campos que se pueden editar desde el Perfil.
     */
    protected $fillable = [
        'nombre',
        'email_admin',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    /**
     * Mutator para que SIEMPRE se guarde la contraseña encriptada.
     */
    public function setPasswordAttribute($value)
    {
        if ($value !== null && $value !== '') {
            $this->attributes['password'] = Hash::make($value);
        }
    }
}
