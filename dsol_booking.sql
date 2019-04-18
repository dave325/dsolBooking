-- Preliminary setup of the dsol_booking tables, subject to change.


-- use dsol_booking;

CREATE TABLE branch (
	b_id int NOT NULL AUTO_INCREMENT,
    b_name varchar(323) NOT NULL,
    PRIMARY KEY(b_id)
);

CREATE TABLE room (
	r_id int NOT NULL AUTO_INCREMENT,
    room_number int,
    b_id int,
    PRIMARY KEY (r_id),
    FOREIGN KEY (b_id) references branch(b_id)
);

 CREATE TABLE room_container (
 	c_id int NOT NULL AUTO_INCREMENT,
 	r_id int,
    t_id int,
 	container_number int,
 	PRIMARY KEY (c_id),
 	FOREIGN KEY (r_id) references room(r_id)
 );

CREATE TABLE branch_schedule (
	bs_id int NOT NULL AUTO_INCREMENT,
    b_id int,
    open_time DATETIME,
    close_time DATETIME,
    PRIMARY KEY (bs_id),
    FOREIGN KEY (b_id) references branch(b_id)
);

CREATE TABLE time_table (
 	t_id int NOT NULL AUTO_INCREMENT,
 	start_time DATETIME,
 	end_time DATETIME,
 	PRIMARY KEY (t_id)
  );

CREATE TABLE reservation (
	res_id int NOT NULL AUTO_INCREMENT,
    c_id int,
    t_id int,
    modified_by varchar(40),
    created_at DATETIME,
    modified_at DATETIME,
    created_by varchar(40),
    company_name varchar(40),
    email varchar(40),
    attendance int,
    notes varchar(323),
    PRIMARY KEY (res_id),
    FOREIGN KEY (c_id) references room_container(c_id),
    FOREIGN KEY (t_id) references time_table(t_id)
);





