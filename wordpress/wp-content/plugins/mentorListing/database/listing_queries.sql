/*
	LISTING QUERIES:
	
		This file contains all the listing queries that we intend on using. 
		
		*** Still a working progress ***

		- Kelvin

*/

/************************************************************************************************/
																	-- Follows the standard C.R.U.D -- 
/************************************************************************************************/

/*

	CREATE queries for the listing queries

*/

INSERT INTO wp_reservationTime (timeId, startTime, endTime)
VALUES (?timeId, "YYYY-MM--DD HH:MM:SS", "YYYY-MM--DD HH:MM:SS");

INSERT INTO wp_reservation (reservationId, email, name, phone, timeId)
VALUES (?reservationId, ?email, ?name, ?phone, ?timeId);

INSERT INTO wp_ReservationMentorMap (mapId, mentorId, reservationId, date)
VALUES (?mapId, ?mentorId, ?reservationId, ?date);

/************************************************************************************************/

/*

	READ queries for the listing queries

*/

-- GET ALL LISTINGS
SELECT m.mentorId, 
                    m.mentor_name, 
                    m.email AS mentorEmail, 
                    a.startTime AS openTime, 
                    a.endTime AS closeTime, 
                    a.recurring,
                    c.mapId, 
                    c.date, 
                    r.reservationId, 
                    r.email, 
                    r.name, 
                    r.phone, 
                    rt.timeId, 
                    rt.startTime, 
                    rt.endTime
                FROM wp_Mentor m
                JOIN wp_AvailableTime a
                ON m.mentorId = a.mentorId
                JOIN wp_ReservationMentorMap c
                ON a.mentorId = c.mentorId
                JOIN wp_reservation r
                ON c.reservationId = r.reservationId
                JOIN wp_reservationTime rt
                ON r.timeId = rt.timeId
                ORDER BY m.mentorId;

-- GET A SPECIFIC LISTING, BY SPECIFIC MENTOR
SELECT m.mentorId, 
                    a.recurring,
                    c.mapId, 
                    c.date, 
                    r.reservationId, 
                    r.email, 
                    r.name, 
                    r.phone, 
                    rt.timeId, 
                    rt.startTime, 
                    rt.endTime
              FROM wp_Mentor m
              JOIN wp_AvailableTime a
              ON m.mentorId = a.mentorId
              JOIN wp_ReservationMentorMap c
              ON a.mentorId = c.mentorId
              JOIN wp_reservation r
              ON c.reservationId = r.reservationId
              JOIN wp_reservationTime rt
              ON r.timeId = rt.timeId
              WHERE m.mentorId = $mentorId
              ORDER BY m.mentorId;


/************************************************************************************************/

/*

	UPDATE queries for the listing queries

*/

-- UPDATE RESERVATION TIME
-- Note: to update a reservation, the timeId AND mentorId must be provided.

UPDATE wp_reservationTime rt
SET rt.startTime = "YYYY-MM--DD HH:MM:SS"
WHERE rt.timeId = ?timeId;

UPDATE wp_reservationTime rt
SET rt.endTime = "YYYY-MM--DD HH:MM:SS"
WHERE rt.timeId = ?timeId;


-- UPDATE wp_ReservationMentorMap c
-- SET c.date = "YYYY-MM--DD"
-- WHERE c.mentorId = ?mentorId;


/************************************************************************************************/

/*

	DELETE queries for the listing queries

*/

-- DELETE RESERVATION
-- Note: reservationId must be provided.

DELETE FROM wp_ReservationMentorMap WHERE reservationId IN (SELECT r.reservationId FROM wp_reservation r WHERE r.timeId = $timeId) AND mentorId = $mentorId;

DELETE FROM wp_reservationTime WHERE timeId = $timeId;

DELETE FROM wp_reservation WHERE timeId = $timeId


/************************************************************************************************/