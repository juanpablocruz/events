-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 24-10-2012 a las 15:20:25
-- Versión del servidor: 5.5.16
-- Versión de PHP: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `events`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `events`
--

CREATE TABLE IF NOT EXISTS `events` (
  `IdEvent` int(11) NOT NULL,
  `UserId` int(11) NOT NULL,
  `Status` tinyint(1) NOT NULL,
  KEY `IdEvent` (`IdEvent`),
  KEY `UserId` (`UserId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `foro`
--

CREATE TABLE IF NOT EXISTS `foro` (
  `IdHilo` int(11) NOT NULL,
  `IdForo` int(11) NOT NULL,
  `Nivel` int(11) NOT NULL,
  `IdCreator` int(11) NOT NULL,
  `Estado` char(11) COLLATE utf8_spanish_ci NOT NULL,
  `Titulo` text COLLATE utf8_spanish_ci NOT NULL,
  `Contenido` text COLLATE utf8_spanish_ci,
  `Respondido` tinyint(3) unsigned zerofill DEFAULT NULL,
  `FechaCreacion` datetime NOT NULL,
  KEY `IdGroup` (`IdHilo`),
  KEY `IdMessage` (`IdForo`),
  KEY `UserId` (`Nivel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `IdGroup` int(11) NOT NULL AUTO_INCREMENT,
  `IdCreator` int(11) NOT NULL,
  `Open` tinyint(1) NOT NULL,
  `Group_Name` char(30) CHARACTER SET latin1 NOT NULL,
  `Type` tinyint(1) NOT NULL,
  PRIMARY KEY (`IdGroup`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `listevents`
--

CREATE TABLE IF NOT EXISTS `listevents` (
  `IdEvent` int(11) NOT NULL AUTO_INCREMENT,
  `IdCreator` int(11) NOT NULL,
  `Name` char(40) CHARACTER SET latin1 NOT NULL,
  `Description` longtext CHARACTER SET latin1 NOT NULL,
  `StartDate` datetime NOT NULL,
  `ExpireDate` datetime NOT NULL,
  `Place` char(40) CHARACTER SET latin1 NOT NULL,
  `DMovil` tinyint(1) NOT NULL,
  `DMail` tinyint(1) NOT NULL,
  `DEvents` tinyint(1) NOT NULL,
  PRIMARY KEY (`IdEvent`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `listmessages`
--

CREATE TABLE IF NOT EXISTS `listmessages` (
  `IdMessage` int(11) NOT NULL AUTO_INCREMENT,
  `IdWriter` int(11) NOT NULL,
  `IdReader` int(11) NOT NULL,
  `DateWrited` datetime DEFAULT NULL,
  `Text` text,
  PRIMARY KEY (`IdMessage`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `login`
--

CREATE TABLE IF NOT EXISTS `login` (
  `UserId` int(11) NOT NULL,
  `Date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `members`
--

CREATE TABLE IF NOT EXISTS `members` (
  `UserId` int(11) NOT NULL AUTO_INCREMENT,
  `Email` char(255) CHARACTER SET latin1 NOT NULL,
  `Password` char(255) CHARACTER SET latin1 NOT NULL,
  `Name` char(30) CHARACTER SET latin1 NOT NULL,
  `LastName` char(50) CHARACTER SET latin1 NOT NULL,
  `Phone` int(10) NOT NULL,
  `Birthday` date NOT NULL,
  `Img` char(30) CHARACTER SET latin1 NOT NULL,
  `RegisterDay` datetime NOT NULL,
  `Auth_Key` char(255) CHARACTER SET latin1 NOT NULL,
  `Active` tinyint(1) NOT NULL,
  `Online` tinyint(1) NOT NULL,
  `LastLog` datetime NOT NULL,
  `Adress` char(50) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`UserId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `messages`
--

CREATE TABLE IF NOT EXISTS `messages` (
  `IdMessage` int(11) NOT NULL,
  `IdWriter` int(11) NOT NULL,
  `IdReader` int(11) NOT NULL,
  `Status` int(11) NOT NULL,
  `Type` int(11) NOT NULL DEFAULT '0',
  KEY `IdMessage` (`IdMessage`),
  KEY `IdWriter` (`IdWriter`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `relations`
--

CREATE TABLE IF NOT EXISTS `relations` (
  `IdFollower` int(11) NOT NULL,
  `IdFollowed` int(11) NOT NULL,
  `IdGroup` int(11) NOT NULL,
  `Status` int(11) NOT NULL DEFAULT '0',
  KEY `IdFollower` (`IdFollower`),
  KEY `IdFollowed` (`IdFollowed`),
  KEY `IdGroup` (`IdGroup`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
