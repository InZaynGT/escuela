<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarea extends Model
{
    protected $table = 'tareas';

    protected $fillable = ['titulo','descripcion','ponderacion','id_periodo','id_materia'];

    use HasFactory;
    public function materia()
    {
        return $this->belongsTo(Materia::class, 'id_materia');
    }

    public function periodo()
    {
        return $this->belongsTo(Periodo::class, 'id_periodo');
    }
}
