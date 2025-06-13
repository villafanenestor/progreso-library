<?php

use Ness\Progreso\Progreso;
use Ness\Progreso\ProgresoRepository;

beforeEach(function () {
    // Limpia la base de datos antes de cada test
    $this->repo = new ProgresoRepository();
    // Si tienes un método para limpiar, úsalo aquí
});

it('crea un progreso en la base de datos', function () {
    $repo = new ProgresoRepository();
    $progreso = new Progreso('123467890', '2023-10-01 12:00:00', '2023-10-01 12:00:00', 'iniciando', 0, 100);
    $repo->crear($progreso);
    $progresoGuardado = $repo->obtener('123467890');
    expect($$progresoGuardado->id)->toBeTrue('123467890');
});

// it('obtiene un progreso existente', function () {
//     $repo = new ProgresoRepository();
//     $progreso = new Progreso('test-get', '2023-10-01 12:00:00', '2023-10-01 12:00:00', 'iniciando', 0, 100);
    
//     $repo->crear($progreso);
//     $obtenido = $repo->obtener('test-get');
    
//     expect($obtenido)->not->toBeNull();
//     expect($obtenido->id)->toBe('test-get');
//     expect($obtenido->estado)->toBe('iniciando');
// });

// it('actualiza un progreso existente', function () {
//     $repo = new ProgresoRepository();
//     $progreso = new Progreso('test-update', '2023-10-01 12:00:00', '2023-10-01 12:00:00', 'iniciando', 0, 100);
    
//     $repo->crear($progreso);
//     $progreso->estado = 'completado';
//     $progreso->contador_actual = 100;
    
//     $resultado = $repo->actualizar($progreso);
//     $actualizado = $repo->obtener('test-update');
    
//     expect($resultado)->toBeTrue();
//     expect($actualizado->estado)->toBe('completado');
//     expect($actualizado->contador_actual)->toBe(100);
// });

// it('elimina un progreso', function () {
//     $repo = new ProgresoRepository();
//     $progreso = new Progreso('test-delete', '2023-10-01 12:00:00', '2023-10-01 12:00:00', 'iniciando', 0, 100);
    
//     $repo->crear($progreso);
//     $resultado = $repo->eliminar('test-delete');
//     $eliminado = $repo->obtener('test-delete');
    
//     expect($resultado)->toBeTrue();
//     expect($eliminado)->toBeNull();
// });