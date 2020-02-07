CREATE TABLE `wpda_bas_bike` (
  `bike_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `bike_brand` varchar(50) NOT NULL,
  `bike_brand_type` varchar(50) NOT NULL,
  `bike_color` varchar(20) DEFAULT NULL,
  `bike_fuel_type` enum('gasoline','diesel','electric') DEFAULT NULL,
  `bike_category` enum('other','naked','racing','cross','shopper') DEFAULT NULL,
  `bike_licence_plate` varchar(10) DEFAULT NULL,
  `bike_mileage` mediumint(8) unsigned DEFAULT NULL,
  `bike_engine_capacity` smallint(4) unsigned DEFAULT NULL,
  `bike_no_cylinders` smallint(1) unsigned DEFAULT NULL,
  `bike_registration_date` date DEFAULT NULL,
  `bike_year_of_construction` year(4) DEFAULT NULL,
  `bike_photo` bigint(20) unsigned DEFAULT NULL,
  `bike_attachments` varchar(200) DEFAULT NULL,
  `bike_price` double unsigned DEFAULT NULL,
  `bike_info` text,
  PRIMARY KEY (`bike_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

INSERT INTO `wpda_bas_bike` (`bike_id`, `bike_brand`, `bike_brand_type`, `bike_color`, `bike_fuel_type`, `bike_category`, `bike_licence_plate`, `bike_mileage`, `bike_engine_capacity`, `bike_no_cylinders`, `bike_registration_date`, `bike_year_of_construction`, `bike_photo`, `bike_attachments`, `bike_price`, `bike_info`) VALUES (1,'Kawasaki','Z 750 ABS','GREEN BLACK','gasoline','naked','AA-11-BB',21500,748,4,'2009-01-27',2009,null,'',5250,null);
INSERT INTO `wpda_bas_bike` (`bike_id`, `bike_brand`, `bike_brand_type`, `bike_color`, `bike_fuel_type`, `bike_category`, `bike_licence_plate`, `bike_mileage`, `bike_engine_capacity`, `bike_no_cylinders`, `bike_registration_date`, `bike_year_of_construction`, `bike_photo`, `bike_attachments`, `bike_price`, `bike_info`) VALUES (2,'Kawasaki','Z 750 R ABS','GREEN BLACK','gasoline','naked','XX-77-YY',26000,748,4,'2011-03-16',2011,null,null,6500,null);
INSERT INTO `wpda_bas_bike` (`bike_id`, `bike_brand`, `bike_brand_type`, `bike_color`, `bike_fuel_type`, `bike_category`, `bike_licence_plate`, `bike_mileage`, `bike_engine_capacity`, `bike_no_cylinders`, `bike_registration_date`, `bike_year_of_construction`, `bike_photo`, `bike_attachments`, `bike_price`, `bike_info`) VALUES (3,'Kawasaki','Z 800 E ABS','BLACK','gasoline','naked','RR-99-NN',40000,806,4,'2014-04-01',2014,null,null,6500,null);
