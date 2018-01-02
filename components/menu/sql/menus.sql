CREATE TABLE IF NOT EXISTS `menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` char(10) NOT NULL,
  `parent` int(11) NOT NULL DEFAULT '0',
  `_order` int(11) NOT NULL DEFAULT '1',
  `name` char(50) NOT NULL,
  `display_name` varchar(100) NOT NULL,
  `link` char(150) NOT NULL,
  `user_access` varchar(150) NOT NULL COMMENT 'User types',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniqueness` (`parent`,`name`,`type`, `link`),
  KEY `parent` (`parent`),
  KEY `type` (`type`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

REPLACE INTO `menus` (`id`,`type`,`parent`,`_order`,`name`,`display_name`,`link`,`user_access`) VALUES 
(1,'top',0,2,'Master','Master','#','["-1"]'),
(2,'top',0,3,'Rights','Rights','#','["-1"]'),
(3,'top',0,4,'Account','Account','#','["*"]'),
(4,'top',2,1,'Comps','<i class="material-icons">view_module</i> Component','user/rights','["-1"]'),
(5,'top',2,2,'Menu','<i class="material-icons">menu</i> Menu','menu/rights','["-1"]'),
(6,'top',3,1,'Profile','<i class="material-icons">perm_identity</i> Profile','user/profile','["*"]'),
(7,'top',3,2,'Exit','<i class="material-icons">exit_to_app</i> Exit','logout','["*"]'),
(10,'top',1,1,'Config','<i class="material-icons">settings</i> Config','config','["id","display_name","-1"]'),
(9,'top',1,2,'Usertypes','<i class="material-icons">face</i> Usertypes','user/usertype','["-1"]'),
(8,'top',1,3,'Users','<i class="material-icons">supervisor_account</i> Users','user','["-1"]');
