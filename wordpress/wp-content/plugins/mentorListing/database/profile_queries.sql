/*
	PROFILE QUERIES:
	
		This file contains all the profile queries that we intend on using. 
		
		*** Still a working progress ***

		- Kelvin

*/

/************************************************************************************************/
																	-- Follows the standard C.R.U.D -- 
/************************************************************************************************/

/*

	CREATE mentor profile 

*/

-- CREATE MENTOR
INSERT INTO Mentor (mentorId, mentor_name, email)
VALUES (?mentorId, ?mentor_name, ?email);

INSERT INTO AvailableTime (timeId, startTime, endTime, recurring, mentorId)
VALUES (?timeId, "YYYY-MM--DD HH:MM:SS", "YYYY-MM--DD HH:MM:SS", ?recurring, ?mentorId);

INSERT INTO skill (skillId, skillName, mentorId)
VALUES (?skillId, ?skillName, ?mentorId);

INSERT INTO certification (certificationId, certificationName, mentorId)
VALUES (?certificationId, ?certificationName, ?mentorId);


/************************************************************************************************/

/*

	READ mentor profile 

*/

-- GET ALL CERTS
SELECT 
                m.mentorId, 
                m.mentor_name, 
                m.email, 
                JSON_ARRAYAGG(c.certificationId) AS certificationIds, 
                JSON_ARRAYAGG(c.certificationName) AS certificationNames
        FROM wp_Mentor m
        JOIN wp_certification c 
        ON m.mentorId = c.mentorId
        GROUP BY m.mentorId, m.mentor_name, m.email;

-- GET CERT BY SPECIFIC mentorId
SELECT 
                m.mentorId, 
                m.mentor_name, 
                m.email, 
                JSON_ARRAYAGG(c.certificationId) AS certificationIds, 
                JSON_ARRAYAGG(c.certificationName) AS certificationNames
        FROM wp_Mentor m
        JOIN wp_certification c 
        ON m.mentorId = c.mentorId
        WHERE m.mentorId = $mentorId;

-- GET ALL SKILLS
SELECT 
                m.mentorId, 
                m.mentor_name, 
                m.email, 
                JSON_ARRAYAGG(s.skillId) AS skillIds, 
                JSON_ARRAYAGG(s.skillName) AS skillNames 
        FROM wp_Mentor m
        JOIN wp_skill s
        ON m.mentorId = s.mentorId
        GROUP BY m.mentorId, m.mentor_name, m.email;

-- GET SKILL BY SPECIFIC mentorId
SELECT 
                m.mentorId, 
                m.mentor_name, 
                m.email, 
                JSON_ARRAYAGG(s.skillId) AS skillIds, 
                JSON_ARRAYAGG(s.skillName) AS skillNames 
        FROM wp_Mentor m
        JOIN wp_skill s
        ON m.mentorId = s.mentorId
        WHERE m.mentorId = $mentorId;


/************************************************************************************************/

/*

	UPDATE mentor profile

*/

-- CHANGE INFORMATION IN MENTOR PROFILE

-- CHANGE NAME
UPDATE Mentor m
SET m.mentor_name = ?mentorName
WHERE m.mentorId = ?mentorId;

-- CHANGE EMAIL
UPDATE Mentor m
SET m.email = ?email
WHERE m.mentorId = ?mentorId;

-- CHANGE SKILL
UPDATE skill s
SET s.skillName = ?skillName
WHERE s.mentorId = ?mentorId;

-- DELETE SKILL
DELETE FROM skill s
WHERE s.skillId = ?skillId;

-- CHANGE CERTIFICATION
UPDATE Mentor m
SET c.certificationName = ?certificationName
WHERE c.mentorId = ?mentorId;

-- DELETE CERTIFICATION
DELETE FROM certification c 
WHERE c.certificationId = ?certificationId;

/*****************************************************/

-- CHANGE INFORMATION IN AVAILABLE TIME

-- CHANGE STARTTIME
UPDATE AvailableTime a
SET a.startTime = "YYYY-MM--DD HH:MM:SS"
WHERE a.timeId = ?timeId;

-- CHANGE ENDTIME
UPDATE AvailableTime a
SET a.endTime = "YYYY-MM--DD HH:MM:SS"
WHERE a.timeId = ?timeId;

-- CHANGE RECURRING
UPDATE AvailableTime a
SET a.recurring = ?isRecurring
WHERE a.timeId = ?timeId;


/************************************************************************************************/

/*

	DELETE mentor profile 
  
  Note: Deleting a mentor involves deleting EVERYTHING

*/

DELETE FROM wp_certification WHERE mentorId = $mentorId;

DELETE FROM wp_skill WHERE mentorId = $mentorId;

DELETE FROM wp_AvailableTime WHERE mentorId = $mentorId;

DELETE FROM wp_reservationTime 
              WHERE timeId IN (
                SELECT r.timeId 
                FROM wp_reservation r 
                WHERE r.reservationId IN (
                  SELECT c.reservationId 
                  FROM wp_ReservationMentorMap c 
                  WHERE c.mentorId = $mentorId
              ));

DELETE FROM wp_reservation
              WHERE reservationId IN (
                SELECT r.reservationId 
                FROM wp_ReservationMentorMap r
                WHERE r.mentorId = $mentorId
              );

DELETE FROM wp_ReservationMentorMap
              WHERE mentorId = $mentorId;

DELETE FROM wp_Mentor WHERE mentorId = $mentorId;



/************************************************************************************************/





















