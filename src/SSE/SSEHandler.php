<?php

namespace Ness\Progreso\SSE;

use Ness\Progreso\Progreso;

class SSEHandler
{
    /**
     * Envía un evento SSE al cliente.
     *
     * @param array    $data  datos a enviar (se enviarán como JSON)
     * @param string   $event tipo de evento (opcional)
     * @param int|null $id    ID del evento (opcional)
     */
    public function sendEvent(array $data, string $event = 'message', ?int $id = null)
    {
        if (!headers_sent()) {
            header('Content-Type: text/event-stream');
            header('Cache-Control: no-cache');
            header('Connection: keep-alive');
        }
        if ($id !== null) {
            echo "id: {$id}\n";
        }
        if ($event !== '') {
            echo "event: {$event}\n";
        }

        $jsonData = json_encode($data);
        foreach (explode("\n", $jsonData) as $line) {
            echo "data: {$line}\n";
        }
        echo "\n";
        if (ob_get_level() > 0) {
            while (ob_get_level() > 0) {
                ob_end_flush();
            }
        }
        flush();
    }

    public function sendNotificacion(array $data, ?int $id = null)
    {
        $this->sendEvent($data, 'notificacion', $id);
    }

    public function sendMensaje(array $data, ?int $id = null)
    {
        $this->sendEvent($data, 'mensaje', $id);
    }

    public function sendError(array $data, ?int $id = null)
    {
        $this->sendEvent($data, 'error', $id);
    }

    public function sendInfo(array $data, ?int $id = null)
    {
        $this->sendEvent($data, 'info', $id);
    }

    public function sendCustomEvent(array $data, string $event, ?int $id = null)
    {
        $this->sendEvent($data, $event, $id);
    }

    public function sendMensajePrivado(array $data, ?int $id = null)
    {
        $this->sendEvent($data, 'mensaje_privado', $id);
    }

    public function sendValidationEvent(array $data, ?int $id = null)
    {
        $this->sendEvent($data, 'validacion', $id);
    }

    public function sendGuardadoEvent(array $data, ?int $id = null)
    {
        $this->sendEvent($data, 'guardado', $id);
    }

    public function sendDoneEvent(array $data, ?int $id = null)
    {
        $this->sendEvent($data, 'done', $id);
    }


    public function sendProgressEvent(Progreso $progreso)
    {
        $data = $progreso->toArray();
        $this->sendEvent($data, $progreso->estado);
    }

    public function sendErrorEvent(string $message, ?int $id = null)
    {
        $data = ['error' => $message];
        $this->sendEvent($data, 'error', $id);
    }
}

// Ejemplo de uso:
// $sse = new SSEHandler();
// $sse->sendEvent(['msg' => 'Hola mundo', 'user' => 'cliente1'], 'notificacion', 1);

// while (true) {
//     // Mantener la conexión abierta
//     sleep(1);
//     $sse->sendNotificacion(['msg' => 'Ping', 'timestamp' => time()]);
// }
