-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 28, 2026 at 06:57 PM
-- Server version: 8.0.42-0ubuntu0.20.04.1
-- PHP Version: 7.4.3-4ubuntu2.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `stage_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `candidatures`
--

CREATE TABLE `candidatures` (
  `id` int NOT NULL,
  `offre_id` int NOT NULL,
  `etudiant_id` int NOT NULL,
  `cv_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lettre_motivation` text COLLATE utf8mb4_unicode_ci,
  `statut` enum('en_attente','acceptee','refusee') COLLATE utf8mb4_unicode_ci DEFAULT 'en_attente',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `entreprises`
--

CREATE TABLE `entreprises` (
  `id` int NOT NULL,
  `nom` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telephone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ville` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `secteur` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `entreprises`
--

INSERT INTO `entreprises` (`id`, `nom`, `description`, `email`, `telephone`, `ville`, `secteur`, `created_at`) VALUES
(1, 'NovaTech Solutions', 'Entreprise spécialisée en développement logiciel sur mesure.', 'contact@novatech.fr', '0240112233', 'Paris', 'Informatique', '2026-03-23 23:13:31'),
(2, 'BrandWave Agency', 'Agence de marketing digital et gestion des réseaux sociaux.', 'rh@brandwave.fr', '0472334455', 'Lyon', 'Marketing', '2026-03-23 23:13:31'),
(3, 'IndusTrial Group', 'Groupe industriel spécialisé en automatisme et robotique.', 'stage@industrial-group.fr', '0240556677', 'Nantes', 'Industrie', '2026-03-23 23:13:31'),
(4, 'Pixel Studio', 'Studio de design UI/UX et identité visuelle.', 'hello@pixelstudio.fr', '0556778899', 'Bordeaux', 'Design', '2026-03-23 23:13:31'),
(5, 'CapitalEdge Finance', 'Cabinet de conseil en finance d\'entreprise et audit.', 'recrutement@capitaledge.fr', '0144889900', 'Paris', 'Finance', '2026-03-23 23:13:31'),
(6, 'DataSphere', 'Startup spécialisée en data science et intelligence artificielle.', 'jobs@datasphere.fr', '0467001122', 'Montpellier', 'Informatique', '2026-03-23 23:13:31'),
(7, 'ComMedia Group', 'Groupe de communication et relations presse.', 'contact@commedia.fr', '0320223344', 'Lille', 'Marketing', '2026-03-23 23:13:31'),
(8, 'SteelPro Industries', 'Industrie lourde, maintenance et gestion de production.', 'rh@steelpro.fr', '0328445566', 'Dunkerque', 'Industrie', '2026-03-23 23:13:31'),
(9, 'AppForge Labs', 'Laboratoire de développement d\'applications mobiles iOS/Android.', 'team@appforge.fr', '0561667788', 'Toulouse', 'Informatique', '2026-03-23 23:13:31'),
(10, 'Lumi Creative', 'Agence créative spécialisée en motion design et animation 3D.', 'studio@lumicreative.fr', '0467889900', 'Montpellier', 'Design', '2026-03-23 23:13:31'),
(61, 'Airbus', 'Entreprise de fabrication des avions et des pièces d\'avion', 'airbus@gmail.com', '0169520937', '', '', '2026-03-28 14:18:05');

-- --------------------------------------------------------

--
-- Table structure for table `evaluations`
--

