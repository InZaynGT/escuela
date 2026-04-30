<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Responsable extends Model
{
    use HasFactory;

    protected $table = 'responsables';

    protected $fillable = [
        'nombre',
        'apellidos',
        'telefono',
        'parentesco',
    ];

    public function estudiantes()
    {
        return $this->belongsToMany(
            Estudiante::class,
            'estudiante_responsable',
            'id_responsable',
            'id_estudiante'
        );
    }

    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombre} {$this->apellidos}";
    }
}
