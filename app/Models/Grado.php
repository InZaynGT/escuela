<?php

namespace App\Models;

use App\Traits\HasAuditTrail;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Grado extends Model
{
    use HasAuditTrail, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Grados')
            ->logOnly(['nombre'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected $table = 'grados';

    protected $fillable = ['nombre', 'created_by', 'updated_by'];

    public function gradoSecciones()
    {
        return $this->hasMany(GradoSeccion::class, 'id_grado');
    }
}
