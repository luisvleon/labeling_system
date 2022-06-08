-- phpMyAdmin SQL Dump
-- version 2.8.0-rc1
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: May 03, 2011 at 11:40 PM
-- Server version: 5.0.51
-- PHP Version: 5.2.6
-- 
-- Database: `labels`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `invoice_active`
-- 

CREATE TABLE `invoice_active` (
  `invid` tinyint(1) NOT NULL,
  `orden_id` smallint(6) NOT NULL,
  `fecha` varchar(10) collate utf8_spanish_ci NOT NULL,
  `shipto_name` varchar(40) collate utf8_spanish_ci NOT NULL,
  `shipto_add` varchar(60) collate utf8_spanish_ci NOT NULL,
  `shipto_add2` varchar(60) collate utf8_spanish_ci NOT NULL,
  `shipto_pho` varchar(40) collate utf8_spanish_ci NOT NULL,
  `cos_id` smallint(6) NOT NULL,
  `user_id` tinyint(2) NOT NULL,
  PRIMARY KEY  (`invid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- 
-- Dumping data for table `invoice_active`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `tbl_company`
-- 

CREATE TABLE `tbl_company` (
  `id` tinyint(1) NOT NULL,
  `razon` varchar(25) collate utf8_spanish_ci NOT NULL,
  `slogan` varchar(50) collate utf8_spanish_ci NOT NULL,
  `ad1` varchar(60) collate utf8_spanish_ci NOT NULL,
  `ad2` varchar(60) collate utf8_spanish_ci NOT NULL,
  `phonos` varchar(60) collate utf8_spanish_ci NOT NULL,
  `email` varchar(40) collate utf8_spanish_ci NOT NULL,
  `web` varchar(40) collate utf8_spanish_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- 
-- Dumping data for table `tbl_company`
-- 

INSERT INTO `tbl_company` VALUES (5, 'Webxport Corporation', 'Clothing Wholesale Apparel', '4501 NW 3rd Ave', 'Miami, Fl, 33127', '(305) 786 3456 - (786) 234 3456', 'webxport@webxport.com', 'http://www.webxport.com');

-- --------------------------------------------------------

-- 
-- Table structure for table `tbl_costumers`
-- 

CREATE TABLE `tbl_costumers` (
  `cos_id` smallint(6) NOT NULL auto_increment,
  `cos_names` varchar(40) collate utf8_spanish_ci NOT NULL,
  `cos_corpname` varchar(40) collate utf8_spanish_ci NOT NULL,
  `cos_ruc` varchar(13) collate utf8_spanish_ci NOT NULL,
  `cos_email` varchar(40) collate utf8_spanish_ci NOT NULL,
  `cos_phone1` varchar(20) collate utf8_spanish_ci NOT NULL,
  `cos_phone2` varchar(20) collate utf8_spanish_ci default NULL,
  `cos_fax` varchar(20) collate utf8_spanish_ci default NULL,
  `pais_label` varchar(25) collate utf8_spanish_ci NOT NULL,
  `ciudad_label` varchar(25) collate utf8_spanish_ci NOT NULL,
  `cos_adress` varchar(60) collate utf8_spanish_ci NOT NULL,
  `cos_comments` varchar(250) collate utf8_spanish_ci default NULL,
  `cos_userid` tinyint(2) NOT NULL,
  PRIMARY KEY  (`cos_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='costumers table' AUTO_INCREMENT=3 ;

-- 
-- Dumping data for table `tbl_costumers`
-- 

INSERT INTO `tbl_costumers` VALUES (1, 'Luis Leon V', 'Websoft S.A.', '0915335236001', 'luisleonv@hotmail.com', '3054015393', NULL, NULL, 'Ecuador', 'Guayaquil', '4501 NW 3rd Ave', NULL, 1);
INSERT INTO `tbl_costumers` VALUES (2, 'Carlos Orellana', 'Oroexport', '1090122222001', 'carlos@hotmail.com', '098908908', '90890', '09908', 'Ecuador', 'Machala', 'klkjh', 'test', 1);

-- --------------------------------------------------------

-- 
-- Table structure for table `tbl_duplicate`
-- 

CREATE TABLE `tbl_duplicate` (
  `ord_id` smallint(6) NOT NULL,
  `rep_num` smallint(4) NOT NULL,
  KEY `ord_id` (`ord_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- 
-- Dumping data for table `tbl_duplicate`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `tbl_invoice`
-- 

CREATE TABLE `tbl_invoice` (
  `invoice_id` smallint(6) NOT NULL auto_increment,
  `cos_id` smallint(6) NOT NULL,
  `shipto_id` smallint(6) NOT NULL,
  `fecha_in` varchar(10) collate utf8_spanish_ci NOT NULL,
  `fecha_out` varchar(10) collate utf8_spanish_ci NOT NULL,
  `comments` text collate utf8_spanish_ci NOT NULL,
  `user_id` tinyint(2) NOT NULL,
  PRIMARY KEY  (`invoice_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Tabla de orden de trabajo' AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `tbl_invoice`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `tbl_mat_labels`
-- 

CREATE TABLE `tbl_mat_labels` (
  `mat_lab_id` mediumint(8) NOT NULL auto_increment,
  `ord_id` smallint(6) NOT NULL,
  `stu_id` tinyint(2) NOT NULL,
  `stu_percen` varchar(3) collate utf8_spanish_ci NOT NULL,
  PRIMARY KEY  (`mat_lab_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Tabla de materiales de la etiqueta' AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `tbl_mat_labels`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `tbl_order`
-- 

CREATE TABLE `tbl_order` (
  `ord_id` smallint(6) NOT NULL auto_increment,
  `cos_id` smallint(6) NOT NULL,
  `ori_id` smallint(6) NOT NULL,
  `size_id` tinyint(2) NOT NULL,
  `ord_date` date NOT NULL,
  `ord_status` tinyint(1) default '1',
  `user_id` tinyint(2) NOT NULL,
  PRIMARY KEY  (`ord_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `tbl_order`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `tbl_origin`
-- 

CREATE TABLE `tbl_origin` (
  `ori_id` tinyint(2) NOT NULL auto_increment,
  `ori_name` varchar(20) collate utf8_spanish_ci NOT NULL,
  PRIMARY KEY  (`ori_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Countries table' AUTO_INCREMENT=6 ;

-- 
-- Dumping data for table `tbl_origin`
-- 

INSERT INTO `tbl_origin` VALUES (1, 'USA');
INSERT INTO `tbl_origin` VALUES (2, 'CHINA');
INSERT INTO `tbl_origin` VALUES (3, 'FILIPINAS');
INSERT INTO `tbl_origin` VALUES (4, 'TAIWAN');
INSERT INTO `tbl_origin` VALUES (5, 'ECUADOR');

-- --------------------------------------------------------

-- 
-- Table structure for table `tbl_par_labels`
-- 

CREATE TABLE `tbl_par_labels` (
  `lar_lab_id` mediumint(8) NOT NULL auto_increment,
  `ord_id` smallint(6) NOT NULL,
  `par_id` tinyint(2) NOT NULL,
  PRIMARY KEY  (`lar_lab_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `tbl_par_labels`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `tbl_params`
-- 

CREATE TABLE `tbl_params` (
  `par_id` tinyint(2) NOT NULL auto_increment,
  `par_label` varchar(40) collate utf8_spanish_ci NOT NULL,
  PRIMARY KEY  (`par_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='parameters table' AUTO_INCREMENT=5 ;

-- 
-- Dumping data for table `tbl_params`
-- 

INSERT INTO `tbl_params` VALUES (1, 'Lavar a maquina');
INSERT INTO `tbl_params` VALUES (2, 'En agua fria');
INSERT INTO `tbl_params` VALUES (3, 'Secado a baja velocidad');
INSERT INTO `tbl_params` VALUES (4, 'No usar blanqueador');

-- --------------------------------------------------------

-- 
-- Table structure for table `tbl_ship_to`
-- 

CREATE TABLE `tbl_ship_to` (
  `shipto_id` smallint(4) NOT NULL auto_increment,
  `cos_id` smallint(6) NOT NULL,
  `shipto_name` varchar(40) collate utf8_spanish_ci NOT NULL,
  `shipto_address` varchar(60) collate utf8_spanish_ci NOT NULL,
  `shipto_phono` varchar(40) collate utf8_spanish_ci NOT NULL,
  `user_id` tinyint(2) NOT NULL,
  PRIMARY KEY  (`shipto_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='tabla de shipping' AUTO_INCREMENT=5 ;

-- 
-- Dumping data for table `tbl_ship_to`
-- 

INSERT INTO `tbl_ship_to` VALUES (1, 1, 'Expresito courrier', '1800 NW Esmeralda, El Doral, Fl, 33187', '305 345 6677', 1);
INSERT INTO `tbl_ship_to` VALUES (2, 1, 'Mariana Vera', 'Sauces 3 Mz 125 villa 12, Guayaquil, Ecuador', '2279743', 1);

-- --------------------------------------------------------

-- 
-- Table structure for table `tbl_sizes`
-- 

CREATE TABLE `tbl_sizes` (
  `size_id` tinyint(2) NOT NULL auto_increment,
  `size_text` varchar(3) collate utf8_spanish_ci NOT NULL,
  `size_label` varchar(20) collate utf8_spanish_ci NOT NULL,
  PRIMARY KEY  (`size_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='size tables' AUTO_INCREMENT=6 ;

-- 
-- Dumping data for table `tbl_sizes`
-- 

INSERT INTO `tbl_sizes` VALUES (1, 'S/P', 'PEQUENO');
INSERT INTO `tbl_sizes` VALUES (2, 'L/G', 'GRANDE');
INSERT INTO `tbl_sizes` VALUES (3, 'XL', 'EXTRA GRANDE');
INSERT INTO `tbl_sizes` VALUES (4, 'XXL', 'EXTRA EXTRA GRANDE');
INSERT INTO `tbl_sizes` VALUES (5, 't', 'tttujjj');

-- --------------------------------------------------------

-- 
-- Table structure for table `tbl_stuff`
-- 

CREATE TABLE `tbl_stuff` (
  `stu_id` tinyint(2) NOT NULL auto_increment,
  `stu_text` varchar(20) collate utf8_spanish_ci NOT NULL,
  PRIMARY KEY  (`stu_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Stuff kind table' AUTO_INCREMENT=4 ;

-- 
-- Dumping data for table `tbl_stuff`
-- 

INSERT INTO `tbl_stuff` VALUES (1, 'Algodon');
INSERT INTO `tbl_stuff` VALUES (2, 'Poliester');
INSERT INTO `tbl_stuff` VALUES (3, 'Rayon');

-- --------------------------------------------------------

-- 
-- Table structure for table `tbl_test`
-- 

CREATE TABLE `tbl_test` (
  `tes_1` varchar(4) collate utf8_spanish_ci default NULL,
  `tes_2` varchar(4) collate utf8_spanish_ci default NULL,
  `test_3` varchar(4) collate utf8_spanish_ci default NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- 
-- Dumping data for table `tbl_test`
-- 

INSERT INTO `tbl_test` VALUES ('test', 'test', 'test');
INSERT INTO `tbl_test` VALUES ('test', 'test', 'test');
INSERT INTO `tbl_test` VALUES ('test', 'test', 'test');
INSERT INTO `tbl_test` VALUES ('test', 'test', 'test');
INSERT INTO `tbl_test` VALUES ('test', 'test', 'test');
INSERT INTO `tbl_test` VALUES ('test', 'test', 'test');
INSERT INTO `tbl_test` VALUES ('test', 'test', 'test');
INSERT INTO `tbl_test` VALUES ('test', 'test', 'test');
INSERT INTO `tbl_test` VALUES ('test', 'test', 'test');
INSERT INTO `tbl_test` VALUES ('test', 'test', 'test');
INSERT INTO `tbl_test` VALUES ('test', 'test', 'test');
INSERT INTO `tbl_test` VALUES ('test', 'test', 'test');
INSERT INTO `tbl_test` VALUES ('test', 'test', 'test');

-- --------------------------------------------------------

-- 
-- Table structure for table `tbl_users`
-- 

CREATE TABLE `tbl_users` (
  `user_id` tinyint(2) NOT NULL auto_increment,
  `user_names` varchar(20) collate utf8_spanish_ci NOT NULL,
  `user_username` varchar(15) collate utf8_spanish_ci NOT NULL,
  `user_password` varchar(15) collate utf8_spanish_ci NOT NULL,
  PRIMARY KEY  (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=2 ;

-- 
-- Dumping data for table `tbl_users`
-- 

INSERT INTO `tbl_users` VALUES (1, 'Luis Leon', 'lleon', 'targus25');

-- --------------------------------------------------------

-- 
-- Table structure for table `tlb_status`
-- 

CREATE TABLE `tlb_status` (
  `sta_id` tinyint(2) NOT NULL,
  `sta_label` varchar(20) collate utf8_spanish_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='tabla de estatus de las etiquetas';

-- 
-- Dumping data for table `tlb_status`
-- 

INSERT INTO `tlb_status` VALUES (1, 'No impresas');
INSERT INTO `tlb_status` VALUES (2, 'para imprimir');
INSERT INTO `tlb_status` VALUES (3, 'Impresas');
