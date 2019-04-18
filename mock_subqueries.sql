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
-- ** NOTE: !! (WILL BE AUTO-GENERATED FROM DB: will change db tables) **

-- Update reservation at a future time by admin, t_id = ?:
UPDATE reservation SET modified_at = now(), modified_by = ?admin WHERE t_id = ?;

-- CURRENTLY A WORKING PROGRESS ..........



