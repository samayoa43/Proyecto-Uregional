<?php 
require_once __DIR__ . '/validar_sesion_admin.php'; 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Docentes - Plataforma Académica</title>
    <!-- Conectamos tu CSS principal -->
    <link rel="stylesheet" href="estilo_administrativo.css?v=<?php echo time(); ?>">
</head>
<body>
    
    <!-- SCRIPT ANTI-PARPADEO PARA MODO OSCURO -->
    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.body.classList.add('dark-mode');
        }
    </script>

    <!-- Llamamos a tu plantilla maestra (Header Azul + Menú Lateral) de forma limpia -->
    <?php 
    $ruta_base = "../";
    require 'encabezado.php'; ?>

    <!-- CONTENEDOR PRINCIPAL -->
    <main class="main-container">
        
        <div class="section-header">
            <h2>Registrar Nuevo Docente</h2>
            <p>Ingresa los datos del nuevo catedrático para darle acceso al portal académico.</p>
        </div>

                <?php if (isset($_GET['exito'])): ?>
        <div class="alert alert-success">
        <span>✅</span>
        <div><strong>¡Operación exitosa!</strong> Docente registrado con éxito.</div>
        </div>
        <?php elseif (isset($_GET['error'])): ?>
        <div class="alert alert-danger">
        <span>⚠️</span>
        <div><strong>Error:</strong> Ocurrió un error al registrar el docente. Inténtalo de nuevo.</div>
        </div>
        <?php endif; ?>

        <!-- Envolvemos el formulario en nuestra tarjeta kpi-card y form-card -->
        <div class="kpi-card form-card">
            <form action="registrar_docentes.php" method="POST">

                <div class="form-group">
                    <label for="nombres" class="form-label">Nombres:</label>
                    <input type="text" id="nombres" name="nombres" class="form-input" required placeholder="Nombres del docente">
                </div>

                <div class="form-group">
                    <label for="apellidos" class="form-label">Apellidos:</label>
                    <input type="text" id="apellidos" name="apellidos" class="form-input" required placeholder="Apellidos del docente">
                </div>

                <div class="form-group">
                    <label for="correo" class="form-label">Correo Electrónico:</label>
                    <!-- Agregué el tipo email para que el navegador valide automáticamente que lleve un @ -->
                    <input type="email" id="correo" name="correo" class="form-input" required placeholder="ejemplo@universidad.edu.gt">
                </div>
                
                <div class="form-group">
                    <label for="contraseña" class="form-label">Contraseña de acceso:</label>
                    <input type="password" id="contraseña" name="contraseña" class="form-input" required placeholder="Asigna una contraseña segura">
                </div>

                <div class="form-acciones">
                    <button type="submit" class="btn-primario">Guardar nuevo docente</button>
                    <a href="inicio_admin.php" class="btn-secundario">Cancelar</a>
                </div>
                
            </form>
        </div>

    </main>

    <?php require 'footer.php'; ?>

    <script src="script_admin.js?v=<?php echo time(); ?>"></script>
</body>
</html>