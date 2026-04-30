<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradoSeccion extends Model
{

    protected $table = 'grado_seccion';
    protected $fillable = ['id_grado','id_seccion'];

    use HasFactory;
    public function grado()
    {
        return $this->belongsTo(Grado::class, 'id_grado');
    }

    public function seccion()
    {
        return $this->belongsTo(Seccion::class, 'id_seccion');
    }

    public function materias()
    {
        return $this->hasMany(Materia::class, 'id_grado_seccion');
    }

    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class, 'id_grado_seccion');
    }
}
