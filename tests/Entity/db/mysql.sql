
CREATE TABLE `seo_backend` (
  `id` mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL DEFAULT '',
  `description` varchar(200) NOT NULL DEFAULT '',
  `symbol` varchar(50) NOT NULL DEFAULT '',
  `enable` tinyint(1) NOT NULL DEFAULT 1,
  `home` varchar(100) NOT NULL DEFAULT '',
  `meta` varchar(10240) NOT NULL DEFAULT '',
  `engine` varchar(50) NOT NULL DEFAULT '',
  `priority` int(11) NOT NULL DEFAULT 10,
  `creation_dtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modif_dtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `tenant` mediumint(9) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `tenant_foreignkey_idx` (`tenant`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

