-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 30-01-2025 a las 18:54:35
-- Versión del servidor: 10.4.27-MariaDB
-- Versión de PHP: 8.0.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `bd_andar`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `idcliente` int(10) UNSIGNED NOT NULL,
  `cli_dniruc` varchar(11) NOT NULL,
  `cli_nombrerazon` varchar(100) NOT NULL,
  `cli_nombrecontact` varchar(100) DEFAULT NULL,
  `cli_correocontact` varchar(100) DEFAULT NULL,
  `cli_telefcontact` varchar(12) NOT NULL,
  `idusuario2` int(11) NOT NULL,
  `cli_fechareg` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`idcliente`, `cli_dniruc`, `cli_nombrerazon`, `cli_nombrecontact`, `cli_correocontact`, `cli_telefcontact`, `idusuario2`, `cli_fechareg`) VALUES
(1, '20125632148', 'Adecco Consulting S.A.', 'Martin Carlos Oviedo Serna', 'martin_10_88@gmail.com', '951357852', 16, '2025-01-27 14:56:25'),
(2, '10454872296', 'Luis Alberto Calderón Sánchez', 'Anita Carlota Santiesteban Miranda', 'asantiestebanmiranda@gmail.com', '975089485', 16, '2025-01-27 15:00:19'),
(3, '20145632145', 'Servicios de Terceros S.A.C.', 'Jorge Zelada Campos', 'jorgito_123456@gmail.com', '985741262', 16, '2025-01-27 15:05:08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_presupuesto`
--

CREATE TABLE `detalle_presupuesto` (
  `idpresupuesto` int(10) UNSIGNED NOT NULL,
  `idtorre` int(10) UNSIGNED NOT NULL,
  `dp_cant` int(11) NOT NULL,
  `dp_precio` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_torre`
--

CREATE TABLE `detalle_torre` (
  `idtorre` int(10) UNSIGNED NOT NULL,
  `idpieza` int(10) UNSIGNED NOT NULL,
  `dt_cantidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `detalle_torre`
--

INSERT INTO `detalle_torre` (`idtorre`, `idpieza`, `dt_cantidad`) VALUES
(2, 66, 5),
(2, 114, 7),
(5, 10, 20),
(5, 37, 15),
(5, 84, 21),
(5, 90, 10),
(5, 111, 28),
(6, 74, 41),
(6, 88, 8),
(6, 90, 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura`
--

CREATE TABLE `factura` (
  `idfactura` int(10) UNSIGNED NOT NULL,
  `fact_nro` varchar(20) NOT NULL,
  `fact_fecha` datetime NOT NULL,
  `idpresupuesto` int(10) UNSIGNED NOT NULL,
  `idusuario2` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `guia`
--

CREATE TABLE `guia` (
  `idguia` int(10) UNSIGNED NOT NULL,
  `gui_nro` varchar(20) NOT NULL,
  `gui_fecha` datetime NOT NULL,
  `gui_fechatraslado` datetime NOT NULL,
  `gui_motivotraslado` varchar(45) DEFAULT NULL,
  `gui_ptopartida` varchar(45) DEFAULT NULL,
  `gui_ptollegada` varchar(45) DEFAULT NULL,
  `gui_vehiculo` varchar(45) DEFAULT NULL,
  `idpresupuesto` int(10) UNSIGNED NOT NULL,
  `idtransportista` int(10) UNSIGNED NOT NULL,
  `idusuario2` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `parametros`
--

CREATE TABLE `parametros` (
  `idparametros` int(10) UNSIGNED NOT NULL,
  `par_porcensem` int(11) NOT NULL,
  `par_logo` varchar(100) DEFAULT NULL,
  `par_firma` varchar(100) DEFAULT NULL,
  `par_direcc` varchar(100) DEFAULT NULL,
  `par_telef` varchar(20) DEFAULT NULL,
  `par_correo` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `parametros`
--

INSERT INTO `parametros` (`idparametros`, `par_porcensem`, `par_logo`, `par_firma`, `par_direcc`, `par_telef`, `par_correo`) VALUES
(2, 12, 'logo.jpeg', 'firma.jpg', 'Avenida Petit Thouars Monopoly', '975089485', 'andamios_andar@hotmail.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pieza`
--

CREATE TABLE `pieza` (
  `idpieza` int(10) UNSIGNED NOT NULL,
  `pie_codigo` varchar(12) NOT NULL,
  `pie_desc` varchar(200) DEFAULT NULL,
  `pie_peso` decimal(8,2) NOT NULL,
  `pie_precio` decimal(10,2) NOT NULL,
  `pie_cant` int(11) NOT NULL,
  `pie_fechareg` datetime NOT NULL,
  `idusuario2` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `pieza`
--

INSERT INTO `pieza` (`idpieza`, `pie_codigo`, `pie_desc`, `pie_peso`, `pie_precio`, `pie_cant`, `pie_fechareg`, `idusuario2`) VALUES
(4, '0715374', 'AR PUERTA ACC P/TORRE DE 1,09M', '7.15', '163.95', 5, '2025-01-29 11:39:15', 1),
(5, '0719543', 'PLAT.ROBUST \"O\" C/E T9 307 C/APERTURA LAT.', '29.50', '565.20', 4, '2025-01-29 11:39:15', 1),
(6, '0724176', 'AR Gancho para Viga Transversal con 2 Medias Uniones WS24', '1.90', '101.31', 4, '2025-01-29 11:39:15', 1),
(7, '103800009', 'BE TORNILLO D.RETENCION 19MM', '0.08', '3.88', 181, '2025-01-29 11:39:15', 1),
(8, '103800011', 'BE TORNILLO D.RETENCION 19MM', '0.05', '3.29', 24, '2025-01-29 11:39:16', 1),
(9, '103800012', 'BE TORNILLO D.RETENCION 22MM', '0.05', '3.29', 50, '2025-01-29 11:39:16', 1),
(10, '103800016', 'BE TORNILLO D.RETENCION 19MM', '0.08', '4.34', 373, '2025-01-29 11:39:16', 1),
(11, '104905061', 'GI TORNILLO 12X60 P/ESPI.VER', '0.08', '1.37', 672, '2025-01-29 11:39:16', 1),
(12, '1260201', 'FG RUEDA 1000 T12', '6.30', '227.53', 20, '2025-01-29 11:39:16', 1),
(13, '1267200', 'RUEDA 1200 C/GRAPA GUIA', '12.00', '431.84', 17, '2025-01-29 11:39:16', 1),
(14, '1754095', 'TUBO ANCLAJE 0,95', '3.70', '29.32', 2, '2025-01-29 11:39:16', 1),
(15, '2601039', 'N. HORIZONTAL 0,39', '1.90', '43.20', 33, '2025-01-29 11:39:16', 1),
(16, '2601073', 'N. HORIZONTAL 0,73', '2.90', '34.87', 391, '2025-01-29 11:39:16', 1),
(17, '2601109', 'N. HORIZONTAL 1,09', '4.00', '41.64', 210, '2025-01-29 11:39:16', 1),
(18, '2601157', 'N. HORIZONTAL 1,57', '5.50', '51.00', 204, '2025-01-29 11:39:16', 1),
(19, '2601207', 'N. HORIZONTAL 2,07', '7.00', '59.67', 328, '2025-01-29 11:39:16', 1),
(20, '2601257', 'N. HORIZONTAL 2,57', '8.50', '68.00', 633, '2025-01-29 11:39:16', 1),
(21, '2601307', 'N. HORIZONTAL 3,07', '10.10', '76.68', 334, '2025-01-29 11:39:16', 1),
(22, '2602000', 'BASE COLLARIN', '1.41', '20.99', 146, '2025-01-29 11:39:16', 1),
(23, '2602022', 'GRAPA ROSETA 6 AGUJEROS', '1.01', '50.48', 24, '2025-01-29 11:39:16', 1),
(24, '2602122', 'GRAPA ROSETA 6 AJUEROS P/BASE', '1.70', '70.08', 10, '2025-01-29 11:39:16', 1),
(25, '2603000', 'RIGIDIZADOR VERT.0,50 C/CAB.AL', '4.00', '59.16', 14, '2025-01-29 11:39:16', 1),
(26, '2603100', 'VERTICAL 1,00', '5.52', '45.45', 2, '2025-01-29 11:39:16', 1),
(27, '2603150', 'VERTICAL 1,50', '7.76', '64.70', 4, '2025-01-29 11:39:16', 1),
(28, '2603200', 'VERTICAL 2,00', '10.10', '74.08', 22, '2025-01-29 11:39:16', 1),
(29, '2604100', 'VERTICAL S/E 1,00', '4.60', '45.45', 46, '2025-01-29 11:39:16', 1),
(30, '2604150', 'VERTICAL S/E 1,50', '6.82', '64.70', 19, '2025-01-29 11:39:16', 1),
(31, '2604200', 'VERTICAL S/E 2,00', '8.96', '74.08', 11, '2025-01-29 11:39:16', 1),
(32, '2605000', 'ESPIGA P/VERTICAL', '1.60', '18.39', 8, '2025-01-29 11:39:16', 1),
(33, '2606000', 'HORIZONTAL EXTENSI. 1,57X2,57', '8.50', '159.26', 2, '2025-01-29 11:39:16', 1),
(34, '2606001', 'HORIZONTAL EXTENSI. 1,09X1,57', '5.70', '149.71', 6, '2025-01-29 11:39:16', 1),
(35, '2617050', 'N. VERTICAL 0,50', '2.70', '38.17', 34, '2025-01-29 11:39:16', 1),
(36, '2617100', 'VERTICAL LW 1,00 M', '4.90', '48.40', 71, '2025-01-29 11:39:16', 1),
(37, '2617150', 'N. VERTICAL 1,50', '7.10', '68.88', 33, '2025-01-29 11:39:16', 1),
(38, '2617200', 'N. VERTICAL 2,00', '9.30', '79.29', 513, '2025-01-29 11:39:16', 1),
(39, '2617300', 'N. VERTICAL 3,00', '13.70', '116.92', 53, '2025-01-29 11:39:16', 1),
(40, '2619100', 'AR VERTICAL LW 1,00M SIN ESPIGA', '4.60', '48.57', 61, '2025-01-29 11:39:16', 1),
(41, '2620157', 'DIAGONAL 1,57', '7.70', '73.21', 4, '2025-01-29 11:39:16', 1),
(42, '2620257', 'DIAGONAL 2,57', '9.50', '81.54', 24, '2025-01-29 11:39:16', 1),
(43, '2620307', 'DIAGONAL 3,07', '10.50', '87.08', 71, '2025-01-29 11:39:16', 1),
(44, '2625157', 'VIGA PUENTE REDON. ACERO 1,57', '9.70', '103.45', 2, '2025-01-29 11:39:16', 1),
(45, '2625307', 'VIGA PUENTE CORDON REDON. 3,07', '19.20', '175.39', 3, '2025-01-29 11:39:16', 1),
(46, '2627004', 'PUERTA ACC  P/TORRE DE 0,73 M.', '5.45', '134.45', 4, '2025-01-29 11:39:16', 1),
(47, '2631039', 'MENSULA O 0,39', '3.90', '69.39', 20, '2025-01-29 11:39:16', 1),
(48, '2631073', 'MENSULA O 0,73', '6.80', '71.47', 37, '2025-01-29 11:39:16', 1),
(49, '2631109', 'MENSULA O 1,09', '12.00', '127.86', 53, '2025-01-29 11:39:16', 1),
(50, '2642039', 'RODAPIE 0,39  PL/ TUBO', '0.50', '24.49', 9, '2025-01-29 11:39:16', 1),
(51, '2642073', 'RODAPIE 0,73  PL/ TUBO', '1.50', '26.02', 73, '2025-01-29 11:39:16', 1),
(52, '2642109', 'RODAPIE 1,09  PL/ TUBO', '2.50', '26.90', 35, '2025-01-29 11:39:16', 1),
(53, '2642157', 'RODAPIE 1,57 PL/ TUBO', '3.50', '28.98', 32, '2025-01-29 11:39:16', 1),
(54, '2642207', 'RODAPIE 2,07  PL/ TUBO', '4.30', '31.05', 49, '2025-01-29 11:39:16', 1),
(55, '2642257', 'RODAPIE 2,57  PL/ TUBO', '5.70', '33.31', 133, '2025-01-29 11:39:16', 1),
(56, '2642307', 'RODAPIE 3,07  PL/ TUBO', '6.30', '35.38', 29, '2025-01-29 11:39:16', 1),
(57, '2660000', 'COLLARIN ALTO', '2.20', '54.12', 26, '2025-01-29 11:39:16', 1),
(58, '2672157', 'VIGA PUENTE REDON. LW 1,57', '8.70', '104.78', 30, '2025-01-29 11:39:16', 1),
(59, '2672207', 'VIGA PUENTE REDON. LW 2,07', '11.40', '126.64', 37, '2025-01-29 11:39:16', 1),
(60, '2672257', 'VIGA PUENTE REDON. LW 2,57', '14.30', '148.51', 17, '2025-01-29 11:39:16', 1),
(61, '2672307', 'VIGA PUENTE REDON. LW 3,07', '17.00', '170.36', 16, '2025-01-29 11:39:16', 1),
(62, '2683073', 'AR DIAGONAL LW 0,73 X 2 MTS', '6.80', '69.39', 19, '2025-01-29 11:39:16', 1),
(63, '2683109', 'AR DIAGONAL LW 1,09X2,00M', '7.00', '70.78', 54, '2025-01-29 11:39:16', 1),
(64, '2683157', 'AR DIAGONAL 1,57X2,00M', '7.70', '72.86', 132, '2025-01-29 11:39:16', 1),
(65, '2683207', 'AR DIAGONAL LW 2,07X2,00M', '8.85', '75.99', 120, '2025-01-29 11:39:16', 1),
(66, '2683257', 'AR DIAGONAL LW 2,57X2,00M', '9.50', '81.19', 80, '2025-01-29 11:39:16', 1),
(67, '2683307', 'AR DIAGONAL LW 3,07X2,00M', '10.50', '86.40', 44, '2025-01-29 11:39:16', 1),
(68, '3862073', 'PLAT. ACERO T9 DE 0,73 X 0,32 M', '7.00', '90.90', 1, '2025-01-29 11:39:16', 1),
(69, '3862157', 'PLAT. ACERO T9 DE 1,57 X 0,32 M', '12.50', '105.14', 6, '2025-01-29 11:39:16', 1),
(70, '3862207', 'BE PLAT. AC. P.TUBO T9 2.07 X 0.32 M', '16.00', '120.22', 17, '2025-01-29 11:39:16', 1),
(71, '3862257', 'PLAT. ACERO T9 DE 2,57 X 0,32 M', '18.90', '136.88', 50, '2025-01-29 11:39:16', 1),
(72, '3862307', 'PLAT. ACERO T9 DE 3,07 X 0,32 M', '22.50', '155.27', 9, '2025-01-29 11:39:16', 1),
(73, '3863109', 'BE O-PLAT. ACERO T9 1,09X0,19M', '7.00', '92.99', 4, '2025-01-29 11:39:16', 1),
(74, '3863157', 'PLAT. AC. P/TUBO T9 1.57X0.19', '10.00', '95.41', 3, '2025-01-29 11:39:16', 1),
(75, '3863207', 'PLAT. AC. P/TUBO T9 2.07 X 0.19 M', '12.70', '106.34', 14, '2025-01-29 11:39:16', 1),
(76, '3863257', 'BE PLAT. AC. ENG. TB. T9 2.57 X 0.19 M', '15.50', '122.66', 18, '2025-01-29 11:39:16', 1),
(77, '3863307', 'PLAT. ACERO T9 DE 3,07 X 0,19 M', '18.20', '139.83', 8, '2025-01-29 11:39:16', 1),
(78, '3871207', 'PLAT. ALU. TB C/T T9 2.07 M', '17.90', '441.33', 19, '2025-01-29 11:39:16', 1),
(79, '3872257', 'PLATAFORMA ROBUST C/T + ESCA/TUBO 2,57', '25.90', '497.03', 45, '2025-01-29 11:39:16', 1),
(80, '3872307', 'PLAT. ROBUST C/T + ESCA/TUBO 3,07', '29.70', '565.20', 5, '2025-01-29 11:39:16', 1),
(81, '3878100', 'PLAT. SIN GARRA 1,00X0,20 M', '4.80', '71.64', 6, '2025-01-29 11:39:16', 1),
(82, '3878150', 'PLAT. SIN GARRA 1,50X0,20 M', '7.20', '84.66', 21, '2025-01-29 11:39:16', 1),
(83, '3878250', 'PLAT. SIN GARRA 2,50X0,20 M', '11.80', '110.34', 12, '2025-01-29 11:39:16', 1),
(84, '3880100', 'PLAT. SIN GARRA 1,00X0,30 M', '6.50', '71.64', 32, '2025-01-29 11:39:16', 1),
(85, '3880150', 'PLAT. SIN GARRA 1,50X0,30 M', '10.30', '84.66', 54, '2025-01-29 11:39:16', 1),
(86, '3880200', 'PLAT. SIN GARRA 2,00X0,30 M', '12.80', '97.15', 88, '2025-01-29 11:39:16', 1),
(87, '3880250', 'PLAT. SIN GARRA 2,50X0,30 M', '15.30', '110.34', 82, '2025-01-29 11:39:16', 1),
(88, '3881000', 'BE ST-SPALTABDECKUNG 0.73 X 0.32M', '2.60', '24.98', 4, '2025-01-29 11:39:16', 1),
(89, '3881001', 'LAMINA CUBRE HUECOS DE 109', '4.00', '37.82', 4, '2025-01-29 11:39:16', 1),
(90, '3881002', 'LAMINA CUBRE HUECOS DE 157', '6.00', '53.78', 23, '2025-01-29 11:39:16', 1),
(91, '3881003', 'LAMINA CUBRE HUECOS DE 207', '8.00', '69.91', 7, '2025-01-29 11:39:16', 1),
(92, '3881005', 'LAMINA CUBRE HUECOS DE 307', '10.70', '104.43', 7, '2025-01-29 11:39:16', 1),
(93, '3890073', 'PLAT. ACERO T9 LW DE 0.73X0.32M', '6.20', '98.20', 12, '2025-01-29 11:39:16', 1),
(94, '3890109', 'PLAT. ACERO T9 LW DE 1.09X0.32M', '8.40', '99.23', 12, '2025-01-29 11:39:16', 1),
(95, '3890157', 'PLAT. ACERO T9 LW DE 1.57X0.32M', '11.30', '112.93', 14, '2025-01-29 11:39:16', 1),
(96, '3890207', 'PLAT. ACERO T9 LW DE 2.07X0.32M', '14.20', '129.25', 56, '2025-01-29 11:39:16', 1),
(97, '3890257', 'PLAT. ACERO T9 LW DE 2.57X0.32M', '17.20', '147.46', 111, '2025-01-29 11:39:16', 1),
(98, '3890307', 'PLAT. ACERO T9 LW DE 3.07X0.32M', '20.10', '166.89', 179, '2025-01-29 11:39:16', 1),
(99, '4001060', 'BASE REGULABLE 0,60', '3.60', '30.01', 122, '2025-01-29 11:39:16', 1),
(100, '4002080', 'BASE REGULABLE 0,80', '4.90', '43.03', 8, '2025-01-29 11:39:16', 1),
(101, '4003000', 'BASE P/SUP. INCLINADAS 0,60', '6.10', '66.97', 44, '2025-01-29 11:39:16', 1),
(102, '4005073', 'MENSULA PARA VANOS INTERMEDIOS', '8.50', '102.01', 5, '2025-01-29 11:39:16', 1),
(103, '4008007', 'ZB ESCALERILLA 7 PELD. T15', '7.80', '99.57', 22, '2025-01-29 11:39:16', 1),
(104, '4009007', 'ZB Escalera de Pisos 7 Escalones Modelo T19', '7.60', '99.57', 19, '2025-01-29 11:39:16', 1),
(105, '4600050', 'TUBO ACERO DE 0,50', '2.30', '11.10', 10, '2025-01-29 11:39:16', 1),
(106, '4600100', 'TUBO ACERO DE 1,00', '4.50', '20.65', 41, '2025-01-29 11:39:16', 1),
(107, '4600150', 'TUBO ACERO DE 1,50', '6.80', '29.32', 29, '2025-01-29 11:39:16', 1),
(108, '4600200', 'TUBO ACERO DE 2,00', '9.00', '39.21', 116, '2025-01-29 11:39:16', 1),
(109, '4600250', 'TUBO ACERO DE 2,50', '11.30', '48.40', 55, '2025-01-29 11:39:16', 1),
(110, '4600300', 'TUBO ACERO DE 3,00', '13.50', '58.81', 10, '2025-01-29 11:39:16', 1),
(111, '4600350', 'TUBO ACERO DE 3,50', '15.80', '67.14', 16, '2025-01-29 11:39:16', 1),
(112, '4600500', 'TUBO ACERO DE 5,00', '22.70', '95.59', 2, '2025-01-29 11:39:16', 1),
(113, '4600600', 'TUBO ACERO DE 6,00', '25.00', '111.03', 3, '2025-01-29 11:39:16', 1),
(114, '4700022', 'GRAPA ORTOGONAL 0,22', '1.30', '16.49', 440, '2025-01-29 11:39:16', 1),
(115, '4702022', 'GRAPA GIRATORIA 0,22', '1.50', '20.30', 30, '2025-01-29 11:39:16', 1),
(116, '4703022', 'GRAPA EMPALME 0,22', '1.80', '28.63', 2, '2025-01-29 11:39:16', 1),
(117, '4706022', 'ESPIGA P/GRAPAR A TUBO DE 48', '1.81', '39.90', 80, '2025-01-29 11:39:16', 1),
(118, '4708022', 'GRAPA 0,22 P/RODAPIE BLITZ', '1.00', '26.90', 1, '2025-01-29 11:39:16', 1),
(119, '4722022', 'N. GRAPA C/TORNILLO ADJ.', '1.40', '43.03', 96, '2025-01-29 11:39:16', 1),
(120, '4739000', 'ESPIGA EMPALME P/TUBO DE 48', '1.20', '11.28', 2, '2025-01-29 11:39:16', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `presupuesto`
--

CREATE TABLE `presupuesto` (
  `idpresupuesto` int(10) UNSIGNED NOT NULL,
  `pre_numero` varchar(20) NOT NULL,
  `pre_fechareg` datetime NOT NULL,
  `pre_correocontact` varchar(100) DEFAULT NULL,
  `idusuario2` int(10) UNSIGNED NOT NULL,
  `idcliente` int(10) UNSIGNED NOT NULL,
  `pre_porcenprecio` int(11) NOT NULL,
  `pre_periododias` int(11) NOT NULL,
  `pre_numtorres` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipousuario`
--

CREATE TABLE `tipousuario` (
  `idtipousuario` int(10) UNSIGNED NOT NULL,
  `tu_tipo` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `tipousuario`
--

INSERT INTO `tipousuario` (`idtipousuario`, `tu_tipo`) VALUES
(1, 'Administrador'),
(2, 'Operador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `torre`
--

CREATE TABLE `torre` (
  `idtorre` int(10) UNSIGNED NOT NULL,
  `tor_desc` varchar(200) NOT NULL,
  `tor_plano` varchar(100) NOT NULL,
  `tor_fechareg` datetime NOT NULL,
  `idusuario2` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `torre`
--

INSERT INTO `torre` (`idtorre`, `tor_desc`, `tor_plano`, `tor_fechareg`, `idusuario2`) VALUES
(2, 'Andamios de crucetas o andamio tradicional', 'plano_fo9922vdvm.pdf', '2025-01-29 18:58:08', 1),
(5, 'Andamios tipo caballete', 'plano_up1xum7ajq.pdf', '2025-01-30 12:00:33', 1),
(6, 'Andamios eléctricos o con plataforma auto elevadora', 'plano_pyryo2w2uw.pdf', '2025-01-30 12:02:31', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transportista`
--

CREATE TABLE `transportista` (
  `idtransportista` int(10) UNSIGNED NOT NULL,
  `tra_nombres` varchar(45) DEFAULT NULL,
  `tra_apellidos` varchar(45) DEFAULT NULL,
  `tra_dni` char(8) DEFAULT NULL,
  `tra_telef` varchar(20) DEFAULT NULL,
  `idusuario2` int(11) NOT NULL,
  `tra_fechareg` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `transportista`
--

INSERT INTO `transportista` (`idtransportista`, `tra_nombres`, `tra_apellidos`, `tra_dni`, `tra_telef`, `idusuario2`, `tra_fechareg`) VALUES
(1, 'Jony', 'Corcuera Sanchez', '47856932', '975089485', 1, '2025-01-27 10:48:34'),
(2, 'Daryl The Dog', 'Dixon Green', '12369854', '975089485', 1, '2025-01-27 10:48:43'),
(3, 'Eugenio', 'Derbez Martinez', '85236987', '987452313', 1, '2025-01-27 10:51:06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `idusuario` int(10) UNSIGNED NOT NULL,
  `usu_dni` char(8) NOT NULL,
  `usu_nombres` varchar(45) NOT NULL,
  `usu_apellidos` varchar(45) NOT NULL,
  `usu_usuario` varchar(45) NOT NULL,
  `usu_password` varchar(80) NOT NULL,
  `usu_fechareg` datetime DEFAULT NULL,
  `idtipousuario` int(10) UNSIGNED NOT NULL,
  `idusuario2` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`idusuario`, `usu_dni`, `usu_nombres`, `usu_apellidos`, `usu_usuario`, `usu_password`, `usu_fechareg`, `idtipousuario`, `idusuario2`) VALUES
(1, '45487229', 'Luis Alberto', 'Calderón Sánchez', 'lcalderons', '$2a$12$YmtIBS/VsxVywSQHV4A2.uFU8VcIdeY.pJDE0ZjKocqkKMwFw/Hka', '2025-01-25 21:50:43', 1, 1),
(2, '44945688', 'Max Scooby', 'Perez Rojas', 'shaggy', '$2a$12$YmtIBS/VsxVywSQHV4A2.uFU8VcIdeY.pJDE0ZjKocqkKMwFw/Hka', '2025-01-25 21:50:43', 2, 1),
(3, '12345678', 'Peter Pedro', 'Parker Araña', 'peterp', '$2a$12$YmtIBS/VsxVywSQHV4A2.uFU8VcIdeY.pJDE0ZjKocqkKMwFw/Hka', '2025-01-25 21:50:43', 2, 1),
(5, '12345678', 'Arnulfo', 'Gallardo Ram', 'arnulfo', '$2a$12$YmtIBS/VsxVywSQHV4A2.uFU8VcIdeY.pJDE0ZjKocqkKMwFw/Hka', '2025-01-25 21:50:43', 2, 1),
(6, '14785236', 'Timoteo', 'Sans Rubio', 'timoteo', '$2a$12$YmtIBS/VsxVywSQHV4A2.uFU8VcIdeY.pJDE0ZjKocqkKMwFw/Hka', '2025-01-25 21:50:43', 2, 1),
(7, '15963254', 'Francisco', 'Rodriguez Chao', 'pancho', '$2a$12$YmtIBS/VsxVywSQHV4A2.uFU8VcIdeY.pJDE0ZjKocqkKMwFw/Hka', '2025-01-25 21:50:43', 2, 1),
(12, '12365498', 'Andres', 'Hurtado Rosquilla', 'chibolin', '$2a$12$YmtIBS/VsxVywSQHV4A2.uFU8VcIdeY.pJDE0ZjKocqkKMwFw/Hka', '2025-01-25 21:50:43', 2, 1),
(16, '12365478', 'Alland', 'Ortega Reyna', 'alan_or', '$2a$12$YmtIBS/VsxVywSQHV4A2.uBXqGmEtpaMTU34EzRFhVoRl2k2sBK5C', '2025-01-25 21:52:12', 1, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`idcliente`);

--
-- Indices de la tabla `detalle_presupuesto`
--
ALTER TABLE `detalle_presupuesto`
  ADD PRIMARY KEY (`idpresupuesto`,`idtorre`),
  ADD KEY `fk_presupuesto_has_torre_torre1_idx` (`idtorre`),
  ADD KEY `fk_presupuesto_has_torre_presupuesto1_idx` (`idpresupuesto`);

--
-- Indices de la tabla `detalle_torre`
--
ALTER TABLE `detalle_torre`
  ADD PRIMARY KEY (`idtorre`,`idpieza`),
  ADD KEY `fk_torre_has_pieza_pieza1_idx` (`idpieza`),
  ADD KEY `fk_torre_has_pieza_torre1_idx` (`idtorre`);

--
-- Indices de la tabla `factura`
--
ALTER TABLE `factura`
  ADD PRIMARY KEY (`idfactura`),
  ADD KEY `fk_factura_presupuesto1_idx` (`idpresupuesto`);

--
-- Indices de la tabla `guia`
--
ALTER TABLE `guia`
  ADD PRIMARY KEY (`idguia`),
  ADD KEY `fk_guia_presupuesto1_idx` (`idpresupuesto`),
  ADD KEY `fk_guia_transportista1_idx` (`idtransportista`);

--
-- Indices de la tabla `parametros`
--
ALTER TABLE `parametros`
  ADD PRIMARY KEY (`idparametros`);

--
-- Indices de la tabla `pieza`
--
ALTER TABLE `pieza`
  ADD PRIMARY KEY (`idpieza`);

--
-- Indices de la tabla `presupuesto`
--
ALTER TABLE `presupuesto`
  ADD PRIMARY KEY (`idpresupuesto`),
  ADD UNIQUE KEY `pre_numero_UNIQUE` (`pre_numero`),
  ADD KEY `fk_presupuesto_usuario1_idx` (`idusuario2`),
  ADD KEY `fk_presupuesto_cliente1_idx` (`idcliente`);

--
-- Indices de la tabla `tipousuario`
--
ALTER TABLE `tipousuario`
  ADD PRIMARY KEY (`idtipousuario`);

--
-- Indices de la tabla `torre`
--
ALTER TABLE `torre`
  ADD PRIMARY KEY (`idtorre`);

--
-- Indices de la tabla `transportista`
--
ALTER TABLE `transportista`
  ADD PRIMARY KEY (`idtransportista`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`idusuario`),
  ADD KEY `fk_usuario_tipousuario1_idx` (`idtipousuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `idcliente` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `factura`
--
ALTER TABLE `factura`
  MODIFY `idfactura` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `guia`
--
ALTER TABLE `guia`
  MODIFY `idguia` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `parametros`
--
ALTER TABLE `parametros`
  MODIFY `idparametros` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `pieza`
--
ALTER TABLE `pieza`
  MODIFY `idpieza` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT de la tabla `presupuesto`
--
ALTER TABLE `presupuesto`
  MODIFY `idpresupuesto` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipousuario`
--
ALTER TABLE `tipousuario`
  MODIFY `idtipousuario` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `torre`
--
ALTER TABLE `torre`
  MODIFY `idtorre` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `transportista`
--
ALTER TABLE `transportista`
  MODIFY `idtransportista` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `idusuario` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalle_presupuesto`
--
ALTER TABLE `detalle_presupuesto`
  ADD CONSTRAINT `fk_presupuesto_has_torre_presupuesto1` FOREIGN KEY (`idpresupuesto`) REFERENCES `presupuesto` (`idpresupuesto`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_presupuesto_has_torre_torre1` FOREIGN KEY (`idtorre`) REFERENCES `torre` (`idtorre`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `detalle_torre`
--
ALTER TABLE `detalle_torre`
  ADD CONSTRAINT `fk_torre_has_pieza_pieza1` FOREIGN KEY (`idpieza`) REFERENCES `pieza` (`idpieza`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_torre_has_pieza_torre1` FOREIGN KEY (`idtorre`) REFERENCES `torre` (`idtorre`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `factura`
--
ALTER TABLE `factura`
  ADD CONSTRAINT `fk_factura_presupuesto1` FOREIGN KEY (`idpresupuesto`) REFERENCES `presupuesto` (`idpresupuesto`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `guia`
--
ALTER TABLE `guia`
  ADD CONSTRAINT `fk_guia_presupuesto1` FOREIGN KEY (`idpresupuesto`) REFERENCES `presupuesto` (`idpresupuesto`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_guia_transportista1` FOREIGN KEY (`idtransportista`) REFERENCES `transportista` (`idtransportista`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `presupuesto`
--
ALTER TABLE `presupuesto`
  ADD CONSTRAINT `fk_presupuesto_cliente1` FOREIGN KEY (`idcliente`) REFERENCES `cliente` (`idcliente`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_presupuesto_usuario1` FOREIGN KEY (`idusuario2`) REFERENCES `usuario` (`idusuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `fk_usuario_tipousuario1` FOREIGN KEY (`idtipousuario`) REFERENCES `tipousuario` (`idtipousuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
