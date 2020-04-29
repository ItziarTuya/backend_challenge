-- USERS

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `email` varchar(256) NOT NULL,
  `phone` varchar(256) NOT NULL,
  `address` varchar(256) NOT NULL,
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;


INSERT INTO `users` (`id`, `name`, `email`, `phone`, `address`, `created`, `modified`) VALUES
(1, 'Tatiana Gordo', 't.gordo@gmail.com', '+34666666661', 'Carrer Andreu Feliu 7. Palma. Spain', '2020-04-21 00:35:07', '2020-04-21 17:34:54'),
(2, 'Aida Barreiros', 'a.barreiros@gmail.com', '+34666666662', 'Carrer Andreu Feliu 7. Palma. Spain', '2020-04-21 00:35:07', '2020-04-21 17:34:54'),
(3, 'Itziar Tuya', 'itziartuya@gmail.com', '+34660685863', 'Carrer Andreu Feliu 7. Palma. Spain', '2020-04-21 00:35:07', '2020-04-21 17:34:54'),
(5, 'Alvaro Dutoit', 'a.dutoit@gmail.com', '+34666666663', 'Carrer Lluis Martí 21. Palma. Spain', '2020-04-21 00:35:07', '2020-04-21 17:34:54'),
(6, 'Javier Tuya', 'j.tuya@gmail.com', '+34666666664', 'Calle Palos de la Frontera 5. Madrid. Spain', '2020-04-21 00:35:07', '2020-04-21 17:34:54'),
(7, 'Rosa Rivas', 'r.rivas@gmail.com', '+34666666665', 'Calle Marques del Nervion 5. Sevilla. Spain', '2020-04-21 00:35:07', '2020-04-21 17:34:54'),
(12, 'Jose Tuya', 'jtc@gmail.com', '+34666666666', 'Calle Marques del Nervion 5. Sevilla. Spain', '2020-04-21 00:35:07', '2020-04-21 17:34:54');


-- CATEGORIES 

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `description` text NOT NULL,
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

INSERT INTO `categories` (`id`, `name`, `description`, `created`, `modified`) VALUES
(1, 'Electricity', 'Lighting and electrical installations.', '2020-04-21 00:35:07', '2020-04-21 17:34:54'),
(2, 'Electronics', 'Gadgets, drones and more.', '2020-04-21 00:35:07', '2020-04-21 17:34:54'),
(3, 'Interior design', 'Design and spaces creation.', '2020-04-21 00:35:07', '2020-04-21 17:34:54'),
(5, 'Painting', 'Facade and interior painting.', '2020-04-21 00:35:07', '2020-04-21 17:34:54'),
(6, 'Facilities', 'Installation of machines, equipment and more.', '2020-04-21 00:35:07', '2020-04-21 17:34:54'),
(13, 'Renewable energy', 'Renewable energy calculation and installation.', '2020-04-21 00:35:07', '2020-04-21 17:34:54');


-- STATUS

CREATE TABLE IF NOT EXISTS `status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

INSERT INTO `status` (`id`, `name`) VALUES
(1, 'pending'),
(2, 'published'),
(3, 'discarded');


-- BUDGETS

CREATE TABLE IF NOT EXISTS `budgets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(32) NOT NULL,
  `description` text NOT NULL,
  `category_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES users(`id`),
  FOREIGN KEY (`category_id`) REFERENCES categories(`id`),
  FOREIGN KEY (`status_id`) REFERENCES status(`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=65 ;


INSERT INTO `budgets` (`id`, `title`, `description`, `category_id`, `user_id`, `status_id`, `created`, `modified`) VALUES
(1, 'Painting', 'Paint the wall.', 5, 3, 1, '2020-04-21 00:35:07', '2020-04-21 17:34:54'),
(2, 'Paint wall', 'Paint back wall.', 5, 3, 1, '2020-04-21 00:35:07', '2020-04-21 17:34:54'),
(3, 'Photovoltaic', 'Renewable energy.', 13, 5, 1, '2020-04-21 00:35:07', '2020-04-21 17:34:54'),
(6, 'Renewable energy', 'Photovoltaic plates',  13, 5, 1, '2020-04-21 00:35:07', '2020-04-21 17:34:54'),
(7, 'Renewable', 'Photovoltaic plates',  13, 5, 1, '2020-04-21 00:35:07', '2020-04-21 17:34:54'),
(8, 'Air conditioning', 'Install air conditioning',  6, 2, 2, '2020-04-21 00:35:07', '2020-04-21 17:34:54'),
(9, 'Installation', 'Install air conditioning',  6, 2, 3, '2020-04-21 00:35:07', '2020-04-21 17:34:54'),
(10, 'AA install', 'Install air conditioning and recycle the old one', 6, 2, 3, '2020-04-21 00:35:07', '2020-04-21 17:34:54'),
(11, 'Ilumination', 'Ilumination project',  1, 6, 3, '2020-04-21 00:35:07', '2020-04-21 17:34:54'),
(12, 'Electricity', 'Ilumination project',  1, 6, 3, '2020-04-21 00:35:07', '2020-04-21 17:34:54'),
(13, 'Ilumination and electricity', 'Ilumination project and installation',  1, 6, 2, '2020-04-21 00:35:07', '2020-04-21 17:34:54'),
(26, 'Another product', 'Awesome product!', 3, 7, 3, '2020-04-21 00:35:07', '2020-04-21 17:34:54'),
(28, 'Design', 'Awesome interior design!', 3, 7, 2, '2020-04-21 00:35:07', '2020-04-21 17:34:54'),
(31, 'Design project', 'Awesome interior design!', 3, 7, 3, '2020-04-21 00:35:07', '2020-04-21 17:34:54'),
(42, 'Home automation project', 'Home automation blinds', 2, 12, 3, '2020-04-21 00:35:07', '2020-04-21 17:34:54'),
(48, 'Blinds project', 'Home automation blinds', 2, 12, 3, '2020-04-21 00:35:07', '2020-04-21 17:34:54'),
(60, 'Premium automation', 'Home automation', 2, 12, 2, '2020-04-21 00:35:07', '2020-04-21 17:34:54');