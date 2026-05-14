<?php
require 'validar_sesion_estudiantes.php';
require '../conexion.php';

$id_tarea = $_POST['id_tarea'];
$id_curso = $_POST['id_curso'];
$comentario = trim($_POST['comentario'] ?? '');

// Obtener datos del estudiante actual
$stmt_id = $conexion->prepare("SELECT id_estudiante, nombres, apellidos FROM estudiantes WHERE id_usuario = ?");
$stmt_id->execute([$_SESSION['id_usuario']]);
$estudiante = $stmt_id->fetch(PDO::FETCH_ASSOC);
$id_estudiante = $estudiante['id_estudiante'];

// Procesar el archivo
if (isset($_FILES['archivo_tarea']) && $_FILES['archivo_tarea']['error'] === UPLOAD_ERR_OK) {
    
    // 1. Definir la ruta dinámica: uploads/curso_X/tarea_Y/
    $directorio_base = "../uploads/curso_" . $id_curso . "/tarea_" . $id_tarea . "/";
    
    // 2. Si las carpetas no existen, las creamos
    if (!file_exists($directorio_base)) {
        mkdir($directorio_base, 0777, true);
    }

    // 3. Limpiar el nombre del archivo y agregarle el ID del alumno para evitar reemplazos
    $nombre_original = basename($_FILES["archivo_tarea"]["name"]);
    // Quitar espacios del nombre original por seguridad
    $nombre_limpio = preg_replace("/[^a-zA-Z0-9.]/", "_", $nombre_original);
    
    $nombre_final = $id_estudiante . "_" . time() . "_" . $nombre_limpio; 
    $ruta_fisica = $directorio_base . $nombre_final;
    
    // Ruta limpia para guardar en la base de datos (quitando el "../")
    $ruta_bd = "uploads/curso_" . $id_curso . "/tarea_" . $id_tarea . "/" . $nombre_final;

    // 4. Mover el archivo
    if (move_uploaded_file($_FILES["archivo_tarea"]["tmp_name"], $ruta_fisica)) {
        
        try {
            // Verificamos si ya había una entrega previa para actualizarla o si es nueva
            $stmt_check = $conexion->prepare("SELECT id_entrega FROM entregas_tareas WHERE id_tarea = ? AND id_estudiante = ?");
            $stmt_check->execute([$id_tarea, $id_estudiante]);
            $existe = $stmt_check->fetch();

            if ($existe) {
                // UPDATE (Si por alguna razón le permites resubir tareas)
                $sql = "UPDATE entregas_tareas SET archivo_ruta = ?, comentarios_estudiante = ?, fecha_entrega = CURRENT_TIMESTAMP WHERE id_entrega = ?";
                $stmt = $conexion->prepare($sql);
                $stmt->execute([$ruta_bd, $comentario, $existe['id_entrega']]);
            } else {
                // INSERT (La primera vez que entrega)
                $sql = "INSERT INTO entregas_tareas (id_tarea, id_estudiante, archivo_ruta, comentarios_estudiante) VALUES (?, ?, ?, ?)";
                $stmt = $conexion->prepare($sql);
                $stmt->execute([$id_tarea, $id_estudiante, $ruta_bd, $comentario]);
            }

            header("Location: mis_tareas.php?exito=1");
            exit();

        } catch (PDOException $e) {
            // Si falla la base de datos, borramos el archivo físico para no tener basura en el servidor
            unlink($ruta_fisica);
            header("Location: mis_tareas.php?error=db");
            exit();
        }
    } else {
        header("Location: mis_tareas.php?error=upload");
        exit();
    }
} else {
    header("Location: mis_tareas.php?error=vacio");
    exit();
}
?>