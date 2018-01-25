
--
-- Table structure for table `grupo_material`
--

CREATE TABLE IF NOT EXISTS `grupo_material` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nomeGrupo` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

--
-- Dumping data for table `grupo_material`
--

INSERT INTO `grupo_material` (`id`, `nomeGrupo`) VALUES
(1, 'Ip gamaglobina'),
(3, 'Imunodarvum'),
(4, 'Candina'),
(5, 'Lisados'),
(6, 'Acaros'),
(7, 'Histamina'),
(8, 'Analantes oral'),
(9, 'Estrófulo oral'),
(10, 'Estrófulo inj'),
(11, 'Toxovacin'),
(12, 'Acne'),
(13, 'Disidroval'),
(14, 'Herpes'),
(15, 'Interferonteradia'),
(16, 'Ip Hepatite'),
(17, 'Consulta medica');

