<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Hotel extends Authenticatable
{
    use HasFactory;

    protected $table = 'transfer_hoteles';
    protected $primaryKey = 'id_hotel';
    
    public $timestamps = false; // Desactiva created_at/updated_at
    
    public function getAuthIdentifierName()
    {
        return 'email_hotel';
    }

    protected $fillable = [
        'nombre',          
        'Comision',       
        'email_hotel',
        'password',
        'id_zona',         
    ];

    protected $hidden = [
        'password',
    ];


}