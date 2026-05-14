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

// B. Obtener Cursos Asignados
$stmt_cursos = $conexion->prepare("
    SELECT c.nombre_curso, d.nombres AS docente_nomb, d.apellidos AS docente_ape, h.dia_semana, h.hora_inicio 
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
");
$stmt_tareas->execute([$id_estudiante]);
$tareas = $stmt_tareas->fetchAll(PDO::FETCH_ASSOC);

?>