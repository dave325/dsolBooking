/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- CREATE TABLES
--
CREATE TABLE `wpda_sas_student` (
    `student_id`                int(11)         NOT NULL    AUTO_INCREMENT,
    `student_gender`            enum('female', 'male'),
    `student_date_of_birth`     date,
    `student_firstname`         varchar(100)    NOT NULL,
    `student_lastname`          varchar(100)    NOT NULL,
    `student_address`           varchar(100)    NOT NULL,
    `student_zipcode`           varchar(10)     NOT NULL,
    `student_city`              varchar(100)    NOT NULL,
    `student_county`            varchar(100),
    `student_country`           varchar(100),
    `student_email`             varchar(254)    NOT NULL,
    `student_phone_no`          varchar(10),
    `student_registration_date` date,
    `student_wp_username`       varchar(60),
    PRIMARY KEY (`student_id`),
    UNIQUE KEY (`student_email`),
    UNIQUE KEY (`student_wp_username`)
);

CREATE TABLE `wpda_sas_teacher` (
    `teacher_id`                int(11)         NOT NULL    AUTO_INCREMENT,
    `teacher_gender`            enum('female', 'male'),
    `teacher_date_of_birth`     date,
    `teacher_firstname`         varchar(100)    NOT NULL,
    `teacher_lastname`          varchar(100)    NOT NULL,
    `teacher_address`           varchar(100)    NOT NULL,
    `teacher_zipcode`           varchar(10)     NOT NULL,
    `teacher_city`              varchar(100)    NOT NULL,
    `teacher_county`            varchar(100),
    `teacher_country`           varchar(100),
    `teacher_email`             varchar(254)    NOT NULL,
    `teacher_phone_no`          varchar(10)     NOT NULL,
    `teacher_hire_date`         date,
    `teacher_wp_username`       varchar(60),
    PRIMARY KEY (`teacher_id`),
    UNIQUE KEY (`teacher_email`),
    UNIQUE KEY (`teacher_wp_username`)
);

CREATE TABLE `wpda_sas_module` (
  `module_id`           int(11)         NOT NULL    AUTO_INCREMENT,
  `module_name`         varchar(50)     NOT NULL,
  `module_description`  varchar(4000)   NOT NULL,
  PRIMARY KEY (`module_id`),
  UNIQUE KEY (`module_name`)
);

CREATE TABLE `wpda_sas_course` (
  `course_id`           int(11)         NOT NULL    AUTO_INCREMENT,
  `course_start_date`   date            NOT NULL,
  `module_id`           int(11)         NOT NULL,
  `teacher_id`          int(11)         NOT NULL,
  PRIMARY KEY (`course_id`)
);

CREATE TABLE `wpda_sas_class` (
  `course_id`   int(11)     NOT NULL,
  `student_id`  int(11)     NOT NULL,
  PRIMARY KEY (`course_id`, `student_id`)
);

CREATE TABLE `wpda_sas_grade` (
  `course_id`   int(11)     NOT NULL,
  `student_id`  int(11)     NOT NULL,
  `grade_date`  date        NOT NULL,
  `grade_name`  varchar(30)  NOT NULL,
  `grade`       varchar(6)  NOT NULL,
  PRIMARY KEY (`course_id`, `student_id`, `grade_date`, `grade_name`)
);

