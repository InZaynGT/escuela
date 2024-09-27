<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class gradoSeccionModel extends Model
{
    use HasFactory;
    protected $table = 'grado_seccion';
    protected $fillable = ['grado', 'seccion']; // Campos separados correctamente
}
