<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Periodo extends Model
{
    use HasFactory;
    use \App\Traits\HasAuditTrail;
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Períodos')
            ->logOnly(['nombre', 'anio', 'fecha_inicio', 'fecha_fin', 'bloqueado'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected $table = 'periodos';
    protected $fillable = ['nombre', 'anio', 'fecha_inicio', 'fecha_fin', 'bloqueado', 'created_by', 'updated_by'];

    protected $casts = ['bloqueado' => 'boolean'];
}
