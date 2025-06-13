<?php

namespace Tests;

use Ness\Progreso\Progreso;

it('crea un progreso correctamente', function () {
    $progreso = new Progreso('id-1', '2023-10-01 12:00:00', '2023-10-01 12:05:00', 'en_progreso', 10, 100);

    expect($progreso->estado)->toBe('en_progreso');
    expect($progreso->contador_actual)->toBe(10);
    expect($progreso->toArray())->toHaveKey('id');
});