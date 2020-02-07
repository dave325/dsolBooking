/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE VIEW wpda_sas_course_lookup AS
SELECT wpda_sas_course.course_id
, wpda_sas_module.module_name
FROM wpda_sas_course
LEFT OUTER JOIN wpda_sas_module ON wpda_sas_course.module_id = wpda_sas_module.module_id;

CREATE VIEW wpda_sas_student_status AS
SELECT wpda_sas_student.*
, wpda_sas_module.*
, wpda_sas_course.course_id
, wpda_sas_course.course_start_date
, wpda_sas_teacher.*
, wpda_sas_grade.grade_date
, wpda_sas_grade.grade_name
, wpda_sas_grade.grade
FROM wpda_sas_student
LEFT OUTER JOIN wpda_sas_class ON wpda_sas_student.student_id = wpda_sas_class.student_id
LEFT OUTER JOIN wpda_sas_course ON wpda_sas_class.course_id = wpda_sas_course.course_id and wpda_sas_student.student_id = wpda_sas_class.student_id
LEFT OUTER JOIN wpda_sas_module ON wpda_sas_course.module_id = wpda_sas_module.module_id
LEFT OUTER JOIN wpda_sas_teacher ON wpda_sas_teacher.teacher_id = wpda_sas_course.teacher_id
LEFT OUTER JOIN wpda_sas_grade ON wpda_sas_grade.course_id = wpda_sas_class.course_id and wpda_sas_grade.student_id = wpda_sas_student.student_id
order by module_name, grade_name, grade_date;

CREATE VIEW wpda_sas_student_courses AS
SELECT wpda_sas_student.*
, wpda_sas_module.*
, wpda_sas_course.course_id
, wpda_sas_course.course_start_date
, wpda_sas_teacher.*
FROM wpda_sas_student
LEFT OUTER JOIN wpda_sas_class ON wpda_sas_student.student_id = wpda_sas_class.student_id
LEFT OUTER JOIN wpda_sas_course ON wpda_sas_class.course_id = wpda_sas_course.course_id and wpda_sas_student.student_id = wpda_sas_class.student_id
LEFT OUTER JOIN wpda_sas_module ON wpda_sas_course.module_id = wpda_sas_module.module_id
LEFT OUTER JOIN wpda_sas_teacher ON wpda_sas_teacher.teacher_id = wpda_sas_course.teacher_id;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
