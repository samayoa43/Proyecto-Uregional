<?php
session_start();
require '../conexion.php'; 

if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== "admin") {
    header("Location: login.php?error=acceso_denegado");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: inicio_admin.php?error=id_faltante");
    exit();
}

$id_encuesta = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pregunta'])) {
    $pregunta = $_POST['pregunta'];
    $tipo_respuesta = $_POST['tipo_respuesta'];

    try {
        $sql_insert = "INSERT INTO preguntas_encuesta (id_encuesta, pregunta, tipo_respuesta) VALUES (?, ?, ?)";
        $stmt_insert = $conexion->prepare($sql_insert);
        $stmt_insert->execute([$id_encuesta, $pregunta, $tipo_respuesta]);

        header("Location: agregar_preguntas.php?id=$id_encuesta&mensaje=pregunta_agregada");
        exit();
    } catch(PDOException $e) {
        $error = "Error al guardar: " . $e->getMessage();
    }
}

$sql_encuesta = "SELECT titulo FROM encuestas WHERE id_encuesta = ?";
$stmt_encuesta = $conexion->prepare($sql_encuesta);
$stmt_encuesta->execute([$id_encuesta]);
$encuesta = $stmt_encuesta->fetch(PDO::FETCH_ASSOC);

$sql_preguntas = "SELECT * FROM preguntas_encuesta WHERE id_encuesta = ?";
$stmt_preguntas = $conexion->prepare($sql_preguntas);
$stmt_preguntas->execute([$id_encuesta]);
$preguntas_existentes = $stmt_preguntas->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurar Preguntas - Plataforma Académica</title>
    <link rel="stylesheet" href="estilo_administrativo.css?v=<?php echo time(); ?>">
</head>
<body>

    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.body.classList.add('dark-mode');
        }
    </script>

    <?php 
    $ruta_base = "../";
    require 'encabezado.php'; ?>

    <main class="main-container">
        
        <div class="section-header">
            <h2>Configurar Preguntas</h2>
            <p>Añadiendo preguntas a la encuesta: <strong class="texto-destacado"><?php echo htmlspecialchars($encuesta['titulo']); ?></strong></p>
        </div>

        <?php if (isset($_GET['mensaje']) && $_GET['mensaje'] == 'pregunta_agregada'): ?>
            <div class="alerta alerta-exito">¡Pregunta añadida correctamente!</div>
        <?php endif; ?>

        <div class="kpi-card form-card" style="max-width: 100%; margin-bottom: 30px;">
            <form action="agregar_preguntas.php?id=<?php echo $id_encuesta; ?>" method="POST" class="formulario-horizontal">
                
                <div class="form-group" style="flex: 2;">
                    <label for="pregunta" class="form-label">Redacta la pregunta:</label>
                    <input type="text" id="pregunta" name="pregunta" class="form-input" required placeholder="Ej: ¿Cómo calificarías las instalaciones de la sede?">
                </div>
                
                <div class="form-group" style="flex: 1;">
                    <label for="tipo_respuesta" class="form-label">Tipo de respuesta:</label>
                    <select id="tipo_respuesta" name="tipo_respuesta" class="form-input" required>
                        <option value="Escala_1_a_5">⭐⭐⭐⭐⭐ (Escala 1 al 5)</option>
                        <option value="Texto_Libre">📝 Texto Libre (Comentarios)</option>
                    </select>
                </div>
                
                <div class="form-group" style="display: flex; align-items: flex-end;">
                    <button type="submit" class="btn-primario">+ Añadir Pregunta</button>
                </div>

            </form>
        </div>

        <div class="kpi-card" style="width: 100%; overflow-x: auto; padding: 25px; box-sizing: border-box;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h3 style="margin: 0; color: #334155;" class="titulo-tabla">Preguntas Actuales (<?php echo count($preguntas_existentes); ?>)</h3>
            </div>
            
            <?php if (count($preguntas_existentes) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th style="width: 50px; text-align: center;">#</th>
                            <th>Pregunta</th>
                            <th style="width: 250px;">Tipo de Respuesta</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($preguntas_existentes as $index => $p): ?>
                            <tr>
                                <td style="text-align: center;"><strong><?php echo $index + 1; ?></strong></td>
                                <td><?php echo htmlspecialchars($p['pregunta']); ?></td>
                                <td>
                                    <?php if($p['tipo_respuesta'] == 'Escala_1_a_5'): ?>
                                        <span class="badge badge-escala">⭐⭐⭐⭐⭐ (1 a 5)</span>
                                    <?php else: ?>
                                        <span class="badge badge-texto">📝 Texto Libre</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="reporte-vacio">
                    <p>Aún no has agregado ninguna pregunta a esta encuesta.</p>
                </div>
            <?php endif; ?>
        </div>

        <div style="margin-top: 20px; text-align: right;">
            <a href="inicio_admin.php" class="btn-volver">Finalizar y Volver al Panel</a>
        </div>

    </main>

    <script src="script_admin.js?v=<?php echo time(); ?>"></script>
</body>
</html>