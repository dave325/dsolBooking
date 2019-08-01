/*
	TESTING QUERIES
	
	
	** PLEASE DO NOT USE THESE QUERIES BELOW WHEN LOADING IN THE INITIAL DUMMY-DATA !!! **

  These queries are used for REFERENCE and when inserting into the actual phpMyadmin for testing purposes.


*/





/*
	Make a new profile
*/

INSERT INTO wp_Mentor (mentorId, mentor_name, email)
VALUES (7, "Lamb", "cmakdfsdewa@gmail.com");

INSERT INTO wp_AvailableTime (timeId, startTime, endTime, recurring, mentorId)
VALUES (7, "2019-01-01 09:00:00", "2019-01-25 17:00:00", 0, 7);

INSERT INTO wp_skill (skillId, skillName, mentorId)
VALUES (15, "Water Seller", 7);
INSERT INTO wp_skill (skillId, skillName, mentorId)
VALUES (16, "Reading", 7);

INSERT INTO wp_certification (certificationId, certificationName, mentorId)
VALUES (14, "MNP Academy", 7);
INSERT INTO wp_certification (certificationId, certificationName, mentorId)
VALUES (15, "LOM Academy", 7);

/*****************/

INSERT INTO wp_Mentor (mentorId, mentor_name, email)
VALUES (8, "John Lennon", "JL@gmail.com");

INSERT INTO wp_AvailableTime (timeId, startTime, endTime, recurring, mentorId)
VALUES (8, "2019-01-01 09:00:00", "2019-01-25 17:00:00", 1, 8);

INSERT INTO wp_skill (skillId, skillName, mentorId)
VALUES (17, "Freezing", 8);
INSERT INTO wp_skill (skillId, skillName, mentorId)
VALUES (18, "Eating", 8);
INSERT INTO wp_skill (skillId, skillName, mentorId)
VALUES (19, "Sleeping", 8);

INSERT INTO wp_certification (certificationId, certificationName, mentorId)
VALUES (16, "OPE Academy", 8);
INSERT INTO wp_certification (certificationId, certificationName, mentorId)
VALUES (17, "ZBF Academy", 8);
INSERT INTO wp_certification (certificationId, certificationName, mentorId)
VALUES (18, "EAK Academy", 8);


/*
		Make a new listing
*/

INSERT INTO wp_reservationTime (timeId, startTime, endTime)
VALUES (9, "2019-01-01 19:00:00", "2019-01-01 20:00:00");

INSERT INTO wp_reservation (reservationId, email, name, phone, timeId)
VALUES (9, "test@gmail.com", Test Tester, "1-800-000-TEST", 9);

INSERT INTO wp_ReservationMentorMap (mapId, mentorId, reservationId, date)
VALUES (9, 7, 9, "2019-01-01");


