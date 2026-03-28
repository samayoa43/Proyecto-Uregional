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
  `correo` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `id_usuario` int DEFAULT NULL,
  PRIMARY KEY (`id_personal`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `administrativo_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla proyecto.administrativo: ~2 rows (aproximadamente)
INSERT INTO `administrativo` (`id_personal`, `nombres`, `apellidos`, `correo`, `contraseña`, `id_usuario`) VALUES
	(1, 'juan', 'admin', 'juanadmin@gmail.com', '1234567', 70),
	(7, 'Stevan ', 'Universidad ', 'StevanU@gmail.com', '1234567', 71);

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla proyecto.asignaciones: ~1 rows (aproximadamente)
INSERT INTO `asignaciones` (`id_asignacion_a`, `id_estudiante`, `id_asignacion`, `fecha_asignacion`) VALUES
	(1, 1, 5, '2026-01-31 00:00:00');

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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla proyecto.asignaciones_docentes: ~3 rows (aproximadamente)
INSERT INTO `asignaciones_docentes` (`id_asignacion`, `id_docente`, `id_curso`) VALUES
	(5, 2, 2),
	(6, 3, 11),
	(7, 2, 10);

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
) ENGINE=InnoDB AUTO_INCREMENT=167 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla proyecto.asistencia: ~67 rows (aproximadamente)
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
	(132, 33, 2, 'Asistente', '2026-03-21 06:00:00'),
	(166, 1, 2, 'Asistente', '2026-03-24 06:00:00');

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
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla proyecto.calificaciones: ~33 rows (aproximadamente)
INSERT INTO `calificaciones` (`id_calificacion`, `id_estudiante`, `id_curso`, `nota`, `nota2`, `nota3`, `nota_final`) VALUES
	(1, 1, 2, 30.00, 30.00, 40.00, 100.00),
	(2, 2, 2, 22.00, 22.00, 32.00, 76.00),
	(3, 3, 2, 22.00, 22.00, 32.00, 76.00),
	(4, 4, 2, 22.00, 22.00, 32.00, 76.00),
	(5, 5, 2, 22.00, 22.00, 32.00, 76.00),
	(6, 6, 2, 22.00, 22.00, 32.00, 76.00),
	(7, 7, 2, 22.00, 22.00, 32.00, 76.00),
	(8, 8, 2, 22.00, 22.00, 32.00, 76.00),
	(9, 9, 2, 22.00, 22.00, 32.00, 76.00),
	(10, 10, 2, 22.00, 22.00, 32.00, 76.00),
	(11, 11, 2, 22.00, 22.00, 32.00, 76.00),
	(12, 12, 2, 22.00, 22.00, 32.00, 76.00),
	(13, 13, 2, 22.00, 22.00, 32.00, 76.00),
	(14, 14, 2, 22.00, 22.00, 32.00, 76.00),
	(15, 15, 2, 22.00, 22.00, 32.00, 76.00),
	(16, 16, 2, 22.00, 22.00, 32.00, 76.00),
	(17, 17, 2, 22.00, 22.00, 32.00, 76.00),
	(18, 18, 2, 22.00, 22.00, 32.00, 76.00),
	(19, 19, 2, 22.00, 22.00, 32.00, 76.00),
	(20, 20, 2, 22.00, 22.00, 32.00, 76.00),
	(21, 21, 2, 22.00, 22.00, 32.00, 76.00),
	(22, 22, 2, 22.00, 22.00, 32.00, 76.00),
	(23, 23, 2, 22.00, 22.00, 32.00, 76.00),
	(24, 24, 2, 22.00, 22.00, 32.00, 76.00),
	(25, 25, 2, 22.00, 22.00, 23.00, 67.00),
	(26, 26, 2, 22.00, 22.00, 32.00, 76.00),
	(27, 27, 2, 22.00, 22.00, 32.00, 76.00),
	(28, 28, 2, 22.00, 22.00, 32.00, 76.00),
	(29, 29, 2, 22.00, 22.00, 32.00, 76.00),
	(30, 30, 2, 22.00, 22.00, 32.00, 76.00),
	(31, 31, 2, 22.00, 22.00, 32.00, 76.00),
	(32, 32, 2, 22.00, 22.00, 32.00, 76.00),
	(33, 33, 2, 22.00, 22.00, 32.00, 76.00);

-- Volcando estructura para tabla proyecto.carreras
CREATE TABLE IF NOT EXISTS `carreras` (
  `id_carrera` int NOT NULL AUTO_INCREMENT,
  `nombre_carrera` varchar(100) NOT NULL,
  PRIMARY KEY (`id_carrera`)
) ENGINE=InnoDB AUTO_INCREMENT=205 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla proyecto.carreras: ~2 rows (aproximadamente)
INSERT INTO `carreras` (`id_carrera`, `nombre_carrera`) VALUES
	(203, 'Administracion de Sistemas Informaticos'),
	(204, 'Administración de Empresas');

-- Volcando estructura para tabla proyecto.cursos
CREATE TABLE IF NOT EXISTS `cursos` (
  `id_curso` int NOT NULL AUTO_INCREMENT,
  `nombre_curso` varchar(100) NOT NULL,
  PRIMARY KEY (`id_curso`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla proyecto.cursos: ~22 rows (aproximadamente)
INSERT INTO `cursos` (`id_curso`, `nombre_curso`) VALUES
	(1, 'proyecto'),
	(2, 'Inteligencia Artificial'),
	(3, 'Comunicación y Redacción'),
	(4, 'Comunicación y Redacción'),
	(5, 'Matemáticas II'),
	(6, 'Técnicas de Estudio e Investigación'),
	(7, 'Matemáticas I'),
	(8, 'Informática'),
	(9, 'Procesos y Algoritmos I'),
	(10, 'Matemáticas II'),
	(11, 'Técnicas de Estudio e Investigación'),
	(12, 'Matemáticas I'),
	(13, 'Informática'),
	(14, 'Procesos y Algoritmos I'),
	(15, 'Matemáticas II'),
	(16, 'Técnicas de Estudio e Investigación'),
	(17, 'Matemáticas I'),
	(18, 'Informática'),
	(19, 'Procesos y Algoritmos I'),
	(20, 'Desarrollo Web'),
	(24, 'Seminario de tesis'),
	(25, 'Seminario de Administración');

-- Volcando estructura para tabla proyecto.docentes
CREATE TABLE IF NOT EXISTS `docentes` (
  `id_docente` int NOT NULL AUTO_INCREMENT,
  `nombres` varchar(60) NOT NULL,
  `apellidos` varchar(60) NOT NULL,
  `correo` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `id_usuario` int DEFAULT NULL,
  PRIMARY KEY (`id_docente`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `docentes_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla proyecto.docentes: ~2 rows (aproximadamente)
INSERT INTO `docentes` (`id_docente`, `nombres`, `apellidos`, `correo`, `contraseña`, `id_usuario`) VALUES
	(2, 'pedro', 'simil', 'pedrosimi@gmail.com', '1234567', 67),
	(3, 'Mago de', 'Oz', 'magazoU@gmail.com', '1234567', 68);

-- Volcando estructura para tabla proyecto.estudiantes
CREATE TABLE IF NOT EXISTS `estudiantes` (
  `id_estudiante` int NOT NULL AUTO_INCREMENT,
  `nombres` varchar(60) NOT NULL,
  `apellidos` varchar(60) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `id_carrera` int DEFAULT NULL,
  `correo` varchar(80) NOT NULL,
  `id_usuario` int DEFAULT NULL,
  PRIMARY KEY (`id_estudiante`),
  KEY `id_carrera` (`id_carrera`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `estudiantes_ibfk_1` FOREIGN KEY (`id_carrera`) REFERENCES `carreras` (`id_carrera`),
  CONSTRAINT `estudiantes_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla proyecto.estudiantes: ~33 rows (aproximadamente)
INSERT INTO `estudiantes` (`id_estudiante`, `nombres`, `apellidos`, `contraseña`, `id_carrera`, `correo`, `id_usuario`) VALUES
	(1, 'juan', 'Ejemplo', '123456789', 203, 'ejemplocorreo@gmail.com', 4),
	(2, 'Cesar', 'Silvamilion', 'jksjkdbjdsbjdbjasdbkjds', 203, 'Silvamilionsama@gmail.com', 5),
	(3, 'Ricardo ', 'Milos', 'jahajhajahjaha', 203, 'Ricardomilos@hotmail.com', 6),
	(4, 'Juan Carlos', 'Pérez Gómez', '$2y$10$e0MYzXy5W.P', 203, 'juan.perez@example.com', 7),
	(5, 'María Elena', 'Rodríguez Paz', '$2y$10$k8NZnYp7Q.L', 203, 'm.rodriguez@example.com', 8),
	(6, 'Luis Alberto', 'García López', '$2y$10$v2LKmRj4W.M', 203, 'luis.garcia@example.com', 9),
	(7, 'Ana Lucía', 'Méndez Ruiz', '$2y$10$a9XPlTq2S.K', 203, 'ana.mendez@example.com', 10),
	(8, 'Carlos Estuardo', 'Morales Sosa', '$2y$10$m3RJnBv8D.P', 203, 'carlos.morales@example.com', 11),
	(9, 'Sofía Isabel', 'Castillo Oro', '$2y$10$f5TKmWx1Z.Q', 203, 'sofia.castillo@example.com', 12),
	(10, 'Diego Alejandro', 'Ramírez Ven', '$2y$10$q1NVbPz6X.R', 203, 'diego.ramirez@example.com', 13),
	(11, 'Claudia María', 'Herrera Gil', '$2y$10$s7LMfGh3C.V', 203, 'claudia.herrera@example.com', 14),
	(12, 'Fernando José', 'Álvarez San', '$2y$10$h9BVCxZ2N.M', 203, 'fernando.alvarez@example.com', 15),
	(13, 'Karla Jimena', 'Díaz Valdéz', '$2y$10$y4KLmNp9Q.W', 203, 'karla.diaz@example.com', 16),
	(14, 'Ricardo Andrés', 'Solares Rey', '$2y$10$j8DFgHj5K.L', 203, 'ricardo.solares@example.com', 17),
	(15, 'Gabriela Fernanda', 'Mazariegos', '$2y$10$p2ZXcVb3N.M', 203, 'gaby.maza@example.com', 18),
	(16, 'Roberto Antonio', 'Luna Pineda', '$2y$10$w6QWErt7Y.U', 203, 'roberto.luna@example.com', 19),
	(17, 'Paola Michelle', 'Estrada Lux', '$2y$10$e4RTYui8O.P', 203, 'paola.estrada@example.com', 20),
	(18, 'Sergio Danilo', 'Cabrera Sol', '$2y$10$r1TYUio9P.A', 203, 'sergio.cabrera@example.com', 21),
	(19, 'Mónica Beatriz', 'Juárez Mar', '$2y$10$t2GHJkl4S.D', 203, 'monica.juarez@example.com', 22),
	(20, 'Jorge Mario', 'Orantes Paz', '$2y$10$y3XCVbn5M.Q', 203, 'jorge.orantes@example.com', 23),
	(21, 'Valeria Inés', 'Sandoval Te', '$2y$10$u7JKLmn8B.V', 203, 'valeria.sandoval@example.com', 24),
	(22, 'Hugo Rolando', 'Cifuentes Go', '$2y$10$i9OPQwe1R.T', 203, 'hugo.cifuentes@example.com', 25),
	(23, 'Andrea Celeste', 'Palacios Ri', '$2y$10$o0ASDfg2H.J', 203, 'andrea.palacios@example.com', 26),
	(24, 'Manuel Enrique', 'Girón Tello', '$2y$10$l1ZXCvb3N.K', 203, 'manuel.giron@example.com', 27),
	(25, 'Jessica Ivonne', 'Reyes Cano', '$2y$10$k2WERty4U.I', 203, 'jessica.reyes@example.com', 28),
	(26, 'Francisco Javier', 'Mejía Lara', '$2y$10$j3SDFgh5J.K', 203, 'javier.mejia@example.com', 29),
	(27, 'Lorena Abigail', 'González Pe', '$2y$10$h4XCVbn6M.L', 203, 'lorena.gonzalez@example.com', 30),
	(28, 'Cristian David', 'Vásquez Al', '$2y$10$g5YUIop7Q.W', 203, 'cristian.vasquez@example.com', 31),
	(29, 'Silvia Patricia', 'Espina Cor', '$2y$10$f6HJKlm8N.X', 203, 'silvia.espina@example.com', 32),
	(30, 'Marco Tulio', 'Arriola San', '$2y$10$d7QWERT9Y.U', 203, 'marco.arriola@example.com', 33),
	(31, 'Heidy Rossana', 'Lemus Paiz', '$2y$10$s8DFGHJ1K.L', 203, 'heidy.lemus@example.com', 34),
	(32, 'Oscar Rene', 'Barrientos', '$2y$10$a9ZXCVB2N.M', 203, 'oscar.barrientos@example.com', 35),
	(33, 'Nataly Sofía', 'Duarte Mend', '$2y$10$z0RTYUI3O.P', 203, 'nataly.duarte@example.com', 36);

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla proyecto.horarios: ~2 rows (aproximadamente)
INSERT INTO `horarios` (`id_horario`, `id_asignacion`, `dia_semana`, `hora_inicio`, `hora_fin`) VALUES
	(1, 5, 'Sábado', '08:00:00', '09:30:00'),
	(2, 6, 'Sábado', '10:00:00', '11:30:00');

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla proyecto.pensum: ~3 rows (aproximadamente)
INSERT INTO `pensum` (`id_pensum`, `id_carrera`, `id_curso`, `semestre`) VALUES
	(1, 203, 20, NULL),
	(2, 203, 24, NULL),
	(3, 203, 25, 9);

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

-- Volcando estructura para tabla proyecto.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id_usuario` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `contraseña` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `estado` tinyint DEFAULT '1',
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `correo` (`correo`)
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla proyecto.usuarios: ~37 rows (aproximadamente)
INSERT INTO `usuarios` (`id_usuario`, `nombre`, `correo`, `contraseña`, `estado`) VALUES
	(4, 'juan Ejemplo', 'ejemplocorreo@gmail.com', '123456789', 1),
	(5, 'Cesar Silvamilion', 'Silvamilionsama@gmail.com', 'jksjkdbjdsbjdbjasdbkjds', 1),
	(6, 'Ricardo  Milos', 'Ricardomilos@hotmail.com', 'jahajhajahjaha', 1),
	(7, 'Juan Carlos Pérez Gómez', 'juan.perez@example.com', '$2y$10$e0MYzXy5W.P', 1),
	(8, 'María Elena Rodríguez Paz', 'm.rodriguez@example.com', '$2y$10$k8NZnYp7Q.L', 1),
	(9, 'Luis Alberto García López', 'luis.garcia@example.com', '$2y$10$v2LKmRj4W.M', 1),
	(10, 'Ana Lucía Méndez Ruiz', 'ana.mendez@example.com', '$2y$10$a9XPlTq2S.K', 1),
	(11, 'Carlos Estuardo Morales Sosa', 'carlos.morales@example.com', '$2y$10$m3RJnBv8D.P', 1),
	(12, 'Sofía Isabel Castillo Oro', 'sofia.castillo@example.com', '$2y$10$f5TKmWx1Z.Q', 1),
	(13, 'Diego Alejandro Ramírez Ven', 'diego.ramirez@example.com', '$2y$10$q1NVbPz6X.R', 1),
	(14, 'Claudia María Herrera Gil', 'claudia.herrera@example.com', '$2y$10$s7LMfGh3C.V', 1),
	(15, 'Fernando José Álvarez San', 'fernando.alvarez@example.com', '$2y$10$h9BVCxZ2N.M', 1),
	(16, 'Karla Jimena Díaz Valdéz', 'karla.diaz@example.com', '$2y$10$y4KLmNp9Q.W', 1),
	(17, 'Ricardo Andrés Solares Rey', 'ricardo.solares@example.com', '$2y$10$j8DFgHj5K.L', 1),
	(18, 'Gabriela Fernanda Mazariegos', 'gaby.maza@example.com', '$2y$10$p2ZXcVb3N.M', 1),
	(19, 'Roberto Antonio Luna Pineda', 'roberto.luna@example.com', '$2y$10$w6QWErt7Y.U', 1),
	(20, 'Paola Michelle Estrada Lux', 'paola.estrada@example.com', '$2y$10$e4RTYui8O.P', 1),
	(21, 'Sergio Danilo Cabrera Sol', 'sergio.cabrera@example.com', '$2y$10$r1TYUio9P.A', 1),
	(22, 'Mónica Beatriz Juárez Mar', 'monica.juarez@example.com', '$2y$10$t2GHJkl4S.D', 1),
	(23, 'Jorge Mario Orantes Paz', 'jorge.orantes@example.com', '$2y$10$y3XCVbn5M.Q', 1),
	(24, 'Valeria Inés Sandoval Te', 'valeria.sandoval@example.com', '$2y$10$u7JKLmn8B.V', 1),
	(25, 'Hugo Rolando Cifuentes Go', 'hugo.cifuentes@example.com', '$2y$10$i9OPQwe1R.T', 1),
	(26, 'Andrea Celeste Palacios Ri', 'andrea.palacios@example.com', '$2y$10$o0ASDfg2H.J', 1),
	(27, 'Manuel Enrique Girón Tello', 'manuel.giron@example.com', '$2y$10$l1ZXCvb3N.K', 1),
	(28, 'Jessica Ivonne Reyes Cano', 'jessica.reyes@example.com', '$2y$10$k2WERty4U.I', 1),
	(29, 'Francisco Javier Mejía Lara', 'javier.mejia@example.com', '$2y$10$j3SDFgh5J.K', 1),
	(30, 'Lorena Abigail González Pe', 'lorena.gonzalez@example.com', '$2y$10$h4XCVbn6M.L', 1),
	(31, 'Cristian David Vásquez Al', 'cristian.vasquez@example.com', '$2y$10$g5YUIop7Q.W', 1),
	(32, 'Silvia Patricia Espina Cor', 'silvia.espina@example.com', '$2y$10$f6HJKlm8N.X', 1),
	(33, 'Marco Tulio Arriola San', 'marco.arriola@example.com', '$2y$10$d7QWERT9Y.U', 1),
	(34, 'Heidy Rossana Lemus Paiz', 'heidy.lemus@example.com', '$2y$10$s8DFGHJ1K.L', 1),
	(35, 'Oscar Rene Barrientos', 'oscar.barrientos@example.com', '$2y$10$a9ZXCVB2N.M', 1),
	(36, 'Nataly Sofía Duarte Mend', 'nataly.duarte@example.com', '$2y$10$z0RTYUI3O.P', 1),
	(67, 'pedro simil', 'pedrosimi@gmail.com', '1234567', 1),
	(68, 'Mago de Oz', 'magazoU@gmail.com', '1234567', 1),
	(70, 'juan admin', 'juanadmin@gmail.com', '1234567', 1),
	(71, 'Stevan  Universidad ', 'StevanU@gmail.com', '1234567', 1);

-- Volcando estructura para tabla proyecto.usuario_roles
CREATE TABLE IF NOT EXISTS `usuario_roles` (
  `id_usuario` int NOT NULL,
  `id_rol` int NOT NULL,
  PRIMARY KEY (`id_usuario`,`id_rol`),
  KEY `id_rol` (`id_rol`),
  CONSTRAINT `usuario_roles_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  CONSTRAINT `usuario_roles_ibfk_2` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla proyecto.usuario_roles: ~37 rows (aproximadamente)
INSERT INTO `usuario_roles` (`id_usuario`, `id_rol`) VALUES
	(70, 1),
	(71, 1),
	(67, 2),
	(68, 2),
	(4, 3),
	(5, 3),
	(6, 3),
	(7, 3),
	(8, 3),
	(9, 3),
	(10, 3),
	(11, 3),
	(12, 3),
	(13, 3),
	(14, 3),
	(15, 3),
	(16, 3),
	(17, 3),
	(18, 3),
	(19, 3),
	(20, 3),
	(21, 3),
	(22, 3),
	(23, 3),
	(24, 3),
	(25, 3),
	(26, 3),
	(27, 3),
	(28, 3),
	(29, 3),
	(30, 3),
	(31, 3),
	(32, 3),
	(33, 3),
	(34, 3),
	(35, 3),
	(36, 3);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
