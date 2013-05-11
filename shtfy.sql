CREATE TABLE `shtfy` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` text NOT NULL,
  `hits` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) CHARSET=utf8 COLLATE=utf8_unicode_ci;
