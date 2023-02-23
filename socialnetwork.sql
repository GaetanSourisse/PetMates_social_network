-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : jeu. 23 fév. 2023 à 09:20
-- Version du serveur : 5.7.24
-- Version de PHP : 8.1.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `socialnetwork`
--

-- --------------------------------------------------------

--
-- Structure de la table `comments`
--

CREATE TABLE `comments` (
  `id` int(10) NOT NULL,
  `id_post` int(11) UNSIGNED NOT NULL,
  `content` varchar(300) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `comments`
--

INSERT INTO `comments` (`id`, `id_post`, `content`, `user_id`, `created`) VALUES
(10, 14, 'Moi je veux bien venir avec toi !', 8, '2023-02-22 09:21:48'),
(11, 9, 'Trop d\'accord avec toi :)', 5, '2023-02-22 09:22:12'),
(12, 14, 'Super on y va à 17h30 vendredi ?', 9, '2023-02-22 10:23:15'),
(13, 8, 'Qui pour aller à Guérande ce weekend pour la fête à l\'oignon médiéval ?', 9, '2023-02-22 10:24:01'),
(14, 14, 'moi aussi !', 9, '2023-02-22 10:35:00');

-- --------------------------------------------------------

--
-- Structure de la table `followers`
--

CREATE TABLE `followers` (
  `id` int(10) UNSIGNED NOT NULL,
  `followed_user_id` int(10) UNSIGNED NOT NULL,
  `following_user_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `followers`
--

INSERT INTO `followers` (`id`, `followed_user_id`, `following_user_id`) VALUES
(1, 5, 3),
(2, 5, 6),
(3, 5, 7),
(4, 1, 5),
(5, 2, 5),
(6, 4, 5),
(7, 1, 2),
(8, 1, 3),
(9, 1, 7),
(10, 1, 6),
(11, 1, 4),
(12, 7, 8),
(13, 5, 8),
(14, 8, 9),
(15, 7, 9),
(16, 9, 10);

-- --------------------------------------------------------

--
-- Structure de la table `likes`
--

CREATE TABLE `likes` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `post_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `likes`
--

INSERT INTO `likes` (`id`, `user_id`, `post_id`) VALUES
(1, 3, 1),
(2, 3, 2),
(3, 3, 3),
(4, 3, 4),
(5, 3, 5),
(6, 3, 6),
(7, 3, 7),
(8, 3, 8),
(9, 3, 9),
(10, 3, 10),
(11, 1, 9),
(12, 2, 9),
(13, 4, 9),
(14, 5, 9),
(15, 8, 12),
(16, 8, 10);

-- --------------------------------------------------------

--
-- Structure de la table `pets`
--

CREATE TABLE `pets` (
  `id` int(11) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `type_pet` varchar(30) NOT NULL,
  `race_pet` varchar(30) NOT NULL,
  `name_pet` varchar(30) NOT NULL,
  `sex_pet` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `pets`
--

INSERT INTO `pets` (`id`, `user_id`, `type_pet`, `race_pet`, `name_pet`, `sex_pet`) VALUES
(11, 10, 'chien', 'labrador', 'gigi', '♂ Mâle'),
(12, 10, 'Chat', 'siamois', 'Peach', '♀ Femelle'),
(13, 5, 'Lapin ', 'Nain', 'Carotte', 'Femelle'),
(14, 1, 'Gecko', 'Léopard', 'Bandit', 'Mâle'),
(15, 8, 'Chat', '', 'Mireille', 'Femelle'),
(16, 9, 'Chien', 'Shetland', 'Simone', 'Femelle'),
(17, 7, 'Chien', 'Jack Russell', 'Nestor', 'Mâle'),
(18, 6, 'Oiseau', 'Perruche', 'Titi', 'Mâle');

-- --------------------------------------------------------

--
-- Structure de la table `posts`
--

CREATE TABLE `posts` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `content` text NOT NULL,
  `created` datetime NOT NULL,
  `parent_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `content`, `created`, `parent_id`) VALUES
(1, 5, 'Trois vétérinaires passionnés du monde des NAC ont créé Caduvet, un ensemble de clinique spécialisée, situées dans le Nord Pas-de-Calais et la Belgique. Depuis 2007, cette activité de clinique vétérinaire dédiée au NAC s\'est développée pour répondre aux besoins des propriétaires de ces animaux et permettre aux vétérinaires de mieux appréhender les soins sur ces petites bêtes. Il existe aujourd\'hui 5 cliniques et des partenariats avec plusieurs structures vétérinaires qui propose des consultations.\r\n\r\nPour la petite histoire, l\'expression \"nouveaux animaux de compagnie\" a été créé en 1984 par un vétérinaire lors d\'une conférence sur justement les problèmes rencontrés pour soigner ces animaux face à leur nombre croissant dans les salles d\'attente. Rappelons que les NAC, c\'est une grande famille, cela regroupe les lapins, les rongeurs, les furets mais aussi les reptiles, les oiseaux et tous les animaux exotiques, inhabituels qui nécessiteraient des soins spécifiques par rapport à ceux prodigués à des chiens et des chats. \r\n', '2020-02-05 18:19:12', NULL),
(2, 5, 'Y a-t-il des promos en animalerie pour le foin des lapins en ce moment ? Carotte est allergique au sien :/', '2020-04-06 18:19:12', NULL),
(3, 5, 'Bonjour à tous !\r\n\r\nJe reviens pour vous partager un projet auquel j\'ai pris part ce mois de décembre. Avec des amis nous avons tourné une petite vidéo sur le thème de noël pour promouvoir des goodies dont tous les bénéfices seront reversés à un refuge.\r\n\r\nSi vous ne pouvez pas acheter un T-shirt, tout partage sera bienvenu, ne serait-ce que pour la beauté du geste :)\r\n\r\nBonne fêtes à tous !', '2020-07-12 18:21:49', NULL),
(4, 5, 'Bonjour, je viens à vous pour vous parler du site Zoomalia si vous n’avez jamais commandé dessus il est temps 🥳 j’ai un lien de parrainage qui vous fera gagner 1000 points sur votre prochaine commande et à moi aussi ce qui nous donne des cadeaux gratuits pour nos loulous. Moi c’est le seul site où je trouve la nourriture pas chère pour mes Loulous 20kg pour 19,99€ 👌🏼 et les frais de port sont vraiment peu élevés ! ', '2020-08-04 18:21:49', NULL),
(5, 5, 'Coucou à tous, qui veut venir l\'anniversaire de Carotte chez moi samedi prochain ?? Elle m\'a suggéré l\'idée de faire un goûter avec tous ses amis NAC !!', '2020-09-25 18:24:30', NULL),
(6, 5, 'Bonjour, merci pour l\'acceptation, mon lapin a cette espèce de bouton sec qui grossi sur l\'oreille qu\'en pensez-vous ? un bon véto à me recommander ??', '2020-10-15 00:35:42', NULL),
(7, 8, 'Mireille a encore fait pipi sur mon linge sale :/ des solutions pour éviter ce carnage quotidien ? ', '2020-10-25 00:35:39', NULL),
(8, 1, 'Bonjour a tous, je viens vers la commu a la recherche de conseils 🙂\r\nJ\'ai une petite Gecko Léopard, de bientôt 3ans (ça pousse si vite !) Et en pleine forme dans son terra\r\nMais voilà, j\'en ai ras la cagoule de racheter des ampoules toutes les deux semaines, quand c\'est pas deux en une semaine. J\'en suis venu à la conclusion que ça venait de mon réflecteur, qui n\'est plus tout jeune (il était a un ami avant, et quand il est parti vivre au Canada j\'ai tout récupéré : terra, lampe, gecko, criquets et vers)\r\nSaurez vous, Ô commu des Écailleux, m\'aider à choisir un bon rapport qualité prix pour mon réflecteur et mon ampoule ?', '2020-11-10 18:26:12', NULL),
(9, 1, 'Bonsoir et merci de m’avoir accepté dans ce groupe.\r\nJ’ai fait l’acquisition de mon tout premier gecko léopard. Il est né en aout 2022, c’est un petit bandit.\r\nJ’ai besoin de conseils concernant quelques petites choses.\r\nDepuis le jour ou je l’ai eu, je ne l’ai jamais pris dans les mains, il est assez craintif alors j’ai décidé de lui laisser le temps de se faire à son nouvel environnement tranquillement.\r\nSon premier repas à eu lieu le vendredi 10 au soir, il est venu manger à la pince. \r\nMa première question concerne son alimentation, combien de fois dois-je le nourrir et à quelle fréquence ?', '2020-11-20 18:26:50', NULL),
(10, 7, 'Bonjour à tous ! Moi et mon chien Nestor avons envie d\'une grande balade au bord de la mer ce dimanche ! Est-ce que ça tente d\'autres personnes ? :)\r\nPlus on est de fous plus on rit !', '2020-11-30 18:31:16', NULL),
(11, 8, 'Bonjour à tous\r\nJ\'aurais besoin de vos conseils svp\r\nJe viens de faire castré ma minette aujourd\'hui et on lui as mis une colorette qu\'elle doit garder 8 à 9 jours.\r\nPersonnellement cela me fait de la peine de lui faire garder cette HORREUR\r\nDes personnes qui ont déjà vécu cette expérience ?', '2023-02-21 14:50:29', NULL),
(12, 8, 'Il y a plusieurs façons d\'apprendre « couché » à votre chat. Vous pouvez par exemple prendre une friandise dans votre main, la poser lentement sur le sol et l\'y tenir. Votre chat finira probablement par se coucher. Au début, donnez-lui la friandise dès le premier signe qu\'il va s\'allonger, puis de plus en plus tard dans le mouvement vers la position couchée. Renforcez le comportement souhaité avec le signal acoustique habituel. Si votre chat a compris ce que l’on attendait de lui, vous pouvez introduire un ordre. Un geste de la main vers le bas convient par exemple très bien. Vous pouvez également utiliser la technique du capturing pour « couché » (voir « assis »), par exemple si vous savez que votre chat va venir s\'allonger avec vous avant une soirée de détente sur le canapé.', '2023-02-21 15:03:54', NULL),
(13, 8, 'Au secours ! je suis coincée ! Mon chat s\'est endormi sur moi, je peux plus bouger !! :\'(', '2023-02-21 15:05:32', NULL),
(14, 9, 'Qui veut venir promener Simone avec moi au cours Cambronne demain ??', '2023-02-21 16:39:37', NULL),
(17, 10, 'Hello tous le monde !', '2023-02-22 10:41:12', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `posts_tags`
--

CREATE TABLE `posts_tags` (
  `id` int(10) UNSIGNED NOT NULL,
  `post_id` int(10) UNSIGNED NOT NULL,
  `tag_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `posts_tags`
--

INSERT INTO `posts_tags` (`id`, `post_id`, `tag_id`) VALUES
(1, 1, 1),
(2, 2, 4),
(3, 3, 3),
(4, 4, 4),
(5, 5, 3),
(6, 6, 1),
(7, 7, 2),
(8, 8, 5),
(9, 9, 5),
(10, 10, 9),
(11, 9, 1),
(12, 12, 6),
(13, 13, 2);

-- --------------------------------------------------------

--
-- Structure de la table `tags`
--

CREATE TABLE `tags` (
  `id` int(10) UNSIGNED NOT NULL,
  `label` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `tags`
--

INSERT INTO `tags` (`id`, `label`) VALUES
(4, 'animalerie'),
(2, 'chat'),
(7, 'chien'),
(6, 'éducation'),
(8, 'ornithologie'),
(3, 'petsitting'),
(9, 'promenade'),
(5, 'reptile'),
(1, 'vétérinaire');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `alias`) VALUES
(1, 'ada@test.org', '098f6bcd4621d373cade4e832627b4f6', 'ada'),
(2, 'alex@test.org', '098f6bcd4621d373cade4e832627b4f6', 'Alexandra'),
(3, 'bea@test.org', '098f6bcd4621d373cade4e832627b4f6', 'Béatrice'),
(4, 'zoe@test.org', '098f6bcd4621d373cade4e832627b4f6', 'Zoé'),
(5, 'felicie@test.org', '098f6bcd4621d373cade4e832627b4f6', 'Félicie'),
(6, 'cecile@test.com', '098f6bcd4621d373cade4e832627b4f6', 'Cécile'),
(7, 'chacha@test.net', '098f6bcd4621d373cade4e832627b4f6', 'Charlotte'),
(8, 'gutine@gutine.fr', '2df4544d6df4834d8ee71743d1a5c2d3', 'gutine'),
(9, 'ali@ali.fr', '86318e52f5ed4801abe1d13d509443de', 'ali'),
(10, 'cindybestaven44@outlook.fr', '2716948a64ef4d479c6d26c2506a63c1', 'Candy');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`id_post`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `followers`
--
ALTER TABLE `followers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_users_has_users_users2_idx` (`following_user_id`),
  ADD KEY `fk_users_has_users_users1_idx` (`followed_user_id`);

--
-- Index pour la table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_users_has_posts_posts1_idx` (`post_id`),
  ADD KEY `fk_users_has_posts_users1_idx` (`user_id`);

--
-- Index pour la table `pets`
--
ALTER TABLE `pets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_posts_users_idx` (`user_id`),
  ADD KEY `fk_posts_posts1_idx` (`parent_id`);

--
-- Index pour la table `posts_tags`
--
ALTER TABLE `posts_tags`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_posts_has_tags_tags1_idx` (`tag_id`),
  ADD KEY `fk_posts_has_tags_posts1_idx` (`post_id`);

--
-- Index pour la table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `label_UNIQUE` (`label`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`),
  ADD UNIQUE KEY `alias_UNIQUE` (`alias`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `followers`
--
ALTER TABLE `followers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `pets`
--
ALTER TABLE `pets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pour la table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT pour la table `posts_tags`
--
ALTER TABLE `posts_tags`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `fk_posts_id` FOREIGN KEY (`id_post`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_users_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `followers`
--
ALTER TABLE `followers`
  ADD CONSTRAINT `fk_users_has_users_users1` FOREIGN KEY (`followed_user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_users_has_users_users2` FOREIGN KEY (`following_user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `fk_users_has_posts_posts1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_users_has_posts_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `pets`
--
ALTER TABLE `pets`
  ADD CONSTRAINT `user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `fk_posts_posts1` FOREIGN KEY (`parent_id`) REFERENCES `posts` (`id`),
  ADD CONSTRAINT `fk_posts_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `posts_tags`
--
ALTER TABLE `posts_tags`
  ADD CONSTRAINT `fk_posts_has_tags_posts1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`),
  ADD CONSTRAINT `fk_posts_has_tags_tags1` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
