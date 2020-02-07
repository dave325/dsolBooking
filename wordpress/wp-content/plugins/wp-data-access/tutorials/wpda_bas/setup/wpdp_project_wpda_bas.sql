INSERT INTO `{wp_prefix}wpda_project` (`project_name`, `project_description`, `add_to_menu`, `menu_name`, `project_sequence`) VALUES
('WPDA BAS','WPDA Bike Administration System','Yes','WPDA BAS',1);
SET @PROJECT_ID = LAST_INSERT_ID();

INSERT INTO `{wp_prefix}wpda_project_page` (`project_id`, `add_to_menu`, `page_name`, `page_type`, `page_table_name`, `page_mode`, `page_allow_insert`, `page_allow_delete`, `page_content`, `page_title`, `page_subtitle`, `page_role`, `page_where`, `page_sequence`) VALUES
(@PROJECT_ID,'Yes','Bikes','table','wpda_bas_bike','edit','yes','yes',0,'Bike Administration','','administrator','',0);
