<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Estudiante extends Model
{
    use HasFactory;
    use \App\Traits\HasAuditTrail;
    use LogsActivity;

    protected $table = 'estudiantes';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Estudiantes')
            ->logOnly(['nombre', 'apellidos', 'cui', 'telefono', 'fecha_nacimiento'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected $fillable = [
        'id_usuario',
        'nombre',
        'apellidos',
        'carne',
        'cui',
        'foto',
        'telefono',
        'fecha_nacimiento',
        'created_by',
        'updated_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class, 'id_estudiante');
    }

    public function tareaEstudiantes()
    {
        return $this->hasMany(TareaEstudiante::class, 'id_estudiante');
    }

    public function responsables()
    {
        return $this->belongsToMany(
            Responsable::class,
            'estudiante_responsable',
            'id_estudiante',
            'id_responsable'
        );
    }

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'id_estudiante');
    }

    public function calificacionesFinales()
    {
        return $this->hasMany(CalificacionFinal::class, 'id_estudiante');
    }

    public function inscripcionActiva()
    {
        return $this->hasOne(Inscripcion::class, 'id_estudiante')
            ->where('estado', 'activo')
            ->where('anio', date('Y'))
            ->with('gradoSeccion.grado', 'gradoSeccion.seccion', 'gradoSeccion.materias');
    }

    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombre} {$this->apellidos}";
    }
}
