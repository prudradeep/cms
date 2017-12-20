CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` char(10) NOT NULL,
  `field` char(100) NOT NULL,
  `value` text NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  UNIQUE KEY `uniqueness` (`type`, `field`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

--At the time of installation
REPLACE INTO `settings` (`type`,`field`,`value`,`status`) VALUES
 ('global','SESSION_LIFE','1800',1),
 ('global','MAX_EXE_TIME','300',1),
-- DEVELOPMENT, PRODUCTION
 ('global','MODE','DEVELOPMENT',1),
 ('global','THEME','materialize',1),
 ('site','language','en',1),
 ('js_plugs','Jquery','jquery-3.2.1.min',1),
 ('font_plugs','Material Icon','material-icons/material-icons',1),
 ('global','SESSION_KEY','phindart_user',1),
 ('global','SESSION_USER','phindart_usertype',1),
 ('global','SESSION_MSG','sess_msg',1),
 ('global','SEARCHBY','search_by',1),
 ('global','SEARCHTXT','search_txt',1),
 ('global','SEARCHOPER','search_oper',1);
