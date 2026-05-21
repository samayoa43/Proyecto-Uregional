-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         8.4.3 - MySQL Community Server - GPL
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para proyecto
CREATE DATABASE IF NOT EXISTS `proyecto` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `proyecto`;

-- Volcando estructura para tabla proyecto.administrativo
CREATE TABLE IF NOT EXISTS `administrativo` (
  `id_personal` int NOT NULL AUTO_INCREMENT,
  `nombres` varchar(60) NOT NULL,
  `apellidos` varchar(60) NOT NULL,
  `id_usuario` int DEFAULT NULL,
  PRIMARY KEY (`id_personal`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `administrativo_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla proyecto.administrativo: ~0 rows (aproximadamente)
INSERT INTO `administrativo` (`id_personal`, `nombres`, `apellidos`, `id_usuario`) VALUES
	(1, 'Admin', 'Principal', 1);

-- Volcando estructura para tabla proyecto.anuncios
CREATE TABLE IF NOT EXISTS `anuncios` (
  `id_anuncio` int NOT NULL AUTO_INCREMENT,
  `id_autor` int NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `mensaje` text NOT NULL,
  `audiencia` enum('Todos','Docentes','Estudiantes','Curso_Especifico') NOT NULL,
  `id_curso_destino` int DEFAULT NULL,
  `fecha_publicacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_anuncio`),
  KEY `id_autor` (`id_autor`),
  KEY `id_curso_destino` (`id_curso_destino`),
  CONSTRAINT `anuncios_ibfk_1` FOREIGN KEY (`id_autor`) REFERENCES `usuarios` (`id_usuario`),
  CONSTRAINT `anuncios_ibfk_2` FOREIGN KEY (`id_curso_destino`) REFERENCES `cursos` (`id_curso`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla proyecto.anuncios: ~0 rows (aproximadamente)
INSERT INTO `anuncios` (`id_anuncio`, `id_autor`, `titulo`, `mensaje`, `audiencia`, `id_curso_destino`, `fecha_publicacion`) VALUES
	(1, 1, 'SUSPENSIÓN DE ACTIVIDADES ACADÉMICAS POR ASUETO NACIONAL', 'COMUNICADO OFICIAL\r\n\r\nLa Administración Universitaria informa a toda la comunidad estudiantil, docente y administrativa que el próximo día de clases será suspendido debido a asueto nacional.\r\n\r\nFecha: 23/05/2026\r\nLas actividades académicas y administrativas se reanudarán en el horario habitual el siguiente día hábil.\r\n\r\nAgradecemos su atención y les invitamos a mantenerse informados a través de los canales oficiales de la universidad.\r\n\r\nAtentamente,\r\nAdministración Universitaria', 'Todos', NULL, '2026-05-21 00:33:22');

-- Volcando estructura para tabla proyecto.asignaciones
CREATE TABLE IF NOT EXISTS `asignaciones` (
  `id_asignacion_a` int NOT NULL AUTO_INCREMENT,
  `id_estudiante` int NOT NULL,
  `id_asignacion` int NOT NULL,
  `id_ciclo` int NOT NULL,
  `fecha_asignacion` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_asignacion_a`),
  KEY `id_estudiante` (`id_estudiante`),
  KEY `id_asignacion` (`id_asignacion`),
  KEY `fk_asignacion_ciclo` (`id_ciclo`),
  CONSTRAINT `asignaciones_ibfk_1` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id_estudiante`),
  CONSTRAINT `asignaciones_ibfk_2` FOREIGN KEY (`id_asignacion`) REFERENCES `asignaciones_docentes` (`id_asignacion`),
  CONSTRAINT `fk_asignacion_ciclo` FOREIGN KEY (`id_ciclo`) REFERENCES `ciclos_academicos` (`id_ciclo`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla proyecto.asignaciones: ~0 rows (aproximadamente)
INSERT INTO `asignaciones` (`id_asignacion_a`, `id_estudiante`, `id_asignacion`, `id_ciclo`, `fecha_asignacion`) VALUES
	(1, 1, 1, 1, '2026-05-20 17:26:48'),
	(2, 1, 3, 1, '2026-05-20 17:26:52'),
	(3, 1, 4, 1, '2026-05-20 17:26:56'),
	(4, 1, 5, 1, '2026-05-20 17:26:58'),
	(5, 1, 2, 1, '2026-05-20 17:27:00');

-- Volcando estructura para tabla proyecto.asignaciones_docentes
CREATE TABLE IF NOT EXISTS `asignaciones_docentes` (
  `id_asignacion` int NOT NULL AUTO_INCREMENT,
  `id_docente` int NOT NULL,
  `id_curso` int NOT NULL,
  PRIMARY KEY (`id_asignacion`),
  KEY `id_docente` (`id_docente`),
  KEY `id_curso` (`id_curso`),
  CONSTRAINT `asignaciones_docentes_ibfk_1` FOREIGN KEY (`id_docente`) REFERENCES `docentes` (`id_docente`),
  CONSTRAINT `asignaciones_docentes_ibfk_2` FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`id_curso`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla proyecto.asignaciones_docentes: ~0 rows (aproximadamente)
INSERT INTO `asignaciones_docentes` (`id_asignacion`, `id_docente`, `id_curso`) VALUES
	(1, 1, 1),
	(2, 1, 2),
	(3, 1, 4),
	(4, 1, 3),
	(5, 1, 5);

-- Volcando estructura para tabla proyecto.asistencia
CREATE TABLE IF NOT EXISTS `asistencia` (
  `id_asistencia` int NOT NULL AUTO_INCREMENT,
  `id_estudiante` int DEFAULT NULL,
  `id_curso` int DEFAULT NULL,
  `estado` enum('Asistente','Falta','permiso') DEFAULT NULL,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_asistencia`),
  UNIQUE KEY `id_estudiante` (`id_estudiante`,`fecha`),
  UNIQUE KEY `id_estudiante_2` (`id_estudiante`,`id_curso`,`fecha`),
  KEY `id_curso` (`id_curso`),
  CONSTRAINT `asistencia_ibfk_1` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id_estudiante`),
  CONSTRAINT `asistencia_ibfk_2` FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`id_curso`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla proyecto.asistencia: ~0 rows (aproximadamente)
INSERT INTO `asistencia` (`id_asistencia`, `id_estudiante`, `id_curso`, `estado`, `fecha`) VALUES
	(1, 1, 1, 'Asistente', '2026-05-21 06:00:00');

-- Volcando estructura para tabla proyecto.calificaciones
CREATE TABLE IF NOT EXISTS `calificaciones` (
  `id_calificacion` int NOT NULL AUTO_INCREMENT,
  `id_estudiante` int DEFAULT NULL,
  `id_curso` int DEFAULT NULL,
  `nota` decimal(5,2) DEFAULT NULL,
  `nota2` decimal(5,2) DEFAULT NULL,
  `nota3` decimal(5,2) DEFAULT NULL,
  `nota_final` decimal(5,2) DEFAULT NULL,
  PRIMARY KEY (`id_calificacion`),
  UNIQUE KEY `id_estudiante` (`id_estudiante`,`id_calificacion`) USING BTREE,
  UNIQUE KEY `id_estudiante_2` (`id_estudiante`,`id_curso`),
  KEY `id_curso` (`id_curso`),
  CONSTRAINT `calificaciones_ibfk_1` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id_estudiante`),
  CONSTRAINT `calificaciones_ibfk_2` FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`id_curso`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla proyecto.calificaciones: ~0 rows (aproximadamente)