--
-- Import table `wpda_sas_student`
--
-- Source: https://www.briandunning.com/sample-data/
--
INSERT INTO `wpda_sas_student` (`student_id`, `student_gender`, `student_date_of_birth`, `student_firstname`, `student_lastname`, `student_address`, `student_zipcode`, `student_city`, `student_county`, `student_country`, `student_email`, `student_phone_no`, `student_registration_date`, `student_wp_username`) VALUES 
(1,'male',null,'James','Butt','6649 N Blue Gum St','70116','New Orleans','Orleans','LA','jbutt@gmail.com','5048451427','2019-01-01',null),
(2,'female',null,'Josephine','Darakjy','4 B Blue Ridge Blvd','48116','Brighton','Livingston','MI','josephine_darakjy@darakjy.org','8102929388','2019-01-01','student'),
(3,'male',null,'Art','Venere','8 W Cerritos Ave #54','08014','Bridgeport','Gloucester','NJ','art@venere.org','8566368749','2019-01-01',null),
(4,'female',null,'Lenna','Paprocki','639 Main St','99501','Anchorage','Anchorage','AK','lpaprocki@hotmail.com','9073854412','2019-01-01',null),
(5,'female',null,'Donette','Foller','34 Center St','45011','Hamilton','Butler','OH','donette.foller@cox.net','5135701893','2019-01-01',null),
(6,'female',null,'Simona','Morasca','3 Mcauley Dr','44805','Ashland','Ashland','OH','simona@morasca.com','4195032484','2019-01-01',null),
(7,'male',null,'Mitsue','Tollner','7 Eads St','60632','Chicago','Cook','IL','mitsue_tollner@yahoo.com','7735736914','2019-01-01',null),
(8,'female',null,'Leota','Dilliard','7 W Jackson Blvd','95111','San Jose','Santa Clara','CA','leota@hotmail.com','4087523500','2019-01-01',null),
(9,'male',null,'Sage','Wieser','5 Boston Ave #88','57105','Sioux Falls','Minnehaha','SD','sage_wieser@cox.net','6054142147','2019-01-01',null),
(10,'male',null,'Kris','Marrier','228 Runamuck Pl #2808','21224','Baltimore','Baltimore City','MD','kris@gmail.com','4106558723','2019-01-01',null),
(11,'female',null,'Minna','Amigon','2371 Jerrold Ave','19443','Kulpsville','Montgomery','PA','minna_amigon@yahoo.com','2158741229','2019-01-01',null),
(12,'male',null,'Abel','Maclead','37275 St  Rt 17m M','11953','Middle Island','Suffolk','NY','amaclead@gmail.com','6313353414','2019-01-01',null),
(13,'female',null,'Kiley','Caldarera','25 E 75th St #69','90034','Los Angeles','Los Angeles','CA','kiley.caldarera@aol.com','3104985651','2019-01-01',null),
(14,'female',null,'Graciela','Ruta','98 Connecticut Ave Nw','44023','Chagrin Falls','Geauga','OH','gruta@cox.net','4407808425','2019-01-01',null),
(15,'female',null,'Cammy','Albares','56 E Morehead St','78045','Laredo','Webb','TX','calbares@gmail.com','9565376195','2019-01-01',null),
(16,'male',null,'Mattie','Poquette','73 State Road 434 E','85013','Phoenix','Maricopa','AZ','mattie@aol.com','6022774385','2019-01-01',null),
(17,'female',null,'Meaghan','Garufi','69734 E Carrillo St','37110','Mc Minnville','Warren','TN','meaghan@hotmail.com','9313139635','2019-01-01',null),
(18,'female',null,'Gladys','Rim','322 New Horizon Blvd','53207','Milwaukee','Milwaukee','WI','gladys.rim@rim.org','4146619598','2019-01-01',null),
(19,'female',null,'Yuki','Whobrey','1 State Route 27','48180','Taylor','Wayne','MI','yuki_whobrey@aol.com','3132887937','2019-01-01',null),
(20,'male',null,'Fletcher','Flosi','394 Manchester Blvd','61109','Rockford','Winnebago','IL','fletcher.flosi@yahoo.com','8158282147','2019-01-01',null),
(21,'female',null,'Bette','Nicka','6 S 33rd St','19014','Aston','Delaware','PA','bette_nicka@cox.net','6105453615','2019-01-01',null),
(22,'female',null,'Veronika','Inouye','6 Greenleaf Ave','95111','San Jose','Santa Clara','CA','vinouye@aol.com','4085401785','2019-01-01',null),
(23,'male',null,'Willard','Kolmetz','618 W Yakima Ave','75062','Irving','Dallas','TX','willard@hotmail.com','9723039197','2019-01-01',null),
(24,'female',null,'Maryann','Royster','74 S Westgate St','12204','Albany','Albany','NY','mroyster@royster.com','5189667987','2019-01-01',null),
(25,'female',null,'Alisha','Slusarski','3273 State St','08846','Middlesex','Middlesex','NJ','alisha@slusarski.com','7326583154','2019-01-01',null),
(26,'female',null,'Allene','Iturbide','1 Central Ave','54481','Stevens Point','Portage','WI','allene_iturbide@cox.net','7156626764','2019-01-01',null),
(27,'female',null,'Chanel','Caudy','86 Nw 66th St #8673','66218','Shawnee','Johnson','KS','chanel.caudy@caudy.org','9133882079','2019-01-01',null),
(28,'male',null,'Ezekiel','Chui','2 Cedar Ave #84','21601','Easton','Talbot','MD','ezekiel@chui.com','4106691642','2019-01-01',null),
(29,'male',null,'Willow','Kusko','90991 Thorburn Ave','10011','New York','New York','NY','wkusko@yahoo.com','2125824976','2019-01-01',null),
(30,'male',null,'Bernardo','Figeroa','386 9th Ave N','77301','Conroe','Montgomery','TX','bfigeroa@aol.com','9363363951','2019-01-01',null),
(31,'female',null,'Ammie','Corrio','74874 Atlantic Ave','43215','Columbus','Franklin','OH','ammie@corrio.com','6148019788','2019-01-01',null),
(32,'female',null,'Francine','Vocelka','366 South Dr','88011','Las Cruces','Dona Ana','NM','francine_vocelka@vocelka.com','5059773911','2019-01-01',null),
(33,'male',null,'Ernie','Stenseth','45 E Liberty St','07660','Ridgefield Park','Bergen','NJ','ernie_stenseth@aol.com','2017096245','2019-01-01',null),
(34,'female',null,'Albina','Glick','4 Ralph Ct','08812','Dunellen','Middlesex','NJ','albina@glick.com','7329247882','2019-01-01',null),
(35,'female',null,'Alishia','Sergi','2742 Distribution Way','10025','New York','New York','NY','asergi@gmail.com','2128601579','2019-01-01',null),
(36,'male',null,'Solange','Shinko','426 Wolf St','70002','Metairie','Jefferson','LA','solange@shinko.com','5049799175','2019-01-01',null),
(37,'male',null,'Jose','Stockham','128 Bransten Rd','10011','New York','New York','NY','jose@yahoo.com','2126758570','2019-01-01',null),
(38,'female',null,'Rozella','Ostrosky','17 Morena Blvd','93012','Camarillo','Ventura','CA','rozella.ostrosky@ostrosky.com','8058326163','2019-01-01',null),
(39,'female',null,'Valentine','Gillian','775 W 17th St','78204','San Antonio','Bexar','TX','valentine_gillian@gmail.com','2108129597','2019-01-01',null),
(40,'female',null,'Kati','Rulapaugh','6980 Dorsett Rd','67410','Abilene','Dickinson','KS','kati.rulapaugh@hotmail.com','7854637829','2019-01-01',null),
(41,'female',null,'Youlanda','Schemmer','2881 Lewis Rd','97754','Prineville','Crook','OR','youlanda@aol.com','5415488197','2019-01-01',null),
(42,'male',null,'Dyan','Oldroyd','7219 Woodfield Rd','66204','Overland Park','Johnson','KS','doldroyd@aol.com','9134134604','2019-01-01',null),
(43,'female',null,'Roxane','Campain','1048 Main St','99708','Fairbanks','Fairbanks North Star','AK','roxane@hotmail.com','9072314722','2019-01-01',null),
(44,'male',null,'Lavera','Perin','678 3rd Ave','33196','Miami','Miami-Dade','FL','lperin@perin.org','3056067291','2019-01-01',null),
(45,'male',null,'Erick','Ferencz','20 S Babcock St','99712','Fairbanks','Fairbanks North Star','AK','erick.ferencz@aol.com','9077411044','2019-01-01',null),
(46,'female',null,'Fatima','Saylors','2 Lighthouse Ave','55343','Hopkins','Hennepin','MN','fsaylors@saylors.org','9527682416','2019-01-01',null),
(47,'female',null,'Jina','Briddick','38938 Park Blvd','02128','Boston','Suffolk','MA','jina_briddick@briddick.com','6173995124','2019-01-01',null),
(48,'female',null,'Kanisha','Waycott','5 Tomahawk Dr','90006','Los Angeles','Los Angeles','CA','kanisha_waycott@yahoo.com','3234532780','2019-01-01',null),
(49,'male',null,'Emerson','Bowley','762 S Main St','53711','Madison','Dane','WI','emerson.bowley@bowley.org','6083367444','2019-01-01',null),
(50,'female',null,'Blair','Malet','209 Decker Dr','19132','Philadelphia','Philadelphia','PA','bmalet@yahoo.com','2159079111','2019-01-01',null),
(51,'male',null,'Brock','Bolognia','4486 W O St #1','10003','New York','New York','NY','bbolognia@yahoo.com','2124029216','2019-01-01',null),
(52,'female',null,'Lorrie','Nestle','39 S 7th St','37388','Tullahoma','Coffee','TN','lnestle@hotmail.com','9318756644','2019-01-01',null),
(53,'male',null,'Sabra','Uyetake','98839 Hawthorne Blvd #6101','29201','Columbia','Richland','SC','sabra@uyetake.org','8039255213','2019-01-01',null),
(54,'female',null,'Marjory','Mastella','71 San Mateo Ave','19087','Wayne','Delaware','PA','mmastella@mastella.com','6108145533','2019-01-01',null),
(55,'male',null,'Karl','Klonowski','76 Brooks St #9','08822','Flemington','Hunterdon','NJ','karl_klonowski@yahoo.com','9088776135','2019-01-01',null),
(56,'female',null,'Tonette','Wenner','4545 Courthouse Rd','11590','Westbury','Nassau','NY','twenner@aol.com','5169686051','2019-01-01',null),
(57,'female',null,'Amber','Monarrez','14288 Foster Ave #4121','19046','Jenkintown','Montgomery','PA','amber_monarrez@monarrez.org','2159348655','2019-01-01',null),
(58,'female',null,'Shenika','Seewald','4 Otis St','91405','Van Nuys','Los Angeles','CA','shenika@gmail.com','8184234007','2019-01-01',null),
(59,'male',null,'Delmy','Ahle','65895 S 16th St','02909','Providence','Providence','RI','delmy.ahle@hotmail.com','4014582547','2019-01-01',null),
(60,'female',null,'Deeanna','Juhas','14302 Pennsylvania Ave','19006','Huntingdon Valley','Montgomery','PA','deeanna_juhas@gmail.com','2152119589','2019-01-01',null),
(61,'female',null,'Blondell','Pugh','201 Hawk Ct','02904','Providence','Providence','RI','bpugh@aol.com','4019608259','2019-01-01',null),
(62,'male',null,'Jamal','Vanausdal','53075 Sw 152nd Ter #615','08831','Monroe Township','Middlesex','NJ','jamal@vanausdal.org','7322341546','2019-01-01',null),
(63,'female',null,'Cecily','Hollack','59 N Groesbeck Hwy','78731','Austin','Travis','TX','cecily@hollack.org','5124863817','2019-01-01',null),
(64,'female',null,'Carmelina','Lindall','2664 Lewis Rd','80126','Littleton','Douglas','CO','carmelina_lindall@lindall.com','3037247371','2019-01-01',null),
(65,'female',null,'Maurine','Yglesias','59 Shady Ln #53','53214','Milwaukee','Milwaukee','WI','maurine_yglesias@yglesias.com','4147481374','2019-01-01',null),
(66,'female',null,'Tawna','Buvens','3305 Nabell Ave #679','10009','New York','New York','NY','tawna@gmail.com','2126749610','2019-01-01',null),
(67,'female',null,'Penney','Weight','18 Fountain St','99515','Anchorage','Anchorage','AK','penney_weight@aol.com','9077979628','2019-01-01',null),
(68,'female',null,'Elly','Morocco','7 W 32nd St','16502','Erie','Erie','PA','elly_morocco@gmail.com','8143935571','2019-01-01',null),
(69,'female',null,'Ilene','Eroman','2853 S Central Expy','21061','Glen Burnie','Anne Arundel','MD','ilene.eroman@hotmail.com','4109149018','2019-01-01',null),
(70,'female',null,'Vallie','Mondella','74 W College St','83707','Boise','Ada','ID','vmondella@mondella.com','2088625339','2019-01-01',null),
(71,'female',null,'Kallie','Blackwood','701 S Harrison Rd','94104','San Francisco','San Francisco','CA','kallie.blackwood@gmail.com','4153152761','2019-01-01',null),
(72,'female',null,'Johnetta','Abdallah','1088 Pinehurst St','27514','Chapel Hill','Orange','NC','johnetta_abdallah@aol.com','9192259345','2019-01-01',null),
(73,'male',null,'Bobbye','Rhym','30 W 80th St #1995','94070','San Carlos','San Mateo','CA','brhym@rhym.com','6505285783','2019-01-01',null),
(74,'female',null,'Micaela','Rhymes','20932 Hedley St','94520','Concord','Contra Costa','CA','micaela_rhymes@gmail.com','9256473298','2019-01-01',null),
(75,'male',null,'Tamar','Hoogland','2737 Pistorio Rd #9230','43140','London','Madison','OH','tamar@hotmail.com','7403438575','2019-01-01',null),
(76,'male',null,'Moon','Parlato','74989 Brandon St','14895','Wellsville','Allegany','NY','moon@yahoo.com','5858668313','2019-01-01',null),
(77,'male',null,'Laurel','Reitler','6 Kains Ave','21215','Baltimore','Baltimore City','MD','laurel_reitler@reitler.com','4105204832','2019-01-01',null),
(78,'female',null,'Delisa','Crupi','47565 W Grand Ave','07105','Newark','Essex','NJ','delisa.crupi@crupi.com','9733542040','2019-01-01',null),
(79,'female',null,'Viva','Toelkes','4284 Dorigo Ln','60647','Chicago','Cook','IL','viva.toelkes@gmail.com','7734465569','2019-01-01',null),
(80,'female',null,'Elza','Lipke','6794 Lake Dr E','07104','Newark','Essex','NJ','elza@yahoo.com','9739273447','2019-01-01',null),
(81,'female',null,'Devorah','Chickering','31 Douglas Blvd #950','88101','Clovis','Curry','NM','devorah@hotmail.com','5059758559','2019-01-01',null),
(82,'male',null,'Timothy','Mulqueen','44 W 4th St','10309','Staten Island','Richmond','NY','timothy_mulqueen@mulqueen.org','7183326527','2019-01-01',null),
(83,'female',null,'Arlette','Honeywell','11279 Loytan St','32254','Jacksonville','Duval','FL','ahoneywell@honeywell.com','9047754480','2019-01-01',null),
(84,'male',null,'Dominque','Dickerson','69 Marquette Ave','94545','Hayward','Alameda','CA','dominque.dickerson@dickerson.org','5109933758','2019-01-01',null),
(85,'female',null,'Lettie','Isenhower','70 W Main St','44122','Beachwood','Cuyahoga','OH','lettie_isenhower@yahoo.com','2166577668','2019-01-01',null),
(86,'female',null,'Myra','Munns','461 Prospect Pl #316','76040','Euless','Tarrant','TX','mmunns@cox.net','8179147518','2019-01-01',null),
(87,'male',null,'Stephaine','Barfield','47154 Whipple Ave Nw','90247','Gardena','Los Angeles','CA','stephaine@barfield.com','3107747643','2019-01-01',null),
(88,'female',null,'Lai','Gato','37 Alabama Ave','60201','Evanston','Cook','IL','lai.gato@gato.org','8477287286','2019-01-01',null),
(89,'male',null,'Stephen','Emigh','3777 E Richmond St #900','44302','Akron','Summit','OH','stephen_emigh@hotmail.com','3305375358','2019-01-01',null),
(90,'female',null,'Tyra','Shields','3 Fort Worth Ave','19106','Philadelphia','Philadelphia','PA','tshields@gmail.com','2152551641','2019-01-01',null),
(91,'female',null,'Tammara','Wardrip','4800 Black Horse Pike','94010','Burlingame','San Mateo','CA','twardrip@cox.net','6508031936','2019-01-01',null);

