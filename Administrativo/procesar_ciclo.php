<?php
require_once __DIR__ . '/validar_sesion_admin.php';
require_once '../conexion.php';

$mensaje_exito = '';
$mensaje_error = '';

// 1. Lógica para CREAR un nuevo ciclo académico (POST)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['crear_ciclo'])) {
    $nombre_ciclo = trim($_POST['nombre_ciclo']);

    try {
        // Validación: Evitar crear un ciclo si ya hay uno activo
        $stmt_check = $conexion->query("SELECT id_ciclo FROM ciclos_academicos WHERE estado = 'Activo'");
        if ($stmt_check->rowCount() > 0) {
            $mensaje_error = "No puedes crear un nuevo semestre porque ya existe uno en curso. Termina el semestre actual primero.";
        } else {
            // Inserción oficial del nuevo semestre
            $sql_insert = "INSERT INTO ciclos_academicos (nombre_ciclo, estado, asignaciones_abiertas) VALUES (?, 'Activo', 0)";
            $stmt = $conexion->prepare($sql_insert);
            $stmt->execute([$nombre_ciclo]);
            $mensaje_exito = "El semestre '$nombre_ciclo' se ha aperturado exitosamente.";
        }
    } catch(PDOException $e) {
        $mensaje_error = "Error de base de datos: " . $e->getMessage();
    }
}

// 2. Lógica para manejar los BOTONES de la tabla (GET)
if (isset($_GET['accion']) && isset($_GET['id'])) {
    $id_ciclo = (int)$_GET['id'];
    
    // Botón: Abrir o Cerrar las asignaciones para los estudiantes
    if ($_GET['accion'] == 'toggle_asignacion') {
        $nuevo_estado = ($_GET['estado_actual'] == 1) ? 0 : 1;
        $stmt = $conexion->prepare("UPDATE ciclos_academicos SET asignaciones_abiertas = ? WHERE id_ciclo = ?");
        $stmt->execute([$nuevo_estado, $id_ciclo]);
        
        // Redirigir para limpiar la URL
        header("Location: ciclo_aca.php");
        exit();
    }
    
    // Botón: Terminar el semestre por completo
    if ($_GET['accion'] == 'cerrar_ciclo') {
        $stmt = $conexion->prepare("UPDATE ciclos_academicos SET estado = 'Cerrado', asignaciones_abiertas = 0 WHERE id_ciclo = ?");
        $stmt->execute([$id_ciclo]);
        
        header("Location: ciclo_aca.php");
        exit();
    }
}

// 3. Extraer todo el historial para inyectarlo en la tabla HTML
$lista_ciclos = [];
try {
    $stmt_lista = $conexion->query("SELECT * FROM ciclos_academicos ORDER BY id_ciclo DESC");
    $lista_ciclos = $stmt_lista->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $mensaje_error = "Error al cargar ciclos: " . $e->getMessage();
}
?>