CREATE TABLE IF NOT EXISTS `userrights` (
  `usertype` varchar(255) NOT NULL,
  `page` char(150) NOT NULL COMMENT 'Component/Controller',
  `pageaccess` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`page`),
  KEY `pageaccess` (`pageaccess`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

REPLACE INTO userrights(usertype, page, pageaccess, status) VALUES
('["*"]','Auth', '632c9594449737188c71ee1c8534f893', 1),
('["-1"]','Config', 'fa535ffb25e1fd20341652f9be21e06e', 1),
('["*"]','Home', '8cf04a9734132302f96da8e113e80ce5', 1),
('["*"]','Menu/Navbar','fa380f90c22023cd7349a381a66bc4a9',1),
('["-1"]','Menu/Rights','c92f5424928166a7c240430f5c3083bb',1),
('["*"]','Status','ec53a8c4f07baed5d8825072c89799be',1),
('["*"]','Status/Maintenance','4b07e951dbf2d9af2193106b1fd20efe',1),
('["-1"]','User','8f9bfe9d1345237cb3b2b205864da075',1),
('["*"]','User/Profile','672ca5b0501a8b8145a30d6bd3279ece',1),
('["-1"]','User/Rights','f71267b09eb7d778d91d9fed04c23061',1),
('["-1"]','User/Usertype','655a6a43cd22fa998724ff832014a380',1);