/*
	DUMMY DATA
	
		This file contains all the INSERTION OF DUMMY DATA QUERIES

		- Kelvin

*/

/******************************************************************************************/

-- ADD A NEW MENTOR

INSERT INTO wp_Mentor (mentorId, mentor_name, email)
VALUES (1, "Bobby", "abc@gmail.com");

INSERT INTO wp_AvailableTime (timeId, startTime, endTime, recurring, mentorId)
VALUES (1, "2019-01-01 09:00:00", "2019-01-25 17:00:00", 1, 1);

INSERT INTO wp_skill (skillId, skillName, mentorId)
VALUES (1, "Fishing", 1);
INSERT INTO wp_skill (skillId, skillName, mentorId)
VALUES (2, "Baking", 1);
INSERT INTO wp_skill (skillId, skillName, mentorId)
VALUES (3, "Woodcuting", 1);

INSERT INTO wp_certification (certificationId, certificationName, mentorId)
VALUES (1, "ABC Academy", 1);
INSERT INTO wp_certification (certificationId, certificationName, mentorId)
VALUES (2, "DEF Academy", 1);
INSERT INTO wp_certification (certificationId, certificationName, mentorId)
VALUES (3, "GHI Academy", 1);

/********************************/

INSERT INTO wp_Mentor (mentorId, mentor_name, email)
VALUES (2, "Annie", "adfsd@gmail.com");

INSERT INTO wp_AvailableTime (timeId, startTime, endTime, recurring, mentorId)
VALUES (2, "2019-01-01 09:00:00", "2019-01-25 17:00:00", 1, 2);

INSERT INTO wp_skill (skillId, skillName, mentorId)
VALUES (4, "Woodcuting", 2);
INSERT INTO wp_skill (skillId, skillName, mentorId)
VALUES (5, "Firemaking", 2);
INSERT INTO wp_skill (skillId, skillName, mentorId)
VALUES (6, "Baking", 2);

INSERT INTO wp_certification (certificationId, certificationName, mentorId)
VALUES (4, "JKL Academy", 2);
INSERT INTO wp_certification (certificationId, certificationName, mentorId)
VALUES (5, "MNO Academy", 2);

/********************************/


INSERT INTO wp_Mentor (mentorId, mentor_name, email)
VALUES (3, "Susan", "tertwqe@gmail.com");

INSERT INTO wp_AvailableTime (timeId, startTime, endTime, recurring, mentorId)
VALUES (3, "2019-01-01 09:00:00", "2019-01-25 17:00:00", 1, 3);

INSERT INTO wp_skill (skillId, skillName, mentorId)
VALUES (7, "Blacksmith", 3);
INSERT INTO wp_skill (skillId, skillName, mentorId)
VALUES (8, "Sandwich Maker", 3);

INSERT INTO wp_certification (certificationId, certificationName, mentorId)
VALUES (6, "PQ Academy", 3);
INSERT INTO wp_certification (certificationId, certificationName, mentorId)
VALUES (7, "KL Academy", 3);

/********************************/

INSERT INTO wp_Mentor (mentorId, mentor_name, email)
VALUES (4, "Eve", "zxcv@gmail.com");

INSERT INTO wp_AvailableTime (timeId, startTime, endTime, recurring, mentorId)
VALUES (4, "2019-01-01 09:00:00", "2019-01-25 17:00:00", 1, 4);

INSERT INTO wp_skill (skillId, skillName, mentorId)
VALUES (9, "Sword Fishing", 4);
INSERT INTO wp_skill (skillId, skillName, mentorId)
VALUES (10, "Javascript", 4);

INSERT INTO wp_certification (certificationId, certificationName, mentorId)
VALUES (8, "POPK Academy", 4);
INSERT INTO wp_certification (certificationId, certificationName, mentorId)
VALUES (9, "AQW Academy", 4);

/********************************/

INSERT INTO wp_Mentor (mentorId, mentor_name, email)
VALUES (5, "Mary", "qwerty@gmail.com");

INSERT INTO wp_AvailableTime (timeId, startTime, endTime, recurring, mentorId)
VALUES (5, "2019-01-01 09:00:00", "2019-01-25 17:00:00", 0, 5);