--
-- Import table `wpda_sas_teacher`
--
-- Source: https://www.briandunning.com/sample-data/
--
INSERT INTO `wpda_sas_teacher` (`teacher_id`, `teacher_gender`, `teacher_date_of_birth`, `teacher_firstname`, `teacher_lastname`, `teacher_address`, `teacher_zipcode`, `teacher_city`, `teacher_county`, `teacher_country`, `teacher_email`, `teacher_phone_no`, `teacher_hire_date`, `teacher_wp_username`) VALUES 
(1,'male',null,'Cory','Gibes','83649 W Belmont Ave','91776','San Gabriel','Los Angeles','CA','cory.gibes@gmail.com','6265721096','2019-01-01',null),
(2,'female',null,'Danica','Bruschke','840 15th Ave','76708','Waco','McLennan','TX','danica_bruschke@gmail.com','2547828569','2019-01-01',null),
(3,'male',null,'Wilda','Giguere','1747 Calle Amanecer #2','99501','Anchorage','Anchorage','AK','wilda@cox.net','9078705536','2019-01-01',null),
(4,'female',null,'Elvera','Benimadho','99385 Charity St #840','95110','San Jose','Santa Clara','CA','elvera.benimadho@cox.net','4087038505','2019-01-01',null),
(5,'female',null,'Carma','Vanheusen','68556 Central Hwy','94577','San Leandro','Alameda','CA','carma@cox.net','5104524835','2019-01-01',null),
(6,'female',null,'Malinda','Hochard','55 Riverside Ave','46202','Indianapolis','Marion','IN','malinda.hochard@yahoo.com','3174722412','2019-01-01',null),
(7,'female',null,'Natalie','Fern','7140 University Ave','82901','Rock Springs','Sweetwater','WY','natalie.fern@hotmail.com','3072793793','2019-01-01','teacher'),
(8,'female',null,'Lisha','Centini','64 5th Ave #1153','22102','Mc Lean','Fairfax','VA','lisha@centini.org','7034757568','2019-01-01',null);

