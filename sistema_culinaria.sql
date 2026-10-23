-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 23/10/2026 às 04:31
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `sistema_culinaria`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `avaliacoes`
--

CREATE TABLE `avaliacoes` (
  `id` int(11) NOT NULL,
  `receita_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `data` varchar(10) NOT NULL,
  `descricao` text NOT NULL,
  `score` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `receitas`
--

CREATE TABLE `receitas` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `ingredientes` varchar(255) NOT NULL,
  `modo_preparo` text NOT NULL,
  `quantidade` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `receitas`
--

INSERT INTO `receitas` (`id`, `nome`, `ingredientes`, `modo_preparo`, `quantidade`) VALUES
(1, 'Bolo de cenoura', 'Massa: 3 cenouras médias, 3 ovos, 2 xícaras de açúcar, 1 de óleo, 2 de farinha, 1 colher de fermento e sal a gosto.  Cobertura: 3 colheres de açúcar, 2 de chocolate em pó, 1 de manteiga e ½ xícara de leite.', 'Modo de Preparo\r\n\r\n\r\n1. Fazendo a Massa\r\n\r\n1. Em uma tigela ou liquidificador, bata os ovos com o açúcar e o óleo até obter uma mistura homogênea e levemente clara.\r\n2. Adicione o chocolate em pó (peneirado, se preferir) e mexa bem.\r\n3. Acrescente a farinha de trigo aos poucos, alternando com o leite, misturando com um fouet ou colher até a massa ficar lisa.\r\n4. Por último, junte o fermento em pó e mexa delicadamente.\r\n5. Despeje a massa em uma forma untada e enfarinhada.\r\n6. Leve ao forno pré-aquecido a 180°C por cerca de 35 a 40 minutos (faça o teste do palito).\r\n\r\n2. Preparando a Calda\r\n\r\n1. Em uma panela pequena, coloque o leite, o chocolate em pó, o açúcar e a manteiga.\r\n2. Leve ao fogo médio, mexendo sempre até levantar fervura e engrossar levemente (cerca de 2 a 3 minutos).\r\n\r\n3. Montagem\r\n\r\n1. Com o bolo ainda quente (ou morno), faça vários furos na superfície usando um garfo ou palito.\r\n2. Despeje a calda quente por cima do bolo, espalhando bem para que penetre nos furos.', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`) VALUES
(1, 'Pedro', 'pedro@email.com', '123456');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `avaliacoes`
--
ALTER TABLE `avaliacoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_avaliacoes_receita` (`receita_id`),
  ADD KEY `idx_avaliacoes_usuario` (`usuario_id`);

--
-- Índices de tabela `receitas`
--
ALTER TABLE `receitas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `avaliacoes`
--
ALTER TABLE `avaliacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `receitas`
--
ALTER TABLE `receitas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `avaliacoes`
--
ALTER TABLE `avaliacoes`
  ADD CONSTRAINT `fk_avaliacoes_receita` FOREIGN KEY (`receita_id`) REFERENCES `receitas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_avaliacoes_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
