DROP TABLE IF EXISTS `#__sphinxdoc`;

CREATE TABLE `#__sphinxdoc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `documentation` varchar(100) NOT NULL,
  `directory` varchar(255) NOT NULL,
   PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

INSERT INTO `#__sphinxdoc` (`documentation`,`directory`) VALUES
        ('cron','cron'),
        ('telldus','telldus');
