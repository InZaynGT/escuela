<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class materiasModel extends Model
{
    use HasFactory;
    protected $table = 'materia';
    protected $fillable = ['nombre'];
}
