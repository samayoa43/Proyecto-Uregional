<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'C:\laragon\www\proyecto\conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_curso'])) {
    $id_curso = $_POST['id_curso'];
    $id_prerrequisito = $_POST['id_curso_previo'];

    if ($id_curso === $id_prerrequisito) {
        $mensaje_error = "Error: Un curso no puede ser prerrequisito de sí mismo.";
    } else {
        try {
            $sql_insert = "INSERT INTO prerrequisitos (id_curso, id_curso_previo) VALUES (?, ?)";
            $stmt_insert = $conexion->prepare($sql_insert);
            $stmt_insert->execute([$id_curso, $id_prerrequisito]);
            
            $mensaje_exito = "¡Prerrequisito asignado correctamente!";
        } catch(PDOException $e) {
            if ($e->getCode() == 23000) {
                $mensaje_error = "Este prerrequisito ya está asignado a este curso.";
            } else {
                $mensaje_error = "Error al guardar: " . $e->getMessage();
            }
        }
    }
}

$lista_carreras = [];
try {
    $stmt_carreras = $conexion->query("SELECT id_carrera, nombre_carrera FROM carreras ORDER BY nombre_carrera ASC");
    $lista_carreras = $stmt_carreras->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $mensaje_error = "Error al cargar carreras: " . $e->getMessage();
}

$carrera_seleccionada = isset($_GET['id_carrera']) ? $_GET['id_carrera'] : null;
$lista_cursos_filtrados = [];

if ($carrera_seleccionada) {
    try {
        $sql_cursos = "SELECT c.id_curso, c.nombre_curso 
                       FROM cursos c
                       INNER JOIN pensum p ON c.id_curso = p.id_curso
                       WHERE p.id_carrera = ?
                       ORDER BY c.nombre_curso ASC";
        $stmt_cursos = $conexion->prepare($sql_cursos);
        $stmt_cursos->execute([$carrera_seleccionada]);
        $lista_cursos_filtrados = $stmt_cursos->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        $mensaje_error = "Error al cargar los cursos filtrados: " . $e->getMessage();
    }
}
?>
