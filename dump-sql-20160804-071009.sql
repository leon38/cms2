-- --------------------------------------------------------

--
-- Structure de la table `categories_contents`
--

DROP TABLE IF EXISTS categories_contents;
CREATE TABLE IF NOT EXISTS `categories_contents` (
  `content_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`content_id`,`category_id`),
  KEY `IDX_6204B84E84A0A3ED` (`content_id`),
  KEY `IDX_6204B84E12469DE2` (`category_id`),
  CONSTRAINT `FK_6204B84E12469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_6204B84E84A0A3ED` FOREIGN KEY (`content_id`) REFERENCES `content` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
--
-- Contenu de la table `categories_contents`
--

INSERT INTO `categories_contents` (content_id, category_id) VALUES ("1", "3"),("2", "3"),("3", "5");

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

DROP TABLE IF EXISTS category;
CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `language_id` int(11) DEFAULT NULL,
  `reference_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci,
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  `ordre` int(11) DEFAULT NULL,
  `lvl` int(11) NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `published` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `banner` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_64C19C1F47645AE` (`url`),
  KEY `IDX_64C19C1727ACA70` (`parent_id`),
  KEY `IDX_64C19C182F1BAF4` (`language_id`),
  KEY `IDX_64C19C11645DEA9` (`reference_id`),
  KEY `IDX_64C19C16F9DB8E7` (`banner`),
  CONSTRAINT `FK_64C19C11645DEA9` FOREIGN KEY (`reference_id`) REFERENCES `category` (`id`),
  CONSTRAINT `FK_64C19C16F9DB8E7` FOREIGN KEY (`banner`) REFERENCES `media` (`id`),
  CONSTRAINT `FK_64C19C1727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `category` (`id`) ON DELETE SET NULL,
  CONSTRAINT `FK_64C19C182F1BAF4` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
--
-- Contenu de la table `category`
--

INSERT INTO `category` (id, parent_id, language_id, reference_id, title, description, lft, rgt, ordre, lvl, url, published, created, modified, banner) VALUES ("1", "", "", "", "Root", "", "1", "16", "1", "0", "root-html", "0", "2016-07-01 00:00:00", "2016-07-01 00:00:00", ""),("2", "1", "1", "", "Non catégorisé", "", "2", "3", "2", "1", "non-categorise-html", "0", "2016-07-02 19:20:29", "2016-07-16 10:08:26", ""),("3", "1", "1", "", "Endroits à visiter", "<p>Tous les lieux ou activités que l\'on fera quand on aura le temps</p>", "4", "5", "", "1", "endroits-a-visiter", "1", "2016-07-16 10:08:26", "2016-07-26 14:43:17", "9"),("4", "1", "1", "", "Recettes", "", "6", "13", "", "1", "recettes", "1", "2016-07-22 08:00:15", "2016-07-28 14:13:30", "31"),("5", "4", "1", "", "Desserts", "", "11", "12", "", "2", "desserts", "1", "2016-07-22 08:54:49", "2016-07-25 15:01:21", "24"),("6", "4", "1", "", "Entrées", "", "7", "8", "", "2", "entrees", "1", "2016-07-22 09:00:04", "2016-07-22 09:00:04", ""),("7", "4", "1", "", "Plats", "", "9", "10", "1", "2", "plats", "1", "2016-07-22 09:05:43", "2016-07-22 09:05:43", ""),("8", "1", "1", "", "Voyages", "", "14", "15", "", "1", "voyages", "1", "2016-07-28 08:32:44", "2016-07-28 08:32:44", "8");

-- --------------------------------------------------------

--
-- Structure de la table `contact_form`
--

DROP TABLE IF EXISTS contact_form;
CREATE TABLE IF NOT EXISTS `contact_form` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `alias` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `tag` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `html_form` longtext COLLATE utf8_unicode_ci NOT NULL,
  `sender` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `subject` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `html_message` longtext COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_7A777FB0E16C6B94` (`alias`),
  UNIQUE KEY `UNIQ_7A777FB0389B783` (`tag`),
  UNIQUE KEY `UNIQ_7A777FB05F004ACF` (`sender`),
  UNIQUE KEY `UNIQ_7A777FB0FBCE3E7A` (`subject`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
-- --------------------------------------------------------

--
-- Structure de la table `content`
--

DROP TABLE IF EXISTS content;
CREATE TABLE IF NOT EXISTS `content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language_id` int(11) DEFAULT NULL,
  `taxonomy_id` int(11) DEFAULT NULL,
  `reference_id` int(11) DEFAULT NULL,
  `author` int(11) DEFAULT NULL,
  `thumbnail` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `published` tinyint(1) NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_FEC530A9F47645AE` (`url`),
  KEY `IDX_FEC530A982F1BAF4` (`language_id`),
  KEY `IDX_FEC530A99557E6F6` (`taxonomy_id`),
  KEY `IDX_FEC530A91645DEA9` (`reference_id`),
  KEY `IDX_FEC530A9BDAFD8C8` (`author`),
  KEY `IDX_FEC530A9C35726E6` (`thumbnail`),
  CONSTRAINT `FK_FEC530A91645DEA9` FOREIGN KEY (`reference_id`) REFERENCES `content` (`id`),
  CONSTRAINT `FK_FEC530A982F1BAF4` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`),
  CONSTRAINT `FK_FEC530A99557E6F6` FOREIGN KEY (`taxonomy_id`) REFERENCES `content_taxonomy` (`id`),
  CONSTRAINT `FK_FEC530A9BDAFD8C8` FOREIGN KEY (`author`) REFERENCES `users` (`id`),
  CONSTRAINT `FK_FEC530A9C35726E6` FOREIGN KEY (`thumbnail`) REFERENCES `media` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
--
-- Contenu de la table `content`
--

INSERT INTO `content` (id, language_id, taxonomy_id, reference_id, author, thumbnail, title, description, created, modified, published, url) VALUES ("1", "1", "2", "1", "1", "1", "Safari de Peaugres", "<p>4 continents sur 80 hectares : un voyage au plus profond de la vie animale. Bienvenue dans le plus grand parc animalier de Rhône Alpes !\nÀ pied, en bus, en voiture, votre parc vous offre tous les modes d\'évasion. Vous voyagez de Madagascar aux pôles, vous rencontrez les espèces les plus insolites et caressez nos amis les plus familiers, comme les animaux de la ferme. Afrique, Amérique du Nord et contrées asiatiques se rejoignent au Safari de Peaugres.</p>", "2016-07-13 11:54:20", "2016-07-18 17:24:26", "1", "safari-de-peaugres"),("2", "1", "2", "2", "1", "2", "Caves de la Chartreuse", "", "2016-07-18 08:58:29", "2016-07-18 10:18:23", "1", "caves-de-la-chartreuse"),("3", "1", "3", "3", "1", "20", "Crumble aux pommes et caramel beurre salé", "<ol><li>Préchauffez le four à th. 6 – 180 °C.</li><li>Pelez et coupez les pommes en tout petits quartiers. Arrosez-les de jus de citron pour qu’ils ne noircissent pas.</li><li>Coupez le beurre salé en parcelles dans une casserole. Ajoutez le sucre en poudre et faites chauffer doucement jusqu’à obtention d’un caramel ambré. Ajoutez les quartiers de pommes et faites cuire 10 minutes à feu doux en retournant sans arrêt pour qu’ils soient bien caramélisés.</li><li>Dans une jatte, mélangez le sucre roux, la farine et le beurre doux en morceaux. Travaillez du bout des doigts jusqu’à obtention d’une pâte sableuse.</li><li>Versez les pommes caramélisées dans un plat à four beurré. Recouvrez-les de pâte et enfournez. Faites cuire 35 minutes.</li><li>Servez dès la sortie du four avec de la crème glacée à la vanille.</li></ol>", "2016-07-22 09:33:08", "2016-07-22 09:35:19", "1", "crumble-aux-pommes-et-caramel-beurre-sale");

-- --------------------------------------------------------

--
-- Structure de la table `content_taxonomy`
--

DROP TABLE IF EXISTS content_taxonomy;
CREATE TABLE IF NOT EXISTS `content_taxonomy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `alias` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
--
-- Contenu de la table `content_taxonomy`
--

INSERT INTO `content_taxonomy` (id, title, alias) VALUES ("1", "Post", "post"),("2", "Lieux à visiter", "lieux-a-visiter"),("3", "Recette", "recette");

-- --------------------------------------------------------

--
-- Structure de la table `field`
--

DROP TABLE IF EXISTS field;
CREATE TABLE IF NOT EXISTS `field` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `field_object` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:object)',
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `published` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
--
-- Contenu de la table `field`
--

