USE wordpress;

-- LIST OF QUERIES FOR ONE-TIME MERGE INFORMATION FROM bookaroom_bookaroom_* TO DSOL BOOKING:

-- 1.  bookaroom_dsol_booking_branch:
	
    -- Dropping column table: 
		 ALTER TABLE bookaroom_dsol_booking_branch DROP branchName; 
	
	 	 -- INSERT INTO book
    INSERT INTO wp_dsol_booking_branch (b_name) SELECT branchDesc FROM bookaroom_branches; 
         
-- 2. bookaroom_dsol_booking_room:

	-- Inserting into DSOL:
		INSERT INTO wp_dsol_booking_room (b_id, room_number) SELECT room_branchID, room_desc  FROM wp_bookaroom_rooms;

-- 3. bookaroom_dsol_booking_container:
    
    -- Inserting into DSOL:
	INSERT INTO wp_dsol_booking_container (r_id, container_number, occupancy) SELECT m.rcm_roomID, rc.roomCont_desc, rc.roomCont_occ FROM wp_bookaroom_roomConts rc INNER JOIN wp_bookaroom_roomConts_members m ON rc.roomCont_ID = m.rcm_roomContID

-- 4. bookaroom_dsol_booking_time
	INSERT INTO wp_dsol_booking_time (start_time, end_time, res_id) 
        SELECT bt.ti_startTime, bt.ti_endTime, res.res_id
        FROM wp_bookaroom_times bt
        INNER JOIN wp_bookaroom_reservations res
        ON res.res_id = bt.ti_extID
        RIGHT JOIN wp_bookaroom_roomConts_members m 
        ON bt.ti_roomID = m.rcm_roomID
        WHERE res.me_numAttend > 0 AND LENGTH(res.me_contactEmail) > 0 AND LENGTH(res.me_desc) > 0;
    
-- 5. bookroom_dsol_booking_reservation
INSERT INTO wp_dsol_booking_reservation (res_id, c_id, modified_by, created_at, modified_at, created_by, company_name, email, attendance, notes) 
	SELECT res.res_id, m.rcm_roomContID, res.me_contactEmail, res.res_created, CURRENT_TIMESTAMP, res.me_contactName, res.me_contactName, res.me_contactEmail, res.me_numAttend, res.me_desc
    FROM wp_bookaroom_reservations res
    INNER JOIN  wp_bookaroom_times t
    ON res.res_id = t.ti_extID
    JOIN wp_bookaroom_roomConts_members m 
    ON  t.ti_roomID = m.rcm_roomID
    WHERE res.me_numAttend > 0 AND LENGTH(res.me_contactEmail) > 0 AND LENGTH(res.me_desc) > 0;
    
        


SELECT wp_dsol_booking_reservation.res_id,                        wp_dsol_booking_reservation.company_name,                        wp_dsol_booking_reservation.email,                        wp_dsol_booking_reservation.attendance,                        wp_dsol_booking_reservation.notes,                        wp_dsol_booking_container.container_number,                        wp_dsol_booking_container.c_id,                        wp_dsol_booking_room.room_number,                        wp_dsol_booking_branch.b_name,                        JSON_ARRAYAGG(wp_dsol_booking_time.start_time) AS start_time,                        JSON_ARRAYAGG(wp_dsol_booking_time.end_time) AS end_time        FROM wp_dsol_booking_branch        LEFT JOIN wp_dsol_booking_room ON wp_dsol_booking_branch.b_id = wp_dsol_booking_room.b_id        LEFT JOIN wp_dsol_booking_container ON wp_dsol_booking_room.r_id = wp_dsol_booking_container.r_id        LEFT JOIN wp_dsol_booking_reservation ON wp_dsol_booking_container.c_id = wp_dsol_booking_reservation.c_id        LEFT JOIN wp_dsol_booking_time ON wp_dsol_booking_time.res_id = wp_dsol_booking_reservation.res_id        WHERE wp_dsol_booking_reservation.res_id IS NOT NULL AND        wp_dsol_booking_time.start_time BETWEEN '2019-07-01 00:00:01' AND '2019-08-27 23:59:59'        GROUP BY wp_dsol_booking_reservation.res_id        ORDER BY JSON_EXTRACT(JSON_ARRAYAGG(wp_dsol_booking_time.start_time) , '$[0]');