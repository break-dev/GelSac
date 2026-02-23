<?php

/**
 * Script de diagnóstico de versiones (Sin dependencia estricta de PATH)
 */

function get_version($command, $is_binary = true)
{
    if ($is_binary) {
        // Intentamos rutas comunes por si no están en el PATH
        $common_paths = ['', '/usr/local/bin/', '/usr/bin/', '/bin/', '~/bin/'];
        foreach ($common_paths as $path) {
            $output = @shell_exec($path . $command . ' --version 2>&1');
            if ($output && !str_contains($output, 'not found')) {
                return explode("\n", trim($output))[0];
            }
        }
    }
    return '<span style="color: #e74c3c;">No detectado</span>';
}

// 1. Versión de PHP (Nativa, siempre funciona)
$php_version = phpversion();

// 2. Versión de Laravel (Buscando en el proyecto)
$laravel_version = 'No detectado (Ejecuta el script en la raíz de un proyecto Laravel)';
if (file_exists('vendor/laravel/framework/src/Illuminate/Foundation/Application.php')) {
    // Si el script está en la raíz de un proyecto Laravel, leemos su versión real
    $composer_lock = @file_get_contents('composer.lock');
    if ($composer_lock) {
        $data = json_decode($composer_lock, true);
        foreach ($data['packages'] as $package) {
            if ($package['name'] === 'laravel/framework') {
                $laravel_version = $package['version'];
                break;
            }
        }
    }
}

$results = [
    'PHP'      => $php_version,
    'Composer' => get_version('composer'),
    'Node.js'  => get_version('node -v', true),
    'NPM'      => get_version('npm -v', true),
    'Laravel'  => $laravel_version
];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnóstico de VPS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container py-5">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0">Información del Entorno</h4>
            </div>
            <div class="card-body">
                <p><strong>Sistema Operativo:</strong> <?php echo php_uname(); ?></p>
                <table class="table table-hover border">
                    <thead class="table-secondary">
                        <tr>
                            <th>Herramienta</th>
                            <th>Versión Detectada</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results as $name => $ver): ?>
                            <tr>
                                <td><strong><?php echo $name; ?></strong></td>
                                <td><code><?php echo $ver; ?></code></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="alert alert-info mt-3">
                    <small>
                        <strong>Nota:</strong> Si Composer o Node aparecen como "No detectado", es muy probable que no estén instalados en este VPS o el proveedor de hosting haya bloqueado el acceso a binarios externos por seguridad.
                    </small>
                </div>
            </div>
        </div>
    </div>
</body>

</html>