<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seccion extends Model
{
    protected $table = 'secciones';
    
    protected $fillable = ['nombre'];
    
    public function gradoSecciones()
    {
        return $this->hasMany(GradoSeccion::class, 'id_seccion');
    }
}