-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 18, 2025 at 06:36 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `germinaretec`
--

-- --------------------------------------------------------

--
-- Table structure for table `materias`
--

CREATE TABLE `materias` (
  `id_materia` int(11) NOT NULL,
  `nome_materia` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `materias`
--

INSERT INTO `materias` (`id_materia`, `nome_materia`) VALUES
(14, 'Administração'),
(8, 'Artes'),
(5, 'Biologia'),
(23, 'Enfermagem'),
(10, 'Espanhol'),
(12, 'Filosofia'),
(2, 'Física'),
(7, 'Geografia'),
(6, 'História'),
(13, 'Informática para Internet'),
(9, 'Inglês'),
(15, 'Marketing'),
(1, 'Matemática'),
(26, 'Mecânica'),
(4, 'Português'),
(3, 'Química'),
(25, 'Recursos humanos'),
(22, 'Serviços jurídicos'),
(11, 'Sociologia'),
(24, 'Técnico em agronegócio');

-- --------------------------------------------------------

--
-- Table structure for table `pergunta`
--

CREATE TABLE `pergunta` (
  `id_pergunta` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `titulo_pergunta` varchar(50) NOT NULL,
  `pergunta` text NOT NULL,
  `data_pergunta` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `perguntaresposta`
--

CREATE TABLE `perguntaresposta` (
  `id_pergunta_resposta` int(11) NOT NULL,
  `id_pergunta` int(11) NOT NULL,
  `id_resposta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pergunta_materias`
--

CREATE TABLE `pergunta_materias` (
  `id_pergunta_materia` int(11) NOT NULL,
  `id_pergunta` int(11) DEFAULT NULL,
  `id_materia` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `post`
--

CREATE TABLE `post` (
  `id_post` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `titulo_post` varchar(50) NOT NULL,
  `conteudo` text NOT NULL,
  `data_post` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `postagem_materias`
--

CREATE TABLE `postagem_materias` (
  `id_post_materia` int(11) NOT NULL,
  `id_post` int(11) DEFAULT NULL,
  `id_materia` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `resposta`
--

CREATE TABLE `resposta` (
  `id_resposta` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_pergunta` int(11) NOT NULL,
  `resposta` text NOT NULL,
  `data_resposta` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nome_usuario` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(100) NOT NULL,
  `data` datetime DEFAULT current_timestamp(),
  `conta_ativa` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `materias`
--
ALTER TABLE `materias`
  ADD PRIMARY KEY (`id_materia`),
  ADD UNIQUE KEY `nome_materia` (`nome_materia`);

--
-- Indexes for table `pergunta`
--
ALTER TABLE `pergunta`
  ADD PRIMARY KEY (`id_pergunta`);

--
-- Indexes for table `perguntaresposta`
--
ALTER TABLE `perguntaresposta`
  ADD PRIMARY KEY (`id_pergunta_resposta`);

--
-- Indexes for table `pergunta_materias`
--
ALTER TABLE `pergunta_materias`
  ADD PRIMARY KEY (`id_pergunta_materia`),
  ADD KEY `id_pergunta` (`id_pergunta`),
  ADD KEY `id_materia` (`id_materia`);

--
-- Indexes for table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id_post`);

--
-- Indexes for table `postagem_materias`
--
ALTER TABLE `postagem_materias`
  ADD PRIMARY KEY (`id_post_materia`),
  ADD KEY `id_post` (`id_post`),
  ADD KEY `id_materia` (`id_materia`);

--
-- Indexes for table `resposta`
--
ALTER TABLE `resposta`
  ADD PRIMARY KEY (`id_resposta`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `nome_usuario` (`nome_usuario`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `senha` (`senha`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `materias`
--
ALTER TABLE `materias`
  MODIFY `id_materia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `pergunta`
--
ALTER TABLE `pergunta`
  MODIFY `id_pergunta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `perguntaresposta`
--
ALTER TABLE `perguntaresposta`
  MODIFY `id_pergunta_resposta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pergunta_materias`
--
ALTER TABLE `pergunta_materias`
  MODIFY `id_pergunta_materia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `post`
--
ALTER TABLE `post`
  MODIFY `id_post` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `postagem_materias`
--
ALTER TABLE `postagem_materias`
  MODIFY `id_post_materia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `resposta`
--
ALTER TABLE `resposta`
  MODIFY `id_resposta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pergunta_materias`
--
ALTER TABLE `pergunta_materias`
  ADD CONSTRAINT `pergunta_materias_ibfk_1` FOREIGN KEY (`id_pergunta`) REFERENCES `pergunta` (`id_pergunta`),
  ADD CONSTRAINT `pergunta_materias_ibfk_2` FOREIGN KEY (`id_materia`) REFERENCES `materias` (`id_materia`);

--
-- Constraints for table `postagem_materias`
--
ALTER TABLE `postagem_materias`
  ADD CONSTRAINT `postagem_materias_ibfk_1` FOREIGN KEY (`id_post`) REFERENCES `post` (`id_post`),
  ADD CONSTRAINT `postagem_materias_ibfk_2` FOREIGN KEY (`id_materia`) REFERENCES `materias` (`id_materia`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
