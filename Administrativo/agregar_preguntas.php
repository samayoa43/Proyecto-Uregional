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
    <title>Configurar Preguntas</title>
    <link rel="stylesheet" href="estilos.css">
    <style>
        .container { max-width: 800px; margin: 30px auto; padding: 20px; font-family: sans-serif; }
        .box-agregar { background: #f9f9f9; padding: 20px; border-radius: 8px; border: 1px solid #ddd; margin-bottom: 30px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-weight: bold; margin-bottom: 5px; }
        .form-group input, .form-group select { width: 100%; padding: 8px; box-sizing: border-box; }
        .btn-guardar { background-color: #007bff; color: white; padding: 10px 15px; border: none; cursor: pointer; border-radius: 4px; }
        .lista-preguntas { border-collapse: collapse; width: 100%; mt-20; }
        .lista-preguntas th, .lista-preguntas td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        .lista-preguntas th { background-color: #f2f2f2; }
        .alerta-exito { background-color: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Añadir preguntas a: <span style="color: #007bff;"><?php echo htmlspecialchars($encuesta['titulo']); ?></span></h2>
        
        <?php if (isset($_GET['mensaje']) && $_GET['mensaje'] == 'pregunta_agregada'): ?>
            <div class="alerta-exito">¡Pregunta añadida correctamente!</div>
        <?php endif; ?>

        <div class="box-agregar">
            <form action="agregar_preguntas.php?id=<?php echo $id_encuesta; ?>" method="POST">
                <div class="form-group">
                    <label for="pregunta">Redacta la pregunta:</label>
                    <input type="text" id="pregunta" name="pregunta" required placeholder="Ej: ¿Cómo calificarías las instalaciones de la sede?">
                </div>
                
                <div class="form-group">
                    <label for="tipo_respuesta">Tipo de respuesta permitida:</label>
                    <select id="tipo_respuesta" name="tipo_respuesta" required>
                        <option value="Escala_1_a_5">Escala del 1 al 5 (Calificación)</option>
                        <option value="Texto_Libre">Texto Libre (Comentarios, sugerencias)</option>
                    </select>
                </div>
                
                <button type="submit" class="btn-guardar">+ Añadir Pregunta</button>
            </form>
        </div>

        <h3>Preguntas Actuales (<?php echo count($preguntas_existentes); ?>)</h3>
        
        <?php if (count($preguntas_existentes) > 0): ?>
            <table class="lista-preguntas">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Pregunta</th>
                        <th>Tipo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($preguntas_existentes as $index => $p): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td><?php echo htmlspecialchars($p['pregunta']); ?></td>
                            <td>
                                <?php 
                                    echo ($p['tipo_respuesta'] == 'Escala_1_a_5') ? '⭐⭐⭐⭐⭐ (1 a 5)' : '📝 Texto Libre'; 
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aún no has agregado ninguna pregunta a esta encuesta.</p>
        <?php endif; ?>

        <br><br>
        <a href="inicio_admin.php" style="padding: 10px; background: #6c757d; color: white; text-decoration: none; border-radius: 4px;">Finalizar y Volver al Panel</a>
    </div>
</body>
</html>