CREATE TABLE `evaluations` (
  `id` int NOT NULL,
  `entreprise_id` int NOT NULL,
  `etudiant_id` int NOT NULL,
  `note` tinyint DEFAULT NULL,
  `commentaire` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ;

-- --------------------------------------------------------

--
-- Table structure for table `offres`
--

CREATE TABLE `offres` (
  `id` int NOT NULL,
  `titre` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `entreprise_id` int NOT NULL,
  `ville` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `domaine` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nb_souhaite` int DEFAULT NULL,
  `delai` int DEFAULT NULL,
  `duree` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remuneration` decimal(8,2) DEFAULT NULL,
  `r_period` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avantages` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_offre` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `offres`
--

INSERT INTO `offres` (`id`, `titre`, `description`, `entreprise_id`, `ville`, `domaine`, `nb_souhaite`, `delai`, `duree`, `remuneration`, `r_period`, `avantages`, `date_offre`) VALUES
(1, 'Développeur Web Front-end', 'Vous rejoindrez l\'équipe digitale de NovaTech pour développer des interfaces modernes en React et intégrer des maquettes Figma. Vous participerez aux revues de code et aux daily meetings.', 1, 'Paris', 'Informatique', 1, 14, '3 mois', '600.00', 'Mois', 'Télétravail,Tickets restaurant,Mutuelle', '2026-03-23 23:13:32'),
(2, 'Stage Marketing Digital', 'BrandWave Agency recherche un stagiaire pour gérer les réseaux sociaux (Instagram, LinkedIn, TikTok), rédiger des newsletters et suivre les campagnes Google Ads.', 2, 'Lyon', 'Marketing', 1, 14, '4 mois', '550.00', 'Mois', 'Tickets restaurant,Transport remboursé,Horaires flexibles', '2026-03-23 23:13:32'),
(3, 'Ingénieur Automatisme', 'IndusTrial Group propose un stage en automatisme industriel. Vous développerez des programmes PLC (Siemens, Schneider) et participerez à la mise en service de lignes de production.', 3, 'Nantes', 'Industrie', 1, 14, '6 mois', '700.00', 'Mois', 'Logement possible,Panier repas,13ème mois', '2026-03-23 23:13:32'),
(4, 'UI/UX Designer', 'Pixel Studio cherche un stagiaire pour concevoir des maquettes Figma, réaliser des tests utilisateurs et contribuer à des projets de refonte d\'interfaces web et mobile.', 4, 'Bordeaux', 'Design', 1, 14, '3 mois', '580.00', 'Mois', 'Télétravail,Tickets restaurant,Formation incluse', '2026-03-23 23:13:32'),
(5, 'Analyste Financier Junior', 'CapitalEdge Finance recrute un stagiaire pour participer à l\'analyse financière de dossiers clients, la modélisation Excel et la rédaction de notes de synthèse.', 5, 'Paris', 'Finance', 1, 14, '5 mois', '800.00', 'Mois', 'Mutuelle premium,Tickets restaurant,Parking', '2026-03-23 23:13:32'),
(6, 'Data Analyst', 'DataSphere propose un stage en analyse de données. Vous exploiterez des datasets avec Python/Pandas, créerez des dashboards Power BI et présenterez vos résultats à l\'équipe.', 6, 'Montpellier', 'Informatique', 2, 14, '4 mois', '650.00', 'Mois', 'Télétravail,Horaires flexibles,Stock options', '2026-03-23 23:13:32'),
(7, 'Chargé de Communication', 'ComMedia Group recherche un stagiaire en communication pour rédiger des communiqués de presse, gérer le planning éditorial et animer les relations avec les médias.', 7, 'Lille', 'Marketing', 1, 14, '4 mois', '530.00', 'Mois', 'Transport remboursé,Tickets restaurant,RTT', '2026-03-23 23:13:32'),
(8, 'Technicien Maintenance Industrielle', 'SteelPro Industries propose un stage en maintenance préventive et curative sur lignes de production. Vous travaillerez en équipe avec les techniciens seniors.', 8, 'Dunkerque', 'Industrie', 1, 14, '6 mois', '720.00', 'Mois', 'Panier repas,Logement possible,Prime de fin de stage', '2026-03-23 23:13:32'),
(9, 'Développeur Mobile iOS', 'AppForge Labs recherche un stagiaire pour développer des fonctionnalités en Swift sur une application mobile grand public. Tests unitaires et publication App Store inclus.', 9, 'Toulouse', 'Informatique', 2, 14, '4 mois', '630.00', 'Mois', 'Télétravail,Tickets restaurant,MacBook fourni', '2026-03-23 23:13:32'),
(10, 'Graphiste Motion Design', 'Lumi Creative propose un stage en motion design. Vous créerez des animations After Effects, des vidéos promotionnelles et des contenus pour les réseaux sociaux.', 10, 'Montpellier', 'Design', 1, 14, '3 mois', '560.00', 'Mois', 'Horaires flexibles,Tickets restaurant,Matériel fourni', '2026-03-23 23:13:32'),
(11, 'Développeur Back-end PHP', 'NovaTech Solutions recherche un stagiaire back-end PHP pour développer des APIs REST, optimiser les requêtes MySQL et mettre en place des tests unitaires PHPUnit.', 1, 'Paris', 'Informatique', 1, 14, '3 mois', '620.00', 'Mois', 'Télétravail,Mutuelle,Tickets restaurant', '2026-03-23 23:13:32'),
(12, 'Chef de Projet Digital', 'BrandWave Agency recrute un stagiaire chef de projet pour coordonner les équipes créatives, rédiger les cahiers des charges et assurer le suivi des livrables clients.', 2, 'Lyon', 'Marketing', 1, 14, '5 mois', '600.00', 'Mois', 'Transport remboursé,Tickets restaurant,Formation', '2026-03-23 23:13:32'),
(13, 'Ingénieur Qualité', 'IndusTrial Group propose un stage en qualité industrielle. Vous rédigerez des procédures, réaliserez des audits internes et suivrez les indicateurs de performance qualité.', 3, 'Nantes', 'Industrie', 3, 14, '6 mois', '710.00', 'Mois', 'Panier repas,13ème mois,Mutuelle', '2026-03-23 23:13:32'),
(14, 'Contrôleur de Gestion', 'CapitalEdge Finance recrute un stagiaire pour participer à la clôture mensuelle, l\'analyse des écarts budgétaires et la production de tableaux de bord de gestion.', 5, 'Paris', 'Finance', 1, 14, '5 mois', '780.00', 'Mois', 'Tickets restaurant,Parking,Mutuelle premium', '2026-03-23 23:13:32'),
(15, 'Développeur Full Stack', 'DataSphere recherche un stagiaire full stack (PHP/JS) pour développer de nouvelles fonctionnalités sur leur plateforme de visualisation de données et corriger des bugs.', 6, 'Montpellier', 'Informatique', 1, 14, '3 mois', '640.00', 'Mois', 'Télétravail,Horaires flexibles,Tickets restaurant', '2026-03-23 23:13:32'),
(92, 'Ingénieur systèmes embarqués', '<p><strong>À propos du poste</strong></p>\r\n            <p>Décrivez ici le contexte général du stage.</p>\r\n\r\n            <p><strong>Missions</strong></p>\r\n            <ul>\r\n                <li>Mission principale 1</li>\r\n                <li>Mission principale 2</li>\r\n                <li>Mission principale 3</li>\r\n            </ul>\r\n\r\n            <p><strong>Profil recherché</strong></p>\r\n            <ul>\r\n                <li>Formation en cours (ex: Bac+3 en informatique)</li>\r\n                <li>Compétences requises</li>\r\n                <li>Qualités attendues</li>\r\n            </ul>', 61, 'Toulouse', 'industrie', NULL, 7, NULL, '800.00', NULL, 'transport,interessement,rtt,vehicule', '2026-03-28 16:41:36'),
(93, 'Ingénieur logiciel', '<p><strong>À propos du poste</strong></p>\r\n            <p>Décrivez ici le contexte général du stage.</p>\r\n\r\n            <p><strong>Missions</strong></p>\r\n            <ul>\r\n                <li>Mission principale 1</li>\r\n                <li>Mission principale 2</li>\r\n                <li>Mission principale 3</li>\r\n            </ul>\r\n\r\n            <p><strong>Profil recherché</strong></p>\r\n            <ul>\r\n                <li>Formation en cours (ex: Bac+3 en informatique)</li>\r\n                <li>Compétences requises</li>\r\n                <li>Qualités attendues</li>\r\n            </ul>', 4, 'Toulouse', 'informatique', 0, 3, NULL, '450.00', 'fMonth', 'transport,interessement,cantine,rtt,flextime', '2026-03-28 17:27:24');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `nom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','pilote','etudiant') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'etudiant',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nom`, `prenom`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Admin', 'Super', 'admin@stagehub.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '2026-03-23 15:13:38'),
(14, 'Martin', 'Alice', 'alice@etudiant.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'etudiant', '2026-03-23 23:21:07'),
(15, 'Leroy', 'Bob', 'bob@etudiant.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'etudiant', '2026-03-23 23:21:07'),
(16, 'Gallet', 'Jeremy', 'jeremy@pilote.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'pilote', '2026-03-23 23:21:07'),
(25, 'DONA', 'Jean', 'dona@gmail.com', '$2y$10$PEAqGvNa6nvdX9g5nejX3.cK3tjd3j./IWp82djnvvq3aiCK5nEqK', 'etudiant', '2026-03-27 19:14:29');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int NOT NULL,
  `etudiant_id` int NOT NULL,
  `offre_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `candidatures`
--
ALTER TABLE `candidatures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `offre_id` (`offre_id`),
  ADD KEY `etudiant_id` (`etudiant_id`);

--
-- Indexes for table `entreprises`
--
ALTER TABLE `entreprises`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nom` (`nom`);

--
-- Indexes for table `evaluations`
--
ALTER TABLE `evaluations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `entreprise_id` (`entreprise_id`),
  ADD KEY `etudiant_id` (`etudiant_id`);

--
-- Indexes for table `offres`
--
ALTER TABLE `offres`
  ADD PRIMARY KEY (`id`),
  ADD KEY `entreprise_id` (`entreprise_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `etudiant_id` (`etudiant_id`),
  ADD KEY `offre_id` (`offre_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `candidatures`
--
ALTER TABLE `candidatures`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `entreprises`
--
ALTER TABLE `entreprises`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `evaluations`
--
ALTER TABLE `evaluations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `offres`
--
ALTER TABLE `offres`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `candidatures`
--
ALTER TABLE `candidatures`
  ADD CONSTRAINT `candidatures_ibfk_1` FOREIGN KEY (`offre_id`) REFERENCES `offres` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `candidatures_ibfk_2` FOREIGN KEY (`etudiant_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `evaluations`
--
ALTER TABLE `evaluations`
  ADD CONSTRAINT `evaluations_ibfk_1` FOREIGN KEY (`entreprise_id`) REFERENCES `entreprises` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `evaluations_ibfk_2` FOREIGN KEY (`etudiant_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `offres`
--
ALTER TABLE `offres`
  ADD CONSTRAINT `offres_ibfk_1` FOREIGN KEY (`entreprise_id`) REFERENCES `entreprises` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`etudiant_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`offre_id`) REFERENCES `offres` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