INSERT INTO `calificaciones` (`id_calificacion`, `id_estudiante`, `id_curso`, `nota`, `nota2`, `nota3`, `nota_final`) VALUES
	(1, 1, 1, 22.00, 0.00, 0.00, 22.00),
	(2, 1, 2, 29.00, 0.00, 0.00, 29.00);

-- Volcando estructura para tabla proyecto.carreras
CREATE TABLE IF NOT EXISTS `carreras` (
  `id_carrera` int NOT NULL AUTO_INCREMENT,
  `nombre_carrera` varchar(100) NOT NULL,
  PRIMARY KEY (`id_carrera`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla proyecto.carreras: ~0 rows (aproximadamente)
INSERT INTO `carreras` (`id_carrera`, `nombre_carrera`) VALUES
	(1, 'Administración de Sistemas Informáticos ');

-- Volcando estructura para tabla proyecto.ciclos_academicos
CREATE TABLE IF NOT EXISTS `ciclos_academicos` (
  `id_ciclo` int NOT NULL AUTO_INCREMENT,
  `nombre_ciclo` varchar(50) NOT NULL,
  `estado` enum('Activo','Cerrado') DEFAULT 'Activo',
  `asignaciones_abiertas` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id_ciclo`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla proyecto.ciclos_academicos: ~0 rows (aproximadamente)
INSERT INTO `ciclos_academicos` (`id_ciclo`, `nombre_ciclo`, `estado`, `asignaciones_abiertas`) VALUES
	(1, 'Primer Semestre 2026', 'Activo', 1);

-- Volcando estructura para tabla proyecto.cursos
CREATE TABLE IF NOT EXISTS `cursos` (
  `id_curso` int NOT NULL AUTO_INCREMENT,
  `nombre_curso` varchar(100) NOT NULL,
  PRIMARY KEY (`id_curso`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla proyecto.cursos: ~0 rows (aproximadamente)
INSERT INTO `cursos` (`id_curso`, `nombre_curso`) VALUES
	(1, 'Comunicación y Redacción'),
	(2, 'Tecnicas de Estudio e Investigacion'),
	(3, 'Matemáticas I'),
	(4, 'Informatica'),
	(5, 'Procesos y Algoritmos I');

-- Volcando estructura para tabla proyecto.docentes
CREATE TABLE IF NOT EXISTS `docentes` (
  `id_docente` int NOT NULL AUTO_INCREMENT,
  `nombres` varchar(60) NOT NULL,
  `apellidos` varchar(60) NOT NULL,
  `id_usuario` int DEFAULT NULL,
  PRIMARY KEY (`id_docente`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `docentes_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla proyecto.docentes: ~0 rows (aproximadamente)
INSERT INTO `docentes` (`id_docente`, `nombres`, `apellidos`, `id_usuario`) VALUES
	(1, 'Juan Alejandro', 'Pérez Hernández', 2),
	(2, 'Francisco Fernando', 'Silvamilion Clover', 4);

-- Volcando estructura para tabla proyecto.encuestas
CREATE TABLE IF NOT EXISTS `encuestas` (
  `id_encuesta` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(150) NOT NULL,
  `descripcion` text,
  `estado` enum('Activa','Cerrada') DEFAULT 'Activa',
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_encuesta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla proyecto.encuestas: ~0 rows (aproximadamente)

-- Volcando estructura para tabla proyecto.entregas_tareas
CREATE TABLE IF NOT EXISTS `entregas_tareas` (
  `id_entrega` int NOT NULL AUTO_INCREMENT,
  `id_tarea` int NOT NULL,
  `id_estudiante` int NOT NULL,
  `archivo_ruta` varchar(255) NOT NULL,
  `comentarios_estudiante` text,
  `calificacion` decimal(5,2) DEFAULT NULL,
  `retroalimentacion_docente` text,
  `fecha_entrega` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_entrega`),
  KEY `fk_entrega_tarea` (`id_tarea`),
  KEY `fk_entrega_estudiante` (`id_estudiante`),
  CONSTRAINT `fk_entrega_estudiante` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id_estudiante`) ON DELETE CASCADE,
  CONSTRAINT `fk_entrega_tarea` FOREIGN KEY (`id_tarea`) REFERENCES `tareas` (`id_tarea`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla proyecto.entregas_tareas: ~0 rows (aproximadamente)

-- Volcando estructura para tabla proyecto.estudiantes
CREATE TABLE IF NOT EXISTS `estudiantes` (
  `id_estudiante` int NOT NULL AUTO_INCREMENT,
  `nombres` varchar(60) NOT NULL,
  `apellidos` varchar(60) NOT NULL,
  `id_carrera` int DEFAULT NULL,
  `id_usuario` int DEFAULT NULL,
  PRIMARY KEY (`id_estudiante`),
  KEY `id_carrera` (`id_carrera`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `estudiantes_ibfk_1` FOREIGN KEY (`id_carrera`) REFERENCES `carreras` (`id_carrera`),
  CONSTRAINT `estudiantes_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla proyecto.estudiantes: ~2 rows (aproximadamente)
INSERT INTO `estudiantes` (`id_estudiante`, `nombres`, `apellidos`, `id_carrera`, `id_usuario`) VALUES
	(1, 'Ricardo Jose', 'Milos Pérez', 1, 3),
	(2, 'Pedro Jose', 'Martínez López', 1, 5);

-- Volcando estructura para tabla proyecto.horarios
CREATE TABLE IF NOT EXISTS `horarios` (
  `id_horario` int NOT NULL AUTO_INCREMENT,
  `id_asignacion` int NOT NULL,
  `dia_semana` enum('Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo') NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  PRIMARY KEY (`id_horario`),
  KEY `id_asignacion` (`id_asignacion`),
  CONSTRAINT `horarios_ibfk_1` FOREIGN KEY (`id_asignacion`) REFERENCES `asignaciones_docentes` (`id_asignacion`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla proyecto.horarios: ~0 rows (aproximadamente)
INSERT INTO `horarios` (`id_horario`, `id_asignacion`, `dia_semana`, `hora_inicio`, `hora_fin`) VALUES
	(1, 1, 'Sábado', '08:00:00', '09:30:00'),
	(2, 3, 'Sábado', '10:00:00', '11:30:00'),
	(3, 4, 'Sábado', '11:30:00', '13:00:00'),
	(4, 5, 'Sábado', '14:00:00', '15:30:00'),
	(5, 2, 'Sábado', '15:30:00', '17:00:00');

-- Volcando estructura para tabla proyecto.pagos
CREATE TABLE IF NOT EXISTS `pagos` (
  `id_pago` int NOT NULL AUTO_INCREMENT,
  `id_estudiante` int NOT NULL,
  `mes_pagado` enum('Inscripción S1','Febrero','Marzo','Abril','Mayo','Junio','Inscripción S2','Julio','Agosto','Septiembre','Octubre','Noviembre') NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `fecha_pago` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `numero_boleta` varchar(50) NOT NULL,
  PRIMARY KEY (`id_pago`),
  UNIQUE KEY `único_pago_mes` (`id_estudiante`,`mes_pagado`),
  CONSTRAINT `fk_pago_estudiante` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id_estudiante`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla proyecto.pagos: ~0 rows (aproximadamente)
INSERT INTO `pagos` (`id_pago`, `id_estudiante`, `mes_pagado`, `monto`, `fecha_pago`, `numero_boleta`) VALUES
	(1, 1, 'Inscripción S1', 400.00, '2026-05-20 23:17:36', 'pi_3TZJMmHZHiFFFbZG1YjPF7rW'),
	(2, 1, 'Febrero', 400.00, '2026-05-20 23:22:21', '1E8806770R5239224'),
	(3, 1, 'Marzo', 400.00, '2026-05-20 23:23:42', '2T462603KX669384U'),
	(4, 1, 'Abril', 400.00, '2026-05-20 23:23:42', '2T462603KX669384U'),
	(5, 1, 'Mayo', 400.00, '2026-05-20 23:23:42', '2T462603KX669384U'),
	(6, 1, 'Junio', 400.00, '2026-05-20 23:23:42', '2T462603KX669384U');

-- Volcando estructura para tabla proyecto.pensum
CREATE TABLE IF NOT EXISTS `pensum` (
  `id_pensum` int NOT NULL AUTO_INCREMENT,
  `id_carrera` int DEFAULT NULL,
  `id_curso` int DEFAULT NULL,
  `semestre` int DEFAULT NULL,
  PRIMARY KEY (`id_pensum`),
  KEY `id_carrera` (`id_carrera`),
  KEY `id_curso` (`id_curso`),
  CONSTRAINT `pensum_ibfk_1` FOREIGN KEY (`id_carrera`) REFERENCES `carreras` (`id_carrera`),
  CONSTRAINT `pensum_ibfk_2` FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`id_curso`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla proyecto.pensum: ~0 rows (aproximadamente)
INSERT INTO `pensum` (`id_pensum`, `id_carrera`, `id_curso`, `semestre`) VALUES
	(1, 1, 1, 1),
	(2, 1, 2, 1),
	(3, 1, 3, 1),
	(4, 1, 4, 1),
	(5, 1, 5, 1);

-- Volcando estructura para tabla proyecto.preguntas_encuesta
CREATE TABLE IF NOT EXISTS `preguntas_encuesta` (
  `id_pregunta` int NOT NULL AUTO_INCREMENT,
  `id_encuesta` int NOT NULL,
  `pregunta` text NOT NULL,
  `tipo_respuesta` enum('Texto_Libre','Escala_1_a_5') DEFAULT 'Texto_Libre',
  PRIMARY KEY (`id_pregunta`),
  KEY `id_encuesta` (`id_encuesta`),
  CONSTRAINT `fk_encuesta_pregunta` FOREIGN KEY (`id_encuesta`) REFERENCES `encuestas` (`id_encuesta`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla proyecto.preguntas_encuesta: ~0 rows (aproximadamente)

-- Volcando estructura para tabla proyecto.prerrequisitos
CREATE TABLE IF NOT EXISTS `prerrequisitos` (
  `id_curso` int NOT NULL,
  `id_curso_previo` int NOT NULL,
  PRIMARY KEY (`id_curso`,`id_curso_previo`),
  KEY `fk_curso_requerido` (`id_curso_previo`),
  CONSTRAINT `fk_curso_actual` FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`id_curso`) ON DELETE CASCADE,
  CONSTRAINT `fk_curso_requerido` FOREIGN KEY (`id_curso_previo`) REFERENCES `cursos` (`id_curso`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla proyecto.prerrequisitos: ~0 rows (aproximadamente)

-- Volcando estructura para tabla proyecto.respuestas_encuesta
CREATE TABLE IF NOT EXISTS `respuestas_encuesta` (
  `id_respuesta` int NOT NULL AUTO_INCREMENT,
  `id_pregunta` int NOT NULL,
  `id_estudiante` int NOT NULL,
  `valor_respuesta` text NOT NULL,
  `fecha_respuesta` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_respuesta`),
  KEY `id_pregunta` (`id_pregunta`),
  KEY `id_estudiante` (`id_estudiante`),
  CONSTRAINT `fk_respuesta_estudiante` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id_estudiante`) ON DELETE CASCADE,
  CONSTRAINT `fk_respuesta_pregunta` FOREIGN KEY (`id_pregunta`) REFERENCES `preguntas_encuesta` (`id_pregunta`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla proyecto.respuestas_encuesta: ~0 rows (aproximadamente)

-- Volcando estructura para tabla proyecto.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id_rol` int NOT NULL AUTO_INCREMENT,
  `nombre_rol` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_rol`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla proyecto.roles: ~3 rows (aproximadamente)
INSERT INTO `roles` (`id_rol`, `nombre_rol`) VALUES
	(1, 'admin'),
	(2, 'docente'),
	(3, 'estudiante');

-- Volcando estructura para tabla proyecto.tareas
CREATE TABLE IF NOT EXISTS `tareas` (
  `id_tarea` int NOT NULL AUTO_INCREMENT,
  `id_curso` int NOT NULL,
  `id_docente` int NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `descripcion` text NOT NULL,
  `fecha_limite` datetime NOT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_tarea`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla proyecto.tareas: ~0 rows (aproximadamente)

-- Volcando estructura para tabla proyecto.tickets_soporte
CREATE TABLE IF NOT EXISTS `tickets_soporte` (
  `id_ticket` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `asunto` varchar(150) NOT NULL,
  `descripcion` text NOT NULL,
  `prioridad` enum('Baja','Media','Alta','Urgente') DEFAULT 'Media',
  `estado` enum('Abierto','En Proceso','Resuelto') DEFAULT 'Abierto',
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_ticket`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `tickets_soporte_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla proyecto.tickets_soporte: ~0 rows (aproximadamente)

-- Volcando estructura para tabla proyecto.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id_usuario` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `contraseña` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `estado` tinyint DEFAULT '1',
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `correo` (`correo`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla proyecto.usuarios: ~5 rows (aproximadamente)
INSERT INTO `usuarios` (`id_usuario`, `nombre`, `correo`, `contraseña`, `estado`) VALUES
	(1, 'Admin Principal', 'admin@admin.com', '$2y$10$id6cw8qYwtQvuocvrE8nDeTBT5u5LKtd9Ck1z/8I1hjU5Tyl.QbJO', 1),
	(2, 'Juan Alejandro Pérez Hernández', 'Docente@uni.com', '$2y$10$dmTYLAQM.MJVjqQBPF4PbumHp56ThkqOkIKEFlYxDHPpbUbryHCqu', 1),
	(3, 'Ricardo Jose Milos Pérez', 'Estudiante@uni.com', '$2y$10$wmHtiVav1jQzfL.m22jqUuEvcEo/.AKTCpt8Lui5WmIklDKBeJjRW', 1),
	(4, 'Francisco Fernando Silvamilion Clover', 'Fransilva@uni.com', '$2y$10$hgCkPd66OJhhgPro.t.fH.HuePwSdonilRv.rrvmwm/VBEQK1FoZC', 1),
	(5, 'Pedro Jose Martínez López', 'Pedrolop@uni.com', '$2y$10$ZYV3D1zO6TVmU.NwZrx.N.u8kgQwZmUGZilxSKvsnnbm5ckZ4lOhy', 1);

-- Volcando estructura para tabla proyecto.usuario_roles
CREATE TABLE IF NOT EXISTS `usuario_roles` (
  `id_usuario` int NOT NULL,
  `id_rol` int NOT NULL,
  PRIMARY KEY (`id_usuario`,`id_rol`),
  KEY `id_rol` (`id_rol`),
  CONSTRAINT `usuario_roles_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  CONSTRAINT `usuario_roles_ibfk_2` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla proyecto.usuario_roles: ~1 rows (aproximadamente)
INSERT INTO `usuario_roles` (`id_usuario`, `id_rol`) VALUES
	(1, 1),
	(2, 2),
	(4, 2),
	(3, 3),
	(5, 3);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
