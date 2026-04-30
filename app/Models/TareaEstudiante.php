<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TareaEstudiante extends Model
{

    protected $table = 'tarea_estudiante';
    protected $fillable = ['id_estudiante', 'id_tarea', 'calificado','calificacion','entrego','observaciones'];

    use HasFactory;
    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'id_estudiante');
    }

    public function tarea()
    {
        return $this->belongsTo(Tarea::class, 'id_tarea');
    }
}
