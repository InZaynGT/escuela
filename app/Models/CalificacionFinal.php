<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalificacionFinal extends Model
{
    use HasFactory;

    protected $table = 'calificaciones_finales';

    use \App\Traits\HasAuditTrail;

    protected $fillable = [
        'id_estudiante',
        'id_materia',
        'id_periodo',
        'anio',
        'promedio',
        'estado',
        'created_by',
        'updated_by',
    ];

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'id_estudiante');
    }

    public function materia()
    {
        return $this->belongsTo(Materia::class, 'id_materia');
    }

    public function periodo()
    {
        return $this->belongsTo(Periodo::class, 'id_periodo');
    }

    public function getEtiquetaEstadoAttribute(): string
    {
        return $this->promedio >= 60 ? 'Aprobado' : 'Reprobado';
    }
}
