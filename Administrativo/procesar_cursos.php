<?php

require 'C:\laragon\www\proyecto\conexion.php';

$mensaje = '';
$lista_carreras = [];

if (isset($_GET['exito']) && $_GET['exito'] == 1) {
    $mensaje = "<div style='color: green; background: #e6ffe6; padding: 10px; border-radius: 5px; margin-bottom: 15px;'>¡Los cursos y sus semestres se guardaron correctamente!</div>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_carrera = $_POST['id_carrera'] ?? null;
    $nombres_cursos = $_POST['nombres_cursos'] ?? []; 
    $semestres_cursos = $_POST['semestres_cursos'] ?? []; 

    if ($id_carrera && !empty($nombres_cursos)) {
        try {
            $conexion->beginTransaction();

            $stmt_buscar = $conexion->prepare("SELECT id_curso FROM cursos WHERE nombre_curso = :nombre LIMIT 1");
            
            $stmt_insertar_curso = $conexion->prepare("INSERT INTO cursos (nombre_curso) VALUES (:nombre)");
            
            $stmt_vincular = $conexion->prepare("INSERT INTO pensum (id_carrera, id_curso, semestre) VALUES (:id_carrera, :id_curso, :semestre)");

            $insertados = 0;

            for ($i = 0; $i < count($nombres_cursos); $i++) {
                
                $nombre_limpio = trim($nombres_cursos[$i]);
                $semestre_actual = !empty($semestres_cursos[$i]) ? trim($semestres_cursos[$i]) : null;
                
                if (!empty($nombre_limpio)) {

                    $stmt_buscar->execute([':nombre' => $nombre_limpio]);
                    $curso_existente = $stmt_buscar->fetch(PDO::FETCH_ASSOC);

                    if ($curso_existente) {
                        $id_curso_actual = $curso_existente['id_curso'];
                    } else {
                        $stmt_insertar_curso->execute([':nombre' => $nombre_limpio]);
                        $id_curso_actual = $conexion->lastInsertId(); 
                    }

                    try {
                        $stmt_vincular->execute([
                            ':id_carrera' => $id_carrera,
                            ':id_curso'   => $id_curso_actual,
                            ':semestre'   => $semestre_actual 
                        ]);
                        $insertados++;
                    } catch (PDOException $e) {
                        if ($e->getCode() != 23000) { throw $e; }
                    }
                }
            }

            $conexion->commit();

            if ($insertados > 0) {
                header("Location: " . $_SERVER['PHP_SELF'] . "?exito=1");
                exit();
            }

        } catch (Exception $e) {
            $conexion->rollBack();
            $mensaje = "<div style='color: red; background: #ffe6e6; padding: 10px; border-radius: 5px; margin-bottom: 15px;'>Error al guardar: " . $e->getMessage() . "</div>";
        }
    } else {
        $mensaje = "<div style='color: orange;'>Por favor, complete los campos necesarios.</div>";
    }
}


try {
    $stmt_carreras = $conexion->query("SELECT id_carrera, nombre_carrera FROM carreras ORDER BY nombre_carrera ASC");
    $lista_carreras = $stmt_carreras->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $mensaje = "<div style='color: red;'>Error al cargar las carreras: " . $e->getMessage() . "</div>";
}
?>
