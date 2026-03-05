<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('clearAll', function () {
    // Lista de comandos a ejecutar
    $commands = [
        'view:clear'    => 'Vistas',
        'cache:clear'   => 'Caché',
        'config:clear'  => 'Configuración',
        'route:clear'   => 'Rutas',
    ];

    foreach ($commands as $command => $name) {
        try {
            Artisan::call($command);
            $this->info("{$name} limpiado correctamente.");
        } catch (\Exception $e) {
            $this->error("Error al limpiar {$name}: {$e->getMessage()}");
        }
    }

    $this->comment('Limpieza completada.');
})->purpose('Clear all caches and configurations');
