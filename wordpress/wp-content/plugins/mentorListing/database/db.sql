
CREATE DATABASE IF NOT EXISTS Mentor_Listing;

USE Mentor_Listing;


CREATE TABLE `Mentor`
(
 `mentorId`    int NOT NULL AUTO_INCREMENT,
 `mentor_name` varchar(128) NOT NULL ,
 `email` varchar(45) NOT NULL,
PRIMARY KEY (`mentorId`)
);


CREATE TABLE `skill`
(
  `skillId` int NOT NULL AUTO_INCREMENT,
  `skillName` varchar(128) NOT NULL,
  `mentorId` int NOT NULL,
PRIMARY KEY (`skillId`),
KEY `fkIdx_51` (`mentorId`),
CONSTRAINT `FK_51` FOREIGN KEY `fkIdx_51` (`mentorId`) REFERENCES `Mentor` (`mentorId`)
);


CREATE TABLE `certification`
(
  `certificationId` int NOT NULL AUTO_INCREMENT,
  `certificationName` varchar(128) NOT NULL,
  `mentorId` int NOT NULL,
PRIMARY KEY (`certificationId`),
KEY `fkIdx_61` (`mentorId`),
CONSTRAINT `FK_61` FOREIGN KEY `fkIdx_61` (`mentorId`) REFERENCES `Mentor` (`mentorId`)
);


CREATE TABLE `AvailableTime`
(
 `timeId`        int NOT NULL AUTO_INCREMENT,
 `startTime`     timestamp NOT NULL ,
 `endTime`       timestamp NOT NULL ,
 `recurring`     tinyint NOT NULL ,
 `mentorId`      int NOT NULL,

PRIMARY KEY (`timeId`),
KEY `fkIdx_21` (`mentorId`),
CONSTRAINT `FK_21` FOREIGN KEY `fkIdx_21` (`mentorId`) REFERENCES `Mentor` (`mentorId`)
);


CREATE TABLE `reservationTime`
(
  `timeId`    int NOT NULL AUTO_INCREMENT,
  `startTime` timestamp NOT NULL,
  `endTime`   timestamp NOT NULL,
PRIMARY KEY (`timeId`)
);


CREATE TABLE `reservation`
(
 `reservationId` int NOT NULL AUTO_INCREMENT,
 `email`         varchar(45) NOT NULL ,
 `name`          varchar(45) NOT NULL ,
 `phone`         varchar(45) NOT NULL ,
 `timeId`        int NOT NULL,
PRIMARY KEY (`reservationId`),
KEY `fkIdx_41` (`timeId`),
CONSTRAINT `FK_41` FOREIGN KEY `fkIdx_41` (`timeId`) REFERENCES `reservationTime` (`timeId`)
);

CREATE TABLE `ReservationMentorMap`
( 
  `mapId` int NOT NULL AUTO_INCREMENT,
  `mentorId`      int NOT NULL,
  `reservationId` int NOT NULL,
  `date`          date NOT NULL,
  PRIMARY KEY (`mapId`),
  KEY `fkIdx_31` (`mentorId`),
  CONSTRAINT `FK_31` FOREIGN KEY `fkIdx_31` (`mentorId`) REFERENCES `Mentor` (`mentorId`),
  KEY `fkIdx_35` (`reservationId`),
  CONSTRAINT `FK_35` FOREIGN KEY `fkIdx_35` (`reservationId`) REFERENCES `reservation` (`reservationId`)
);





