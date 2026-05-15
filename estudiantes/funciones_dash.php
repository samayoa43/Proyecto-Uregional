<?php

require '../conexion.php'; // Tu archivo de conexión PDO

// 3. Consultas a TU base de datos (Base_datos_f.sql)

// A. Obtener Notas (Usando tu diseño horizontal)
$stmt_notas = $conexion->prepare("
    SELECT c.nombre_curso, cal.nota, cal.nota2, cal.nota3, cal.nota_final 
    FROM calificaciones cal
    INNER JOIN cursos c ON cal.id_curso = c.id_curso
    WHERE cal.id_estudiante = ?
");
$stmt_notas->execute([$id_estudiante]);
$notas = $stmt_notas->fetchAll(PDO::FETCH_ASSOC);

// B. Obtener Cursos Asignados con Porcentaje de Asistencia
$stmt_cursos = $conexion->prepare("
    SELECT 
        c.id_curso,
        c.nombre_curso, 
        d.nombres AS docente_nomb, 
        d.apellidos AS docente_ape, 
        h.dia_semana, 
        h.hora_inicio,
        (SELECT 
            CASE 
                WHEN COUNT(*) > 0 THEN ROUND((SUM(CASE WHEN estado = 'Asistente' THEN 1 ELSE 0 END) / COUNT(*)) * 100, 0)
                ELSE 0 
            END
         FROM asistencia 
         WHERE id_estudiante = a.id_estudiante AND id_curso = ad.id_curso
        ) AS porcentaje_asistencia
    FROM asignaciones a
    INNER JOIN asignaciones_docentes ad ON a.id_asignacion = ad.id_asignacion
    INNER JOIN cursos c ON ad.id_curso = c.id_curso
    INNER JOIN docentes d ON ad.id_docente = d.id_docente
    LEFT JOIN horarios h ON ad.id_asignacion = h.id_asignacion
    WHERE a.id_estudiante = ?
");
$stmt_cursos->execute([$id_estudiante]);
$cursos = $stmt_cursos->fetchAll(PDO::FETCH_ASSOC);

// C. Obtener Pagos
$stmt_pagos = $conexion->prepare("SELECT mes_pagado, monto, fecha_pago, numero_boleta FROM pagos WHERE id_estudiante = ? ORDER BY fecha_pago DESC");
$stmt_pagos->execute([$id_estudiante]);
$pagos = $stmt_pagos->fetchAll(PDO::FETCH_ASSOC);

// D. Obtener Tareas Pendientes
$stmt_tareas = $conexion->prepare("
    SELECT t.id_tarea, t.titulo, t.descripcion, t.fecha_limite, c.nombre_curso
    FROM tareas t
    INNER JOIN asignaciones_docentes ad ON t.id_curso = ad.id_curso AND t.id_docente = ad.id_docente
    INNER JOIN asignaciones a ON ad.id_asignacion = a.id_asignacion
    INNER JOIN cursos c ON t.id_curso = c.id_curso
    WHERE a.id_estudiante = ?
    AND t.id_tarea NOT IN (
        SELECT id_tarea 
        FROM entregas_tareas 
        WHERE id_estudiante = ?
    )
");
// Pasamos el $id_estudiante dos veces porque la consulta ahora tiene dos signos de interrogación (?)
$stmt_tareas->execute([$id_estudiante, $id_estudiante]);
$tareas = $stmt_tareas->fetchAll(PDO::FETCH_ASSOC);

// E. Obtener Anuncios del Tablón
$stmt_anuncios = $conexion->prepare("
    SELECT a.titulo, a.mensaje, a.fecha_publicacion, u.nombre AS autor, c.nombre_curso
    FROM anuncios a
    INNER JOIN usuarios u ON a.id_autor = u.id_usuario
    LEFT JOIN cursos c ON a.id_curso_destino = c.id_curso
    WHERE a.audiencia IN ('Todos', 'Estudiantes')
       OR (a.audiencia = 'Curso_Especifico' AND a.id_curso_destino IN (
           SELECT ad.id_curso
           FROM asignaciones asig
           INNER JOIN asignaciones_docentes ad ON asig.id_asignacion = ad.id_asignacion
           WHERE asig.id_estudiante = ?
       ))
    ORDER BY a.fecha_publicacion DESC
    LIMIT 10
");
$stmt_anuncios->execute([$id_estudiante]);
$anuncios = $stmt_anuncios->fetchAll(PDO::FETCH_ASSOC);

?>