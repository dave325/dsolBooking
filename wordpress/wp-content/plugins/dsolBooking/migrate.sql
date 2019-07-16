USE wordpress;

-- LIST OF QUERIES FOR ONE-TIME MERGE INFORMATION FROM bookaroom_bookaroom_* TO DSOL BOOKING:

-- 1.  bookaroom_dsol_booking_branch:
	
    -- Dropping column table: 
		 ALTER TABLE bookaroom_dsol_booking_branch DROP branchName; 
	
	 	 -- INSERT INTO book
    INSERT INTO incubator_dsol_booking_branch (b_name) SELECT branchDesc FROM bookaroom_branches; 
         
-- 2. bookaroom_dsol_booking_room:

	-- Inserting into DSOL:
		INSERT INTO incubator_dsol_booking_room (b_id, room_number) SELECT room_branchID, room_desc  FROM incubator_bookaroom_rooms;

-- 3. bookaroom_dsol_booking_container:
    
    -- Inserting into DSOL:
	INSERT INTO incubator_dsol_booking_container (r_id, container_number, occupancy) SELECT m.rcm_roomID, rc.roomCont_desc, rc.roomCont_occ FROM incubator_bookaroom_roomConts rc INNER JOIN incubator_bookaroom_roomConts_members m ON rc.roomCont_ID = m.rcm_roomContID

-- 4. bookaroom_dsol_booking_time
	INSERT INTO incubator_dsol_booking_time (start_time, end_time, res_id) 
        SELECT bt.ti_startTime, bt.ti_endTime, res.res_id
        FROM incubator_bookaroom_times bt
        INNER JOIN incubator_bookaroom_reservations res
        ON res.res_id = bt.ti_extID
        INNER JOIN incubator_bookaroom_roomConts_members m 
        ON bt.ti_roomID = m.rcm_roomID;
    
-- 5. bookroom_dsol_booking_reservation
INSERT INTO incubator_dsol_booking_reservation (c_id, t_id, modified_by, created_at, modified_at, created_by, company_name, email, attendance, notes) 
	SELECT m.rcm_roomContID, t.ti_id, res.me_contactEmail, res.res_created, CURRENT_TIMESTAMP, res.me_contactName, res.me_contactName, res.me_contactEmail, res.me_numAttend, res.me_notes
    FROM incubator_bookaroom_reservations res
    INNER JOIN  incubator_bookaroom_times t
    ON res.res_id = t.ti_extID
    INNER JOIN incubator_bookaroom_roomConts_members m 
    ON  t.ti_roomID = m.rcm_roomID;
    
        

