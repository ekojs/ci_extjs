/*
Navicat MySQL Data Transfer

Source Server         : EKOJS-PC
Source Server Version : 50621
Source Host           : localhost:3306
Source Database       : test

Target Server Type    : MYSQL
Target Server Version : 50621
File Encoding         : 65001

Date: 2016-04-05 21:10:02
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `kategori`
-- ----------------------------
DROP TABLE IF EXISTS `kategori`;
CREATE TABLE `kategori` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nama_UNIQUE` (`nama`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of kategori
-- ----------------------------
INSERT INTO `kategori` VALUES ('4', 'Agama');
INSERT INTO `kategori` VALUES ('5', 'Buku');
INSERT INTO `kategori` VALUES ('1', 'Kebudayaan');
INSERT INTO `kategori` VALUES ('2', 'Komputer');
INSERT INTO `kategori` VALUES ('3', 'Politik');
INSERT INTO `kategori` VALUES ('6', 'Security');
INSERT INTO `kategori` VALUES ('7', 'Sejarah');
INSERT INTO `kategori` VALUES ('8', 'tes');

-- ----------------------------
-- Table structure for `logger`
-- ----------------------------
DROP TABLE IF EXISTS `logger`;
CREATE TABLE `logger` (
  `Session_id` varchar(255) NOT NULL,
  `Username` varchar(45) NOT NULL,
  `Login` datetime DEFAULT NULL,
  `Logout` datetime DEFAULT NULL,
  `Keterangan` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`Session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of logger
-- ----------------------------
INSERT INTO `logger` VALUES ('34523', 'asdfsaf', '2016-04-08 00:00:00', '2016-04-19 00:00:00', 'dfaasfsaf');
INSERT INTO `logger` VALUES ('asdfasf', 'sdfgdsfg', null, null, 'fafda');

-- ----------------------------
-- Table structure for `penerbit`
-- ----------------------------
DROP TABLE IF EXISTS `penerbit`;
CREATE TABLE `penerbit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(45) NOT NULL,
  `alamat` text,
  `web` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nama_UNIQUE` (`nama`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of penerbit
-- ----------------------------
INSERT INTO `penerbit` VALUES ('1', 'Airlangga', 'Surabaya', 'Airlangga.com', 'abc@gmail.com');
INSERT INTO `penerbit` VALUES ('2', 'Yudhistira Cahya Baskoro', 'Sultan Hasanuddin 1', 'baskoro.com', 'cebe@baskoro.com');

-- ----------------------------
-- Table structure for `pengarang`
-- ----------------------------
DROP TABLE IF EXISTS `pengarang`;
CREATE TABLE `pengarang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(45) NOT NULL,
  `email` varchar(45) DEFAULT NULL,
  `telp` varchar(15) DEFAULT NULL,
  `foto` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pengarang
-- ----------------------------
INSERT INTO `pengarang` VALUES ('1', 'Eko Junaidi Salam', 'ekojs@kejaksaan.go.id', '123124235', 'foto_Eko Junaidi Salam.jpg');
INSERT INTO `pengarang` VALUES ('2', 'Anonymous', 'anonim@email.com', '11223344', 'anonymous.jpg');
INSERT INTO `pengarang` VALUES ('3', 'Yudhis', 'yudhis@gmail.com', '34563463', 'foto_yudhis.jpg');