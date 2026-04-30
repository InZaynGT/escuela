<?php

namespace App\Models;

use App\Traits\HasAuditTrail;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Seccion extends Model
{
    use HasAuditTrail, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Secciones')
            ->logOnly(['nombre'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected $table = 'secciones';

    protected $fillable = ['nombre', 'created_by', 'updated_by'];
    
    public function gradoSecciones()
    {
        return $this->hasMany(GradoSeccion::class, 'id_seccion');
    }
}