INSERT INTO `field` (id, type, field_object, title, name, published, created) VALUES ("3", "MapField", "O:47:\"CMS\\Bundle\\ContentBundle\\Entity\\Fields\\MapField\":2:{s:53:\"\0CMS\\Bundle\\ContentBundle\\Entity\\Fields\\MapField\0type\";s:3:\"map\";s:55:\"\0CMS\\Bundle\\ContentBundle\\Entity\\Fields\\MapField\0params\";a:0:{}}", "Carte", "carte", "1", "2016-07-13 09:22:31"),("4", "BooleanField", "O:51:\"CMS\\Bundle\\ContentBundle\\Entity\\Fields\\BooleanField\":3:{s:57:\"\0CMS\\Bundle\\ContentBundle\\Entity\\Fields\\BooleanField\0html\";N;s:57:\"\0CMS\\Bundle\\ContentBundle\\Entity\\Fields\\BooleanField\0type\";s:8:\"checkbox\";s:59:\"\0CMS\\Bundle\\ContentBundle\\Entity\\Fields\\BooleanField\0params\";a:3:{s:7:\"options\";N;s:8:\"required\";i:0;s:8:\"multiple\";i:0;}}", "Visité", "visited", "1", "2016-07-13 12:47:31");

-- --------------------------------------------------------

