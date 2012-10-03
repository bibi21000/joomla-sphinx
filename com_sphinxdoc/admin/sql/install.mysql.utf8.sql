DROP TABLE IF EXISTS `#__sphinxdoc`;

CREATE TABLE `#__sphinxdoc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `documentation` varchar(100) NOT NULL,
  `directory` varchar(255) NOT NULL,
  `index` varchar(255) NOT NULL DEFAULT '_index.html',
  `catid` int(11) NOT NULL DEFAULT '0',
  `alias` varchar(255) NOT NULL DEFAULT '',
  `updateh` int(2) NOT NULL DEFAULT '0',
  `params` TEXT NOT NULL DEFAULT '',
  `description` TEXT NOT NULL DEFAULT '',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `created_by` int(10) unsigned NOT NULL default '0',
  `created_by_alias` varchar(255) NOT NULL default '',
  `modified` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified_by` int(10) unsigned NOT NULL default '0',
  `language` char(7) NOT NULL DEFAULT '',
  `ordering` int(11) NOT NULL DEFAULT '0',
   PRIMARY KEY  (`id`),
   KEY `idx_catid` (`catid`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

INSERT INTO `#__sphinxdoc` (`documentation`,`directory`) VALUES
        ('cron','cron'),
        ('telldus','telldus');