INSERT INTO wp_skill (skillId, skillName, mentorId)
VALUES (11, "Sleeping", 5);
INSERT INTO wp_skill (skillId, skillName, mentorId)
VALUES (12, "Eating", 5);

INSERT INTO wp_certification (certificationId, certificationName, mentorId)
VALUES (10, "QWE Academy", 5);
INSERT INTO wp_certification (certificationId, certificationName, mentorId)
VALUES (11, "RTY Academy", 5);

/********************************/

INSERT INTO wp_Mentor (mentorId, mentor_name, email)
VALUES (6, "Adam", "cmakdewa@gmail.com");

INSERT INTO wp_AvailableTime (timeId, startTime, endTime, recurring, mentorId)
VALUES (6, "2019-01-01 09:00:00", "2019-01-25 17:00:00", 0, 6);

INSERT INTO wp_skill (skillId, skillName, mentorId)
VALUES (13, "Ebay Seller", 6);
INSERT INTO wp_skill (skillId, skillName, mentorId)
VALUES (14, "Walking", 6);

INSERT INTO wp_certification (certificationId, certificationName, mentorId)
VALUES (12, "MNA Academy", 6);
INSERT INTO wp_certification (certificationId, certificationName, mentorId)
VALUES (13, "LOK Academy", 6);

/******************************************************************************************/

-- MAKE A NEW RESERVATION

INSERT INTO wp_reservationTime (timeId, startTime, endTime)
VALUES (1, "2019-01-01 09:00:00", "2019-01-01 12:00:00");

INSERT INTO wp_reservation (reservationId, email, name, phone, timeId)
VALUES (1, "Dylan123@gmail.com", "Dylan Smith", "1-800-355-0122", 1);

INSERT INTO wp_ReservationMentorMap (mapId, mentorId, reservationId, date)
VALUES (1, 1, 1, "2019-01-01");


INSERT INTO wp_reservationTime (timeId, startTime, endTime)
VALUES (2, "2019-01-01 13:00:00", "2019-01-01 17:00:00");

INSERT INTO wp_reservation (reservationId, email, name, phone, timeId)
VALUES (2, "Diana123@gmail.com", "Diana Pie", "1-800-355-0123", 2);

INSERT INTO wp_ReservationMentorMap (mapId, mentorId, reservationId, date)
VALUES (2, 2, 2, "2019-01-01");

INSERT INTO wp_reservationTime (timeId, startTime, endTime)
VALUES (3, "2019-01-02 09:00:00", "2019-01-02 12:00:00");

INSERT INTO wp_reservation (reservationId, email, name, phone, timeId)
VALUES (3, "Don123@gmail.com", "Don Draper", "1-800-355-1111", 3);

INSERT INTO wp_ReservationMentorMap (mapId, mentorId, reservationId, date)
VALUES (3, 3, 3, "2019-01-02");


INSERT INTO wp_reservationTime (timeId, startTime, endTime)
VALUES (4, "2019-01-02 13:00:00", "2019-01-02 17:00:00");

INSERT INTO wp_reservation (reservationId, email, name, phone, timeId)
VALUES (4, "Walter123@gmail.com", "Walter White", "1-800-355-1222", 4);

INSERT INTO wp_ReservationMentorMap (mapId, mentorId, reservationId, date)
VALUES (4, 4, 4, "2019-01-02");

INSERT INTO wp_reservationTime (timeId, startTime, endTime)
VALUES (5, "2019-01-03 09:00:00", "2019-01-03 12:00:00");

INSERT INTO wp_reservation (reservationId, email, name, phone, timeId)
VALUES (5, "Adam123@gmail.com", "Adam Smith", "1-800-355-1222", 5);

INSERT INTO wp_ReservationMentorMap (mapId, mentorId, reservationId, date)
VALUES (5, 5, 5, "2019-01-03");

INSERT INTO wp_reservationTime (timeId, startTime, endTime)
VALUES (6, "2019-01-03 13:00:00", "2019-01-03 17:00:00");

INSERT INTO wp_reservation (reservationId, email, name, phone, timeId)
VALUES (6, "James123@gmail.com", "James Sour", "1-800-355-1202", 6);

INSERT INTO wp_ReservationMentorMap (mapId, mentorId, reservationId, date)
VALUES (6, 6, 6, "2019-01-03");


/******************************************************************************************/

