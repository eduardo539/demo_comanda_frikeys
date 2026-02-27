/*
SQLyog Ultimate v11.11 (64 bit)
MySQL - 5.5.5-10.4.32-MariaDB : Database - bd_frikeys
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`bd_frikeys` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `bd_frikeys`;

/*Table structure for table `categoria` */

DROP TABLE IF EXISTS `categoria`;

CREATE TABLE `categoria` (
  `categoria_id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria` varchar(250) NOT NULL,
  PRIMARY KEY (`categoria_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `categoria` */

LOCK TABLES `categoria` WRITE;

UNLOCK TABLES;

/*Table structure for table `detalle_pedido` */

DROP TABLE IF EXISTS `detalle_pedido`;

CREATE TABLE `detalle_pedido` (
  `detalle_id` int(11) NOT NULL AUTO_INCREMENT,
  `folio_pedido` varchar(200) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `total` double NOT NULL,
  `mesa_id` int(11) NOT NULL,
  `estado_id` int(11) NOT NULL,
  PRIMARY KEY (`detalle_id`),
  KEY `producto_id` (`producto_id`),
  KEY `mesa_id` (`mesa_id`),
  KEY `estado_id` (`estado_id`),
  CONSTRAINT `estado_id` FOREIGN KEY (`estado_id`) REFERENCES `estado_pedido` (`estado_id`),
  CONSTRAINT `mesa_id` FOREIGN KEY (`mesa_id`) REFERENCES `mesa` (`mesa_id`),
  CONSTRAINT `producto_id` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`producto_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `detalle_pedido` */

LOCK TABLES `detalle_pedido` WRITE;

UNLOCK TABLES;

/*Table structure for table `estado_pedido` */

DROP TABLE IF EXISTS `estado_pedido`;

CREATE TABLE `estado_pedido` (
  `estado_id` int(11) NOT NULL AUTO_INCREMENT,
  `estado_pedido` varchar(50) NOT NULL,
  PRIMARY KEY (`estado_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `estado_pedido` */

LOCK TABLES `estado_pedido` WRITE;

insert  into `estado_pedido`(`estado_id`,`estado_pedido`) values (1,'RECIBIDO'),(2,'EN PREPARACIÓN'),(3,'LISTO'),(4,'CANCELADO');

UNLOCK TABLES;

/*Table structure for table `estados` */

DROP TABLE IF EXISTS `estados`;

CREATE TABLE `estados` (
  `estado_gen_id` int(11) NOT NULL AUTO_INCREMENT,
  `estado` varchar(50) NOT NULL,
  PRIMARY KEY (`estado_gen_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `estados` */

LOCK TABLES `estados` WRITE;

insert  into `estados`(`estado_gen_id`,`estado`) values (1,'ACTIVO'),(2,'INACTIVO');

UNLOCK TABLES;

/*Table structure for table `mesa` */

DROP TABLE IF EXISTS `mesa`;

CREATE TABLE `mesa` (
  `mesa_id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_mesa` varchar(50) NOT NULL,
  `uuid` varchar(500) NOT NULL,
  `qr_img` text NOT NULL,
  PRIMARY KEY (`mesa_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `mesa` */

LOCK TABLES `mesa` WRITE;

insert  into `mesa`(`mesa_id`,`nombre_mesa`,`uuid`,`qr_img`) values (1,'MESA 1','123456','');

UNLOCK TABLES;

/*Table structure for table `productos` */

DROP TABLE IF EXISTS `productos`;

CREATE TABLE `productos` (
  `producto_id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria_id` int(11) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `descripcion` varchar(500) NOT NULL,
  `costo` double NOT NULL,
  `estado_gen_id` int(11) NOT NULL,
  `imagen` text NOT NULL,
  PRIMARY KEY (`producto_id`),
  KEY `categoria_id` (`categoria_id`),
  KEY `nombre` (`estado_gen_id`),
  CONSTRAINT `categoria_id` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`categoria_id`),
  CONSTRAINT `nombre` FOREIGN KEY (`estado_gen_id`) REFERENCES `estados` (`estado_gen_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `productos` */

LOCK TABLES `productos` WRITE;

UNLOCK TABLES;

/*Table structure for table `roles` */

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `rol_id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_rol` varchar(150) NOT NULL,
  PRIMARY KEY (`rol_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `roles` */

LOCK TABLES `roles` WRITE;

insert  into `roles`(`rol_id`,`nombre_rol`) values (1,'ADMINISTRADOR'),(2,'COCINA');

UNLOCK TABLES;

/*Table structure for table `usuario` */

DROP TABLE IF EXISTS `usuario`;

CREATE TABLE `usuario` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(100) NOT NULL,
  `Apellidos` varchar(250) NOT NULL,
  `telefono` varchar(10) NOT NULL,
  `edad` int(11) NOT NULL,
  `usuario` varchar(100) NOT NULL,
  `passw` varchar(200) NOT NULL,
  `rol_id` int(11) NOT NULL,
  `estado_gen_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`),
  KEY `rol_id` (`rol_id`),
  KEY `estado_gen_id` (`estado_gen_id`),
  CONSTRAINT `estado_gen_id` FOREIGN KEY (`estado_gen_id`) REFERENCES `estados` (`estado_gen_id`),
  CONSTRAINT `rol_id` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`rol_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `usuario` */

LOCK TABLES `usuario` WRITE;

insert  into `usuario`(`user_id`,`Nombre`,`Apellidos`,`telefono`,`edad`,`usuario`,`passw`,`rol_id`,`estado_gen_id`) values (1,'EDUARDO','DOMINGUEZ','1234567890',25,'eduardo539','123456',1,1);

UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
