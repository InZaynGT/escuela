<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Support\CreatesTestEntities;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase, CreatesTestEntities;

    public function test_public_registration_is_disabled(): void
    {
        $this->get('/register')->assertNotFound();
    }

    public function test_admin_login_redirects_to_admin_dashboard(): void
    {
        $admin = $this->createAdmin();

        $this->post('/login', ['email' => $admin->email, 'password' => 'password'])
             ->assertRedirect(route('admin.dashboard'));
    }

    public function test_teacher_login_redirects_to_teacher_dashboard(): void
    {
        [$user] = $this->createTeacher();

        $this->post('/login', ['email' => $user->email, 'password' => 'password'])
             ->assertRedirect(route('teacher.dashboard'));
    }

    public function test_student_login_redirects_to_student_dashboard(): void
    {
        [$user] = $this->createStudent();

        $this->post('/login', ['email' => $user->email, 'password' => 'password'])
             ->assertRedirect(route('student.dashboard'));
    }

    public function test_unauthenticated_user_is_redirected_to_login(): void
    {
        $this->get('/admin/dashboard')->assertRedirect('/login');
    }

    public function test_teacher_cannot_access_admin_routes(): void
    {
        [$user] = $this->createTeacher();

        $this->actingAs($user)
             ->get('/admin/dashboard')
             ->assertForbidden();
    }

    public function test_student_cannot_access_teacher_routes(): void
    {
        [$user] = $this->createStudent();

        $this->actingAs($user)
             ->get('/teacher/asistencia')
             ->assertForbidden();
    }

    public function test_admin_cannot_access_teacher_routes(): void
    {
        $admin = $this->createAdmin();

        $this->actingAs($admin)
             ->get('/teacher/asistencia')
             ->assertForbidden();
    }

    public function test_student_cannot_access_admin_routes(): void
    {
        [$user] = $this->createStudent();

        $this->actingAs($user)
             ->get('/admin/dashboard')
             ->assertForbidden();
    }

    public function test_invalid_credentials_return_validation_error(): void
    {
        $this->createAdmin();

        $this->post('/login', ['email' => 'admin@test.local', 'password' => 'wrong-password'])
             ->assertSessionHasErrors('email');
    }
}
