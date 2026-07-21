<?php

namespace Tests\Feature;

use App\Models\Login;
use App\Models\Transaksi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransaksiApiTest extends TestCase
{
    // Gunakan RefreshDatabase jika Anda ingin database dibersihkan setiap test
    // Namun karena proyek ini menggunakan SQL dump, hati-hati jika menggunakan RefreshDatabase
    // use RefreshDatabase;

    protected $user;
    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Buat user dummy untuk testing jika belum ada, atau ambil yang ada
        $this->user = Login::where('level', 'Kasir')->first() ?: Login::create([
            'nama_user' => 'Test Kasir',
            'user' => 'kasir@test.com',
            'pass' => password_hash('password', PASSWORD_DEFAULT),
            'email' => 'kasir@test.com',
            'level' => 'Kasir',
            'umkm_id' => 1
        ]);

        $this->admin = Login::where('level', 'Admin')->first() ?: Login::create([
            'nama_user' => 'Test Admin',
            'user' => 'admin@test.com',
            'pass' => password_hash('password', PASSWORD_DEFAULT),
            'email' => 'admin@test.com',
            'level' => 'Admin',
            'umkm_id' => 1
        ]);
    }

    public function test_update_status_to_lunas_success()
    {
        $trx = Transaksi::create([
            'no_bon' => 'TEST001',
            'kasir_id' => $this->user->id,
            'customer_id' => 0,
            'status' => 'Bayar Nanti',
            'grandtotal' => 10000,
            'umkm_id' => 1
        ]);

        $response = $this->actingAs($this->user)
            ->patchJson(route('kasir.update-status'), [
                'id' => $trx->id,
                'status' => 'lunas'
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Status transaksi berhasil diperbarui.')
            ->assertJsonPath('data.status', 'Lunas');

        $this->assertDatabaseHas('transaksi', [
            'id' => $trx->id,
            'status' => 'Lunas',
            'status_updated_by' => $this->user->id
        ]);
    }

    public function test_update_status_invalid_input()
    {
        $response = $this->actingAs($this->user)
            ->patchJson(route('kasir.update-status'), [
                'id' => 99999, // ID tidak ada
                'status' => 'invalid-status'
            ]);

        $response->assertStatus(422)
            ->assertJsonPath('error', 'Input tidak valid.');
    }

    public function test_kasir_cannot_revert_lunas_to_belum_lunas()
    {
        $trx = Transaksi::create([
            'no_bon' => 'TEST002',
            'kasir_id' => $this->user->id,
            'customer_id' => 0,
            'status' => 'Lunas',
            'grandtotal' => 10000,
            'umkm_id' => 1
        ]);

        $response = $this->actingAs($this->user)
            ->patchJson(route('kasir.update-status'), [
                'id' => $trx->id,
                'status' => 'belum lunas'
            ]);

        $response->assertStatus(403)
            ->assertJsonPath('error', 'Hanya Admin yang dapat mengubah status transaksi yang sudah Lunas.');
    }

    public function test_admin_can_revert_lunas_to_belum_lunas()
    {
        $trx = Transaksi::create([
            'no_bon' => 'TEST003',
            'kasir_id' => $this->user->id,
            'customer_id' => 0,
            'status' => 'Lunas',
            'grandtotal' => 10000,
            'umkm_id' => 1
        ]);

        $response = $this->actingAs($this->admin)
            ->patchJson(route('kasir.update-status'), [
                'id' => $trx->id,
                'status' => 'belum lunas'
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.status', 'Belum lunas');
    }
}
