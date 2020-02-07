/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE TABLE `wpda_sas_student` (
    `student_id`                int(11)         NOT NULL    AUTO_INCREMENT,
    `student_gender`            enum('female', 'male'),
    `student_date_of_birth`     date,
    `student_firstname`         varchar(100)    NOT NULL,
    `student_lastname`          varchar(100)    NOT NULL,
    `student_address`           varchar(100)    NOT NULL,
    `student_zipcode`           varchar(10)     NOT NULL,
    `student_city`              varchar(100)    NOT NULL,
    `student_county`            varchar(100),
    `student_country`           varchar(100),
    `student_email`             varchar(254)    NOT NULL,
    `student_phone_no`          varchar(10),
    `student_registration_date` date,
    `student_wp_username`       varchar(60),
    PRIMARY KEY (`student_id`),
    UNIQUE KEY (`student_email`),
    UNIQUE KEY (`student_wp_username`)
);

CREATE TABLE `wpda_sas_teacher` (
    `teacher_id`                int(11)         NOT NULL    AUTO_INCREMENT,
    `teacher_gender`            enum('female', 'male'),
    `teacher_date_of_birth`     date,
    `teacher_firstname`         varchar(100)    NOT NULL,
    `teacher_lastname`          varchar(100)    NOT NULL,
    `teacher_address`           varchar(100)    NOT NULL,
    `teacher_zipcode`           varchar(10)     NOT NULL,
    `teacher_city`              varchar(100)    NOT NULL,
    `teacher_county`            varchar(100),
    `teacher_country`           varchar(100),
    `teacher_email`             varchar(254)    NOT NULL,
    `teacher_phone_no`          varchar(10)     NOT NULL,
    `teacher_hire_date`         date,
    `teacher_wp_username`       varchar(60),
    PRIMARY KEY (`teacher_id`),
    UNIQUE KEY (`teacher_email`),
    UNIQUE KEY (`teacher_wp_username`)
);

CREATE TABLE `wpda_sas_module` (
  `module_id`           int(11)         NOT NULL    AUTO_INCREMENT,
  `module_name`         varchar(50)     NOT NULL,
  `module_description`  varchar(4000)   NOT NULL,
  PRIMARY KEY (`module_id`),
  UNIQUE KEY (`module_name`)
);

CREATE TABLE `wpda_sas_course` (
  `course_id`           int(11)         NOT NULL    AUTO_INCREMENT,
  `course_start_date`   date            NOT NULL,
  `module_id`           int(11)         NOT NULL,
  `teacher_id`          int(11)         NOT NULL,
  PRIMARY KEY (`course_id`)
);

CREATE TABLE `wpda_sas_class` (
  `course_id`   int(11)     NOT NULL,
  `student_id`  int(11)     NOT NULL,
  PRIMARY KEY (`course_id`, `student_id`)
);

CREATE TABLE `wpda_sas_grade` (
  `course_id`   int(11)     NOT NULL,
  `student_id`  int(11)     NOT NULL,
  `grade_date`  date        NOT NULL,
  `grade_name`  varchar(30)  NOT NULL,
  `grade`       varchar(6)  NOT NULL,
  PRIMARY KEY (`course_id`, `student_id`, `grade_date`, `grade_name`)
);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
