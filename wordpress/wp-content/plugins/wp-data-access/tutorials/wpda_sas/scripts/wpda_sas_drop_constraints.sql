/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

select concat('alter table ', table_name, ' drop foreign key ', constraint_name, ';')
from information_schema.table_constraints 
where table_name like 'wpda_sas%'
and constraint_type = 'FOREIGN KEY'
into outfile '/var/tmp/wpda_sas_drop_constraints.sql';

source /var/tmp/wpda_sas_drop_constraints.sql;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
