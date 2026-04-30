<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;


    public function profesor()
    {
        return $this->hasOne(Profesor::class, 'id_usuario');
    }

    public function estudiante()
    {
        return $this->hasOne(Estudiante::class, 'id_usuario');
    }

    public function isAdmin()
    {
        return $this->rol === 'admin';
    }

    public function isTeacher()
    {
        return $this->rol === 'docente';
    }

    public function isStudent()
    {
        return $this->rol === 'estudiante';
    }

    // Método para obtener el nombre según el rol
    public function getRolNombreAttribute()
    {
        $roles = [
            'admin' => 'Administrador',
            'docente' => 'Docente',
            'estudiante' => 'Estudiante'
        ];

        return $roles[$this->rol];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'rol',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
