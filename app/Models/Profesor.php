<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Profesor extends Model
{
    use HasFactory;
    use \App\Traits\HasAuditTrail;
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Docentes')
            ->logOnly(['nombre', 'apellidos', 'telefono'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected $table = 'profesores';
    protected $fillable = ['nombre', 'apellidos', 'telefono', 'id_usuario', 'created_by', 'updated_by'];
    public function user()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function materias()
    {
        return $this->belongsToMany(
            Materia::class,
            'materia_profesor',
            'id_profesor',
            'id_materia'
        );
    }

    public function getNombreCompletoAttribute()
    {
        return "{$this->nombre} {$this->apellidos}";
    }
}