--
-- Structure de la table `fields_taxonomy`
--

DROP TABLE IF EXISTS fields_taxonomy;
CREATE TABLE IF NOT EXISTS `fields_taxonomy` (
  `field_id` int(11) NOT NULL,
  `content_taxonomy_id` int(11) NOT NULL,
  PRIMARY KEY (`field_id`,`content_taxonomy_id`),
  KEY `IDX_63B09889443707B0` (`field_id`),
  KEY `IDX_63B09889C67D4C70` (`content_taxonomy_id`),
  CONSTRAINT `FK_63B09889443707B0` FOREIGN KEY (`field_id`) REFERENCES `field` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_63B09889C67D4C70` FOREIGN KEY (`content_taxonomy_id`) REFERENCES `content_taxonomy` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
--
-- Contenu de la table `fields_taxonomy`
--

INSERT INTO `fields_taxonomy` (field_id, content_taxonomy_id) VALUES ("3", "2"),("4", "2");

-- --------------------------------------------------------

--
-- Structure de la table `fieldsvalues`
--

DROP TABLE IF EXISTS fieldsvalues;
CREATE TABLE IF NOT EXISTS `fieldsvalues` (
  `content_id` int(11) NOT NULL,
  `field_id` int(11) NOT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`content_id`,`field_id`),
  KEY `IDX_18FE930C84A0A3ED` (`content_id`),
  KEY `IDX_18FE930C443707B0` (`field_id`),
  CONSTRAINT `FK_18FE930C443707B0` FOREIGN KEY (`field_id`) REFERENCES `field` (`id`),
  CONSTRAINT `FK_18FE930C84A0A3ED` FOREIGN KEY (`content_id`) REFERENCES `content` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
--
-- Contenu de la table `fieldsvalues`
--

INSERT INTO `fieldsvalues` (content_id, field_id, value) VALUES ("1", "3", "a:3:{s:7:\"address\";s:16:\"Peaugres, France\";s:8:\"latitude\";s:9:\"45.268261\";s:9:\"longitude\";s:8:\"4.717963\";}"),("1", "4", "b:0;"),("2", "3", "a:3:{s:7:\"address\";s:32:\"10 Bd Edgar Kofler, 38500 Voiron\";s:8:\"latitude\";s:10:\"45.3617313\";s:9:\"longitude\";s:17:\"5.598536100000047\";}"),("2", "4", "b:0;");

-- --------------------------------------------------------

--
-- Structure de la table `language`
--

DROP TABLE IF EXISTS language;
CREATE TABLE IF NOT EXISTS `language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `code_local` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `code_lang` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `sens_ecriture` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ordre` int(11) NOT NULL,
  `default_lang` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
--
-- Contenu de la table `language`
--

INSERT INTO `language` (id, name, code_local, code_lang, sens_ecriture, ordre, default_lang) VALUES ("1", "Français", "fr_fr", "fr_fr", "ltr", "0", "1");

-- --------------------------------------------------------

--
-- Structure de la table `media`
--

DROP TABLE IF EXISTS media;
CREATE TABLE IF NOT EXISTS `media` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date_added` datetime NOT NULL,
  `metas` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:array)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
