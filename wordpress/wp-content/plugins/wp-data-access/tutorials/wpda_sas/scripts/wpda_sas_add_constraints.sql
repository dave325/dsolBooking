/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

ALTER TABLE `wpda_sas_course`
    ADD FOREIGN KEY `fk_course_module`(`module_id`)
    REFERENCES `wpda_sas_module`(`module_id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION;
ALTER TABLE `wpda_sas_course`
    ADD FOREIGN KEY `fk_course_teacher`(`teacher_id`)
    REFERENCES `wpda_sas_teacher`(`teacher_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION;

ALTER TABLE `wpda_sas_class`
    ADD FOREIGN KEY `fk_class_course`(`course_id`)
    REFERENCES `wpda_sas_course`(`course_id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION;
ALTER TABLE `wpda_sas_class`
    ADD FOREIGN KEY `fk_class_student`(`student_id`)
    REFERENCES `wpda_sas_student`(`student_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION;

ALTER TABLE `wpda_sas_grade`
    ADD FOREIGN KEY `fk_grade_course`(`course_id`)
    REFERENCES `wpda_sas_course`(`course_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION;
ALTER TABLE `wpda_sas_grade`
    ADD FOREIGN KEY `fk_grade_student`(`student_id`)
    REFERENCES `wpda_sas_student`(`student_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION;

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