--
-- Import table `wpda_sas_module`
--
INSERT INTO `wpda_sas_module` (`module_id`, `module_name`, `module_description`) VALUES 
(1,'HTML For Dummies','HTML beginners course'),
(2,'HTML Advanced Topics','HTML for experienced developers'),
(3,'PHP Programming','PHP beginners course'),
(4,'PHP Advanced Programming','Building dynamic websites with PHP'),
(5,'MySQL Fundamentals Part 1','Covers MySQL installation, DML and DDL'),
(6,'MySQL Fundamentals Part 2','Advanced Mysql topics like stored procedures and database triggers'),
(7,'CSS For Dummies','CSS beginners course'),
(8,'CSS Flexbox Fundamentals','CSS advanced course');

--
-- Import table `wpda_sas_course`
--
INSERT INTO `wpda_sas_course` (`course_id`, `course_start_date`, `module_id`, `teacher_id`) VALUES 
(1,'2019-01-14',1,4),
(2,'2019-03-11',1,4),
(3,'2019-05-13',1,4),
(4,'2019-07-15',1,4),
(5,'2019-09-16',1,4),
(6,'2019-11-11',1,4),
(7,'2019-02-11',2,7),
(8,'2019-04-15',2,7),
(9,'2019-06-17',2,7),
(10,'2019-08-26',2,7),
(11,'2019-10-21',2,7),
(12,'2019-12-16',2,7),
(13,'2019-01-21',3,5),
(14,'2019-05-20',3,5),
(15,'2019-09-09',3,5),
(16,'2019-02-25',4,6),
(17,'2019-06-17',4,6),
(18,'2019-10-07',4,6),
(19,'2019-03-04',5,1),
(20,'2019-09-30',5,1),
(21,'2019-04-04',6,1),
(22,'2019-10-28',6,1),
(23,'2019-01-28',7,2),
(24,'2019-03-18',7,2),
(25,'2019-05-27',7,2),
(26,'2019-07-22',7,2),
(27,'2019-09-23',7,2),
(28,'2019-11-18',7,2),
(29,'2019-03-11',8,8),
(30,'2019-10-07',8,8);

