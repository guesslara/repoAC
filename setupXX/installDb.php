<?php
/*
 *Estructura de la base de datos para la interfaz principal, las tablas adicionales se tienen que importar de manera separada
 *Esta parte del script es siguiendo un asistente de configuracion del sistema que añadira las tablas necesarias en la base de datos seleccionada
 *previamente se solicitan los datos del servidor y se configura el archivo de configuracion asi como el acceso y preparacion de conexion a la base de datos.
 *
 *El diseño incluye un menu general llamado Admin por Default y una opción unica que es Administrar Sistema, en el cual se mostraran las opciones generales
 *del modulo y las partes que lo componen.
 *
 *8 de Mayo de 2013
 *
 *Autor: Gerardo Lara - Programador Analista
 *
*/
$sql="CREATE TABLE IF NOT EXISTS `cambiossistema` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(200) NOT NULL,
  `fecha` datetime NOT NULL,
  `status` varchar(10) NOT NULL,
  `descripcion` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

$sql="CREATE TABLE IF NOT EXISTS `configuracionglobal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombreConf` varchar(60) NOT NULL,
  `valor` varchar(30) NOT NULL,
  `descripcion` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;";

$sql="INSERT INTO `configuracionglobal` (`id`, `nombreConf`, `valor`, `descripcion`) VALUES
(1, 'sitio_desactivado', 'No', '');";

$sql="CREATE TABLE IF NOT EXISTS `errores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `des` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

$sql="CREATE TABLE IF NOT EXISTS `grupos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_hora_creacion` datetime NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `activo` smallint(1) NOT NULL DEFAULT '1',
  `opcFuncional` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;";

$sql="INSERT INTO `grupos` (`id`, `fecha_hora_creacion`, `nombre`, `activo`, `opcFuncional`) VALUES
(1, '2013-05-08 00:00:00', 'Admin', 1, '1,');";

$sql="CREATE TABLE IF NOT EXISTS `gruposmods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `modulo` varchar(50) NOT NULL,
  `pertenece_a` varchar(50) NOT NULL,
  `pertenece_a_menu` int(11) NOT NULL,
  `numeroMenu` int(11) NOT NULL,
  `activo` smallint(1) NOT NULL DEFAULT '1',
  `ruta` text NOT NULL,
  `rutaMenuSub` varchar(50) NOT NULL,
  `rutaimg` varchar(100) NOT NULL,
  `moduloSubMenu` varchar(200) NOT NULL,
  `R` smallint(1) NOT NULL DEFAULT '0',
  `W` smallint(1) NOT NULL DEFAULT '0',
  `X` smallint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;";

$sql="INSERT INTO `gruposmods` (`id`, `modulo`, `pertenece_a`, `pertenece_a_menu`, `numeroMenu`, `activo`, `ruta`, `rutaMenuSub`, `rutaimg`, `moduloSubMenu`, `R`, `W`, `X`) VALUES
(1, 'Admin', 'Menu', 1, 999, 1, '', '', '', '', 0, 0, 0);";

$sql="CREATE TABLE IF NOT EXISTS `submenu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_menu` int(11) NOT NULL,
  `nombreSubMenu` varchar(100) NOT NULL,
  `rutaSubMenu` varchar(300) NOT NULL,
  `activo` tinyint(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;";

$sql="INSERT INTO `submenu` (`id`, `id_menu`, `nombreSubMenu`, `rutaSubMenu`, `activo`) VALUES
(1, 1, 'Administrar Sistema', '../modulos/mod_admon3/index.php', 1);";

$sql="CREATE TABLE IF NOT EXISTS `userautoriza` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `nip` varchar(32) NOT NULL,
  `monto` varchar(50) NOT NULL COMMENT 'Monto a Autorizar',
  `intentos` int(11) NOT NULL DEFAULT '0' COMMENT 'numIntentos',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

$sql="CREATE TABLE IF NOT EXISTS `usuariosControl` (
  `ID` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `usuario` tinytext NOT NULL,
  `pass` tinytext NOT NULL,
  `nombre` varchar(35) NOT NULL,
  `apaterno` varchar(30) NOT NULL,
  `nivel_acceso` smallint(4) unsigned NOT NULL DEFAULT '0',
  `cambiarPass` int(11) NOT NULL DEFAULT '0',
  `directorio` varchar(100) NOT NULL DEFAULT 'Sin directorio',
  `sexo` char(3) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `nomina` int(11) NOT NULL,
  `grupo` varchar(20) NOT NULL,
  `grupo2` int(11) NOT NULL,
  `obs` varchar(1000) NOT NULL,
  `activo` smallint(1) NOT NULL DEFAULT '1',
  `fecha_creacion` date NOT NULL,
  `hora_creacion` time NOT NULL,
  `fecha_eliminacion` date NOT NULL,
  `hora_eliminacion` time NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;";

$sql="INSERT INTO `usuariosControl` (`ID`, `usuario`, `pass`, `nombre`, `apaterno`, `nivel_acceso`, `cambiarPass`, `directorio`, `sexo`, `tipo`, `nomina`, `grupo`, `grupo2`, `obs`, `activo`, `fecha_creacion`, `hora_creacion`, `fecha_eliminacion`, `hora_eliminacion`) VALUES
(1, 'Admin', '0cc175b9c0f1b6a831c399e269772661', 'Administrador ', 'Sistema', 0, 1, 'Sin directorio', 'M', '', 2441, '1', 1, '', 1, '0000-00-00', '00:00:00', '0000-00-00', '00:00:00');";

?>