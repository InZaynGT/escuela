<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Materia extends Model
{
    use HasFactory;
    use \App\Traits\HasAuditTrail;
    use LogsActivity;

    protected $table = 'materias';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Materias')
            ->logOnly(['nombre', 'id_grado_seccion'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected $fillable = ['nombre', 'id_grado_seccion', 'created_by', 'updated_by'];

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

}
