<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Importante para auth

class Admin extends Authenticatable
{
    use HasFactory;

    protected $table = 'transfer_admin';
    protected $primaryKey = 'id_admin';
    
    public $timestamps = false;
    // Necesario para que el login funcione con la columna email_admin
    protected $username = 'email_admin';
    protected $fillable = ['nombre', 'email_admin', 'password'];
    protected $hidden = ['password'];
}