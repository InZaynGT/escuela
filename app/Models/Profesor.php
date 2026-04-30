<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profesor extends Model
{
    protected $table = 'profesores';
    use HasFactory;
    public function user()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function materias()
    {
        return $this->belongsToMany(
            Materia::class,
            'materia_profesor',
            'id_profesor',
            'id_materia'
        );
    }

    public function getNombreCompletoAttribute()
    {
        return "{$this->nombre} {$this->apellidos}";
    }
}
