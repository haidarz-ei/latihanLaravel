<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\EkycRegistration;
use Illuminate\Support\Facades\Hash;

class EkycStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_ekyc_status_shows_accepted_for_authenticated_user()
    {
        // Buat user
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);

        // Buat ekyc registration dengan status accepted
        EkycRegistration::create([
            'user_id' => $user->id,
            'nama' => 'Test User',
            'nik' => '1234567890123456',
            'tanggal_lahir' => '1990-01-01',
            'alamat' => 'Test Address',
            'status' => 'accepted',
        ]);

        // Login sebagai user
        $this->actingAs($user);

        // Akses route /ekyc/status
        $response = $this->get('/ekyc/status');

        // Assert status 200 dan tidak redirect
        $response->assertStatus(200);

        // Assert bahwa halaman mengandung teks "Diterima" (accepted)
        $response->assertSee('Diterima');
    }

    public function test_ekyc_status_shows_rejected_for_authenticated_user()
    {
        // Buat user
        $user = User::factory()->create([
            'email' => 'test2@example.com',
            'password' => Hash::make('password'),
        ]);

        // Buat ekyc registration dengan status rejected
        EkycRegistration::create([
            'user_id' => $user->id,
            'nama' => 'Test User',
            'nik' => '1234567890123456',
            'tanggal_lahir' => '1990-01-01',
            'alamat' => 'Test Address',
            'status' => 'rejected',
        ]);

        // Login sebagai user
        $this->actingAs($user);

        // Akses route /ekyc/status
        $response = $this->get('/ekyc/status');

        // Assert status 200 dan tidak redirect
        $response->assertStatus(200);

        // Assert bahwa halaman mengandung teks "Ditolak" (rejected)
        $response->assertSee('Ditolak');
    }

    public function test_ekyc_status_redirects_to_login_for_unauthenticated_user()
    {
        // Akses route /ekyc/status tanpa login
        $response = $this->get('/ekyc/status');

        // Assert redirect ke login
        $response->assertRedirect('/login');
    }
}
