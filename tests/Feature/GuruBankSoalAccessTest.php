<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows a guru to access the bank soal page', function () {
    $guru = User::factory()->create([
        'name' => 'Guru Test',
        'email' => 'guru@example.com',
        'role' => 'guru',
    ]);

    $this->actingAs($guru, 'web');

    $response = $this->get('/guru/bank-soal');

    $response->assertStatus(200);
    $response->assertSee('Bank Soal');
});
