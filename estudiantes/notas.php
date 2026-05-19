<?php
require 'validar_sesion_estudiantes.php';
require_once __DIR__ . '/procesar_notas.php';
require 'verificar_solvencia.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Calificaciones</title>
    <link rel="stylesheet" href="estilos_estudiantes.css"> 
</head>
<body>
<div class="app-shell">
    
    <?php include 'encabezado.php'; ?>

    <main class="content">
        <section class="panel glass">
            <div class="panel-header">
                <h3>Calificaciones</h3>
                <p class="muted">Historial de notas parciales y finales.</p>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr><th>Curso</th><th>Nota 1</th><th>Nota 2</th><th>Nota 3</th><th>Nota Final</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach($notas as $nota): ?>
                        <tr>
                            <td><?php echo $nota['nombre_curso']; ?></td>
                            <td><?php echo $nota['nota'] ?? '-'; ?></td>
                            <td><?php echo $nota['nota2'] ?? '-'; ?></td>
                            <td><?php echo $nota['nota3'] ?? '-'; ?></td>
                            <td><strong><?php echo $nota['nota_final'] ?? '-'; ?></strong></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</div>
</body>
</html>