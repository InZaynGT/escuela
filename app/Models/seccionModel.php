<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class seccionModel extends Model
{
    use HasFactory;
    protected $table = 'seccion';
    protected $fillable = ['nombre'];
}
