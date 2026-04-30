<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materia extends Model
{
    use HasFactory;

    protected $table = 'materias';

    protected $fillable = ['nombre', 'id_grado_seccion'];

    public function gradoSeccion()
    {
        return $this->belongsTo(GradoSeccion::class, 'id_grado_seccion');
    }

    public function tareas()
    {
        return $this->hasMany(Tarea::class, 'id_materia');
    }

    public function profesores()
    {
        return $this->belongsToMany(
            Profesor::class,
            'materia_profesor',
            'id_materia',
            'id_profesor'
        );
    }

    public function calificacionesFinales()
    {
        return $this->hasMany(CalificacionFinal::class, 'id_materia');
    }

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'id_materia');
    }
}
