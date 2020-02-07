/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE TABLE `wpda_bas_bike` (
    `bike_id`                   bigint(20) unsigned	NOT NULL    AUTO_INCREMENT,
    `bike_brand`		        varchar(50)		NOT NULL,
    `bike_brand_type`		    varchar(50)		NOT NULL,
    `bike_color`		        varchar(20),
    `bike_fuel_type`		    enum('gasoline', 'diesel', 'electric'),
    `bike_category`		        enum('other', 'naked', 'racing', 'cross', 'shopper'),
    `bike_licence_plate`	    varchar(10),
    `bike_mileage`		        mediumint unsigned,
    `bike_engine_capacity`	    smallint(4) unsigned,
    `bike_no_cylinders`		    smallint(1) unsigned,
    `bike_registration_date`	date,
    `bike_year_of_construction`	year,
    `bike_photo`             	bigint(20) unsigned,
    `bike_attachments`          varchar(200),
    `bike_price`		        double unsigned,
    `bike_info`			        text,
    PRIMARY KEY (`bike_id`)
);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
