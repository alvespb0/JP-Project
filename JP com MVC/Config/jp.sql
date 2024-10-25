use jpcontab_jp;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `jp`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `empresa`
--

CREATE TABLE `empresa` (
  `ID_empresa` int(11) NOT NULL,
  `links_empresa` varchar(255) NOT NULL,
  `nome_empresa` varchar(255) NOT NULL,
  `cnpj_empresa` varchar(255) NOT NULL,
  `particularidades_empresa` text NOT NULL,
  `endereco_empresa` text NOT NULL,
  `obs_link` text NOT NULL,
  `obs_particularidades` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `empresa`
--

INSERT INTO `empresa` (`ID_empresa`, `links_empresa`, `nome_empresa`, `cnpj_empresa`, `particularidades_empresa`, `endereco_empresa`, `obs_link`, `obs_particularidades`) VALUES
(75, 'jp.com.br', 'JP - contabilidade', '12624615962', 'grande', 'das araucarias', 'link hospedado na hostinger', 'Sem obs sobre as particularidades'),
(76, 'https://beemind.com.br', 'Empresa BEE MIND', '29533435000183', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', ' R. Mauro Nerbass, 72 - Sala 03 - Da Bates, Lages - SC, 88524-420', 'Site hospedado no .br', 'Sem observações');

-- --------------------------------------------------------

--
-- Estrutura para tabela `empresa_importacao`
--

CREATE TABLE `empresa_importacao` (
  `ID_daEmpresa` int(11) NOT NULL,
  `ID_formaDeImportacao` int(11) NOT NULL,
  `obs_importacao` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `empresa_importacao`
--

INSERT INTO `empresa_importacao` (`ID_daEmpresa`, `ID_formaDeImportacao`, `obs_importacao`) VALUES
(75, 134, 'Entrada somente por SPED'),
(75, 135, 'Entrada somente por SPED'),
(75, 136, 'Entrada somente por SPED'),
(75, 137, 'Entrada somente por SPED'),
(76, 138, 'Entrada somente por SPED'),
(76, 139, 'Entrada somente por SPED');

-- --------------------------------------------------------

--
-- Estrutura para tabela `empresa_recebimento`
--

CREATE TABLE `empresa_recebimento` (
  `empresa_id` int(11) NOT NULL,
  `forma_recebimento_id` int(11) NOT NULL,
  `subforma_recebimento_id` int(11) NOT NULL,
  `obs_recebimento` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `empresa_recebimento`
--

INSERT INTO `empresa_recebimento` (`empresa_id`, `forma_recebimento_id`, `subforma_recebimento_id`, `obs_recebimento`) VALUES
(75, 44, 52, 'whatsapp business'),
(75, 44, 53, 'whatsapp business'),
(75, 44, 54, 'whatsapp business'),
(76, 45, 55, 'nenhuma'),
(76, 45, 56, 'nenhuma');

-- --------------------------------------------------------

--
-- Estrutura para tabela `formas_importacao`
--

CREATE TABLE `formas_importacao` (
  `ID_formasImportacao` int(11) NOT NULL,
  `tipo_formasImportacao` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `formas_importacao`
--

INSERT INTO `formas_importacao` (`ID_formasImportacao`, `tipo_formasImportacao`) VALUES
(134, 'Entrada por SPED'),
(135, 'Saída por SPED'),
(136, 'Saída por XML'),
(137, 'Entradas pelo SAT'),
(138, 'Entrada por SPED'),
(139, 'Saída por XML');

-- --------------------------------------------------------

--
-- Estrutura para tabela `forma_recebimento`
--

CREATE TABLE `forma_recebimento` (
  `ID_formaRecebimento` int(11) NOT NULL,
  `Tipo_formaRecebimento` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `forma_recebimento`
--

INSERT INTO `forma_recebimento` (`ID_formaRecebimento`, `Tipo_formaRecebimento`) VALUES
(44, 'digital e fisico'),
(45, 'digital');

-- --------------------------------------------------------

--
-- Estrutura para tabela `subforma_recebimento`
--

CREATE TABLE `subforma_recebimento` (
  `ID_subforma` int(11) NOT NULL,
  `nome_subforma` varchar(255) DEFAULT NULL,
  `ID_formaDeRecebimento` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `subforma_recebimento`
--

INSERT INTO `subforma_recebimento` (`ID_subforma`, `nome_subforma`, `ID_formaDeRecebimento`) VALUES
(52, 'email', 44),
(53, 'Whatsapp', 44),
(54, 'Skype', 44),
(55, 'email', 45),
(56, 'Whatsapp', 45);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `login_usuario` varchar(255) NOT NULL,
  `senha_usuario` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`ID_empresa`);

--
-- Índices de tabela `empresa_importacao`
--
ALTER TABLE `empresa_importacao`
  ADD PRIMARY KEY (`ID_daEmpresa`,`ID_formaDeImportacao`),
  ADD KEY `ID_formaDeImportacao` (`ID_formaDeImportacao`);

--
-- Índices de tabela `empresa_recebimento`
--
ALTER TABLE `empresa_recebimento`
  ADD PRIMARY KEY (`empresa_id`,`forma_recebimento_id`,`subforma_recebimento_id`),
  ADD KEY `forma_recebimento_id` (`forma_recebimento_id`),
  ADD KEY `subforma_recebimento_id` (`subforma_recebimento_id`);

--
-- Índices de tabela `formas_importacao`
--
ALTER TABLE `formas_importacao`
  ADD PRIMARY KEY (`ID_formasImportacao`);

--
-- Índices de tabela `forma_recebimento`
--
ALTER TABLE `forma_recebimento`
  ADD PRIMARY KEY (`ID_formaRecebimento`);

--
-- Índices de tabela `subforma_recebimento`
--
ALTER TABLE `subforma_recebimento`
  ADD PRIMARY KEY (`ID_subforma`),
  ADD KEY `ID_formaDeRecebimento` (`ID_formaDeRecebimento`);

--
-- Índices de tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `empresa`
--
ALTER TABLE `empresa`
  MODIFY `ID_empresa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT de tabela `formas_importacao`
--
ALTER TABLE `formas_importacao`
  MODIFY `ID_formasImportacao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=140;

--
-- AUTO_INCREMENT de tabela `forma_recebimento`
--
ALTER TABLE `forma_recebimento`
  MODIFY `ID_formaRecebimento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT de tabela `subforma_recebimento`
--
ALTER TABLE `subforma_recebimento`
  MODIFY `ID_subforma` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `empresa_importacao`
--
ALTER TABLE `empresa_importacao`
  ADD CONSTRAINT `empresa_importacao_ibfk_1` FOREIGN KEY (`ID_daEmpresa`) REFERENCES `empresa` (`id_empresa`),
  ADD CONSTRAINT `empresa_importacao_ibfk_2` FOREIGN KEY (`ID_formaDeImportacao`) REFERENCES `formas_importacao` (`ID_formasImportacao`);

--
-- Restrições para tabelas `empresa_recebimento`
--
ALTER TABLE `empresa_recebimento`
  ADD CONSTRAINT `empresa_recebimento_ibfk_1` FOREIGN KEY (`empresa_id`) REFERENCES `empresa` (`id_empresa`),
  ADD CONSTRAINT `empresa_recebimento_ibfk_2` FOREIGN KEY (`forma_recebimento_id`) REFERENCES `forma_recebimento` (`ID_formaRecebimento`),
  ADD CONSTRAINT `empresa_recebimento_ibfk_3` FOREIGN KEY (`subforma_recebimento_id`) REFERENCES `subforma_recebimento` (`ID_subforma`);

--
-- Restrições para tabelas `subforma_recebimento`
--
ALTER TABLE `subforma_recebimento`
  ADD CONSTRAINT `subforma_recebimento_ibfk_1` FOREIGN KEY (`ID_formaDeRecebimento`) REFERENCES `forma_recebimento` (`ID_formaRecebimento`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
