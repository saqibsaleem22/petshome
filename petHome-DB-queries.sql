-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 18-01-2021 a las 14:25:53
-- Versión del servidor: 5.7.32
-- Versión de PHP: 7.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de datos: `petShelter`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `animals`
--

CREATE TABLE `animals` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `age` int(11) NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `placer_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `animals`
--

INSERT INTO `animals` (`id`, `name`, `type`, `age`, `description`, `size`, `photo`, `status`, `placer_id`) VALUES
(20, 'Disney', 'cat', 3, 'This is nice cat', 'M', '161069926720.png', 'adopted', 36),
(21, 'Perrito', 'dog', 4, 'This is nice dog.', 'S', '161070590621.png', 'adopted', 36),
(22, 'Kitty', 'cat', 4, 'This is very nice cat and we like it very much.', 'XS', '161090875322.png', 'available', 36);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conversation`
--

CREATE TABLE `conversation` (
  `id` int(11) NOT NULL,
  `animal_id` int(11) NOT NULL,
  `requester_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `conversation`
--

INSERT INTO `conversation` (`id`, `animal_id`, `requester_id`) VALUES
(3, 20, 37),
(4, 21, 37);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20201101212320', '2020-12-25 10:51:34', 165),
('DoctrineMigrations\\Version20201102132208', '2020-12-25 10:51:35', 30),
('DoctrineMigrations\\Version20201102181327', '2020-12-25 10:51:35', 142),
('DoctrineMigrations\\Version20210111202516', '2021-01-11 20:25:30', 225),
('DoctrineMigrations\\Version20210111202758', '2021-01-11 20:28:04', 152),
('DoctrineMigrations\\Version20210114151734', '2021-01-14 15:17:56', 348),
('DoctrineMigrations\\Version20210114154810', '2021-01-14 15:48:24', 277),
('DoctrineMigrations\\Version20210114165215', '2021-01-14 16:58:33', 256),
('DoctrineMigrations\\Version20210115140017', '2021-01-15 14:00:34', 220),
('DoctrineMigrations\\Version20210118110406', '2021-01-18 11:04:19', 287);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `conversation_id` int(11) NOT NULL,
  `text` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `attachment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `message`
--

INSERT INTO `message` (`id`, `conversation_id`, `text`, `attachment`, `type`, `status`) VALUES
(1, 3, 'hola mundo', '', 'request', 'read'),
(2, 4, 'what are you doing', '', 'request', 'read'),
(3, 4, 'vxcvxcv', '', 'request', 'read'),
(4, 3, 'que tal', '', 'response', 'read'),
(5, 3, 'I want to adopt animal', '', 'request', 'read'),
(7, 3, '', '16109724527g6.jpg', 'request', 'read'),
(8, 3, 'message with photo and text', '16109746338bg1.jpg', 'request', 'unread');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `user`
--

INSERT INTO `user` (`id`, `email`, `password`, `avatar`) VALUES
(36, 'saqibsaleem22@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$lkWeQhHQ/jIOBipKmoSgpQ$MJi7d8Ud0xX/WUMNCyFyMqqfCMLKAYrWAGjezzil6dg', NULL),
(37, 'saqib.daw@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$GhTSTmyrSQPzyjMVp9uwqw$rMaFUWls4dkUKt+i2YyjuqM8MEZ9RcYq+04Z6N2mJiI', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `animals`
--
ALTER TABLE `animals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_966C69DD3BABD422` (`placer_id`);

--
-- Indices de la tabla `conversation`
--
ALTER TABLE `conversation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_8A8E26E98E962C16` (`animal_id`),
  ADD KEY `IDX_8A8E26E9ED442CF4` (`requester_id`);

--
-- Indices de la tabla `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Indices de la tabla `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_B6BD307F9AC0396` (`conversation_id`);

--
-- Indices de la tabla `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `animals`
--
ALTER TABLE `animals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `conversation`
--
ALTER TABLE `conversation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `animals`
--
ALTER TABLE `animals`
  ADD CONSTRAINT `FK_966C69DD3BABD422` FOREIGN KEY (`placer_id`) REFERENCES `user` (`id`);

--
-- Filtros para la tabla `conversation`
--
ALTER TABLE `conversation`
  ADD CONSTRAINT `FK_8A8E26E98E962C16` FOREIGN KEY (`animal_id`) REFERENCES `animals` (`id`),
  ADD CONSTRAINT `FK_8A8E26E9ED442CF4` FOREIGN KEY (`requester_id`) REFERENCES `user` (`id`);

--
-- Filtros para la tabla `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `FK_B6BD307F9AC0396` FOREIGN KEY (`conversation_id`) REFERENCES `conversation` (`id`);