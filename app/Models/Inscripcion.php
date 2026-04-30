<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscripcion extends Model
{
    protected $table = 'inscripciones';
    protected $fillable = ['id_estudiante', 'id_grado_seccion', 'anio', 'estado'];
    use HasFactory;

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'id_estudiante');
    }

    public function gradoSeccion()
    {
        return $this->belongsTo(GradoSeccion::class, 'id_grado_seccion');
    }
}