--
-- Contenu de la table `media`
--

INSERT INTO `media` (id, path, date_added, metas) VALUES ("1", "2016/07/photo-1431023824486-a9afaaa5838c.jpg", "2016-07-13 11:47:27", "a:2:{s:5:\"alt_1\";s:32:\"photo 1431023824486 a9afaaa5838c\";s:7:\"title_1\";s:32:\"photo 1431023824486 a9afaaa5838c\";}"),("2", "2016/07/Cave-et-Moine-4-TER.jpg", "2016-07-18 08:52:34", "a:2:{s:5:\"alt_1\";s:19:\"Cave et Moine 4 TER\";s:7:\"title_1\";s:19:\"Cave et Moine 4 TER\";}"),("3", "2016/07/145709175737878100-peaugres-lion.jpg", "2016-07-18 09:15:34", "a:2:{s:5:\"alt_1\";s:32:\"145709175737878100 peaugres lion\";s:7:\"title_1\";s:32:\"145709175737878100 peaugres lion\";}"),("5", "2016/07/photo-1420537659459-1e231ca42aa1.jpg", "2016-07-18 09:24:47", "a:2:{s:5:\"alt_1\";s:32:\"photo 1420537659459 1e231ca42aa1\";s:7:\"title_1\";s:32:\"photo 1420537659459 1e231ca42aa1\";}"),("6", "2016/07/photo-1466770438196-3b6577bb84d1.jpg", "2016-07-18 09:29:45", "a:2:{s:5:\"alt_1\";s:32:\"photo 1466770438196 3b6577bb84d1\";s:7:\"title_1\";s:32:\"photo 1466770438196 3b6577bb84d1\";}"),("7", "2016/07/photo-1466736059270-5951b72dcb05.jpg", "2016-07-18 09:32:28", "a:2:{s:5:\"alt_1\";s:32:\"photo 1466736059270 5951b72dcb05\";s:7:\"title_1\";s:32:\"photo 1466736059270 5951b72dcb05\";}"),("8", "2016/07/photo-1466970601638-4e5fb6556584.jpg", "2016-07-18 09:48:40", "a:2:{s:5:\"alt_1\";s:32:\"photo 1466970601638 4e5fb6556584\";s:7:\"title_1\";s:32:\"photo 1466970601638 4e5fb6556584\";}"),("9", "2016/07/photo-1466657718950-8f9346c04f8f.jpg", "2016-07-18 09:50:22", "a:2:{s:5:\"alt_1\";s:10:\"coccinelle\";s:7:\"title_1\";s:10:\"coccinelle\";}"),("10", "2016/07/photo-1466725574919-8f40de97af6d.jpg", "2016-07-18 09:51:01", "a:2:{s:5:\"alt_1\";s:32:\"photo 1466725574919 8f40de97af6d\";s:7:\"title_1\";s:32:\"photo 1466725574919 8f40de97af6d\";}"),("11", "2016/07/photo-1467057637141-24085832ed12.jpg", "2016-07-18 09:55:35", "a:2:{s:5:\"alt_1\";s:32:\"photo 1467057637141 24085832ed12\";s:7:\"title_1\";s:32:\"photo 1467057637141 24085832ed12\";}"),("18", "2016/07/2EsHHwmRswlLYnaG07Ew_paris-motionbug.com.jpg", "2016-07-18 10:17:59", "a:2:{s:5:\"alt_1\";s:40:\"2EsHHwmRswlLYnaG07Ew_paris motionbug.com\";s:7:\"title_1\";s:40:\"2EsHHwmRswlLYnaG07Ew_paris motionbug.com\";}"),("19", "2016/07/crumble-e1453130201918.jpg", "2016-07-22 09:31:45", "a:2:{s:5:\"alt_1\";s:7:\"crumble\";s:7:\"title_1\";s:7:\"crumble\";}"),("20", "2016/07/pomme-au-four-facon-crumble.jpg", "2016-07-22 09:35:06", "a:2:{s:5:\"alt_1\";s:27:\"pomme au four facon crumble\";s:7:\"title_1\";s:27:\"pomme au four facon crumble\";}"),("24", "2016/07/macarons.jpg", "2016-07-25 14:52:09", "a:2:{s:5:\"alt_1\";s:8:\"macarons\";s:7:\"title_1\";s:8:\"macarons\";}"),("31", "2016/07/photo-1456418047667-56bcd35b1a88.jpg", "2016-07-28 14:12:21", "a:2:{s:5:\"alt_1\";s:32:\"photo 1456418047667 56bcd35b1a88\";s:7:\"title_1\";s:32:\"photo 1456418047667 56bcd35b1a88\";}"),("32", "2016/07/photo-1461664255484-4d234c53b737.jpg", "2016-07-28 14:12:33", "a:2:{s:5:\"alt_1\";s:32:\"photo 1461664255484 4d234c53b737\";s:7:\"title_1\";s:32:\"photo 1461664255484 4d234c53b737\";}");

