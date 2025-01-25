-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-01-2025 a las 21:14:27
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
  `cli_correocontact` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factura`
--

CREATE TABLE `factura` (
  `idfactura` int(10) UNSIGNED NOT NULL,
  `fact_nro` varchar(20) NOT NULL,
  `fact_fecha` datetime NOT NULL,
  `idpresupuesto` int(10) UNSIGNED NOT NULL
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
  `idtransportista` int(10) UNSIGNED NOT NULL
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
(2, 12, 'logo.jpeg', 'firma.jpg', 'Urb Miguel Grau 2da Etapa D2', '975089485', 'alberto_@hotmail.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pieza`
--

CREATE TABLE `pieza` (
  `idpieza` int(10) UNSIGNED NOT NULL,
  `pie_codigo` varchar(12) NOT NULL,
  `pie_desc` text DEFAULT NULL,
  `pie_peso` decimal(8,2) NOT NULL,
  `pie_precio` decimal(10,2) NOT NULL,
  `pie_cant` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `presupuesto`
--

CREATE TABLE `presupuesto` (
  `idpresupuesto` int(10) UNSIGNED NOT NULL,
  `pre_numero` varchar(20) NOT NULL,
  `pre_fechareg` datetime NOT NULL,
  `pre_correocontact` varchar(100) DEFAULT NULL,
  `idusuario` int(10) UNSIGNED NOT NULL,
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
  `tor_plano` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transportista`
--

CREATE TABLE `transportista` (
  `idtransportista` int(10) UNSIGNED NOT NULL,
  `tra_nombres` varchar(45) DEFAULT NULL,
  `tra_apellidos` varchar(45) DEFAULT NULL,
  `tra_dni` char(8) DEFAULT NULL,
  `tra_telef` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

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
  `usu_fechalog` datetime DEFAULT NULL,
  `idtipousuario` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`idusuario`, `usu_dni`, `usu_nombres`, `usu_apellidos`, `usu_usuario`, `usu_password`, `usu_fechalog`, `idtipousuario`) VALUES
(1, '45487229', 'Luis Alberto', 'Calderón Sánchez', 'lcalderons', '$2a$12$YmtIBS/VsxVywSQHV4A2.uFU8VcIdeY.pJDE0ZjKocqkKMwFw/Hka', NULL, 1),
(2, '44945688', 'Max Orlando', 'Perez Rojas', 'shaggy', '12345678', NULL, 2),
(3, '12345678', 'Peter Pedro', 'Parker Araña', 'peterp', '12345678', NULL, 2),
(4, '25639874', 'Alland', 'Ortega Reyna', 'alan_or', '12345678', NULL, 1),
(5, '12345678', 'Arnulfo', 'Gallardo Ram', 'arnulfo', '12345678', NULL, 2),
(6, '14785236', 'Timoteo', 'Sans Rubio', 'timoteo', '12345678', NULL, 2),
(7, '15963254', 'Francisco', 'Rodriguez Chao', 'pancho', 'adssadsad', NULL, 2),
(8, '34345345', 'dsdadassd', 'asdasdasd', 'asasccc', '23234234234', NULL, 2),
(9, '12324324', 'fsdfsdf', 'sdfsdfsdf', 'asasqqq', 'fsdfsdfsdf', NULL, 2),
(10, '12434353', 'adsadsa', 'vxzvxcv', 'asasbbb', 'fdfsdfsdfsd', NULL, 2),
(11, '23545435', 'dszdsadas', 'asdasdasd', 'asaseee', 'asdsadsad', NULL, 2),
(12, '12365498', 'Andres', 'Hurtado Rosquilla', 'chibolin', '12345678', NULL, 2);

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
  ADD KEY `fk_presupuesto_usuario1_idx` (`idusuario`),
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
  MODIFY `idcliente` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

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
  MODIFY `idpieza` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

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
  MODIFY `idtorre` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `transportista`
--
ALTER TABLE `transportista`
  MODIFY `idtransportista` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `idusuario` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

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
  ADD CONSTRAINT `fk_presupuesto_usuario1` FOREIGN KEY (`idusuario`) REFERENCES `usuario` (`idusuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `fk_usuario_tipousuario1` FOREIGN KEY (`idtipousuario`) REFERENCES `tipousuario` (`idtipousuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
