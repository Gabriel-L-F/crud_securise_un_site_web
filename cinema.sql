-- phpMyAdmin SQL Dump
-- version 6.0.0-dev+20251125.142c0cf3be
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 22, 2026 at 10:21 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cinema`
--

-- --------------------------------------------------------

--
-- Table structure for table `film`
--

CREATE TABLE `film` (
  `id` int NOT NULL,
  `title` varchar(250) NOT NULL,
  `realisateur` varchar(250) NOT NULL,
  `synopsis` text NOT NULL,
  `date_sortie` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `film`
--

INSERT INTO `film` (`id`, `title`, `realisateur`, `synopsis`, `date_sortie`) VALUES
(1, 'Avatar', 'James Camerone ', 'Sur le monde extraterrestre luxuriant de Pandora vivent les Na\'vi, des êtres qui semblent primitifs, mais qui sont très évolués. Jake Sully, un ancien Marine paralysé, redevient mobile grâce à un tel Avatar et tombe amoureux d\'une femme Na\'vi. Alors qu\'un lien avec elle grandit, il est entraîné dans une bataille pour la survie de son monde.', '2009-12-17'),
(4, 'Douze Hommes en colère', ' Sidney Lumet', 'Un jeune homme d\'origine modeste est accusé du meurtre de son père et risque la peine de mort. Le jury composé de douze hommes se retire pour délibérer et procède immédiatement à un vote: onze votent coupables, or la décision doit être prise à l\'unanimité. Le juré qui a voté non-coupable, sommé de se justifier, explique qu\'il a un doute et que la vie d\'un homme mérite quelques heures de discussion. Il s\'emploie alors à les convaincre un par un.', '1957-10-04'),
(5, 'Erin Brockovich, seule contre tous', 'Steven Soderbergh', 'Mère élevant seule ses trois enfants, Erin Brockovich n\'avait vraiment pas besoin d\'un accident de voiture. D\'autant que le responsable sort du tribunal financièrement indemne. Obligée de trouver rapidement un travail pour couvrir tous ses frais médicaux et de justice, Erin obtient de son avocat de l\'employer comme archiviste dans son cabinet. Son allure et son franc-parler ne lui valent pas des débuts faciles, mais elle apprend vite.', '2000-04-26'),
(6, 'Jurassic Park', 'Steven Spielberg', 'Ne pas réveiller le chat qui dort -- c\'est ce que le milliardaire John Hammond aurait dû se rappeler avant de se lancer dans le clonage de dinosaures. C\'est à partir d\'une goutte de sang absorbée par un moustique fossilisé que John Hammond et son équipe ont réussi à faire renaître une dizaine d\'espèces de dinosaures. Il s\'apprête maintenant avec la complicité du docteur Alan Grant, paléontologue de renom, et de son amie Ellie, à ouvrir le plus grand parc à thème du monde', '1993-10-20');

-- --------------------------------------------------------

--
-- Table structure for table `salle`
--

CREATE TABLE `salle` (
  `id` int NOT NULL,
  `nom` varchar(250) NOT NULL,
  `nb_place` int NOT NULL,
  `is_3d` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `salle`
--

INSERT INTO `salle` (`id`, `nom`, `nb_place`, `is_3d`) VALUES
(3, 'Salle 1', 351, 0),
(4, 'Salle 2', 150, 0);

-- --------------------------------------------------------

--
-- Table structure for table `seance`
--

CREATE TABLE `seance` (
  `id` int NOT NULL,
  `id_salle` int NOT NULL,
  `id_film` int NOT NULL,
  `date_heure` datetime NOT NULL,
  `prix` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `seance`
--

INSERT INTO `seance` (`id`, `id_salle`, `id_film`, `date_heure`, `prix`) VALUES
(5, 3, 1, '2026-02-20 14:00:00', 8),
(7, 4, 4, '2026-02-22 20:00:00', 5),
(8, 4, 5, '2026-02-21 20:00:00', 7),
(9, 4, 6, '2026-02-22 17:00:00', 7);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `password` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `nom` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `admin`, `password`, `email`, `nom`) VALUES
(1, 1, '$2y$10$.xaKmhc8DcWkcVWhfv/5texyLHKzL7zm7tzCtMB8CnGRc4w.kdoGK', 'gab@gmail.com', 'Gabriel'),
(2, 0, '$2y$10$3A86TaK/liC/phKXpuctxu2zNVKFinxaC/yq7r73JRrZlYJAuXnoO', 'benois@gail.com', 'benois');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `film`
--
ALTER TABLE `film`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `salle`
--
ALTER TABLE `salle`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `seance`
--
ALTER TABLE `seance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_salle` (`id_salle`),
  ADD KEY `id_film` (`id_film`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `film`
--
ALTER TABLE `film`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `salle`
--
ALTER TABLE `salle`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `seance`
--
ALTER TABLE `seance`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `seance`
--
ALTER TABLE `seance`
  ADD CONSTRAINT `seance_ibfk_1` FOREIGN KEY (`id_film`) REFERENCES `film` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `seance_ibfk_2` FOREIGN KEY (`id_salle`) REFERENCES `salle` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
