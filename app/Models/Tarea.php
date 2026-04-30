<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Tarea extends Model
{
    protected $table = 'tareas';

    use HasFactory;
    use \App\Traits\HasAuditTrail;
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Tareas')
            ->logOnly(['titulo', 'descripcion', 'ponderacion', 'id_periodo', 'id_materia'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected $fillable = ['titulo', 'descripcion', 'ponderacion', 'id_periodo', 'id_materia', 'created_by', 'updated_by'];
    public function materia()
    {
        return $this->belongsTo(Materia::class, 'id_materia');
    }

    public function periodo()
    {
        return $this->belongsTo(Periodo::class, 'id_periodo');
    }
}
