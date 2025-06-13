<?php

namespace Ness\Progreso;

use Ness\Progreso\Progreso;
use Ness\Progreso\ProgresoRepository;
use Ness\Progreso\SSE\SSEHandler;
use Exception;
use DateTime;

class ProgressManager
{
    private ProgresoRepository $repo;
    private SSEHandler $sse;
    private int $tiempoEspera = 30;

    public function __construct(int $sleep = 0, int $segundosEspera = 30)
    {
        $this->repo = new ProgresoRepository();
        $this->sse = new SSEHandler();
        $this->repo->limpiarProgresosAntiguos();
        $this->tiempoEspera = $segundosEspera;
        sleep($sleep); // Simula un tiempo de espera para evitar problemas de conexión
    }

    public function crear($id, $contador_actual = 0, $total = 0, $estado = 'iniciando')
    {
        if ($this->repo->obtener($id)) {
            throw new Exception("El progreso con id $id ya existe.");
        }
        $now = date('Y-m-d H:i:s');
        $progreso = new Progreso($id, $now, $now, $estado, $contador_actual, $total);
        $this->repo->crear($progreso);
    }



    public function find($id): Progreso|false
    {
        return $this->repo->obtener($id);
    }

    public function sendEvent($id)
    {
        $progreso = $this->find($id);
        if ($progreso) {
            $this->sse->sendProgressEvent($progreso);
        } else {
            $this->sse->sendErrorEvent("No se encontró progreso para ID: $id");
        }
    }

    public function partialUpdate($id, $contador_actual = null, $estado = null, $total = null)
    {
        $progreso = $this->repo->obtener($id);
        if (!$progreso) {
            throw new Exception("El progreso con id $id no existe.");
        }
        if ($contador_actual !== null) {
            $progreso->contador_actual = $contador_actual;
        }
        if ($estado !== null) {
            $progreso->estado = $estado;
        }
        if ($total !== null) {
            $progreso->total = $total;
        }
        if($contador_actual >= $progreso->total && $progreso->total > 0) {
            $progreso->contador_actual = $progreso->total; // Asegura que el contador no supere el total
        }
        $progreso->fecha_actualizacion = date('Y-m-d H:i:s');
        $this->repo->actualizar($progreso);
    }


    private function getSegundosInactividad(Progreso $progreso): int
    {
        $inicio = new DateTime($progreso->fecha_actualizacion);
        $fin = new DateTime(); // ahora
        return $fin->getTimestamp() - $inicio->getTimestamp();
    }

    public function actualizarTiempoInactividad($id): void
    {
        $progreso = $this->repo->obtener($id);
        if (!$progreso) {
            throw new Exception("El progreso con id $id no existe.");
        }
        $tiempo_inactividad = $this->getSegundosInactividad($progreso);
        $progreso->tiempo_inactividad = $tiempo_inactividad;
        $this->repo->actualizarTiempoInactividad($id, $tiempo_inactividad);
    }

    public function actualizar(Progreso $progreso): void
    {
        if ($progreso->tiempo_inactividad > $this->tiempoEspera) {
            $this->repo->actualizarEstado($progreso->id, 'finalizado');
        }
    }

    // Otros métodos de negocio...
}
