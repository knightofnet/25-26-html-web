-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 23 mars 2025 à 11:47
-- Version du serveur : 10.4.22-MariaDB
-- Version de PHP : 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `jeuxvideos`
--

-- --------------------------------------------------------

--
-- Structure de la table `console`
--

CREATE TABLE `console` (
  `idconsole` int(11) NOT NULL,
  `nomconsole` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `console`
--

INSERT INTO `console` (`idconsole`, `nomconsole`) VALUES
(1, 'Dreamcast'),
(2, 'Gameboy'),
(3, 'GameCube'),
(4, 'GBA'),
(5, 'Megadrive'),
(6, 'NES'),
(7, 'Nintendo 64'),
(8, 'PC'),
(9, 'PS'),
(10, 'PS2'),
(11, 'SuperNES'),
(12, 'Xbox');

-- --------------------------------------------------------

--
-- Structure de la table `jeux`
--

CREATE TABLE `jeux` (
  `idjeu` int(11) NOT NULL,
  `nomjeu` varchar(50) NOT NULL,
  `FKidpersonne` int(11) NOT NULL,
  `FKidconsole` int(11) NOT NULL,
  `prix` int(11) NOT NULL,
  `nbjoueurmax` int(11) NOT NULL,
  `commentaire` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `jeux`
--

INSERT INTO `jeux` (`idjeu`, `nomjeu`, `FKidpersonne`, `FKidconsole`, `prix`, `nbjoueurmax`, `commentaire`) VALUES
(1, 'Super Mario Bros', 2, 6, 4, 1, 'Un jeu d\'anthologie !'),
(2, 'Sonic', 5, 5, 2, 1, 'Pour moi, le meilleur jeu du monde !'),
(3, 'Zelda : ocarina of time', 2, 7, 15, 1, 'Un jeu grand, beau et complet comme on en voit rarement de nos jours'),
(4, 'Mario Kart 64', 2, 7, 25, 4, 'Un excellent jeu de kart !'),
(5, 'Super Smash Bros Melee', 4, 3, 55, 4, 'Un jeu de baston délirant !'),
(6, 'Dead or Alive Xtreme Beach Volley Ball', 5, 12, 60, 4, 'Un jeu de beach volley de toute beauté '),
(7, 'Enter the Matrix', 4, 8, 45, 1, 'Plutôt bof comme jeu, mais ça complète bien le film'),
(8, 'Max Payne 2', 4, 8, 50, 1, 'Très réaliste, une sorte de film noir sur fond d\'histoire d\'amour. A essayer !'),
(9, 'Yoshi\'s Island', 2, 11, 6, 1, 'Le paradis des Yoshis '),
(10, 'Commandos 3', 2, 8, 44, 12, 'Un bon jeu d\'action où on dirige un commando pendant la 2ème guerre mondiale !'),
(11, 'Final Fantasy X', 5, 10, 40, 1, 'Encore un Final Fantasy mais celui la est encore plus beau !'),
(12, 'Pokemon Rubis', 2, 4, 44, 4, 'Pika-Pika-chu !!!'),
(13, 'Starcraft', 4, 8, 19, 8, 'Le meilleur jeux pc de tout les temps !'),
(14, 'Grand Theft Auto 3', 4, 10, 30, 1, 'Comme dans les autres Gta on ecrase tout le monde :) .'),
(15, 'Homeworld 2', 4, 8, 45, 6, 'Superbe ! '),
(16, 'Aladin', 5, 11, 10, 1, 'Comme le dessin Animé !'),
(17, 'Super Mario Bros 3', 4, 11, 10, 2, 'Le meilleur Mario selon moi.'),
(18, 'SSX 3', 2, 12, 56, 2, 'Un très bon jeu de snow !'),
(19, 'Star Wars : Jedi outcast', 5, 12, 33, 1, 'Encore un jeu sur star-wars où on se prend pour Luke Skywalker !'),
(20, 'Actua Soccer 3', 5, 9, 30, 2, 'Un jeu de foot assez bof ...'),
(21, 'Time Crisis 3', 2, 10, 40, 1, 'Un troisième volet efficace mais pas vraiment surprenant'),
(22, 'X-FILES', 5, 9, 25, 1, 'Un jeu censé ressembler a la série mais assez raté ...'),
(23, 'Soul Calibur 2', 5, 12, 54, 1, 'Un jeu bien axé sur le combat'),
(24, 'Diablo', 2, 9, 20, 1, 'Comme sur PC mais la c\'est sur un ecran de télé :) !'),
(25, 'Street Fighter 2', 5, 5, 10, 2, 'Le célèbre jeu de combat !'),
(26, 'Gundam Battle Assault 2', 2, 9, 29, 1, 'Jeu japonais dont le gameplay est un peu limité. Peu de robots malheureusement'),
(27, 'Spider-Man', 2, 5, 15, 1, 'Vivez l\'aventure de l\'homme araignée'),
(28, 'Midtown Madness 3', 4, 12, 59, 6, 'Dans la suite des autres versions de Midtown Madness'),
(29, 'Tetris', 2, 2, 5, 1, 'Qui ne connait pas '),
(30, 'The Rocketeer', 4, 6, 2, 1, 'Un super un film et un jeu de m*rde '),
(31, 'Pro Evolution Soccer 3', 5, 10, 59, 2, 'Un petit jeu de foot sur PS2'),
(32, 'Ice Hockey', 4, 6, 7, 2, 'Jamais joué mais a mon avis ca parle de hockey sur glace '),
(33, 'Sydney 2000', 2, 1, 15, 2, 'Les JO de Sydney dans votre salon !'),
(34, 'NBA 2k', 5, 1, 12, 2, 'A votre avis '),
(35, 'Aliens Versus Predator : Extinction', 4, 10, 20, 2, 'Un shoot\'em up pour PC'),
(36, 'Crazy Taxi', 2, 1, 11, 1, 'Conduite de taxi en folie !'),
(37, 'Le Maillon Faible', 3, 10, 10, 1, 'Le jeu de l\'émission'),
(38, 'FIFA 64', 4, 7, 25, 2, 'Le premier jeu de foot sur la N64 !'),
(39, 'Qui Veut Gagner Des Millions', 2, 10, 10, 1, 'Le jeu de l\'émission'),
(40, 'Monopoly', 6, 7, 21, 4, 'Bheuuu le monopoly sur N64 !'),
(41, 'Taxi 3', 1, 10, 19, 4, 'Un jeu de voiture sur le film'),
(42, 'Indiana Jones Et Le Tombeau De L\'Empereur', 2, 10, 25, 1, 'Notre aventurier préféré est de retour !!!'),
(43, 'F-ZERO', 3, 4, 25, 4, 'Un super jeu de course futuriste !'),
(44, 'Harry Potter Et La Chambre Des Secrets', 3, 12, 30, 1, 'Abracadabra !! Le célebre magicien est de retour !'),
(45, 'Half-Life', 1, 8, 15, 32, 'Autre meilleur jeu de tout les temps (surtout ses mods).'),
(46, 'Myst III Exile', 6, 12, 49, 1, 'Un jeu de réflexion'),
(47, 'Wario World', 6, 3, 40, 4, 'Wario vs Mario ! Qui gagnera ! '),
(48, 'Rollercoaster Tycoon', 2, 12, 29, 1, 'Jeu de gestion de parc attraction'),
(49, 'Splinter Cell', 5, 12, 53, 1, 'Jeu magnifique !');

-- --------------------------------------------------------

--
-- Structure de la table `personne`
--

CREATE TABLE `personne` (
  `idpersonne` int(11) NOT NULL,
  `nompersonne` varchar(50) NOT NULL,
  `typepersonne` varchar(20) NOT NULL DEFAULT 'joueur'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `personne`
--

INSERT INTO `personne` (`idpersonne`, `nompersonne`, `typepersonne`) VALUES
(1, 'Gaelle', 'joueur'),
(2, 'Florent', 'joueur'),
(3, 'Walid', 'joueur'),
(4, 'Mathieu', 'joueur'),
(5, 'Lina', 'joueur'),
(6, 'Sebastien', 'joueur'),
(7, 'Soline', 'joueur'),
(8, 'Romain', 'administrateur'),
(9, 'Lourdsini', 'partenaire'),
(10, 'Loic', 'partenaire'),
(11, 'Nassim', 'partenaire');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `console`
--
ALTER TABLE `console`
  ADD PRIMARY KEY (`idconsole`);

--
-- Index pour la table `jeux`
--
ALTER TABLE `jeux`
  ADD PRIMARY KEY (`idjeu`),
  ADD KEY `FKidpersonne` (`FKidpersonne`),
  ADD KEY `FKidconsole` (`FKidconsole`);

--
-- Index pour la table `personne`
--
ALTER TABLE `personne`
  ADD PRIMARY KEY (`idpersonne`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `console`
--
ALTER TABLE `console`
  MODIFY `idconsole` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `jeux`
--
ALTER TABLE `jeux`
  MODIFY `idjeu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT pour la table `personne`
--
ALTER TABLE `personne`
  MODIFY `idpersonne` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `jeux`
--
ALTER TABLE `jeux`
  ADD CONSTRAINT `jeux_ibfk_1` FOREIGN KEY (`FKidpersonne`) REFERENCES `personne` (`idpersonne`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `jeux_ibfk_2` FOREIGN KEY (`FKidconsole`) REFERENCES `console` (`idconsole`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
