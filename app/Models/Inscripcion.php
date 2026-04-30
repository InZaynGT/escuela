<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Inscripcion extends Model
{
    use HasFactory;
    use \App\Traits\HasAuditTrail;
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Inscripciones')
            ->logOnly(['id_estudiante', 'id_grado_seccion', 'anio', 'estado'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected $table = 'inscripciones';
    protected $fillable = ['id_estudiante', 'id_grado_seccion', 'anio', 'estado', 'created_by', 'updated_by'];

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'id_estudiante');
    }

    public function gradoSeccion()
    {
        return $this->belongsTo(GradoSeccion::class, 'id_grado_seccion');
    }
}
