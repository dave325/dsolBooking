-- DUMMY DATA:

-- NOTE:
-- 1. There is only one branch for now.
-- 2. The number of rooms: 1-10
-- 3. The number of container rooms: 1-7

Insert into branch (b_id, b_name) values (1000, "Branch 1");
Insert into branch_schedule(bs_id, b_id, open_time, close_time) values (1, 1000, 20190901123000, 20200601123000);
Insert into room (r_id, room_number, b_id) values (100, 1, 1000);
Insert into room_container (c_id, r_id, t_id, container_number) values (10, 100, 1231, 1);
Insert into time_table (t_id, start_time, end_time) values (1, 20190901123000, 20190901033000);
Insert into reservation(res_id, c_id, t_id, modified_by, created_at, modified_at, created_by, company_name,email,attendance,notes) values (1, 10, 1, "Kelvin", 20190901103000, NULL, "Kelvin", "Kelvin's Company", "kelvin123@gmail.com", 3, "Need more paper");
-- At a future time when you update it:
update reservation set modified_at = now() where t_id = 1;

-- Same Branch (id: 1000)
-- Same branch_schedule (since same branch)
Insert into room (r_id, room_number, b_id) values (200, 2, 1000);
Insert into room_container (c_id, r_id, t_id, container_number) values (20, 200, 1231, 1);
Insert into time_table (t_id, start_time, end_time) values (2, 20190902123000, 20190902033000);
Insert into reservation(res_id, c_id, t_id, modified_by, created_at, modified_at, created_by, company_name,email,attendance,notes) values (2, 20, 2, "Tom", 20190901103000, NULL, "Tom", "Tom's Company", "tom123@gmail.com", 2, "Need more stationary");
-- At a future time when you update it by person= ? :
update reservation set modified_at = now(), modified_by = ? where t_id = 2;


-- Same Branch (id: 1000)
-- Same branch_schedule (since same branch)
Insert into room (r_id, room_number, b_id) values (300, 2, 1000);
Insert into room_container (c_id, r_id, t_id, container_number) values (21, 200, 4533, 1);
Insert into time_table (t_id, start_time, end_time) values (3, 20190902033500, 20190902043000);
Insert into reservation(res_id, c_id, t_id, modified_by, created_at, modified_at, created_by, company_name,email,attendance,notes) values (3, 21, 3, "Jerry", 20190901103000, NULL, "Jerry", "Jerry's Company", "jerry123@gmail.com", 2, "Need more laptops");
-- At a future time when you update it by person= ? :
update reservation set modified_at = now(), modified_by = ? where t_id = 3;


