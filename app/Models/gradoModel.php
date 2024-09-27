<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class gradoModel extends Model
{
    use HasFactory;
    protected $table = 'grados';
    protected $fillable = ['nombre'];
}