--
-- Import table `wpda_sas_class`
--
INSERT INTO `wpda_sas_class` (`course_id`, `student_id`) VALUES 
(1,1),
(1,2),
(1,3),
(1,4),
(1,5),
(1,6),
(1,7),
(1,8),
(1,9),
(2,20),
(2,21),
(2,22),
(2,23),
(2,24),
(2,25),
(2,26),
(2,27),
(2,28),
(2,29),
(3,30),
(3,31),
(3,32),
(3,33),
(3,34),
(3,35),
(3,36),
(3,37),
(4,40),
(4,41),
(4,42),
(4,43),
(4,44),
(4,45),
(4,46),
(4,47),
(5,50),
(5,51),
(5,52),
(5,53),
(5,54),
(5,55),
(5,56),
(5,57),
(5,58),
(5,59),
(5,60),
(5,61),
(5,62),
(6,10),
(6,11),
(6,12),
(6,13),
(6,14),
(6,15),
(6,16),
(6,17),
(6,18),
(7,1),
(7,2),
(7,3),
(7,4),
(7,5),
(7,6),
(7,7),
(7,8),
(7,9),
(8,20),
(8,21),
(8,22),
(8,23),
(8,24),
(8,25),
(8,26),
(8,27),
(8,28),
(8,29),
(9,30),
(9,31),
(9,32),
(9,33),
(9,34),
(9,35),
(9,36),
(9,37),
(10,40),
(10,41),
(10,42),
(10,43),
(10,44),
(10,45),
(10,46),
(10,47),
(11,50),
(11,51),
(11,52),
(11,53),
(11,54),
(11,55),
(11,56),
(11,57),
(11,58),
(11,59),
(11,60),
(11,61),
(11,62),
(12,10),
(12,11),
(12,12),
(12,13),
(12,14),
(12,15),
(12,16),
(12,17),
(12,18),
(13,10),
(13,11),
(13,12),
(13,13),
(13,14),
(13,15),
(13,16),
(13,17),
(13,18),
(13,1),
(14,2),
(14,3),
(14,32),
(14,33),
(14,34),
(14,35),
(14,36),
(14,37),
(14,38),
(15,4),
(15,5),
(15,6),
(15,7),
(15,54),
(15,55),
(15,56),
(15,57),
(16,20),
(16,21),
(16,22),
(16,23),
(16,24),
(16,25),
(16,26),
(16,27),
(16,28),
(16,29),
(17,40),
(17,41),
(17,42),
(17,43),
(17,44),
(17,45),
(17,46),
(17,47),
(17,48),
(18,1),
(18,2),
(18,3),
(18,4),
(18,64),
(18,65),
(18,66),
(18,67),
(19,70),
(19,71),
(19,72),
(19,73),
(19,74),
(19,75),
(19,76),
(19,77),
(19,78),
(20,71),
(20,72),
(20,73),
(20,74),
(20,75),
(20,76),
(20,77),
(21,80),
(21,81),
(21,82),
(21,83),
(21,84),
(21,85),
(21,86),
(21,87),
(21,88),
(21,89),
(21,90),
(22,81),
(22,82),
(22,83),
(22,84),
(22,85),
(22,86),
(22,87),
(22,88),
(22,89),
(23,1),
(23,2),
(23,3),
(23,4),
(23,5),
(23,6),
(23,7),
(23,8),
(23,9),
(24,20),
(24,21),
(24,22),
(24,23),
(24,24),
(24,25),
(24,26),
(24,27),
(24,28),
(24,29),
(25,30),
(25,31),
(25,32),
(25,33),
(25,34),
(25,35),
(25,36),
(25,37),
(26,40),
(26,41),
(26,42),
(26,43),
(26,44),
(26,45),
(26,46),
(26,47),
(27,50),
(27,51),
(27,52),
(27,53),
(27,54),
(27,55),
(27,56),
(27,57),
(27,58),
(27,59),
(27,60),
(27,61),
(27,62),
(28,10),
(28,11),
(28,12),
(28,13),
(28,14),
(28,15),
(28,16),
(28,17),
(28,18),
(29,31),
(29,32),
(29,33),
(29,34),
(29,35),
(29,36),
(29,37),
(30,40),
(30,41),
(30,42),
(30,43),
(30,44),
(30,45),
(30,46),
(30,47);

