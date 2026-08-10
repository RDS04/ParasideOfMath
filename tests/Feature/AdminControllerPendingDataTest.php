<?php

use App\Http\Controllers\Admin\AdminController;

it('moves pending schedule data to active biodata when approving pending lessons', function () {
    $controller = new AdminController();
    $method = new ReflectionMethod($controller, 'mergePendingBiodata');
    $method->setAccessible(true);

    $biodata = [
        'mapel_jadwal' => ['Matematika'],
        'sesi_per_mapel' => [8],
        'hari_per_mapel' => [['Senin']],
        'tanggal_mulai_per_mapel' => ['2026-08-10'],
        'pending_mapel_jadwal' => ['Fisika'],
        'pending_sesi_per_mapel' => [10],
        'pending_hari_per_mapel' => [['Rabu']],
        'pending_tanggal_mulai_per_mapel' => ['2026-08-12'],
    ];

    $result = $method->invoke($controller, $biodata);

    expect($result['mapel_jadwal'])->toEqual(['Matematika', 'Fisika'])
        ->and($result['sesi_per_mapel'])->toEqual([8, 10])
        ->and($result['hari_per_mapel'])->toEqual([['Senin'], ['Rabu']])
        ->and($result['tanggal_mulai_per_mapel'])->toEqual(['2026-08-10', '2026-08-12'])
        ->and($result['pending_mapel_jadwal'] ?? null)->toBeNull()
        ->and($result['pending_hari_per_mapel'] ?? null)->toBeNull();
});
