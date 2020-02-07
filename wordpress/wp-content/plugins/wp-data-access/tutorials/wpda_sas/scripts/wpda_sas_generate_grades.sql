/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Generate grades for table `wpda_sas_grade` up to current date
--
INSERT INTO `wpda_sas_grade` (`course_id`, `student_id`, `grade_date`, `grade_name`, `grade`)
select 
  course_id
, student_id
, date_add(course_start_date, interval 4 day)
, 'EXAM'
, 3+ceil(rand()*7)
from wpda_sas_student_status
where course_start_date < case when weekday(now()) < 5 then now()-7 else now() end;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
