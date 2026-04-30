<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class TareaEstudiante extends Model
{
    protected $table = 'tarea_estudiante';

    use HasFactory;
    use \App\Traits\HasAuditTrail;
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Calificaciones')
            ->logOnly(['id_estudiante', 'id_tarea', 'calificacion', 'entrego', 'observaciones'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected $fillable = ['id_estudiante', 'id_tarea', 'calificado', 'calificacion', 'entrego', 'observaciones', 'created_by', 'updated_by'];
    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'id_estudiante');
    }

    public function tarea()
    {
        return $this->belongsTo(Tarea::class, 'id_tarea');
    }
}
