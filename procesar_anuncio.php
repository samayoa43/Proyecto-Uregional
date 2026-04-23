<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'conexion.php'; 

if (!isset($_SESSION['logged_in']) || ($_SESSION['rol'] !== 'admin' && $_SESSION['rol'] !== 'docente')) {
    die("<h2 style='color:red; text-align:center;'>Acceso Denegado. Solo personal autorizado puede publicar anuncios.</h2>");
}

$mensaje_exito = null;
$mensaje_error = null;

$id_autor = $_SESSION['id_usuario'];
$rol_usuario = $_SESSION['rol'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['publicar_anuncio'])) {
    $titulo = trim($_POST['titulo']);
    $mensaje = trim($_POST['mensaje']);
    $audiencia = $_POST['audiencia'];

    // Validación de seguridad extra: Evitar que un Docente manipule el HTML para enviar a 'Todos'
    if ($rol_usuario === 'Docente' && $audiencia !== 'Estudiantes') {
        $mensaje_error = "Error de permisos: Los docentes solo pueden dirigir anuncios a los estudiantes.";
    } else {
        try {
            $sql_insert = "INSERT INTO anuncios (id_autor, titulo, mensaje, audiencia) VALUES (?, ?, ?, ?)";
            $stmt_insert = $conexion->prepare($sql_insert);
            $stmt_insert->execute([$id_autor, $titulo, $mensaje, $audiencia]);
            
            $mensaje_exito = "¡El anuncio '$titulo' ha sido publicado y ya es visible en el tablón!";
        } catch(PDOException $e) {
            $mensaje_error = "Error al publicar el anuncio: " . $e->getMessage();
        }
    }
}
?>