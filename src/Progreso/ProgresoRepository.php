<?php
namespace Ness\Progreso;
use PDO;

class ProgresoRepository
{
    private PDO $db;

    public function __construct($dbPath = null)
    {
        if ($dbPath === null) {
            $dbPath = sys_get_temp_dir() . '/progreso.sqlite';
        }
        $this->db = new PDO('sqlite:' . $dbPath);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->crearTablaSiNoExiste();
        if (file_exists($dbPath)) {
            error_log("La base de datos SQLite fue creada en: $dbPath");
        } else {
            error_log("No se encontró la base de datos en: $dbPath");
        }
    }

    private function crearTablaSiNoExiste()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS progreso (
            id TEXT PRIMARY KEY,
            fecha_creacion DATETIME NOT NULL,
            fecha_actualizacion DATETIME NOT NULL,
            estado TEXT NOT NULL,
            contador_actual INTEGER NOT NULL,
            total INTEGER NOT NULL,
            tiempo_inactividad INTEGER DEFAULT 0
        )';
        $this->db->exec($sql);
    }

    public function crear(Progreso $progreso): void
    {
        $sql = 'INSERT INTO progreso (id, fecha_creacion, fecha_actualizacion, estado, contador_actual, total)
                VALUES (:id, :fecha_creacion, :fecha_actualizacion, :estado, :contador_actual, :total)';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id' => $progreso->id,
            ':fecha_creacion' => $progreso->fecha_creacion,
            ':fecha_actualizacion' => $progreso->fecha_actualizacion,
            ':estado' => $progreso->estado,
            ':contador_actual' => $progreso->contador_actual,
            ':total' => $progreso->total,
        ]);
    }

    public function actualizar(Progreso $progreso): void
    {
        $sql = 'UPDATE progreso SET
                    fecha_actualizacion = :fecha_actualizacion,
                    estado = :estado,
                    contador_actual = :contador_actual,
                    total = :total,
                    tiempo_inactividad = :tiempo_inactividad
                WHERE id = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id' => $progreso->id,
            ':fecha_actualizacion' => $progreso->fecha_actualizacion,
            ':estado' => $progreso->estado,
            ':contador_actual' => $progreso->contador_actual,
            ':total' => $progreso->total,
            ':tiempo_inactividad' => $progreso->tiempo_inactividad, // Aseguramos que este campo exista
        ]);
    }

    public function actualizarTiempoInactividad($id, $tiempo_inactividad): void
    {
        $sql = 'UPDATE progreso SET tiempo_inactividad = :tiempo_inactividad WHERE id = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id' => $id,
            ':tiempo_inactividad' => $tiempo_inactividad,
        ]);
    }

    public function actualizarEstado($id, $estado): void
    {
        $sql = 'UPDATE progreso SET estado = :estado WHERE id = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id' => $id,
            ':estado' => $estado,
        ]);
    }

    public function obtener($id): Progreso|false
    {
        $sql = 'SELECT * FROM progreso WHERE id = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Progreso(
                $row['id'],
                $row['fecha_creacion'],
                $row['fecha_actualizacion'],
                $row['estado'],
                $row['contador_actual'],
                $row['total'],
                $row['tiempo_inactividad'] // Aseguramos que este campo exista
            );
        }
        return false;
    }

    public function eliminar($id): void
    {
        $sql = 'DELETE FROM progreso WHERE id = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
    }


    public function limpiarProgresosAntiguos($minutos = 30): void
    {
        $fechaLimite = date('Y-m-d H:i:s', strtotime("-$minutos minutes"));
        $sql = 'DELETE FROM progreso WHERE fecha_creacion < :fecha_limite';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':fecha_limite' => $fechaLimite]);
    }
}
