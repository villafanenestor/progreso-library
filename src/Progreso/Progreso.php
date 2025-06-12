<?php
namespace Ness\Progreso;

class Progreso
{
    public $id;
    public $fecha_creacion;
    public $fecha_actualizacion;
    public $estado;
    public $contador_actual;
    public $total;
    public $tiempo_inactividad;

    public function __construct($id, $fecha_creacion, $fecha_actualizacion, $estado, $contador_actual, $total, $tiempo_inactividad = 0)
    {
        $this->id = $id;
        $this->fecha_creacion = $fecha_creacion;
        $this->fecha_actualizacion = $fecha_actualizacion;
        $this->estado = $estado;
        $this->contador_actual = $contador_actual;
        $this->total = $total;
        $this->tiempo_inactividad = $tiempo_inactividad; // Inicializado a 0 por defecto
    }

    public function toArray(): array
    {
        return [
            'id'                  => $this->id,
            'fecha_creacion'      => $this->fecha_creacion,
            'fecha_actualizacion' => $this->fecha_actualizacion,
            'estado'              => $this->estado,
            'contador_actual'     => $this->contador_actual,
            'total'               => $this->total,
            'tiempo_inactividad'  => $this->tiempo_inactividad,
        ];
    }

    public function __toString(): string
    {
        return json_encode($this->toArray());
    }
}