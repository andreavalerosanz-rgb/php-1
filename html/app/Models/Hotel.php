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
}