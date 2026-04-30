<?php

namespace App\Models;

use App\Traits\HasAuditTrail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Asistencia extends Model
{
    use HasFactory, HasAuditTrail, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Asistencias')
            ->logOnly(['id_estudiante', 'id_grado_seccion', 'fecha', 'estado', 'observacion'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected $table = 'asistencias';

    protected $fillable = [
        'id_estudiante',
        'id_grado_seccion',
        'id_periodo',
        'fecha',
        'estado',
        'observacion',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    const PRESENTE    = 'presente';
    const AUSENTE     = 'ausente';
    const TARDANZA    = 'tardanza';
    const JUSTIFICADO = 'justificado';

    public static array $estados = [
        self::PRESENTE    => 'Presente',
        self::AUSENTE     => 'Ausente',
        self::TARDANZA    => 'Tardanza',
        self::JUSTIFICADO => 'Justificado',
    ];

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'id_estudiante');
    }

    public function gradoSeccion()
    {
        return $this->belongsTo(GradoSeccion::class, 'id_grado_seccion');
    }

    public function periodo()
    {
        return $this->belongsTo(Periodo::class, 'id_periodo');
    }
}
