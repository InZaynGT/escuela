<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Estudiante;
use App\Models\Profesor;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Admin
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@escuela.com',
            'password' => bcrypt('admin123'),
            'rol' => 'admin'
        ]);

        // Docentes
        $docente1 = User::create([
            'name' => 'Carlos Pérez',
            'email' => 'carlos@escuela.com',
            'password' => bcrypt('docente123'),
            'rol' => 'docente'
        ]);

        Profesor::create([
            'id_usuario' => $docente1->id,
            'nombre' => 'Carlos',
            'apellidos' => 'Pérez Martínez',
            'telefono' => '12345678'
        ]);

        $docente2 = User::create([
            'name' => 'María López',
            'email' => 'maria@escuela.com',
            'password' => bcrypt('docente123'),
            'rol' => 'docente'
        ]);

        Profesor::create([
            'id_usuario' => $docente2->id,
            'nombre' => 'María',
            'apellidos' => 'López García',
            'telefono' => '87654321'
        ]);

        // Estudiantes
        for ($i = 1; $i <= 5; $i++) {
            $estudianteUser = User::create([
                'name' => "Estudiante $i",
                'email' => "estudiante$i@escuela.com",
                'password' => bcrypt('estudiante123'),
                'rol' => 'estudiante'
            ]);

            Estudiante::create([
                'id_usuario' => $estudianteUser->id,
                'nombre' => "Estudiante",
                'apellidos' => "Apellido $i",
                'cui' => "1234567890$i",
                'telefono' => "12345678",
                'fecha_nacimiento' => "2010-01-0$i"
            ]);
        }
    }
}
