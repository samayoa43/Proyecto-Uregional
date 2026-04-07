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
  `contraseña` varchar(255) NOT NULL,
  `correo` varchar(80) NOT NULL,
  PRIMARY KEY (`id_personal`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla proyecto.administrativo: ~1 rows (aproximadamente)
INSERT INTO `administrativo` (`id_personal`, `nombres`, `apellidos`, `contraseña`, `correo`) VALUES
	(1, 'juan', 'admin', '1234567', 'juanadmin@gmail.com');

-- Volcando estructura para tabla proyecto.asignaciones
CREATE TABLE IF NOT EXISTS `asignaciones` (
  `id_asignacion_a` int NOT NULL AUTO_INCREMENT,
  `id_estudiante` int NOT NULL,
  `id_asignacion` int NOT NULL,
  `fecha_asignacion` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_asignacion_a`),
  KEY `id_estudiante` (`id_estudiante`),
  KEY `id_asignacion` (`id_asignacion`),
  CONSTRAINT `asignaciones_ibfk_1` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id_estudiante`),
  CONSTRAINT `asignaciones_ibfk_2` FOREIGN KEY (`id_asignacion`) REFERENCES `asignaciones_docentes` (`id_asignacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla proyecto.asignaciones: ~0 rows (aproximadamente)

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

-- Volcando datos para la tabla proyecto.asignaciones_docentes: ~1 rows (aproximadamente)
INSERT INTO `asignaciones_docentes` (`id_asignacion`, `id_docente`, `id_curso`) VALUES
	(5, 2, 2);

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
) ENGINE=InnoDB AUTO_INCREMENT=166 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla proyecto.asistencia: ~48 rows (aproximadamente)
INSERT INTO `asistencia` (`id_asistencia`, `id_estudiante`, `id_curso`, `estado`, `fecha`) VALUES
	(1, 1, 2, 'Asistente', '2026-03-20 06:00:00'),
	(2, 2, 2, 'Asistente', '2026-03-20 06:00:00'),
	(3, 3, 2, 'Asistente', '2026-03-20 06:00:00'),
	(4, 4, 2, 'Asistente', '2026-03-20 06:00:00'),
	(5, 5, 2, 'Asistente', '2026-03-20 06:00:00'),
	(6, 6, 2, 'Asistente', '2026-03-20 06:00:00'),
	(7, 7, 2, 'Asistente', '2026-03-20 06:00:00'),
	(8, 8, 2, 'Falta', '2026-03-20 06:00:00'),
	(9, 9, 2, 'Asistente', '2026-03-20 06:00:00'),
	(10, 10, 2, 'permiso', '2026-03-20 06:00:00'),
	(11, 11, 2, 'Asistente', '2026-03-20 06:00:00'),
	(12, 12, 2, 'Asistente', '2026-03-20 06:00:00'),
	(13, 13, 2, 'Falta', '2026-03-20 06:00:00'),
	(14, 14, 2, 'Asistente', '2026-03-20 06:00:00'),
	(15, 15, 2, 'Asistente', '2026-03-20 06:00:00'),
	(16, 16, 2, 'Asistente', '2026-03-20 06:00:00'),
	(17, 17, 2, 'Asistente', '2026-03-20 06:00:00'),
	(18, 18, 2, 'Asistente', '2026-03-20 06:00:00'),
	(19, 19, 2, 'Asistente', '2026-03-20 06:00:00'),
	(20, 20, 2, 'Asistente', '2026-03-20 06:00:00'),
	(21, 21, 2, 'Asistente', '2026-03-20 06:00:00'),
	(22, 22, 2, 'Asistente', '2026-03-20 06:00:00'),
	(23, 23, 2, 'Asistente', '2026-03-20 06:00:00'),
	(24, 24, 2, 'Asistente', '2026-03-20 06:00:00'),
	(25, 25, 2, 'Asistente', '2026-03-20 06:00:00'),
	(26, 26, 2, 'Asistente', '2026-03-20 06:00:00'),
	(27, 27, 2, 'Asistente', '2026-03-20 06:00:00'),
	(28, 28, 2, 'Asistente', '2026-03-20 06:00:00'),
	(29, 29, 2, 'Asistente', '2026-03-20 06:00:00'),
	(30, 30, 2, 'Asistente', '2026-03-20 06:00:00'),
	(31, 31, 2, 'Asistente', '2026-03-20 06:00:00'),
	(32, 32, 2, 'Asistente', '2026-03-20 06:00:00'),
	(33, 33, 2, 'Asistente', '2026-03-20 06:00:00'),
	(100, 1, 2, 'Falta', '2026-03-21 06:00:00'),
	(101, 2, 2, 'Asistente', '2026-03-21 06:00:00'),
	(102, 3, 2, 'Asistente', '2026-03-21 06:00:00'),
	(103, 4, 2, 'Falta', '2026-03-21 06:00:00'),
	(104, 5, 2, 'Asistente', '2026-03-21 06:00:00'),
	(105, 6, 2, 'Asistente', '2026-03-21 06:00:00'),
	(106, 7, 2, 'Asistente', '2026-03-21 06:00:00'),
	(107, 8, 2, 'Asistente', '2026-03-21 06:00:00'),
	(108, 9, 2, 'Falta', '2026-03-21 06:00:00'),
	(109, 10, 2, 'Asistente', '2026-03-21 06:00:00'),
	(110, 11, 2, 'Asistente', '2026-03-21 06:00:00'),
	(111, 12, 2, 'Asistente', '2026-03-21 06:00:00'),
	(112, 13, 2, 'Asistente', '2026-03-21 06:00:00'),
	(113, 14, 2, 'Asistente', '2026-03-21 06:00:00'),
	(114, 15, 2, 'Asistente', '2026-03-21 06:00:00'),
	(115, 16, 2, 'Asistente', '2026-03-21 06:00:00'),
	(116, 17, 2, 'Asistente', '2026-03-21 06:00:00'),
	(117, 18, 2, 'Asistente', '2026-03-21 06:00:00'),
	(118, 19, 2, 'Asistente', '2026-03-21 06:00:00'),
	(119, 20, 2, 'Asistente', '2026-03-21 06:00:00'),
	(120, 21, 2, 'Asistente', '2026-03-21 06:00:00'),
	(121, 22, 2, 'Asistente', '2026-03-21 06:00:00'),
	(122, 23, 2, 'Asistente', '2026-03-21 06:00:00'),
	(123, 24, 2, 'Asistente', '2026-03-21 06:00:00'),
	(124, 25, 2, 'Asistente', '2026-03-21 06:00:00'),
	(125, 26, 2, 'Asistente', '2026-03-21 06:00:00'),
	(126, 27, 2, 'Asistente', '2026-03-21 06:00:00'),
	(127, 28, 2, 'Asistente', '2026-03-21 06:00:00'),
	(128, 29, 2, 'Asistente', '2026-03-21 06:00:00'),
	(129, 30, 2, 'Asistente', '2026-03-21 06:00:00'),
	(130, 31, 2, 'Asistente', '2026-03-21 06:00:00'),
	(131, 32, 2, 'Asistente', '2026-03-21 06:00:00'),
	(132, 33, 2, 'Asistente', '2026-03-21 06:00:00');

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
  KEY `id_curso` (`id_curso`),
  CONSTRAINT `calificaciones_ibfk_1` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id_estudiante`),
  CONSTRAINT `calificaciones_ibfk_2` FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`id_curso`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla proyecto.calificaciones: ~33 rows (aproximadamente)
INSERT INTO `calificaciones` (`id_calificacion`, `id_estudiante`, `id_curso`, `nota`, `nota2`, `nota3`, `nota_final`) VALUES
	(1, 1, 2, 18.00, 0.00, 0.00, 18.00),
	(2, 2, 2, 21.00, 0.00, 0.00, 21.00),
	(3, 3, 2, 21.00, 0.00, 0.00, 21.00),
	(4, 4, 2, 21.00, 0.00, 0.00, 21.00),
	(5, 5, 2, 21.00, 0.00, 0.00, 21.00),
	(6, 6, 2, 21.00, 0.00, 0.00, 21.00),
	(7, 7, 2, 21.00, 0.00, 0.00, 21.00),
	(8, 8, 2, 21.00, 0.00, 0.00, 21.00),
	(9, 9, 2, 21.00, 0.00, 0.00, 21.00),
	(10, 10, 2, 12.00, 0.00, 0.00, 12.00),
	(11, 11, 2, 21.00, 0.00, 0.00, 21.00),
	(12, 12, 2, 21.00, 0.00, 0.00, 21.00),
	(13, 13, 2, 21.00, 0.00, 0.00, 21.00),
	(14, 14, 2, 21.00, 0.00, 0.00, 21.00),
	(15, 15, 2, 21.00, 0.00, 0.00, 21.00),
	(16, 16, 2, 21.00, 0.00, 0.00, 21.00),
	(17, 17, 2, 21.00, 0.00, 0.00, 21.00),
	(18, 18, 2, 21.00, 0.00, 0.00, 21.00),
	(19, 19, 2, 12.00, 0.00, 0.00, 12.00),
	(20, 20, 2, 30.00, 0.00, 0.00, 30.00),
	(21, 21, 2, 30.00, 0.00, 0.00, 30.00),
	(22, 22, 2, 22.00, 0.00, 0.00, 22.00),
	(23, 23, 2, 22.00, 0.00, 0.00, 22.00),
	(24, 24, 2, 22.00, 0.00, 0.00, 22.00),
	(25, 25, 2, 22.00, 0.00, 0.00, 22.00),
	(26, 26, 2, 22.00, 0.00, 0.00, 22.00),
	(27, 27, 2, 22.00, 0.00, 0.00, 22.00),
	(28, 28, 2, 22.00, 0.00, 0.00, 22.00),
	(29, 29, 2, 22.00, 0.00, 0.00, 22.00),
	(30, 30, 2, 22.00, 0.00, 0.00, 22.00),
	(31, 31, 2, 22.00, 0.00, 0.00, 22.00),
	(32, 32, 2, 22.00, 0.00, 0.00, 22.00),
	(33, 33, 2, 22.00, 0.00, 0.00, 22.00);

-- Volcando estructura para tabla proyecto.carreras
CREATE TABLE IF NOT EXISTS `carreras` (
  `id_carrera` int NOT NULL AUTO_INCREMENT,
  `nombre_carrera` varchar(100) NOT NULL,
  PRIMARY KEY (`id_carrera`)
) ENGINE=InnoDB AUTO_INCREMENT=204 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla proyecto.carreras: ~0 rows (aproximadamente)
INSERT INTO `carreras` (`id_carrera`, `nombre_carrera`) VALUES
	(203, 'Administracion de Sistemas Informaticos');

-- Volcando estructura para tabla proyecto.cursos
CREATE TABLE IF NOT EXISTS `cursos` (
  `id_curso` int NOT NULL AUTO_INCREMENT,
  `nombre_curso` varchar(100) NOT NULL,
  `id_carrera` int DEFAULT NULL,
  PRIMARY KEY (`id_curso`),
  KEY `id_carrera` (`id_carrera`),
  CONSTRAINT `cursos_ibfk_1` FOREIGN KEY (`id_carrera`) REFERENCES `carreras` (`id_carrera`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla proyecto.cursos: ~0 rows (aproximadamente)
INSERT INTO `cursos` (`id_curso`, `nombre_curso`, `id_carrera`) VALUES
	(1, 'proyecto', 203),
	(2, 'Inteligencia Artificial', 203);

-- Volcando estructura para tabla proyecto.docentes
CREATE TABLE IF NOT EXISTS `docentes` (
  `id_docente` int NOT NULL AUTO_INCREMENT,
  `nombres` varchar(60) NOT NULL,
  `apellidos` varchar(60) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `correo` varchar(80) NOT NULL,
  PRIMARY KEY (`id_docente`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla proyecto.docentes: ~1 rows (aproximadamente)
INSERT INTO `docentes` (`id_docente`, `nombres`, `apellidos`, `contraseña`, `correo`) VALUES
	(2, 'pedro', 'simil', '1234567', 'pedrosimi@gmail.com');

-- Volcando estructura para tabla proyecto.estudiantes
CREATE TABLE IF NOT EXISTS `estudiantes` (
  `id_estudiante` int NOT NULL AUTO_INCREMENT,
  `nombres` varchar(60) NOT NULL,
  `apellidos` varchar(60) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `id_carrera` int DEFAULT NULL,
  `correo` varchar(80) NOT NULL,
  PRIMARY KEY (`id_estudiante`),
  KEY `id_carrera` (`id_carrera`),
  CONSTRAINT `estudiantes_ibfk_1` FOREIGN KEY (`id_carrera`) REFERENCES `carreras` (`id_carrera`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla proyecto.estudiantes: ~33 rows (aproximadamente)
INSERT INTO `estudiantes` (`id_estudiante`, `nombres`, `apellidos`, `contraseña`, `id_carrera`, `correo`) VALUES
	(1, 'juan', 'Ejemplo', '123456789', 203, 'ejemplocorreo@gmail.com'),
	(2, 'Cesar', 'Silvamilion', 'jksjkdbjdsbjdbjasdbkjds', 203, 'ejemplocorreo@gmail.com'),
	(3, 'Ricardo ', 'Milos', 'jahajhajahjaha', 203, 'ejemplocorreo@gmail.com'),
	(4, 'Juan Carlos', 'Pérez Gómez', '$2y$10$e0MYzXy5W.P', 203, 'juan.perez@example.com'),
	(5, 'María Elena', 'Rodríguez Paz', '$2y$10$k8NZnYp7Q.L', 203, 'm.rodriguez@example.com'),
	(6, 'Luis Alberto', 'García López', '$2y$10$v2LKmRj4W.M', 203, 'luis.garcia@example.com'),
	(7, 'Ana Lucía', 'Méndez Ruiz', '$2y$10$a9XPlTq2S.K', 203, 'ana.mendez@example.com'),
	(8, 'Carlos Estuardo', 'Morales Sosa', '$2y$10$m3RJnBv8D.P', 203, 'carlos.morales@example.com'),
	(9, 'Sofía Isabel', 'Castillo Oro', '$2y$10$f5TKmWx1Z.Q', 203, 'sofia.castillo@example.com'),
	(10, 'Diego Alejandro', 'Ramírez Ven', '$2y$10$q1NVbPz6X.R', 203, 'diego.ramirez@example.com'),
	(11, 'Claudia María', 'Herrera Gil', '$2y$10$s7LMfGh3C.V', 203, 'claudia.herrera@example.com'),
	(12, 'Fernando José', 'Álvarez San', '$2y$10$h9BVCxZ2N.M', 203, 'fernando.alvarez@example.com'),
	(13, 'Karla Jimena', 'Díaz Valdéz', '$2y$10$y4KLmNp9Q.W', 203, 'karla.diaz@example.com'),
	(14, 'Ricardo Andrés', 'Solares Rey', '$2y$10$j8DFgHj5K.L', 203, 'ricardo.solares@example.com'),
	(15, 'Gabriela Fernanda', 'Mazariegos', '$2y$10$p2ZXcVb3N.M', 203, 'gaby.maza@example.com'),
	(16, 'Roberto Antonio', 'Luna Pineda', '$2y$10$w6QWErt7Y.U', 203, 'roberto.luna@example.com'),
	(17, 'Paola Michelle', 'Estrada Lux', '$2y$10$e4RTYui8O.P', 203, 'paola.estrada@example.com'),
	(18, 'Sergio Danilo', 'Cabrera Sol', '$2y$10$r1TYUio9P.A', 203, 'sergio.cabrera@example.com'),
	(19, 'Mónica Beatriz', 'Juárez Mar', '$2y$10$t2GHJkl4S.D', 203, 'monica.juarez@example.com'),
	(20, 'Jorge Mario', 'Orantes Paz', '$2y$10$y3XCVbn5M.Q', 203, 'jorge.orantes@example.com'),
	(21, 'Valeria Inés', 'Sandoval Te', '$2y$10$u7JKLmn8B.V', 203, 'valeria.sandoval@example.com'),
	(22, 'Hugo Rolando', 'Cifuentes Go', '$2y$10$i9OPQwe1R.T', 203, 'hugo.cifuentes@example.com'),
	(23, 'Andrea Celeste', 'Palacios Ri', '$2y$10$o0ASDfg2H.J', 203, 'andrea.palacios@example.com'),
	(24, 'Manuel Enrique', 'Girón Tello', '$2y$10$l1ZXCvb3N.K', 203, 'manuel.giron@example.com'),
	(25, 'Jessica Ivonne', 'Reyes Cano', '$2y$10$k2WERty4U.I', 203, 'jessica.reyes@example.com'),
	(26, 'Francisco Javier', 'Mejía Lara', '$2y$10$j3SDFgh5J.K', 203, 'javier.mejia@example.com'),
	(27, 'Lorena Abigail', 'González Pe', '$2y$10$h4XCVbn6M.L', 203, 'lorena.gonzalez@example.com'),
	(28, 'Cristian David', 'Vásquez Al', '$2y$10$g5YUIop7Q.W', 203, 'cristian.vasquez@example.com'),
	(29, 'Silvia Patricia', 'Espina Cor', '$2y$10$f6HJKlm8N.X', 203, 'silvia.espina@example.com'),
	(30, 'Marco Tulio', 'Arriola San', '$2y$10$d7QWERT9Y.U', 203, 'marco.arriola@example.com'),
	(31, 'Heidy Rossana', 'Lemus Paiz', '$2y$10$s8DFGHJ1K.L', 203, 'heidy.lemus@example.com'),
	(32, 'Oscar Rene', 'Barrientos', '$2y$10$a9ZXCVB2N.M', 203, 'oscar.barrientos@example.com'),
	(33, 'Nataly Sofía', 'Duarte Mend', '$2y$10$z0RTYUI3O.P', 203, 'nataly.duarte@example.com');

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla proyecto.horarios: ~0 rows (aproximadamente)
INSERT INTO `horarios` (`id_horario`, `id_asignacion`, `dia_semana`, `hora_inicio`, `hora_fin`) VALUES
	(1, 5, 'Sábado', '08:00:00', '09:30:00');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