-- --------------------------------------------------------

--
-- Structure de la table `menu_entries`
--

DROP TABLE IF EXISTS menu_entries;
CREATE TABLE IF NOT EXISTS `menu_entries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `menu_taxonomy` int(11) DEFAULT NULL,
  `content` int(11) DEFAULT NULL,
  `category` int(11) DEFAULT NULL,
  `taxonomy` int(11) DEFAULT NULL,
  `title` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `lvl` int(11) NOT NULL,
  `lft` int(11) NOT NULL,
  `rgt` int(11) NOT NULL,
  `root` int(11) NOT NULL,
  `external_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_A523AE7B727ACA70` (`parent_id`),
  KEY `IDX_A523AE7BD143FDAB` (`menu_taxonomy`),
  KEY `IDX_A523AE7BFEC530A9` (`content`),
  KEY `IDX_A523AE7B64C19C1` (`category`),
  KEY `IDX_A523AE7BFD12B83D` (`taxonomy`),
  CONSTRAINT `FK_A523AE7B64C19C1` FOREIGN KEY (`category`) REFERENCES `category` (`id`),
  CONSTRAINT `FK_A523AE7B727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `menu_entries` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_A523AE7BD143FDAB` FOREIGN KEY (`menu_taxonomy`) REFERENCES `menu_taxonomy` (`id`),
  CONSTRAINT `FK_A523AE7BFD12B83D` FOREIGN KEY (`taxonomy`) REFERENCES `content_taxonomy` (`id`),
  CONSTRAINT `FK_A523AE7BFEC530A9` FOREIGN KEY (`content`) REFERENCES `content` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
--
-- Contenu de la table `menu_entries`
--

INSERT INTO `menu_entries` (id, parent_id, menu_taxonomy, content, category, taxonomy, title, status, lvl, lft, rgt, root, external_url) VALUES ("1", "", "3", "", "", "", "Root Menu principal", "0", "0", "1", "14", "1", ""),("2", "1", "3", "", "3", "", "Endroits à visiter", "1", "1", "2", "3", "1", ""),("3", "1", "3", "", "4", "", "Recettes", "1", "1", "4", "11", "1", ""),("4", "3", "3", "", "6", "", "Entrées", "1", "2", "7", "8", "1", ""),("5", "3", "3", "", "7", "", "Plats", "1", "2", "9", "10", "1", ""),("6", "3", "3", "", "5", "", "Desserts", "1", "2", "5", "6", "1", ""),("7", "1", "3", "", "8", "", "Voyages", "1", "1", "12", "13", "1", ""),("8", "", "4", "", "", "", "Root Menu centre", "0", "0", "1", "10", "8", ""),("9", "8", "4", "", "", "", "Accueil", "1", "1", "2", "3", "8", ""),("10", "8", "4", "", "3", "", "Endroits à visiter", "1", "1", "4", "5", "8", ""),("11", "8", "4", "", "4", "", "Recettes", "1", "1", "8", "9", "8", ""),("12", "8", "4", "", "8", "", "Voyages", "1", "1", "6", "7", "8", "");

-- --------------------------------------------------------

--
-- Structure de la table `menu_taxonomy`
--

DROP TABLE IF EXISTS menu_taxonomy;
CREATE TABLE IF NOT EXISTS `menu_taxonomy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `language` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `position` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_D143FDABD4DB71B5` (`language`),
  CONSTRAINT `FK_D143FDABD4DB71B5` FOREIGN KEY (`language`) REFERENCES `language` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
--
-- Contenu de la table `menu_taxonomy`
--

INSERT INTO `menu_taxonomy` (id, language, title, slug, status, position) VALUES ("3", "1", "Menu principal", "menu-principal", "1", "header"),("4", "1", "Menu centre", "menu-centre", "1", "center");

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

DROP TABLE IF EXISTS messages;
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `receivers` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `subject` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `message` longtext COLLATE utf8_unicode_ci NOT NULL,
  `sent_date` datetime NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
-- --------------------------------------------------------

--
-- Structure de la table `meta`
--

DROP TABLE IF EXISTS meta;
CREATE TABLE IF NOT EXISTS `meta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` longtext COLLATE utf8_unicode_ci NOT NULL,
  `value` longtext COLLATE utf8_unicode_ci NOT NULL,
  `published` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
-- --------------------------------------------------------

--
-- Structure de la table `metas_taxonomy`
--

DROP TABLE IF EXISTS metas_taxonomy;
CREATE TABLE IF NOT EXISTS `metas_taxonomy` (
  `meta_id` int(11) NOT NULL,
  `content_taxonomy_id` int(11) NOT NULL,
  PRIMARY KEY (`meta_id`,`content_taxonomy_id`),
  KEY `IDX_67F3CBE339FCA6F9` (`meta_id`),
  KEY `IDX_67F3CBE3C67D4C70` (`content_taxonomy_id`),
  CONSTRAINT `FK_67F3CBE339FCA6F9` FOREIGN KEY (`meta_id`) REFERENCES `meta` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_67F3CBE3C67D4C70` FOREIGN KEY (`content_taxonomy_id`) REFERENCES `content_taxonomy` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
