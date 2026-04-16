<?php
session_start();
require '../conexion.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];

    try {
        $sql = "INSERT INTO encuestas (titulo, descripcion, estado) VALUES (?, ?, 'Activa')";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([$titulo, $descripcion]);

        $id_nueva_encuesta = $conexion->lastInsertId();

        header("Location: agregar_preguntas.php?id=" . $id_nueva_encuesta . "&exito=1");
        exit();

    } catch(PDOException $e) {
        echo "Error al crear la encuesta: " . $e->getMessage();
    }
} else {
    header("Location: crear_encuesta.php");
    exit();
}
?>