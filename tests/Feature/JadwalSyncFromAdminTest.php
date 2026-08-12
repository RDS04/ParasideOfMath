<?php

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

it('uses admin-configured day and time data on guru and siswa jadwal pages', function () {
    $admin = User::factory()->create([
        'name' => 'Admin Test',
        'email' => 'admin@example.com',
        'role' => 'admin',
    ]);

    $guru = User::factory()->create([
        'name' => 'Guru Test',
        'email' => 'guru@example.com',
        'role' => 'guru',
    ]);

    $siswa = Siswa::create([
        'name' => 'Siswa Test',
        'email' => 'siswa@example.com',
        'password' => bcrypt('password'),
        'status' => 'active',
        'biodata' => [
            'mapel_jadwal' => ['Matematika'],
            'sesi_per_mapel' => [6],
            'hari_per_mapel' => [
                ['Senin', 'Rabu'],
            ],
            'jam_per_mapel' => [
                ['jam_mulai' => '09:00', 'jam_selesai' => '10:30'],
            ],
            'tutor_per_mapel' => ['Matematika' => 'Guru Test'],
        ],
        'tipe_paket' => 'Mapel: Matematika | Hari: Senin,Rabu | Sesi: 6x | Guru: Guru Test',
    ]);

    actingAs($guru, 'web');
    $guruResponse = $this->get('/guru/jadwal');
    $guruResponse->assertOk();
    $guruResponse->assertSee('09:00');

    actingAs($siswa, 'siswa');
    $siswaResponse = $this->get('/siswa/jadwal');
    $siswaResponse->assertOk();
    $siswaResponse->assertSee('09:00');
});