-- --------------------------------------------------------

--
-- Structure de la table `metavalues_category`
--

DROP TABLE IF EXISTS metavalues_category;
CREATE TABLE IF NOT EXISTS `metavalues_category` (
  `category_id` int(11) NOT NULL,
  `meta_id` int(11) NOT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`category_id`,`meta_id`),
  KEY `IDX_EAA726DF12469DE2` (`category_id`),
  KEY `IDX_EAA726DF39FCA6F9` (`meta_id`),
  CONSTRAINT `FK_EAA726DF12469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  CONSTRAINT `FK_EAA726DF39FCA6F9` FOREIGN KEY (`meta_id`) REFERENCES `meta` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
-- --------------------------------------------------------

--
-- Structure de la table `metavalues_content`
--

DROP TABLE IF EXISTS metavalues_content;
CREATE TABLE IF NOT EXISTS `metavalues_content` (
  `content_id` int(11) NOT NULL,
  `meta_id` int(11) NOT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`content_id`,`meta_id`),
  KEY `IDX_7608086884A0A3ED` (`content_id`),
  KEY `IDX_7608086839FCA6F9` (`meta_id`),
  CONSTRAINT `FK_7608086839FCA6F9` FOREIGN KEY (`meta_id`) REFERENCES `meta` (`id`),
  CONSTRAINT `FK_7608086884A0A3ED` FOREIGN KEY (`content_id`) REFERENCES `content` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
-- --------------------------------------------------------

--
-- Structure de la table `options`
--

DROP TABLE IF EXISTS options;
CREATE TABLE IF NOT EXISTS `options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `option_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `option_value` longtext COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `general` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_D035FA87B62DD4E5` (`option_name`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
--
-- Contenu de la table `options`
--

INSERT INTO `options` (id, option_name, option_value, type, general) VALUES ("1", "slogan", "Un site fait avec le CMS de 3c-evolution", "text", "1"),("2", "email_admin", "leoncorono@gmail.com", "email", "1"),("3", "timezone", "Europe/Paris", "timezone", "1"),("4", "date_format", "d F Y", "choice", "1"),("5", "sitename", "TravelCloud", "text", "1"),("6", "square", "{\"width\":300,\"height\":300}", "image_settings", "0"),("7", "theme", "Borderland", "text", "0"),("8", "medium", "{\"width\":650,\"height\":450}", "image_settings", "0"),("9", "banner", "7", "image", "1"),("10", "positions", "a:3:{i:0;s:6:\"header\";i:1;s:6:\"center\";i:2;s:6:\"footer\";}", "array", "0");

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

DROP TABLE IF EXISTS roles;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `role_nicename` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
--
-- Contenu de la table `roles`
--

INSERT INTO `roles` (id, name, role_nicename) VALUES ("1", "ROLE_SUPER_ADMIN", "Super Administrator"),("2", "ROLE_ADMIN", "Administrator");

-- --------------------------------------------------------

--
-- Structure de la table `roles_users`
--

DROP TABLE IF EXISTS roles_users;
CREATE TABLE IF NOT EXISTS `roles_users` (
  `role_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`role_id`,`user_id`),
  KEY `IDX_3D80FB2CD60322AC` (`role_id`),
  KEY `IDX_3D80FB2CA76ED395` (`user_id`),
  CONSTRAINT `FK_3D80FB2CA76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_3D80FB2CD60322AC` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
--
-- Contenu de la table `roles_users`
--

INSERT INTO `roles_users` (role_id, user_id) VALUES ("1", "1");

-- --------------------------------------------------------

--
-- Structure de la table `usermeta`
--

DROP TABLE IF EXISTS usermeta;
CREATE TABLE IF NOT EXISTS `usermeta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) DEFAULT NULL,
  `meta_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `meta_value` longtext COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_718F6C448D93D649` (`user`),
  CONSTRAINT `FK_718F6C448D93D649` FOREIGN KEY (`user`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
--
-- Contenu de la table `usermeta`
--

INSERT INTO `usermeta` (id, user, meta_key, meta_value, type) VALUES ("1", "1", "firstname", "Damien", "text"),("2", "1", "lastname", "Corona", "text"),("3", "1", "id_twitter", "@leon38", "text"),("4", "1", "facebook_url", "https://www.facebook.com/damien.corona", "text"),("5", "1", "gplus_url", "", "text"),("6", "1", "about_me", "Développeur à 3c-evolution", "textarea");

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS users;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_login` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `user_pass` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_nicename` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `user_url` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_registered` datetime NOT NULL,
  `user_activation_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_status` tinyint(1) NOT NULL,
  `display_name` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
--
-- Contenu de la table `users`
--

INSERT INTO `users` (id, user_login, user_pass, salt, user_nicename, user_email, user_url, user_registered, user_activation_key, user_status, display_name, avatar) VALUES ("1", "admin", "ZRqMAHRMvghkIYGQHyS4LaqvlX4=", "09e92960c9f5ee5a41bfe460ff23f4f9", "Doudou", "leoncorono@gmail.com", "", "2016-07-08 09:44:26", "ce5fa526615b82e0e99bd2df6b94fb0008dc0778", "1", "complete_name", "_7n82t1A.jpeg");

-- --------------------------------------------------------

--
-- Structure de la table `widgetentity`
--

DROP TABLE IF EXISTS widgetentity;
CREATE TABLE IF NOT EXISTS `widgetentity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci NOT NULL,
  `args` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `position` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `updated` datetime NOT NULL,
  `added` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
