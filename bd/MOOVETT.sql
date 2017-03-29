-- phpMyAdmin SQL Dump
-- version 4.2.12deb2
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 19-11-2016 a las 00:45:26
-- Versión del servidor: 5.5.44-0+deb8u1
-- Versión de PHP: 5.6.13-0+deb8u1

--
-- Base de datos: `MOOVETT`
--

DROP DATABASE IF EXISTS `MOOVETT`;
SET SQL_MODE=`NO_AUTO_VALUE_ON_ZERO`;
CREATE DATABASE `MOOVETT` DEFAULT CHARACTER SET UTF8 COLLATE utf8_general_ci;
USE `MOOVETT`;
GRANT USAGE ON *.* TO 'moovett'@'localhost';
   DROP USER 'moovett'@'localhost';

CREATE USER 'moovett'@'localhost' IDENTIFIED BY  'moovett2016';

GRANT USAGE ON * . * TO  'moovett'@'localhost' IDENTIFIED BY  'moovett2016' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0 ;

GRANT ALL PRIVILEGES ON  `MOOVETT` . * TO  'moovett'@'localhost' WITH GRANT OPTION ;


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Actividad`
--

CREATE TABLE IF NOT EXISTS `Actividad` (
  `Id_Actividad` varchar(10) NOT NULL,
  `Nombre` varchar(20) NOT NULL,
  `DNI_Trabajador` varchar(9) NOT NULL,
  `Id_Espacio` varchar(10) NOT NULL,
  `Precio` float NOT NULL,
  `Categoria` enum('Baile','Deportiva','','') NOT NULL,
  `Capacidad_Max` int(11) NOT NULL,
  `Duracion` enum('Mensual','Bimensual','Trimensual','Cuatrimestral') NOT NULL,
  `Fecha` date NOT NULL,
  `Hora_Inicio` time NOT NULL,
  `Hora_Fin` time NOT NULL,
  `Id_Calendario` varchar(10) NOT NULL,
  `Borrado` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Actividad`
--

