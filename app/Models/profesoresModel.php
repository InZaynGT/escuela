<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class profesoresModel extends Model
{
    use HasFactory;

    protected $table = 'profesores';

    protected $fillable = [
        'nombre', 
        'apellidos', 
        'telefono', 
        'ID_USUARIO'
    ];

    // Definir la relación con el modelo User
    public function user()
    {
        return $this->belongsTo(User::class, 'ID_USUARIO');
    }
}
