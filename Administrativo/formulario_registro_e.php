<?php
require 'C:\laragon\www\proyecto\conexion.php';

try {

    $sql_carreras = "SELECT id_carrera, nombre_carrera FROM carreras ORDER BY nombre_carrera ASC";
    $stmt_carreras = $conexion->prepare($sql_carreras);
    $stmt_carreras->execute();

    $lista_carreras = $stmt_carreras->fetchAll(PDO::FETCH_ASSOC);
    
} catch(PDOException $e) {
    echo "Error al cargar las carreras: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Estudiantes - Plataforma Académica</title>
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
            <h2>Registrar Nuevo Estudiante</h2>
            <p>Ingresa los datos personales y asigna la carrera a la que aplicará el nuevo alumno.</p>
        </div>

        <div class="kpi-card form-card">
            <form action="registrar_estudiantes.php" method="POST">

                <div class="form-group">
                    <label for="nombres" class="form-label">Nombres:</label>
                    <input type="text" id="nombres" name="nombres" class="form-input" required placeholder="Nombres del estudiante">
                </div>

                <div class="form-group">
                    <label for="apellidos" class="form-label">Apellidos:</label>
                    <input type="text" id="apellidos" name="apellidos" class="form-input" required placeholder="Apellidos del estudiante">
                </div>

                <div class="form-group">
                    <label for="correo" class="form-label">Correo Electrónico:</label>
                    <input type="email" id="correo" name="correo" class="form-input" placeholder="Ej. estudiante@universidad.edu.gt" required>
                </div>
                
                <div class="form-group">
                    <label for="contraseña" class="form-label">Contraseña de acceso:</label>
                    <input type="password" id="contraseña" name="contraseña" class="form-input" required placeholder="Asigna una contraseña segura">
                </div>

                <div class="form-group">
                    <label for="id_carrera" class="form-label">Carrera a la que aplica:</label>
                    <select name="id_carrera" id="id_carrera" class="form-input" required>
                        <option value="" disabled selected>-- Seleccione una carrera --</option>
                        <?php
                        if (!empty($lista_carreras)) {
                            foreach ($lista_carreras as $carrera) {
                                echo "<option value='" . htmlspecialchars($carrera['id_carrera']) . "'>";
                                echo htmlspecialchars($carrera['nombre_carrera']);
                                echo "</option>";
                            }
                        } else {
                            echo "<option value='' disabled>No hay carreras registradas</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-acciones">
                    <button type="submit" class="btn-primario">Guardar nuevo estudiante</button>
                    <a href="inicio_admin.php" class="btn-secundario">Cancelar</a>
                </div>
                
            </form>
        </div>

    </main>

    <?php require 'footer.php'; ?>

    <script src="script_admin.js?v=<?php echo time(); ?>"></script>
</body>
</html>