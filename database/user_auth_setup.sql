CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(200) NOT NULL,
  `useremail` varchar(200) NOT NULL,
  `userpassword` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `user` (`username`, `useremail`, `userpassword`)
SELECT 'admin', 'admin@email.com', '$2y$12$cPfK2OXFaPOxX2Lxh3SzC.gOqjvqNI8KX6SKzqCKY15lsAcO/amcm'
WHERE NOT EXISTS (
  SELECT 1 FROM `user` WHERE `useremail` = 'admin@email.com'
);
