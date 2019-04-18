-- NOTE: Look bookaroom-meetings-public.php for reference

-- ******************************************************

-- GET ALL INFORMATION

-- 1. Show all branches:
SELECT b.b_id AS branchId, b.b_name AS branchName 
FROM branch b;

-- 2. Show all rooms under a certain branch id = ? :
SELECT r.r_id AS roomId, r.room_number AS roomNumber, r.b_id AS branchId 
FROM room r WHERE r.b_id = ?;

-- 3. Show all schedules for room_container = ? :
SELECT rs.res_id AS resId, rs.c_id AS containerId, rs.t_id AS timeId, rs.modified_by AS modifiedBy, rs.created_at AS createdAt, rs.modified_at AS modifiedAt, rs.created_by AS createdBy, rs.company_name AS companyName, rs.email AS email, rs.attendance AS attendance, rs.notes AS notes 
FROM reservation rs WHERE rs.c_id = ? ;

-- 4. Show all room containers for room id = ? :
SELECT rc.c_id AS containerId, rc.r_id AS roomId, rc.t_id AS timeId, rc.container_number AS containerNumber 
FROM room_container rc WHERE rc.r_id = ? ;


-- ******************************************************

-- ADD NEW INFO (POST)
-- ** NOTE: !! (WILL BE AUTO-GENERATED FROM DB: will change db tables) **

-- 1. Add new branch for b_name = ?
INSERT INTO branch (b_id, b_name) VALUES (!!, ?b_name);

-- 2. Add new room for b_id = ? and room_number = ? 
INSERT INTO room (r_id, room_number, b_id) VALUES (!!, ?room_number, ?b_id);

-- 3. Add new room container for room_id = ? and t_id = !!
INSERT INTO room_container (c_id, r_id, t_id, container_number) VALUES (!!, ?room_id, ?t_id, !!);

-- 4. Add new schedule for room:
INSERT INTO reservation (res_id, c_id, t_id, modified_by, created_at, modified_at, created_by, company_name,email,attendance,notes)
VALUES (!!, ?c_id, ?t_id, NULL, now(), NULL, ?userName, ?companyName, ?email, ?attendance, ?notes );

-- Add into time_table. Same time id as in reservation, 
INSERT INTO time_table (t_id, start_time, end_time) 
VALUES (?t_id, ?start_time, ?end_time);

-- ******************************************************

-- UPDATE EXISTING INFO (POST)

-- Update reservation time at a future time by admin, t_id = ?:
UPDATE time_table SET start_time = ?, end_time = ? WHERE t_id = ?;
UPDATE reservation SET modified_at = now(), modified_by = ?admin WHERE t_id = ?;

-- Change room number:
UPDATE room SET room_number = ? WHERE r_id = ?;

-- Change room_container number (?num) :
UPDATE room_container SET container_number = ?num WHERE c_id = ?;


-- *** Can be combined into one update query ***

-- Change number of people (?num) in reservation :
UPDATE reservation SET attendance = ?num WHERE res_id = ?;

-- Change email (?email) in reservation:
UPDATE reservation SET email = ?email WHERE res_id = ?;

-- Change notes (?notes) in reservation:
UPDATE reservation SET notes = ?notes WHERE res_id = ?;

-- Change company name (?name) in reservation:
UPDATE reservation SET company_name = ?name WHERE res_id = ?;


-- ********************************************************************************************************************

-- DELETE INFO

-- *******************************
-- Individual SELECT statements:
Select branch.b_id from branch LEFT JOIN room ON branch.b_id = room.b_id group by branch.b_id;
SELECT room.r_id from room LEFT JOIN room_container ON room.r_id = room_container.r_id group by room.r_id;
SELECT room_container.r_id from room_container LEFT JOIN reservation ON room_container.c_id = reservation.c_id group by room_container.r_id;

-- *******************************
-- Collective SELECT statement:
SELECT branch.b_id
FROM branch
LEFT JOIN room ON branch.b_id = room.b_id
LEFT JOIN room_container ON room.r_id = room_container.r_id
LEFT JOIN reservation ON room_container.c_id = reservation.c_id
GROUP BY branch.b_id; 

-- *******************************
-- Specify branch_id = ?
SELECT branch.b_id
FROM branch
LEFT JOIN room ON branch.b_id = room.b_id
LEFT JOIN room_container ON room.r_id = room_container.r_id
LEFT JOIN reservation ON room_container.c_id = reservation.c_id
WHERE branch.b_id = ?
GROUP BY branch.b_id; 