INSERT INTO `Actividad` (`Id_Actividad`, `Nombre`, `DNI_Trabajador`, `Id_Espacio`, `Precio`, `Categoria`, `Capacidad_Max`, `Duracion`, `Fecha`, `Hora_Inicio`, `Hora_Fin`, `Id_Calendario`, `Borrado`) VALUES
('0123456789', 'Actividad 1', '22222222J', '9874563210', 10.5, 'Baile', 20, 'Mensual', '2016-12-17', '15:30:00', '16:30:00', '3210654987', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Alumno`
--

CREATE TABLE IF NOT EXISTS `Alumno` (
  `DNI` varchar(9) NOT NULL,
  `Apellidos` varchar(70) NOT NULL,
  `Nombre` varchar(50) NOT NULL,
  `Direccion` varchar(100) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Fecha_Nacimiento` date NOT NULL,
  `Observaciones` varchar(500) NOT NULL,
  `Profesion` varchar(50) NOT NULL,
  `Borrado` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Alumno`
--

INSERT INTO `Alumno` (`DNI`, `Apellidos`, `Nombre`, `Direccion`, `Email`, `Fecha_Nacimiento`, `Observaciones`, `Profesion`, `Borrado`) VALUES
('33333333P', 'ApellidoA ApellidoA', 'NombreA', 'Direccion alumno, calle alumno', 'alumno@hotmail.com', '2017-10-31', 'Escrito de observaciones', 'Contable', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Asistencia`
--

CREATE TABLE IF NOT EXISTS `Asistencia` (
  `DNI` varchar(9) NOT NULL,
  `Fecha_Asistencia` date NOT NULL,
  `Asistencia` tinyint(1) NOT NULL,
  `Id_Actividad` varchar(10) NOT NULL,
  `Borrado` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Asistencia`
--

INSERT INTO `Asistencia` (`DNI`, `Fecha_Asistencia`, `Asistencia`, `Id_Actividad`, `Borrado`) VALUES
('33333333P', '2016-12-17', 1, '0123456789', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Caja`
--

CREATE TABLE IF NOT EXISTS `Caja` (
  `Id_Caja` mediumint(9) PRIMARY KEY AUTO_INCREMENT,
  `Fecha` date NOT NULL,
  `Tipo` enum('Ingreso','Pago') NOT NULL,
  `Importe` float NOT NULL,
  `Comentario` varchar(300) DEFAULT NULL,
  `Borrado` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Caja`
--

INSERT INTO `Caja` (`Id_Caja`,`Fecha`, `Tipo`, `Importe`, `Comentario`, `Borrado`) VALUES
(5,'2016-11-15', 'Ingreso', 50.5,'', 0),
(2,'2016-11-15', 'Pago', 30,'', 0),
(3,'2016-11-15', 'Ingreso', 20,'', 0),
(4,'2016-11-29', 'Ingreso', 30,'Factura cobrada con fecha 2016-11-29', 0),
(1,'2016-11-01', 'Ingreso', 50.5,'Factura cobrada con fecha 2016-11-01', 0),
(10,'2016-12-10', 'Pago', 60,'', 0),
(9,'2016-12-09', 'Pago', 20,'', 0),
(7,'2016-11-30', 'Pago', 10,'', 0),
(8,'2016-11-30', 'Ingreso', 30,'', 0),
(6,'2016-11-29', 'Ingreso', 20,'', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Calendario`
--

CREATE TABLE IF NOT EXISTS `Calendario` (
  `Id_Calendario` varchar(10) NOT NULL,
  `Fecha_Inicio` date NOT NULL,
  `Fecha_Fin` date NOT NULL,
  `Hora_Inicio` time NOT NULL,
  `Hora_Fin` time NOT NULL,
  `Borrado` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Calendario`
--

INSERT INTO `Calendario` (`Id_Calendario`, `Fecha_Inicio`, `Fecha_Fin`, `Hora_Inicio`, `Hora_Fin`, `Borrado`) VALUES
('3210654987', '2016-10-31', '2016-12-31', '09:00:00', '20:00:00', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Cliente_Externo`
--

CREATE TABLE IF NOT EXISTS `Cliente_Externo` (
  `Id_Cliente` varchar(10) NOT NULL,
  `Nombre` varchar(50) NOT NULL,
  `DNI` varchar(9) NOT NULL,
  `Tlf` int(9) NOT NULL,
  `Email` varchar(30) NOT NULL,
  `Borrado` tinyint(1) NOT NULL DEFAULT '0',
  `Direccion` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Cliente_Externo`
--

INSERT INTO `Cliente_Externo` (`Id_Cliente`, `Nombre`, `DNI`, `Tlf`, `Email`, `Borrado`, `Direccion`) VALUES
('12341234D', 'Iago Fernandez', '12341234D', 614748347, 'iago@gmail.com', 0, 'Progeso 24, 3C'),
('34567889Q', 'Jorge González', '34567889Q', 627673456, 'jorge@gmail.com', 0, 'Progeso 26, 2A'),
('98765432M', 'Pedro Vázquez', '98765432M', 689234675, 'pedro@gmail.com', 0, 'Rúa Nova 2, 1A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Cobro`
--

CREATE TABLE IF NOT EXISTS `Cobro` (
  `Id_Cobro` varchar(10) NOT NULL,
  `DNI_Alumno` varchar(9) NOT NULL,
  `Id_Actividad` varchar(10) NOT NULL,
  `Importe` float NOT NULL,
  `Fecha_Cobro` date NOT NULL,
  `Fecha_Confirmacion` date NOT NULL,
  `Tipo` enum('Efectivo','Domiciliacion','TPV') NOT NULL,
  `Borrado` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Cobro`
--

INSERT INTO `Cobro` (`Id_Cobro`, `DNI_Alumno`, `Id_Actividad`, `Importe`, `Fecha_Cobro`, `Fecha_Confirmacion`, `Tipo`, `Borrado`) VALUES
('1010101010', '33333333P', '0123456789', 18.9, '2016-12-17', '2016-12-17', 'Efectivo', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Controlador`
--

CREATE TABLE IF NOT EXISTS `Controlador` (
  `Nombre_Controlador` varchar(30) NOT NULL,
  `Accion` varchar(30) NOT NULL,
  `Borrado` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Controlador`
--

INSERT INTO `Controlador` (`Nombre_Controlador`, `Accion`, `Borrado`) VALUES
('Factura', 'Add', 0),
('Factura', 'Delete', 0),
('Factura', 'Edit', 0),
('Factura', 'List', 0),
('Factura', 'Show', 0),
('Linea_Factura', 'Add', 0),
('Linea_Factura', 'Delete', 0),
('Linea_Factura', 'Edit', 0),
('Linea_Factura', 'List', 0),
('Linea_Factura', 'Show', 0),
('Caja', 'Add', 0),
('Caja', 'Delete', 0),
('Caja', 'Edit', 0),
('Caja', 'List', 0),
('Caja', 'Show', 0),('Espacio', 'Add', 0),
('Espacio', 'Delete', 0),
('Espacio', 'Edit', 0),
('Espacio', 'List', 0),
('Espacio', 'Show', 0),
('Reserva_Espacio', 'Add', 0),
('Reserva_Espacio', 'Delete', 0),
('Reserva_Espacio', 'Edit', 0),
('Reserva_Espacio', 'List', 0),
('Reserva_Espacio', 'Show', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Descuento`
--

CREATE TABLE IF NOT EXISTS `Descuento` (
  `Id_Descuento` varchar(20) NOT NULL,
  `Requisitos` varchar(150) NOT NULL,
  `Porcentaje` float NOT NULL,
  `Borrado` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Descuento`
--

INSERT INTO `Descuento` (`Id_Descuento`, `Requisitos`, `Porcentaje`, `Borrado`) VALUES
('8588588585', 'Los requitos son...', 10, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Documento`
--

CREATE TABLE IF NOT EXISTS `Documento` (
  `DNI` varchar(9) NOT NULL,
  `Tipo_Documento` varchar(20) NOT NULL,
  `Fecha_Documento` date NOT NULL,
  `Doc` longblob NOT NULL,
  `Borrado` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Documento`
--

INSERT INTO `Documento` (`DNI`, `Tipo_Documento`, `Fecha_Documento`, `Doc`, `Borrado`) VALUES
('33333333P', 'SEPA', '2017-10-31', 'Documento.jpg', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Espacio`
--

CREATE TABLE IF NOT EXISTS `Espacio` (
  `Id_Espacio` varchar(10) NOT NULL,
  `Nombre` varchar(30) NOT NULL,
  `Borrado` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Espacio`
--


INSERT INTO `Espacio` (`Id_Espacio`, `Nombre`, `Borrado`) VALUES ('9874563210', 'Aula 1', 0); 
INSERT INTO `Espacio` (`Id_Espacio`, `Nombre`, `Borrado`) VALUES ('1766371728', 'Aula A', 0);
INSERT INTO `Espacio` (`Id_Espacio`, `Nombre`, `Borrado`) VALUES ('hdsj123781', 'Aula B', 0);
INSERT INTO `Espacio` (`Id_Espacio`, `Nombre`, `Borrado`) VALUES ('098djsjjjj', 'Aula C', 0);
INSERT INTO `Espacio` (`Id_Espacio`, `Nombre`, `Borrado`) VALUES ('owuwqjdsns', 'Aula D', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Evento`
--

CREATE TABLE IF NOT EXISTS `Evento` (
  `Id_Evento` int(20) NOT NULL,
  `Descripcion` varchar(500) NOT NULL,
  `Nombre` varchar(20) NOT NULL,
  `Borrado` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Evento`
--

INSERT INTO `Evento` (`Id_Evento`, `Descripcion`, `Nombre`, `Borrado`) VALUES
(1, 'Esta es la descripción del evento del Magosto', 'Magosto', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Factura`
--

CREATE TABLE IF NOT EXISTS `Factura` (
  `Id_Factura` mediumint(9) PRIMARY KEY AUTO_INCREMENT,
  `Id_Cliente` varchar(10) NOT NULL,
  `Fecha` date DEFAULT NULL,
  `Fecha_Cobro` date DEFAULT NULL,
  `Total` float NOT NULL DEFAULT 0,
  `Pagada` enum('No','Efectivo','Domiciliacion','TPV') NOT NULL DEFAULT 'No',
  `Borrado` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Factura`
--

INSERT INTO `Factura` (`Id_Factura`, `Id_Cliente`, `Fecha`, `Fecha_Cobro`, `Total`, `Pagada`, `Borrado`) VALUES
(3, '12341234D', NULL, NULL, 125, 'No', 0),
(4, '34567889Q', '2016-11-25', '2016-11-29', 20, 'Efectivo', 0),
(5, '12341234D', '2016-11-29', NULL, 80.5, 'No', 0),
(6, '34567889Q', NULL, NULL, 20, 'No', 1),
(8, '34567889Q', '2016-12-12', '2016-12-13', 120.5, 'Domiciliacion', 0),
(7, '12341234D', '2016-12-02', '2016-12-05', 20, 'TPV', 0),
(9, '98765432M', NULL, NULL, 50.5, 'No', 0),
(2, '34567889Q', '2016-11-15', NULL, 30, 'No', 0),
(1, '98765432M', '2016-10-28', '2016-11-01', 20, 'Efectivo', 0);


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Grupo`
--

CREATE TABLE IF NOT EXISTS `Grupo` (
  `Nombre_Grupo` varchar(30) NOT NULL,
  `Borrado` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Grupo`
--

INSERT INTO `Grupo` (`Nombre_Grupo`, `Borrado`) VALUES
('Admin', 0),
('Monitor', 0),
('Secretario', 0),
('Usuario', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Horario`
--

CREATE TABLE IF NOT EXISTS `Horario` (
  `Id_Horario` varchar(10) NOT NULL,
  `Id_Calendario` varchar(10) NOT NULL,
  `Hora_Inicio` time NOT NULL,
  `Hora_Fin` time NOT NULL,
  `Borrado` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Horario`
--

INSERT INTO `Horario` (`Id_Horario`, `Id_Calendario`, `Hora_Inicio`, `Hora_Fin`, `Borrado`) VALUES
('1616161616', '3210654987', '09:00:00', '19:00:00', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Inscripcion`
--

CREATE TABLE IF NOT EXISTS `Inscripcion` (
  `DNI_A` varchar(9) NOT NULL,
  `Id_Actividad` varchar(10) NOT NULL,
  `Fecha` date NOT NULL,
  `Borrado` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Inscripcion`
--

INSERT INTO `Inscripcion` (`DNI_A`, `Id_Actividad`, `Fecha`, `Borrado`) VALUES
('33333333P', '0123456789', '2016-12-17', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Jornada`
--

CREATE TABLE IF NOT EXISTS `Jornada` (
  `DNI` varchar(9) NOT NULL,
  `Hora_Entrada` time NOT NULL,
  `Hora_Salida` time NOT NULL,
  `Observaciones` varchar(50) NOT NULL,
  `Borrado` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Jornada`
--

INSERT INTO `Jornada` (`DNI`, `Hora_Entrada`, `Hora_Salida`, `Observaciones`, `Borrado`) VALUES
('22222222J', '08:55:00', '17:03:00', 'Observaciones', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Lesion`
--

CREATE TABLE IF NOT EXISTS `Lesion` (
  `DNI` varchar(9) NOT NULL,
`Id_Lesion` int(9) NOT NULL,
  `Tipo` varchar(50) NOT NULL,
  `Curada` tinyint(1) NOT NULL,
  `Descripcion` varchar(500) NOT NULL,
  `Borrado` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=252525253 DEFAULT CHARSET=utf8;


--
-- Volcado de datos para la tabla `Lesion`
--

INSERT INTO `Lesion` (`DNI`, `Id_Lesion`, `Tipo`, `Curada`, `Descripcion`, `Borrado`) VALUES
('33333333P', '252525252', 'Lesion cervical', 0, 'La lesion se encuentra en...', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Linea_Factura`
--

CREATE TABLE IF NOT EXISTS `Linea_Factura` (
`Id_Linea_Factura` mediumint(9) PRIMARY KEY AUTO_INCREMENT,
  `Id_Factura` mediumint(9) NOT NULL,
  `Id_Servicio` varchar(10) NOT NULL,
  `Descripcion` varchar(500) DEFAULT NULL,
  `Importe` float NOT NULL,
  `Borrado` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Linea_Factura`
--

INSERT INTO `Linea_Factura` (`Id_Linea_Factura`, `Id_Factura`, `Id_Servicio`, `Descripcion`, `Importe`, `Borrado`) VALUES
(1, 1, '963852741', 'Descuento por cliente habitual', 20, 0),
(2, 2, '963852741', '', 30, 0),
(3, 3, '963232742', '', 40, 0),
(4, 3, '963852741', '', 30, 0),
(5, 3, '963852442', '', 20, 0),
(14, 3, '963852372', '', 35, 0),
(6, 4, '963852442', '', 20, 0),
(7, 5, '925852742', '', 50.5, 0),
(8, 5, '963852372', 'Descuento especial', 30, 0),
(9, 6, '963852741', '', 30, 0),
(10, 7, '963852442', '', 20, 0),
(11, 8, '963852741', '', 30, 0),
(12, 8, '963232742', '', 40, 0),
(13, 8, '925852742', '', 50.5, 0),
(15, 9, '925852742', '', 50.5, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Log_Lesion`
--

CREATE TABLE IF NOT EXISTS `Log_Lesion` (
  `DNI_T` varchar(9) NOT NULL,
  `DNI_A` varchar(9) NOT NULL,
  `Id_Lesion` varchar(9) NOT NULL,
  `Fecha_Log` date NOT NULL,
  `Hora_Log` time NOT NULL,
  `Borrado` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Log_Lesion`
--

INSERT INTO `Log_Lesion` (`DNI_T`, `DNI_A`, `Id_Lesion`, `Fecha_Log`, `Hora_Log`, `Borrado`) VALUES
('22222222J', '33333333P', '252525252', '2017-10-31', '15:05:00', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Masaje`
--

CREATE TABLE IF NOT EXISTS `Masaje` (
  `Id_Masaje` varchar(10) NOT NULL,
  `Nombre` varchar(50) NOT NULL,
  `Borrado` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Masaje`
--

INSERT INTO `Masaje` (`Id_Masaje`, `Nombre`, `Borrado`) VALUES
('2424242424', 'Exfoliante', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Permisos`
--

CREATE TABLE IF NOT EXISTS `Permisos` (
  `Nombre_Grupo` varchar(30) NOT NULL,
  `Nombre_Controlador` varchar(30) NOT NULL,
  `Accion` varchar(30) NOT NULL,
  `Borrado` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Permisos`
--

INSERT INTO `Permisos` (`Nombre_Grupo`, `Nombre_Controlador`, `Accion`, `Borrado`) VALUES
('Secretario','Factura', 'Add', 0),
('Secretario','Factura', 'Delete', 0),
('Secretario','Factura', 'Edit', 0),
('Secretario','Factura', 'List', 0),
('Secretario','Factura', 'Show', 0),
('Secretario','Linea_Factura', 'Add', 0),
('Secretario','Linea_Factura', 'Delete', 0),
('Secretario','Linea_Factura', 'Edit', 0),
('Secretario','Linea_Factura', 'List', 0),
('Secretario','Linea_Factura', 'Show', 0),
('Secretario','Caja', 'Add', 0),
('Secretario','Caja', 'Delete', 0),
('Secretario','Caja', 'Edit', 0),
('Secretario','Caja', 'List', 0),
('Secretario','Caja', 'Show', 0),
('Secretario','Espacio', 'Add', 0),
('Secretario','Espacio', 'Delete', 0),
('Secretario','Espacio', 'Edit', 0),
('Secretario','Espacio', 'List', 0),
('Secretario','Espacio', 'Show', 0),
('Secretario','Reserva_Espacio', 'Add', 0),
('Secretario','Reserva_Espacio', 'Delete', 0),
('Secretario','Reserva_Espacio', 'Edit', 0),
('Secretario','Reserva_Espacio', 'List', 0),
('Secretario','Reserva_Espacio', 'Show', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Reserva_Espacio`
--

CREATE TABLE IF NOT EXISTS `Reserva_Espacio` (
  `Id_Reserva` INT NOT NULL AUTO_INCREMENT,
  `Id_Espacio` varchar(10) NOT NULL,
  `Hora_Inicio` time NOT NULL,
  `Hora_Fin` time NOT NULL,
  `Fecha` date NOT NULL,
  `Descripción` varchar(50) NOT NULL,
  `Borrado` tinyint(1) NOT NULL DEFAULT '0',
  `DNI_Reserva` varchar(9) NOT NULL,
    PRIMARY KEY(Id_Reserva)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Reserva_Espacio`
--

INSERT INTO `Reserva_Espacio` (`Id_Reserva`, `Id_Espacio`, `Hora_Inicio`, `Hora_Fin`, `Fecha`, `Descripción`, `Borrado`, `DNI_Reserva`) VALUES
('1', '9874563210', '12:00:00', '13:00:00', '2017-02-15', 'Descripcion reserva 1', 0, '22222222J');

INSERT INTO `Reserva_Espacio` (`Id_Reserva`, `Id_Espacio`, `Hora_Inicio`, `Hora_Fin`, `Fecha`, `Descripción`, `Borrado`, `DNI_Reserva`) VALUES
('2', 'hdsj123781', '22:00:00', '23:00:00', '2017-02-15', 'Descripcion reserva 2', 0, '12345678Y');

INSERT INTO `Reserva_Espacio` (`Id_Reserva`, `Id_Espacio`, `Hora_Inicio`, `Hora_Fin`, `Fecha`, `Descripción`, `Borrado`, `DNI_Reserva`) VALUES
('3', '098djsjjjj', '10:00:00', '12:00:00', '2017-02-15', 'Descripcion reserva 3', 0, '87612347A');

INSERT INTO `Reserva_Espacio` (`Id_Reserva`, `Id_Espacio`, `Hora_Inicio`, `Hora_Fin`, `Fecha`, `Descripción`, `Borrado`, `DNI_Reserva`) VALUES
('4', 'owuwqjdsns', '12:00:00', '13:00:00', '2017-02-15', 'Descripcion reserva 4', 0, '09867812Z');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Reserva_Evento`
--

CREATE TABLE IF NOT EXISTS `Reserva_Evento` (
  `Id_Evento` int(20) NOT NULL,
  `Id_Reserva` int(20) NOT NULL,
  `Borrado` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Reserva_Evento`
--

INSERT INTO `Reserva_Evento` (`Id_Evento`, `Id_Reserva`, `Borrado`) VALUES
(1, 2147483647, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Reserva_Masaje`
--

CREATE TABLE IF NOT EXISTS `Reserva_Masaje` (
  `Id_Masaje` varchar(10) NOT NULL,
  `Id_Reserva` varchar(10) NOT NULL,
  `DNI_Alumno` varchar(9) NOT NULL,
  `DNI_Trabajador` varchar(9) NOT NULL,
  `Tipo` varchar(10) NOT NULL,
  `Hora_Inicio` time NOT NULL,
  `Hora_Fin` time NOT NULL,
  `Fecha` date NOT NULL,
  `Borrado` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Reserva_Masaje`
--

INSERT INTO `Reserva_Masaje` (`Id_Masaje`, `Id_Reserva`, `DNI_Alumno`, `DNI_Trabajador`, `Tipo`, `Hora_Inicio`, `Hora_Fin`, `Fecha`, `Borrado`) VALUES
('2424242424', '5656565656', '33333333P', '22222222J', 'Exfoliante', '16:00:00', '17:00:00', '2016-11-23', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Servicio`
--

CREATE TABLE IF NOT EXISTS `Servicio` (
  `Id_Servicio` varchar(10) NOT NULL,
  `Id_Trabajador` varchar(10) NOT NULL,
  `Nombre` varchar(30) NOT NULL,
  `Precio` float NOT NULL,
  `Descripcion` varchar(500) NOT NULL,
  `Borrado` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Servicio`
--

INSERT INTO `Servicio` (`Id_Servicio`, `Id_Trabajador`, `Nombre`, `Precio`, `Descripcion`, `Borrado`) VALUES
('963852741', '22222222J', 'Actividades para niños', 30, 'Actividades externas', 0),
('963232742', '22222222J', 'Actividades tercera edad', 40, 'Actividades externas', 0),
('963852442', '11111111H', 'Carrera solidaria', 20, '', 0),
('925852742', '12345678Z', 'Step', 50.5, '', 0),
('963852372', '11111111H', 'Ejercicios aeróbicos', 35, '', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Telefonos_Alumno`
--

CREATE TABLE IF NOT EXISTS `Telefonos_Alumno` (
  `DNI` varchar(9) NOT NULL,
  `Telefono` int(9) NOT NULL,
  `Borrado` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Telefonos_Alumno`
--

INSERT INTO `Telefonos_Alumno` (`DNI`, `Telefono`, `Borrado`) VALUES
('33333333P', 777777777, 0);

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `Trabajador`
--

CREATE TABLE IF NOT EXISTS `Trabajador` (
  `DNI` varchar(9) NOT NULL,
  `Apellidos` varchar(70) NOT NULL,
  `Nombre` varchar(50) NOT NULL,
  `Url_Foto` varchar(100) NOT NULL,
  `Direccion` varchar(100) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Fecha_Nacimiento` date NOT NULL,
  `Observaciones` varchar(500) NOT NULL,
  `Numero_Cuenta` varchar(40) NOT NULL,
  `Telefono` varchar(20) NOT NULL,
  `Tipo_Empleado` enum('administrador','secretario','monitor','fisioterapeuta','cafeteria','limpieza','otros') NOT NULL,
  `Externo` tinyint(1) NOT NULL,
  `Borrado` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Trabajador`
--

INSERT INTO `Trabajador` (`DNI`, `Apellidos`, `Nombre`, `Url_Foto`, `Direccion`, `Email`, `Fecha_Nacimiento`, `Observaciones`, `Numero_Cuenta`, `Telefono`, `Tipo_Empleado`, `Externo`, `Borrado`) VALUES
('11111111H', 'ApellidoTr ApellidoTr', 'NombreTr', '/var/www/html/images/11111111H.jpg', 'Direccion 1, calle 1', 'email1@gmail.com', '1990-12-25', 'Observo que este trabajador se esfuerza', '98532145321564879665', '988254896', 'secretario', 0, 0),
('12345678Z', 'ApellidoTr3 Apellido2Tr3', 'NobreTr3', '/var/www/html/images/12345678Z.jpg', 'Direccion , calle', 'email@gmail.com', '1988-05-07', 'Observaciones', '98532145321564879667', '654723165', 'administrador', 0, 0),
('22222222J', 'ApellidoTr2 Apellido2Tr', 'Nombre2Tr', '/var/www/html/images/22222222J.png', 'Direccion 2, calle 2', 'email2@gmail.com', '1989-07-21', 'Observo que este trabajador...', '98532145321564349634', '722356498', 'monitor', 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Trabajador_Evento`
--

CREATE TABLE IF NOT EXISTS `Trabajador_Evento` (
  `DNI` varchar(9) NOT NULL,
  `Id_Evento` int(20) NOT NULL,
  `Borrado` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Trabajador_Evento`
--

INSERT INTO `Trabajador_Evento` (`DNI`, `Id_Evento`, `Borrado`) VALUES
('22222222J', 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Usuario`
--

CREATE TABLE IF NOT EXISTS `Usuario` (
  `DNI` varchar(9) DEFAULT NULL,
  `Nombre_Grupo` varchar(30),
  `Password` varchar(40) NOT NULL,
  `Usuario` varchar(30) NOT NULL,
  `Borrado` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Usuario`
--

INSERT INTO `Usuario` (`DNI`, `Nombre_Grupo`, `Password`, `Usuario`, `Borrado`) VALUES
('11111111H', 'Monitor', '08b5411f848a2581a41672a759c87380', 'monitor', 0),
('22222222J', 'Secretario', '09ca0d5095609fe35bb7c9c7246e3cae', 'secretario', 0);
INSERT INTO `Usuario` (`Nombre_Grupo`, `Password`, `Usuario`, `Borrado`) VALUES
('Admin', '21232f297a57a5a743894a0e4a801fc3', 'admin', 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `Actividad`
--
ALTER TABLE `Actividad`
 ADD PRIMARY KEY (`Id_Actividad`), ADD KEY `DNI_Trabajador` (`DNI_Trabajador`), ADD KEY `Id_Espacio` (`Id_Espacio`), ADD KEY `Id_Calendario` (`Id_Calendario`);

--
-- Indices de la tabla `Alumno`
--
ALTER TABLE `Alumno`
 ADD PRIMARY KEY (`DNI`);

--
-- Indices de la tabla `Asistencia`
--
ALTER TABLE `Asistencia`
 ADD PRIMARY KEY (`DNI`,`Fecha_Asistencia`,`Id_Actividad`), ADD KEY `Id_Actividad` (`Id_Actividad`);

--
-- Indices de la tabla `Calendario`
--
ALTER TABLE `Calendario`
 ADD PRIMARY KEY (`Id_Calendario`);

--
-- Indices de la tabla `Cliente_Externo`
--
ALTER TABLE `Cliente_Externo`
 ADD PRIMARY KEY (`Id_Cliente`), ADD UNIQUE KEY `DNI` (`DNI`);
 
--
-- Indices de la tabla `Cobro`
--
ALTER TABLE `Cobro`
 ADD PRIMARY KEY (`Id_Cobro`), ADD KEY `DNI_Alumno` (`DNI_Alumno`);

--
-- Indices de la tabla `Controlador`
--
ALTER TABLE `Controlador`
 ADD PRIMARY KEY (`Nombre_Controlador`,`Accion`);

--
-- Indices de la tabla `Descuento`
--
ALTER TABLE `Descuento`
 ADD PRIMARY KEY (`Id_Descuento`);

--
-- Indices de la tabla `Documento`
--
ALTER TABLE `Documento`
 ADD PRIMARY KEY (`DNI`,`Tipo_Documento`,`Fecha_Documento`);

--
-- Indices de la tabla `Espacio`
--
ALTER TABLE `Espacio`
 ADD PRIMARY KEY (`Id_Espacio`);

--
-- Indices de la tabla `Evento`
--
ALTER TABLE `Evento`
 ADD PRIMARY KEY (`Id_Evento`);

--
-- Indices de la tabla `Factura`
--
ALTER TABLE `Factura`
 ADD KEY `Factura_ibfk_1` (`Id_Cliente`);

--
-- Indices de la tabla `Grupo`
--
ALTER TABLE `Grupo`
 ADD PRIMARY KEY (`Nombre_Grupo`);

--
-- Indices de la tabla `Horario`
--
ALTER TABLE `Horario`
 ADD PRIMARY KEY (`Id_Horario`), ADD KEY `Id_Calendario` (`Id_Calendario`);

--
-- Indices de la tabla `Inscripcion`
--
ALTER TABLE `Inscripcion`
 ADD PRIMARY KEY (`DNI_A`,`Id_Actividad`), ADD KEY `Id_Actividad` (`Id_Actividad`);

--
-- Indices de la tabla `Jornada`
--
ALTER TABLE `Jornada`
 ADD PRIMARY KEY (`DNI`,`Hora_Entrada`,`Hora_Salida`);

--
-- Indices de la tabla `Lesion`
--
ALTER TABLE `Lesion`
 ADD PRIMARY KEY (`Id_Lesion`);

--
-- Indices de la tabla `Log_Lesion`
--
ALTER TABLE `Log_Lesion`
 ADD PRIMARY KEY (`DNI_T`,`DNI_A`,`Id_Lesion`), ADD KEY `DNI_A` (`DNI_A`);

--
-- Indices de la tabla `Masaje`
--
ALTER TABLE `Masaje`
 ADD PRIMARY KEY (`Id_Masaje`);

--
-- Indices de la tabla `Permisos`
--
ALTER TABLE `Permisos`
 ADD PRIMARY KEY (`Nombre_Grupo`,`Nombre_Controlador`,`Accion`), ADD KEY `Nombre_Controlador` (`Nombre_Controlador`);

--
-- Indices de la tabla `Reserva_Evento`
--
ALTER TABLE `Reserva_Evento`
 ADD PRIMARY KEY (`Id_Evento`,`Id_Reserva`);

--
-- Indices de la tabla `Reserva_Masaje`
--
ALTER TABLE `Reserva_Masaje`
 ADD PRIMARY KEY (`Id_Masaje`,`Id_Reserva`,`DNI_Alumno`,`DNI_Trabajador`), ADD KEY `Id_Masaje` (`Id_Masaje`), ADD KEY `DNI_A` (`DNI_Alumno`), ADD KEY `DNI_T` (`DNI_Trabajador`);

--
-- Indices de la tabla `Servicio`
--
ALTER TABLE `Servicio`
 ADD PRIMARY KEY (`Id_Servicio`);

--
-- Indices de la tabla `Telefonos_Alumno`
--
ALTER TABLE `Telefonos_Alumno`
 ADD PRIMARY KEY (`DNI`,`Telefono`);

--
-- Indices de la tabla `Trabajador`
--
ALTER TABLE `Trabajador`
 ADD PRIMARY KEY (`DNI`);

--
-- Indices de la tabla `Trabajador_Evento`
--
ALTER TABLE `Trabajador_Evento`
 ADD PRIMARY KEY (`DNI`,`Id_Evento`), ADD KEY `Id_Evento` (`Id_Evento`);

--
-- Indices de la tabla `Usuario`
--
ALTER TABLE `Usuario`
 ADD PRIMARY KEY (`Usuario`), ADD KEY `Nombre_Grupo` (`Nombre_Grupo`), ADD KEY `Usuario_ibfk_3` (`DNI`);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `Actividad`
--
ALTER TABLE `Actividad`
ADD CONSTRAINT `Actividad_ibfk_1` FOREIGN KEY (`DNI_Trabajador`) REFERENCES `Trabajador` (`DNI`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `Actividad_ibfk_2` FOREIGN KEY (`Id_Espacio`) REFERENCES `Espacio` (`Id_Espacio`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `Actividad_ibfk_3` FOREIGN KEY (`Id_Calendario`) REFERENCES `Calendario` (`Id_Calendario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `Asistencia`
--
ALTER TABLE `Asistencia`
ADD CONSTRAINT `Asistencia_ibfk_1` FOREIGN KEY (`DNI`) REFERENCES `Alumno` (`DNI`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `Asistencia_ibfk_2` FOREIGN KEY (`Id_Actividad`) REFERENCES `Actividad` (`Id_Actividad`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `Cobro`
--
ALTER TABLE `Cobro`
ADD CONSTRAINT `Cobro_ibfk_1` FOREIGN KEY (`DNI_Alumno`) REFERENCES `Inscripcion` (`DNI_A`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `Documento`
--
ALTER TABLE `Documento`
ADD CONSTRAINT `Documento_ibfk_1` FOREIGN KEY (`DNI`) REFERENCES `Alumno` (`DNI`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `Factura`
--
ALTER TABLE `Factura`
ADD CONSTRAINT `Factura_ibfk_1` FOREIGN KEY (`Id_Cliente`) REFERENCES `Cliente_Externo` (`Id_Cliente`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `Horario`
--
ALTER TABLE `Horario`
ADD CONSTRAINT `Horario_ibfk_1` FOREIGN KEY (`Id_Calendario`) REFERENCES `Calendario` (`Id_Calendario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `Inscripcion`
--
ALTER TABLE `Inscripcion`
ADD CONSTRAINT `Inscripcion_ibfk_1` FOREIGN KEY (`DNI_A`) REFERENCES `Alumno` (`DNI`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `Inscripcion_ibfk_2` FOREIGN KEY (`Id_Actividad`) REFERENCES `Actividad` (`Id_Actividad`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `Jornada`
--
ALTER TABLE `Jornada`
ADD CONSTRAINT `Jornada_ibfk_1` FOREIGN KEY (`DNI`) REFERENCES `Trabajador` (`DNI`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `Linea_Factura`
--
ALTER TABLE `Linea_Factura`
ADD CONSTRAINT `Linea_Factura_ibfk_1` FOREIGN KEY (`Id_Servicio`) REFERENCES `Servicio` (`Id_Servicio`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `Linea_Factura_ibfk_2` FOREIGN KEY (`Id_Factura`) REFERENCES `Factura` (`Id_Factura`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `Log_Lesion`
--
ALTER TABLE `Log_Lesion`
ADD CONSTRAINT `Log_Lesion_ibfk_1` FOREIGN KEY (`DNI_T`) REFERENCES `Trabajador` (`DNI`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `Log_Lesion_ibfk_2` FOREIGN KEY (`DNI_A`) REFERENCES `Alumno` (`DNI`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `Permisos`
--
ALTER TABLE `Permisos`
ADD CONSTRAINT `Permisos_ibfk_1` FOREIGN KEY (`Nombre_Grupo`) REFERENCES `Grupo` (`Nombre_Grupo`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `Permisos_ibfk_2` FOREIGN KEY (`Nombre_Controlador`) REFERENCES `Controlador` (`Nombre_Controlador`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `Reserva_Espacio`
--
ALTER TABLE `Reserva_Espacio`
ADD CONSTRAINT `Reserva_Espacio_ibfk_1` FOREIGN KEY (`Id_Espacio`) REFERENCES `Espacio` (`Id_Espacio`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `Reserva_Evento`
--
ALTER TABLE `Reserva_Evento`
ADD CONSTRAINT `Reserva_Evento_ibfk_1` FOREIGN KEY (`Id_Evento`) REFERENCES `Evento` (`Id_Evento`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `Reserva_Masaje`
--
ALTER TABLE `Reserva_Masaje`
ADD CONSTRAINT `Reserva_Masaje_ibfk_1` FOREIGN KEY (`Id_Masaje`) REFERENCES `Masaje` (`Id_Masaje`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `Reserva_Masaje_ibfk_2` FOREIGN KEY (`DNI_Alumno`) REFERENCES `Alumno` (`DNI`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `Reserva_Masaje_ibfk_3` FOREIGN KEY (`DNI_Trabajador`) REFERENCES `Trabajador` (`DNI`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `Servicio`
--
ALTER TABLE `Servicio`
ADD CONSTRAINT `Servicio_ibfk_1` FOREIGN KEY (`Id_Trabajador`) REFERENCES `Trabajador` (`DNI`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `Telefonos_Alumno`
--
ALTER TABLE `Telefonos_Alumno`
ADD CONSTRAINT `Telefonos_Alumno_ibfk_1` FOREIGN KEY (`DNI`) REFERENCES `Alumno` (`DNI`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `Trabajador_Evento`
--
ALTER TABLE `Trabajador_Evento`
ADD CONSTRAINT `Trabajador_Evento_ibfk_1` FOREIGN KEY (`DNI`) REFERENCES `Trabajador` (`DNI`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `Trabajador_Evento_ibfk_2` FOREIGN KEY (`Id_Evento`) REFERENCES `Evento` (`Id_Evento`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `Usuario`
--
ALTER TABLE `Usuario`
ADD CONSTRAINT `Usuario_ibfk_2` FOREIGN KEY (`Nombre_Grupo`) REFERENCES `Grupo` (`Nombre_Grupo`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `Usuario_ibfk_3` FOREIGN KEY (`DNI`) REFERENCES `Trabajador` (`DNI`) ON DELETE CASCADE ON UPDATE CASCADE;

-- AUTO_INCREMENT de la tabla `Lesion`
--
ALTER TABLE `Lesion`
MODIFY `Id_Lesion` int(9) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=252525253;
--
