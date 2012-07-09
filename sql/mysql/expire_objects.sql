CREATE TABLE IF NOT EXISTS `expire_objects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `node_id` int(11) NOT NULL,
  `expire_on` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;