--
-- CREATE VIEWS
--
CREATE VIEW wpda_sas_course_lookup AS
SELECT wpda_sas_course.course_id
, wpda_sas_module.module_name
FROM wpda_sas_course
LEFT OUTER JOIN wpda_sas_module ON wpda_sas_course.module_id = wpda_sas_module.module_id;

CREATE VIEW wpda_sas_student_status AS
SELECT wpda_sas_student.*
, wpda_sas_module.*
, wpda_sas_course.course_id
, wpda_sas_course.course_start_date
, wpda_sas_teacher.*
, wpda_sas_grade.grade_date
, wpda_sas_grade.grade_name
, wpda_sas_grade.grade
FROM wpda_sas_student
LEFT OUTER JOIN wpda_sas_class ON wpda_sas_student.student_id = wpda_sas_class.student_id
LEFT OUTER JOIN wpda_sas_course ON wpda_sas_class.course_id = wpda_sas_course.course_id and wpda_sas_student.student_id = wpda_sas_class.student_id
LEFT OUTER JOIN wpda_sas_module ON wpda_sas_course.module_id = wpda_sas_module.module_id
LEFT OUTER JOIN wpda_sas_teacher ON wpda_sas_teacher.teacher_id = wpda_sas_course.teacher_id
LEFT OUTER JOIN wpda_sas_grade ON wpda_sas_grade.course_id = wpda_sas_class.course_id and wpda_sas_grade.student_id = wpda_sas_student.student_id
order by module_name, grade_name, grade_date;

