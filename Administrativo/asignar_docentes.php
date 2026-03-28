<?php
require 'C:\laragon\www\proyecto\conexion.php';

$mensaje = '';
$lista_docentes = [];
$lista_cursos = [];


if (isset($_GET['exito']) && $_GET['exito'] == 1) {
    $mensaje = "<div>¡Docente asignado al curso exitosamente!</div>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_docente = $_POST['id_docente'] ?? null;
    $id_curso = $_POST['id_curso'] ?? null;

    if ($id_docente && $id_curso) {
        try {
            $sql = "INSERT INTO asignaciones_docentes (id_docente, id_curso) 
                    VALUES (:id_docente, :id_curso)";
            
            $stmt = $conexion->prepare($sql);
            $stmt->execute([
                ':id_docente' => $id_docente,
                ':id_curso' => $id_curso,
            ]);

            header("Location: " . $_SERVER['PHP_SELF'] . "?exito=1");
            exit();

        } catch (PDOException $e) {
            $mensaje = "<div>Error al asignar: " . $e->getMessage() . "</div>";
        }
    } else {
        $mensaje = "<div>Por favor, complete todos los campos.</div>";
    }
}

try {

    $stmt_docentes = $conexion->query("SELECT id_docente, nombres, apellidos FROM docentes ORDER BY apellidos ASC");
    $lista_docentes = $stmt_docentes->fetchAll(PDO::FETCH_ASSOC);

    $stmt_cursos = $conexion->query("SELECT id_curso, nombre_curso FROM cursos ORDER BY nombre_curso ASC");
    $lista_cursos = $stmt_cursos->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    $mensaje = "<div style='color: red;'>Error al cargar las listas: " . $e->getMessage() . "</div>";
}
?>
