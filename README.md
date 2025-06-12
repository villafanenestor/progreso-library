# Progreso

Librería PHP para gestionar y monitorear el progreso de tareas, con soporte para eventos SSE (Server-Sent Events) y almacenamiento en SQLite.

## Características

- Gestión de objetos de progreso con estado, contador y tiempo de inactividad.
- Almacenamiento persistente usando SQLite.
- Envío de eventos en tiempo real mediante SSE.
- Estructura modular y autoloading PSR-4 con Composer.

## Instalación

1. Clona el repositorio:
   ```bash
   git clone git@github.com:villafanenestor/progreso-library.git
   cd progreso
   ```

## Uso básico

```php
require 'vendor/autoload.php';

use Ness\Progreso\Progreso;
use Ness\Progreso\ProgresoRepository;
use Ness\Progreso\ProgressManager;
use Ness\Progreso\SSE\SSEHandler;

// Crear un nuevo progreso
$progreso = new Progreso('1', date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), 'iniciando', 0, 100);
$repo = new ProgresoRepository();
$repo->crear($progreso);

// Gestionar progreso
$manager = new ProgressManager();
$manager->crear('2', 0, 50, 'pendiente');

// Enviar evento SSE
$sse = new SSEHandler();
$sse->sendProgressEvent($progreso);
```

## Estructura del proyecto

```
src/
├── Progreso.php
├── ProgressManager.php
├── ProgresoRepository.php
└── SSE/
    └── SSEHandler.php
```

## Pruebas

Puedes crear un archivo `test.php` para probar la funcionalidad de la librería.

## Contribuciones

¡Las contribuciones son bienvenidas! Abre un issue o un pull request para sugerencias o mejoras.

## Licencia

MIT
