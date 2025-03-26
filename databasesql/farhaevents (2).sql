-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : mer. 26 mars 2025 à 12:33
-- Version du serveur : 5.7.24
-- Version de PHP : 8.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `farhaevents`
--

-- --------------------------------------------------------

--
-- Structure de la table `billet`
--

CREATE TABLE `billet` (
  `billetId` varchar(15) NOT NULL,
  `typeBillet` varchar(50) NOT NULL,
  `placeNum` int(11) NOT NULL,
  `idReservation` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `billet`
--

INSERT INTO `billet` (`billetId`, `typeBillet`, `placeNum`, `idReservation`) VALUES
('B1N1', 'Normal', 1, 1),
('B1R2', 'Reduit', 2, 1);

-- --------------------------------------------------------

--
-- Structure de la table `edition`
--

CREATE TABLE `edition` (
  `editionId` int(11) NOT NULL,
  `dateEvent` date NOT NULL,
  `timeEvent` time NOT NULL,
  `eventId` char(6) NOT NULL,
  `NumSalle` int(11) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `edition`
--

INSERT INTO `edition` (`editionId`, `dateEvent`, `timeEvent`, `eventId`, `NumSalle`, `image`) VALUES
(1, '2025-04-10', '19:00:00', 'EV001', 101, 'https://wistatefair.com/fair/wp-content/uploads/2013/11/vendor-stages-600x400-1.png'),
(2, '2025-05-05', '20:30:00', 'EV002', 102, 'https://www.shutterstock.com/image-photo/back-view-cinematic-shot-conductor-600nw-2287585941.jpg'),
(3, '2025-06-15', '18:00:00', 'EV003', 103, 'https://www.lalive.com/assets/img/RealD_Premiere_1064x625_4-d6472a4a29.jpg'),
(4, '2025-07-20', '21:00:00', 'EV004', 102, 'https://dcm1eeuyachdi.cloudfront.net/fit-in/3840x3840/filters:quality(100):format(webp)/media/images/events/quayles-brewery/img-banner/521fcc27-a2d.png'),
(5, '2025-08-12', '09:00:00', 'EV005', 101, 'https://img.evbuc.com/https%3A%2F%2Fcdn.evbuc.com%2Fimages%2F942773333%2F240918952701%2F1%2Foriginal.20250123-112132?w=1000&auto=format%2Ccompress&q=75&sharp=10&s=5b767871f1b8214786107e963a629ee1'),
(6, '2025-09-30', '18:30:00', 'EV006', 102, 'https://www.zarely.co/cdn/shop/articles/Top_Ballet_Events_Worldwide_In_2019_1400x.jpg?v=1563412990'),
(7, '2025-10-22', '14:00:00', 'EV007', 103, 'https://images.bauerhosting.com/legacy/media/5ff3/5939/0786/1347/10a2/e9d6/richard-deng-in7-ybhrWvY-unsplash.jpg?ar=16%3A9&fit=crop&crop=top&auto=format&w=1440&q=80'),
(8, '2025-11-18', '16:00:00', 'EV008', 102, 'https://play3r.net/wp-content/uploads/2018/09/ZBR05528.jpg'),
(9, '2025-12-05', '20:00:00', 'EV009', 101, 'https://img.jagranjosh.com/images/2025/January/1212025/Best-Astronomical-Events-in-2025.webp'),
(10, '2026-01-15', '11:00:00', 'EV010', 103, 'https://cdn.prod.website-files.com/620b4dfc30add2618d5e13a5/622b57f4d6fe6adb2ed2c62c_ultimate-festival-planning-guide.jpeg');

-- --------------------------------------------------------

--
-- Structure de la table `evenement`
--

CREATE TABLE `evenement` (
  `eventId` char(6) NOT NULL,
  `eventType` varchar(50) NOT NULL,
  `eventTitle` varchar(100) NOT NULL,
  `eventDescription` text NOT NULL,
  `TariffNormal` decimal(10,2) NOT NULL,
  `TariffReduit` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `evenement`
--

INSERT INTO `evenement` (`eventId`, `eventType`, `eventTitle`, `eventDescription`, `TariffNormal`, `TariffReduit`) VALUES
('EV001', 'Musique', 'Rock Night', 'A live rock concert.', '50.00', '30.00'),
('EV002', 'Théatre', 'Shakespeare Play', 'A classical play performance.', '40.00', '25.00'),
('EV003', 'Cinéma', 'Movie Premiere', 'Exclusive screening of a new film.', '35.00', '20.00'),
('EV004', 'Théatre', 'Stand-up Special', 'A hilarious comedy night.', '45.00', '25.00'),
('EV005', 'Rencontres', 'Tech Summit 2025', 'The latest trends in technology.', '80.00', '50.00'),
('EV006', 'Théatre', 'Ballet Performance', 'A beautiful ballet show.', '55.00', '35.00'),
('EV007', 'Rencontres', 'Photography Basics', 'Learn photography from experts.', '30.00', '15.00'),
('EV008', 'Rencontres', 'Esports Championship', 'Compete in an intense gaming event.', '60.00', '40.00'),
('EV009', 'Rencontres', 'Astronomy Night', 'Explore the stars with professionals.', '20.00', '10.00'),
('EV010', 'Rencontres', 'Food and Music Fest', 'Enjoy food and music together.', '25.00', '15.00');

-- --------------------------------------------------------

--
-- Structure de la table `reservation`
--

CREATE TABLE `reservation` (
  `idReservation` int(11) NOT NULL,
  `qteBilletsNormal` int(11) NOT NULL,
  `qteBilletsReduit` int(11) NOT NULL,
  `editionId` int(11) NOT NULL,
  `idUser` char(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `reservation`
--

INSERT INTO `reservation` (`idReservation`, `qteBilletsNormal`, `qteBilletsReduit`, `editionId`, `idUser`) VALUES
(1, 1, 1, 2, '2');

-- --------------------------------------------------------

--
-- Structure de la table `salle`
--

CREATE TABLE `salle` (
  `NumSalle` int(11) NOT NULL,
  `capSalle` int(11) NOT NULL,
  `DescSalle` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `salle`
--

INSERT INTO `salle` (`NumSalle`, `capSalle`, `DescSalle`) VALUES
(101, 5, 'Main Concert Hall'),
(102, 150, 'Small Theater'),
(103, 250, 'Cinema Room');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `idUser` char(10) NOT NULL,
  `nomUser` varchar(30) NOT NULL,
  `prenomUser` varchar(30) NOT NULL,
  `mailUser` varchar(100) NOT NULL,
  `motPasse` char(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`idUser`, `nomUser`, `prenomUser`, `mailUser`, `motPasse`) VALUES
('1', 'el meayouf', 'mohamed', 'elmeayouf.mohamed.solicode@gmail.com', '12345678'),
('2', 'el meayouf', 'youssef', 'k@k.com', '01');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `billet`
--
ALTER TABLE `billet`
  ADD PRIMARY KEY (`billetId`),
  ADD KEY `idReservation` (`idReservation`);

--
-- Index pour la table `edition`
--
ALTER TABLE `edition`
  ADD PRIMARY KEY (`editionId`),
  ADD KEY `eventId` (`eventId`),
  ADD KEY `NumSalle` (`NumSalle`);

--
-- Index pour la table `evenement`
--
ALTER TABLE `evenement`
  ADD PRIMARY KEY (`eventId`);

--
-- Index pour la table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`idReservation`),
  ADD KEY `editionId` (`editionId`),
  ADD KEY `idUser` (`idUser`);

--
-- Index pour la table `salle`
--
ALTER TABLE `salle`
  ADD PRIMARY KEY (`NumSalle`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`idUser`),
  ADD UNIQUE KEY `mailUser` (`mailUser`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `edition`
--
ALTER TABLE `edition`
  MODIFY `editionId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `idReservation` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `billet`
--
ALTER TABLE `billet`
  ADD CONSTRAINT `billet_ibfk_1` FOREIGN KEY (`idReservation`) REFERENCES `reservation` (`idReservation`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
