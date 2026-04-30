<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
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

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * Genera un email único del formato nombre.apellido[@N]@eorm.local
     * basado en el primer nombre y primer apellido del estudiante.
     */
    public static function generarEmailEstudiante(string $nombre, string $apellidos): string
    {
        $n    = self::slugificar(explode(' ', trim($nombre))[0]);
        $a    = self::slugificar(explode(' ', trim($apellidos))[0]);
        $base = $n . '.' . $a;

        $email = $base . '@eorm.local';
        $i     = 2;
        while (self::where('email', $email)->exists()) {
            $email = $base . $i . '@eorm.local';
            $i++;
        }

        return $email;
    }

    private static function slugificar(string $texto): string
    {
        $texto = mb_strtolower($texto, 'UTF-8');
        $texto = strtr($texto, [
            'á'=>'a','é'=>'e','í'=>'i','ó'=>'o','ú'=>'u','ü'=>'u','ñ'=>'n',
            'à'=>'a','è'=>'e','ì'=>'i','ò'=>'o','ù'=>'u',
        ]);
        return preg_replace('/[^a-z0-9]/', '', $texto);
    }
}
