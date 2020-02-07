### ChangeLog for WP Data Access

#### 3.0.0 / --- under construction ---

Fixed: Column name in DataTable class gone 
Added: Action hook 'wpda_extend_list_table' to support list table extension 
Added: Filter wpda_column_default to influence column layout in list tables
Added: New media types to Data Publisher
Fixed: Function wpdadiehard_convert_to_screen() not found (form post #12362969 - charlesnguyen)
Added: Full remote database support to Data Backup
Fixed: Search context lost on web page
Added: Support for $$USER$$ and $$USERID$$ to Data Publisher (form post #12352968 - Dozen)
Added: Use second, third, nth column in lookup to build where clause (change request - mieke van kooten)
Added: Support for media files of type video (play inline)
Added: Support for media files of type music (play inline) (form post #12258885 - dizwell)
Fixed: Media files not shown correctly after upload from WordPress media page
Fixed: Data Publisher gives an error when "Allow paging?" = NO
Added: Text columns are now shown in a textarea (multi line) instead of an input element
Added: Info to the Data Publisher how to authorise tables
Added: Allow registration only > project page > allow insert = only (no list table, update, delete, import)
Added: Allow Data Project to insert only (no update, delete, import)  (website comment database administration - jeffrey turner)
Added: Allow functions to be used in Data Publisher advanced table options (forum support #12332715 - marcellein)
Fixed: Layout messes up when using multiple columns in a relationship
Added: Filter to user search (WPDA_List_Table->construct_where_clause) (forum support #12315718 - charlesgodwin)
Added: Allow to hide plugin menu in dashboard (does not hide data projects)
Changed: Moved plugin settings page to dashboard settings menu
Added: Context sensitive help to plugin pages
Removed: Plugin help from menu
Added: Manage remote databases from Plugin Settings
Added: Full remote database support to Data Designer
Added: Test publication directly from Data Publisher main page
Added: Copy publication link to Data Publisher (form post #12275882 - dizwell)
Fixed: Change responsive type "collaped" to "collapsed" (form post #12275882 - dizwell)
Added: Screen options now also available in Data Projects
Fixed: WHERE Clause in Data Publisher only works with equals (=) (form post #12301684 - philippkaiser)
Fixed: DB_NAME in wp-config.php does not match real database name (lower_case_table_names = 1)
Fixed: Screen options not working correctly
Changed: Improved layout test frame Data Publisher
Added: Settings, review and donation links to plugin description
Fixed: Do not use offset and limit if serverSide is false
Fixed: Invalid Dropbox key
Added: Full remote database support to Data Projects
Added: Full remote database support to Data Publisher
Added: Full remote database support to Plugin Settings pages
Added: Full remote database support to Data Explorer
Added: Manage remote databases from Data Explorer
Changed: Menu item shows error page if repository table not found (instead of hiding menu item)
Added: On drop table delete all table settings from repository (labels, media columns, menus) 

#### 2.7.3 / 2019-12-18

Added: Help info to advanced table options
Added: Customize Datatables shortcode - adding standard and advanced options (form post #12236372 - rswebmaster)
Fixed: Can’t seem to change the number of rows initially displayed (form post #12247152 - dizwell)
Fixed: Cannot drop view in another database
Changed: Listbox behaviour responsive output
Fixed: Select listbox in Data Publisher not working correctly.
Added: Default where and order by to child table (table options) (form post #12232151 - khansadi)
Changed: Switched to new Dropbox app "WP Data Access Box"
Added: Sort on multiple columns in Data Publisher (form post #12226580 - spounch)
Changed: Layout simple form items to save space
Changed: Decreased parent area on Data Projects pages (remove title + add less/more button)
Added: External database support to WordPress media library columns
Added: External database support to data menus
Added: External database support to table settings
Added: External database support to shortcode wpdadiehard
Added: External database support to Data Projects
Added: Table access control for external databases to Data Explorer
Added: Table access control for external databases to plugin backend settings
Fixed: Disabled select and format columns buttons in Data Publisher in view mode
Changed: Removed WordPress table access options from Front-end Settings for external databases
Fixed: Some buttons and actions available in Data Designer for WP tables and view mode
Fixed: Added new line to end of export file to prevent error when importing as ZIP file  
Added: Create database from Data Explorer main page (forum post #11706835)
Added: Drop database from Data Explorer main page (forum post #11706835)
Fixed: Manage link in Data Explorer not working with system views
Fixed: Sort not working without default order by (support forum #1219867 - @ssamyn)

#### 2.7.2 / 2019-11-29

Added: Cookie settings (plugin settings page) to allow keeping cookies when switching panels
Added: Arguments added to shortcode [wpdataaccess] database, sql_where, sql_orderby
Added: Default WHERE/ORDER BY to publication (support forum #11907073 - @Gbade)
Removed: Settings tab and alter table button from Data Explorer when connected to other database
Added: Connect to other databases from Data Publisher (forum support #11706835 - steveediger)
Added: Internationalisation to Data Publisher front-end (data publisher settings page) (forum support #12181966 - ssamyn)
Updated: All html script tags to use text instead of language attribute
Updated: Menu item link to plugin help
Added: Allow shortcode access in posts and pages (plugin settings page)
Added: Support custom date and time formats (plugin settings page) (form post #12123210 - dmnauta)
Fixed: List tables not supporting responsive mode (forum support #12146070 - dsbking)
Fixed: Tabpage not responding (forum support #12123137 - dmnauta)
Added: Allow to export view to XML, JSON, Excel and CSV (forum support #12131944 - dsbking)
Fixed: Listboxes not working correctly in Safari (forum support #12114671 - sander zumbrink)

#### 2.7.1 / 2019-10-10

Fixed: Do not show version update notification when page called from shortcode
Fixed: Ask user for confirmation on copy table options set
Fixed: Make text "back to list" more specific on parent/child pages
Fixed: User should confirm when pressing the Reconcile Table button 
Fixed: Error on lookup if item value is null
Fixed: Warning creating default object from empty value when entering tab_label first time
Fixed: Plugin table Settings not used if no table options found for Data Projects table
Fixed: Column labels are not taken into account in exports (CSV and Excel)
Fixed: Role selection in Data Menus should show the role label not the role
Fixed: Role selection in Data Projects should show the role label not the role
Fixed: Cannot change Options Set Name  (forum support #12099274 - mieke van kooten)
Fixed: Shortcode wpdadiehard returns an error if convert_to_screen is already declared (forum support #12084970 - kirkgroome)

#### 2.7.0 / 2019-10-31

Added: Support role checking in shortcode wpdadiehard (data management on web pages)
Added: CSS class to DataTables (class name = database column name)
Fixed: Move back to list after adding an existing record for an n:m relationship
Fixed: Updated for WordPress 5.3 and 5.4
Fixed: Changed wp_die call to work properly in WordPress 5.4
Fixed: Data Designer listboxes not showing correctly in WordPress 5.4
Fixed: Width select item not showing correctly in WordPress 5.4
Fixed: Generated HTML media listbox wrong format 
Fixed: Input item of type text not showing correctly in WordPress 5.4
Added: Copy table options to new set
Added: Support for multiple table options sets
Fixed: Cannot use lookup in list table as first column
Moved: WPDA_Design_Table_Model and WPDP_Project_Design_Table_Model to Plugin_Table_Models
Fixed: PHP error for incorrect n:m relationship
Removed: Media columns from Data Projects (media columns now supported in table settings only)
Removed: Media columns from Data Publisher (media columns now supported in table settings only)
Added: Dynamic hyperlink to list table (review wmuskie | forum support #12038786 - OriOn)
Added: Button "Add New" child record always visible (website comment known limitations - mieke van kooten)
Changed: Label SHOW MORE/LESS button
Added: $$USERID$$ environment variable (forum support #12022533 - docwatsons)
Added: WordPress role management to allow multiple rows per user
Fixed: Select/deselect all rows for bulk actions not working for shortcode wpdadiehard
Changed: Rename Data Projects menu slug and file names from wpdp to wpda
Added: Export table settings with table (SQL export only - selectable)
Fixed: Page type table using wrong classes in shortcode wpdadiehard
Removed: Project ID column from Data Project page list tables
Added: Show shortcode action Data Projects page list table
Changed: Column order on Data Projects page
Fixed: Disable autocomplete for data/time columns
Fixed: $$USER$$ filter not working in shortcode (forum support #012022533 - docwatsons)
Fixed: Plugin backup tables not deleted on plugin removal
Fixed: On plugin activation backup plugin tables only for a new version

#### 2.6.1 / 2019-01-04

Fixed: Data type attribute not taken into account in Data Designer
Fixed: jQuery DataTables auto width calculation removed
Changed: Renamed Data Projects table prefix from wpdp to wpda
Removed: Const OPTION_WPDA_PREFIX (no functionality)
Removed: Const OPTION_WPDA_NAME (never used)
Fixed: Table wp_wpda_table_settings not removed on uninstall (forum support #11970313 - soprano)
Fixed: Tab labels not set correctly when using shortcode
Fixed: Message box not shown when using shortcode
Fixed: jQuery datetimepicker not available when using shortcode
Fixed: $wp_user->data->user_login not set for anonymous user (no login) 
Fixed: Add New button shown when parent-child form in view mode
Fixed: Two back buttons show in view mode for child rows
Fixed: Delete action available for child rows even in view mode
Fixed: Column ordering in Data Projects not using table options
Fixed: Error $actions is not an array if batches are disabled 
Fixed: Button SHOW LESS/SHOW MORE not shown on web page
Added: Action hook 'wpda_extend_simple_form' to support form extension 
Changed: Allow plugin folder dir to be overwritten to improve support for inheritance
Changed: Reference self to static to improve support for inheritance
Changed: Cursor type when dragging and dropping element

#### 2.6.0 / 2019-09-18

* Updated: Dutch language translation
* Added: Quick tours Data Publisher and Data Projects (support forum #11794759 - merlinsilk)
* Changed: Menu item Plugin Help opens external public page in new tab/window
* Changed: Moved documentation to public website
* Fixed: Numeric fields do not allow negative values (support forum #11892289 - wpsd2006)
* Changed: Disabled media column selection in Data Projects (moved to Data Explorer)
* Fixed: Cannot delete page from Data Project (support forum - #11889053 - wpsd2006)
* Changed: Default column label to first letter upper and rest lower for every word in label
* Fixed: Confirm delete backup tables
* Changed: Disabled media column selection in Data Publisher (moved to Data Explorer)
* Added: Message if button Select is clicked in Data Publisher on insert (need to save first)
* Fixed: Button Format Columns not working if format column is empty
* Removed: Test publication link from Data Publisher list table
* CLEANUP: Rewritten all plugin table models to use one base class
* Added: Table model for plugin table wp_wpda_table_settings
* Added: Plugin settings table to store table related settings
* Changed: Simplified Data Menus structure
* Moved: Data Menus to Data Explorer main page
* Moved: Manage Media to Data Explorer main page
* Moved: Data Backup menu to Data Explorer main page
* Added: Filter parameter to shortcode wpdadiehard (support forum #11844079 - tritongr)
* Fixed: Error on populating listbox when no tables selected in front-end settings (support forum #11844474 - rllopez66)
* Fixed: Column labels Manage Media list table not correctly defined
* Added: Plugin table and column settings to Data Explorer (work in progress)
* Added: Date / Time picker to data entry forms
* Fixed: Debian/MySQL8 sys table columns unordered without ordinal_position (support form #11820996 - jblakely)
* Fixed: Debian/MySQL8 sys table columns in uppercase without alias (support form #11820996 - jblakely)
* Added: Foreign keys to Table management on Data Explorer main page
* Fixed: Error message on duplicate key (support form #1179998 - Merlin Silk)

#### 2.5.1 / 2019-08-13

* Fixed: Font on web pages changed after updating to 2.5.0 (support forum #11814585 - bwhitemm and olbweb)
* Added: Data Project pages are now available on web pages using shortcode 'wpdadiehard'

#### 2.5.0 / 2019-08-02

* Removed: Bootstrap scripts and styles
* Changed: Scripts and styles for shortcodes only loaded when needed
* Changed: Scripts and styles for jQuery DataTables only loaded when needed
* Added: Data management on web page (forum post #11694569)
* Added: Use user defined title in project CRUD forms
* Added: Support for column labels to Data Publisher
* Added: Support for images to Data Publisher (forum post #11658244 - kentauron)
* Added: Support for media items (forum post #11658244 - kentauron)
* CLEANUP: Moved validation check to Simple_Form_Item (and sub classes)
* CLEANUP: Removed JS templates to support older browsers
* Changed: Updated JS/CSS versions for bootstrap, datatables and datatables responsive
* Added: Use unique index for row actions if no primary key is defined in Data Explorer
* Added: Drag and drop columns in Data Designer and Data Projects > Manage Table Options
* CLEANUP: Allow sub classes of WPDA_Simple_Form_Item to handle specific column types

#### 2.0.15 /  2019-07-08

* Added: Video tutorials for the Data Publisher tool
* Added: Set WordPress username as default user $$USER$$ (support topic #11656471 - kentauron)
* Fixed: Cascading delete on parent performs delete on child views
* Fixed: Auto increment field shown as key=no and mandatory=no in Data Projects
* Added: Show less/more button to parent form
* Fixed: MariaDB 10.2.7 and higher handles default values different than other DBMSs (support topic #11675290 - smolenaar)
* Fixed: Join USING not correctly handled on CentOS 7 MariaDB 10.3 (create project error finally solved!)
* Added: Data Publisher tool (supports generation of shortcodes)
* Removed: Shortcode button from visual editor
* Fixed: Check if auto increment column is false (create project error?)
* Fixed: Do not add auto increment column to insert (create project error?)
* Added: Value for sql_mode to system info
* Fixed: Updating failed error when saving a page that uses the plugin shortcode
* Fixed: Label for primary key columns not showing correctly in project list tables
* Added: Button to remove old backup tables (Manage Plugin > Manage Repository)
* Fixed: Allow insert/delete not working for project pages
* CLEANUP: Remove deprecated options
* CLEANUP: Replace nobr tags with span + nobr class
* CLEANUP: Language translation support
* CLEANUP: Source code documentation
* CLEANUP: API documentation
* CLEANUP: Source code reformatted to WordPress standards

#### 2.0.14 /  2019-06-08

* Changed: Import from Data Explorer main page is always allowed (admin user)
* Added: Data Designer integrated with Data Explorer (alter table and indexes directly from Data Explorer) 
* Fixed: Cannot enter html characters in Simple Form text fields (support topic 11562559 - leouesb)
* Added: Export from Data Explorer table page to XML, JSON, Excel and CSV  (support topic 11565221 - rswebmaster)
* Fixed: Error on delete parent when parent has lookups defined
* Added: Reconcile button to Data Designer
* Added: (re)Create index button to Data Designer
* Added: Alter table button to Data Designer
* Added: Drop index button to Data Designer
* Added: Drop table button to Data Designer
* Added: Show alter table script button to Data Designer
* Added: Show create table script button to Data Designer
* Added: Allow to show/hide deleted columns (compared with database table)
* Added: Highlight new, deleted and modified columns in Data Designer
* Added: Listbox to Data Backup to enable viewing all scheduled WordPress jobs
* Added: Data Backup button to Data Explorer header
* Changed: Uniform layout and behaviour for all buttons and links in page titles 
* Changed: Import title and info text (checks if zip upload is allowed)  
* Fixed: Export to csv deletes double quotes in text

#### 2.0.13 / 2019-05-17

* Fixed: Database name containing minus character leads to query errors (support topic 11540179 - Prause)
* Added: Export tables from Data Explorer to SQL (with(out) WP prefix), XML, json, Excel, csv files (support topic 11533487 - rswebmaster)

#### 2.0.12 / 2019-05-14

* Updated: Plugin help pages
* Added: Video tutorial to install the demo app
* Fixed: Search on table with no search columns should show no rows
* Fixed: Cannot search on lookup items
* Fixed: Sorting on lookup columns is not possible (removed header link from table list)
* Added: Check if file_uploads = On before upload (disable file upload if file_uploads = Off) 
* Fixed: Not correctly jumping back to list table source page after "Add Existing" > "search"
* Fixed: Data Explorer main page shows all tables on show favourites only no favourites defined
* Fixed: Export and Data Backup fail when memory_limit is too small
* Added: Check file size against upload_max_filesize before uploading imnport file
* Changed: Import now using streams to better support large files
* Changed: Export and Data Backup now using streams to better support large files
* Added: Log table to "Manage Repository" and "System Info"
* Changed: Export procedure now writes seperate insert statement for every row
* Fixed: Export/import procedures non WP schema performed on WP schema
* Added: System info tab to improve and simplify plugin support and communication

#### 2.0.11 / 2019-04-30

* Fixed: After editing a data record user always returns to page 1 (support topic 11476140 - Hannes - Decentris)
* Fixed: Cannot add new page to project (support topic 11477423 - fendervr)
* Added: Drop logging table on uninstall
* Added: Possibility to save repository backup tables during a plugin update
* Changed: Simplified repository (re)creation to decrease the possibility of failure
* Fixed: Export files writes {wp_prefix}_ instead of {wp_prefix}
* Fixed: View only list tables should not allow delete bulk actions
* Fixed: Cannot search in list of values (search is performed on main list table)
* Fixed: Site blocked after unattended plugin update (support topic 11472418 - tjgorman) (patched version 2.0.10)
* Fixed: Class 'WPDataProjects\List_Table\WPDP_List_Columns_Cache' not found (patched version 2.0.10)
* Fixed: Plugin table array removed from table cache (patched version 2.0.10)

#### 2.0.10 / 2019-04-25

* Changed: Moved all security checks from menu preparation to page preparation
* Added: Data Backup now supports unattended (background/no browser) adhoc backups (support topic 11466155 - stevekatasi)
* Changed: Improved and simplified Data Backup procedure
* Fixed: Added WordPress database schema and plugin tables to cache (support topic 11461930 - stevekatasi)
* Fixed: Added cache to list column classes to increase database performance (support topic 11461930 - stevekatasi)
* Fixed: Optimized class WPDP_List_Table_Lookup due to bad performance issue (support topic 11461930 - stevekatasi)
* Fixed: Create table menu items fails for MySQL 5.6 and prior (support topic 11461174 - rswebmaster)
* Added: Demo project (app) WPDA_SAS - School Administration System
* Added: Code example how to use WP Data Access classes in PHP plugin code
* Fixed: Default and list-values imported without single quotes on Reverse Engineering (support topic 11423815 - Hannes - Decentris)
* Fixed: Data Projects export not working in FireFix (support topic 11429499 - Hannes - Decentris)
* Fixed: Submenus of data apps not shown correctly for roles null or empty string
* Changed: Export tables with variable wpdb prefix to support import into repository with different wpdb prefix
* Fixed: Set data type not handled correctly in the Data Designer (support topic 11423815 - Hannes - Decentris)
* Added: Explain how to define enum and list type in the Data Designer (support topic 11423815 - Hannes - Decentris)
* Fixed: Added latest version of WP_List_Table to project to reclaim navigation buttons
* Fixed: Submenus of data apps not shown correctly for roles other than administrator
* Fixed: WP table prefix not taken into account (support topic 11411195 - Hannes - Decentris)
* Fixed: Key column labels not displayed correctly in table list
* Added: A listbox is generated for lookup items in data entry forms  
* Added: It is now possible to add a lookup column to a table list 
* Added: Disable relationship and data entry form config for views and tables without a primary key (Data Projects)
* Added: Allow views and tables without a primary key to be used (Data Projects)
* Added: Allow to create relationships between tables and views (Data Projects)
* Added: Table type (TABLE,VIEW) to WPDA_Design_Table_Model (WPDP_Project_Design_Table_Model inherited)
* Fixed: Import script containing multiple SQL statements failed on Windows (using \r\n) 

#### 2.0.8 / 2019-02-05

* Added: Video tutorial to explain how to create many to many relationships in Data Projects
* Changed: Static content not correctly filtered
* Added: Make username accessible in where clause of project list tables
* Added: Where clause to project list tables to influence selection (parent only)
* Added: Support for MySQL set data type (listbox handling multiple selections)
* Added: Role (multiple) to data project pages to give non admin users access to data apps
* Changed: Content in list table wrapped (request from Enterprise Branding) 
* Changed: What's new message now shown on all plugin pages
* Changed: Dropbox path now updatable
* Changed: Add / at the end of the backup folder name if not entered

#### 2.0.7 / 2019-01-27

* Added: Data backup tool to automatically backup table data to a local folder or Dropbox folder

#### 2.0.6 / 2018-12-16

* Added: Check number max size and precision in data entry forms
* Added: "Add New" button for parent in parent-child pages
* Added: Show list of available tables in data entry form for project>pages
* Fixed: Data Explorer manage table tabs not working correctly with multiple windows 
* Changed: Allow to hide primary key columns in data entry forms
* Changed: Allow to hide primary key columns in table list
* Fixed: Hide columns not working in all data entry forms
* Fixed: Data Project table page: mode, title and subtitle not taken into account

#### 2.0.5 / 2018-12-14

* Removed /themes/smoothness/jquery-ui.css from plugin admin class (shortcode button not working)
* Added: New screenshots to WordPress Plugin Directory
* Fixed: Export not working when "ask for confirmation when starting export" in settings is checked
* Changed: Tabs in list table (table actions) not working in Internet Explorer
* Changed: Links in list table not working in Internet Explorer

#### 2.0.4 / 2018-12-11

* Changed: Plugin description in WordPress Plugin Directory
* Changed: Layout of the manage table window

#### 2.0.3 / 2018-12-05

* Added: Optimize table from Data Explorer > manage table > actions tab
* Added: Hint user if table optimization should be considered
* Changed: Data menus was moved to Data Projects > Manage Dashboard Menus
* Added: Columns data size, index size and overhead to Data Explorer main page
* Added: Hide columns on Data Explorer mainpage
* Changed: Changed to order of the tabs in the manage table/view window
* Changed: Replaced icon to manage table of view with standard WordPress listtable link
* Changed: Changed import button text and labels for better understanding of import functionality
* Added: Video tutorial to explain how to create one to many relationships in Data Projects

#### 2.0.2 / 2018-12-03

* Fixed: Removed subtitle from Data Designer and Data Menus list
* Added: What's new page to inform users about new features
* Added: First video tutorial to explain Data Projects tool

#### 2.0.1 / 2018-11-27

* Fixed: Null values not exported correctly
* Fixed: Do not allow to hide mandatory columns in data entry forms

#### 2.0.0 / 2018-11-09

* Added: Data Projects to plugin
  * Create WordPress Data Apps
  * Add app to dashboard menu
  * Supports static pages
  * Supports CRUD pages
  * Supports parent/child pages
* Added: Documentation to plugin menu
* Fixed: Repository activation error
* Stopped: Website redirected to WordPress Plugin Directory

#### 1.6.9 / 2018-03-20

* Fixed: Bulk actions not executed due to fix in 1.6.7 on favourites change
* Added: Show MySQL error when create table fails
* Changed: Prepared WPDA_Design_Table_Model to support transparent structures

#### 1.6.8 / 2018-03-17

* Changed: Added new screenshots
* Fixed: Missing check unique column names and index names
* Fixed: Delete index in Data Designer not working
* Changed: Default mode Data Designer changed to advanced

#### 1.6.7 / 2018-03-16

* Fixed: Switch to editing mode after create table/index in Data Designer
* Fixed: Prevent bulk selections being executed on favourites change
* Fixed: Multiple alerts on invalid bulk drop or truncate selection

#### 1.6.6 / 2018-03-16

* Added: Copy table (including/excluding data)
* Added: Rename table/view
* Changed: Simplified usage of table/view/index actions from Data Explorer

#### 1.6.5 / 2018-03-15

* Added: Drop index from Data Explorer

#### 1.6.4 / 2018-03-14

* Fixed: Column 'Unique?' on 'Indexes' tab of Data Explorer always showing 'No'

#### 1.6.3 / 2018-03-14

* Fixed: Create table not working

#### 1.6.2 / 2018-03-01

* Fixed: Action button issues
* Fixed: Ask for confirmation on bulk-drop and bulk-truncate
* Fixed: Schema issues

#### 1.6.1 / 2018-03-01

* Added: Allow ZIP file imports to support larger import files (uses ZipArchive)

#### 1.6.0 / 2018-02-15

* Added: Create tables in basic or advanced mode (switch between modes)
* Added: Allow data and database administration of other schemas
* Added: Import table(s) button to Data Explorer (allows multiple imports)

#### 1.5.2 / 2018-02-06

* Added: Check every request for plugin updates (compare db version with plugin version)  

#### 1.5.1 / 2018-02-03

* Added: Check #Rows ( perform count if #Rows < WPDA::OPTION_BE_INNODB_COUNT )

#### 1.5.0 / 2018-01-23

* Added: Engine field to Data Explorer
* Added: Number of records field to Data Explorer
* Added: Drop and bulk drop for views (accessible through icon in Data Explorer)
* Added: Bulk drop and bulk truncate for tables (accessible through icon in Data Explorer)
* Added: View table/view structure (accessible through icon in Data Explorer)
* Added: Option to backend settings to get default search value functionality (forget search value)
* Added: Support for parent detail navigation
* Added: Added argument 'allow_import' to WPDA_List_Table to hide import button
* Changed: Always show page 1 on new search 
* Changed: Improved layout Simple Form
* Changed: Hide button 'Back To List' in view mode
* Removed: Menu WP Data Tables (replaced by favourites menu)
* Fixed: Current page selector not working
* Fixed: Check max length for input (attribute maxlength)
* Fixed: On expanding favourites table name not shown
* Fixed: Remember search value after navigating to details
* Fixed: WPDA_List_Table::construct_where_clause() not respecting values already in $this->where 
* Fixed: Searching in favourites not working
* Fixed: Disable only form items in view mode
* Fixed: Argument 'show_view_link' has no effect
* Fixed: Argument 'allow_insert' has no effect
* Fixed: Back button in list table when called from data explorer or favourites

#### 1.2.1 / 2018-01-14

* Fixed: Skip empty index on create table
* Fixed: Data entry form should showing CURRENT_TIMESTAMP as default value
* Fixed: Bulk checkboxes shown without bulk actions (tables export disabled)
* Fixed: List table favourites not showing labels when empty

#### 1.2.0 / 2018-01-13

* Fixed: Recognize missing wp_wpda_table_design
* Fixed: Single file for every alter table stetement (wp_wpda_table_design) 
* Added: Add tables to favourites (WP Data Tables still in menu but will be removed soon)

#### 1.1.1 / 2018-01-13

* Fixed: Create table wp_wpda_table_design (older versions of mysql not supporting timestamp)
* Fixed: Hidden columns array returns false

#### 1.1.0 / 2018-01-09

* Added: Data Designer
    * Design tables and indexes
    * Create tables and indexes from design
* Added: Drop table (from list table)
* Added: Truncate table (from list table)
* Fixed: Recognize all WordPress tables (single and multisite)
* Fixed: Link 'export' not showing in Data Explorer

#### 1.0.0 / 2017-12-04

* Fixed: I can’t add table to menu (2017-12-29)
* Fixed: Activating the plugin affects styles on the front page (2017-12-29)
* Fixed: Sanitization error (2017-12-29)
* Initial commit