CREATE VIEW wpda_sas_student_courses AS
SELECT wpda_sas_student.*
, wpda_sas_module.*
, wpda_sas_course.course_id
, wpda_sas_course.course_start_date
, wpda_sas_teacher.*
FROM wpda_sas_student
LEFT OUTER JOIN wpda_sas_class ON wpda_sas_student.student_id = wpda_sas_class.student_id
LEFT OUTER JOIN wpda_sas_course ON wpda_sas_class.course_id = wpda_sas_course.course_id and wpda_sas_student.student_id = wpda_sas_class.student_id
LEFT OUTER JOIN wpda_sas_module ON wpda_sas_course.module_id = wpda_sas_module.module_id
LEFT OUTER JOIN wpda_sas_teacher ON wpda_sas_teacher.teacher_id = wpda_sas_course.teacher_id;

--
-- Generate grades for table `wpda_sas_grade` up to current date
--
INSERT INTO `wpda_sas_grade` (`course_id`, `student_id`, `grade_date`, `grade_name`, `grade`)
select 
  course_id
, student_id
, date_add(course_start_date, interval 4 day)
, 'EXAM'
, 3+ceil(rand()*7)
from wpda_sas_student_status
where course_start_date < case when weekday(now()) < 5 then now()-7 else now() end;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
