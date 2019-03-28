<?php
/*
Plugin Name: D Solutions Booking Plugin
Plugin URI: https://github.com/dave325/dsolBooking
Description: Figure it out
Version: 1.0.0
Author: David Solutions 
Author URI: http://dataramsolutions.com
License: GPLv2 or later
Text Domain: dsol-booking
*/
global $dsol_booking_version;
$dsol_booking_version = "1";

define( 'DSOL_BOOKING_PATH', plugin_dir_path( __FILE__ ) );

/**
 * Change init functions and class
 */
register_activation_hook( __FILE__, array( 'DsolBookingPluginHooks', 'on_activate' ) );
register_deactivation_hook( __FILE__, array( 'DsolBookingPluginHooks', 'on_deactivate' ) );
register_uninstall_hook( __FILE__, array( 'DsolBookingPluginHooks', 'on_uninstall' ) );

add_action( 'init', 'myStartSession', 1);
add_action( 'wp_logout', 'myEndSession' );
add_action( 'init', 'my_script_enqueuer' );

#add_filter( 'the_content',  array( 'bookaroom_public', 'mainForm' ) );

add_action( 'admin_notices', array( 'DsolBookingPluginHooks', 'plugin_activation_message' ) ) ;

add_action( 'admin_menu', array( 'bookaroom_settings', 'add_settingsPage' ) );

#add_filter(		'gform_pre_render',			array( 'bookaroom_creditCardPayments', 'returnIncomingValidation' ) );
#add_action(		'gform_after_submission',	array( 'bookaroom_creditCardPayments', 'finishedSubmission' ));

function my_script_enqueuer() {
	// Prefix later on
	add_shortcode( 'meetingRooms',	array( 'bookaroom_public', 'mainForm' ) );
	add_shortcode( 'profile',	array( 'bookaroom_company_profile', 'showBookings' ) );
	$width = get_option( 'bookaroom_screenWidth' );

	if( !empty( $width ) || $width == 1 ) {
		wp_enqueue_style( 'book-a-room-style', plugin_dir_url( __FILE__ ) . 'css/bookaroom_thin.css' );
	} else {
		wp_enqueue_style( 'book-a-room-style', plugin_dir_url( __FILE__ ) . 'css/bookaroom_day.css' );
	}
	
	global $bookaroom_db_version;
	
	wp_dequeue_script('jquery');
	wp_enqueue_style( 'jquery_ui_css', "https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" );
	wp_enqueue_script( 'bookaroom_js', plugins_url( 'book-a-room/js/jstree/jquery.jstree.js' ), false );
	wp_enqueue_script( 'jquery_ui', "https://code.jquery.com/ui/1.12.1/jquery-ui.min.js", 'jquery','',false );
	# languages
	load_plugin_textdomain( 'book-a-room', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 

}


class DsolBookingPluginHooks
# simple class for activating, deactivating and uninstalling plugin
{
    public static function on_activate( $dbOnly = false )
	# this is only run when hooked by activating plugin
    {
		
		global $wpdb;
		global $bookaroom_db_version;
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		# create table for branches
		$sql = "CREATE TABLE {$wpdb->prefix}dsol_booking_branches (
					branchID int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
					branchDesc varchar(64) CHARACTER SET latin1 NOT NULL,
					branchOpen_0 time DEFAULT NULL,
					branchOpen_1 time DEFAULT NULL,
					branchOpen_2 time DEFAULT NULL,
					branchOpen_3 time DEFAULT NULL,
					branchOpen_4 time DEFAULT NULL,
					branchOpen_5 time DEFAULT NULL,
					branchOpen_6 time DEFAULT NULL,
					branchClose_0 time DEFAULT NULL,
					branchClose_1 time DEFAULT NULL,
					branchClose_2 time DEFAULT NULL,
					branchClose_3 time DEFAULT NULL,
					branchClose_4 time DEFAULT NULL,
					branchClose_5 time DEFAULT NULL,
					branchClose_6 time DEFAULT NULL,
					PRIMARY KEY  (branchID)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;";
		dbDelta( $sql );
		

		# create table for closings
		$sql = "CREATE TABLE {$wpdb->prefix}bookaroom_closings (
				  closingID int(10) unsigned NOT NULL AUTO_INCREMENT,
				  reoccuring tinyint(1) NOT NULL DEFAULT '0',
				  type enum('date','range','special') NOT NULL,
				  startDay tinyint(3) unsigned DEFAULT NULL,
				  startMonth tinyint(3) unsigned DEFAULT NULL,
				  startYear smallint(5) unsigned DEFAULT NULL,
				  endDay tinyint(3) unsigned DEFAULT NULL,
				  endMonth tinyint(3) unsigned DEFAULT NULL,
				  endYear smallint(5) unsigned DEFAULT NULL,
				  spWeek tinyint(3) unsigned DEFAULT NULL,
				  spDay tinyint(3) unsigned DEFAULT NULL,
				  spMonth smallint(6) DEFAULT NULL,
				  spYear smallint(5) unsigned DEFAULT NULL,
				  allClosed tinyint(1) NOT NULL,
				  roomsClosed text,
				  closingName varchar(128) NOT NULL,
				  username varchar(128) NOT NULL,
				  changed timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				  PRIMARY KEY  (closingID)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;";

		dbDelta( $sql );

		# create table for event age selection
		$sql = "CREATE TABLE {$wpdb->prefix}bookaroom_eventAges (
				  ea_id int(11) NOT NULL AUTO_INCREMENT,
				  ea_eventID int(11) NOT NULL,
				  ea_ageID int(11) NOT NULL,
				  PRIMARY KEY  (ea_id),
				  KEY ea_eventID (ea_eventID,ea_ageID)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;";
		
		dbDelta( $sql );
		
		# create table for event category selection
		$sql = "CREATE TABLE {$wpdb->prefix}bookaroom_eventCats (
				  ec_id int(11) NOT NULL AUTO_INCREMENT,
				  ec_eventID int(11) NOT NULL,
				  ec_catID int(11) NOT NULL,
				  PRIMARY KEY  (ec_id),
				  KEY ec_eventID (ec_eventID,ec_catID)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;";
		
		dbDelta( $sql );
				
		# create table for event Age groups
		$sql = "CREATE TABLE {$wpdb->prefix}bookaroom_event_ages (
				  age_id int(11) unsigned NOT NULL AUTO_INCREMENT,
				  age_desc varchar(254) CHARACTER SET latin1 NOT NULL,
				  age_order int(10) unsigned NOT NULL,
				  age_active tinyint(1) NOT NULL DEFAULT '1',
				  PRIMARY KEY (age_id),
				  UNIQUE KEY age_id (age_id)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;";

		dbDelta( $sql );

		# create table for event Category groups
		#$table_name = $wpdb->prefix . "bookaroom_event_categories";
		
		$sql = "CREATE TABLE {$wpdb->prefix}bookaroom_event_categories (
				  categories_id int(11) unsigned NOT NULL AUTO_INCREMENT,
				  categories_desc varchar(254) NOT NULL,
				  categories_order int(10) unsigned NOT NULL,
				  categories_active tinyint(1) NOT NULL DEFAULT '1',
				  PRIMARY KEY  (categories_id)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;";

		dbDelta( $sql );
		
		# Create table for registrations
		$sql = "CREATE TABLE {$wpdb->prefix}bookaroom_registrations (
				  reg_id int(10) unsigned NOT NULL AUTO_INCREMENT,
				  reg_fullName varchar(255) COLLATE utf8_unicode_ci NOT NULL,
				  reg_phone varchar(34) COLLATE utf8_unicode_ci DEFAULT NULL,
				  reg_email varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
				  reg_notes text COLLATE utf8_unicode_ci,
				  reg_eventID int(11) NOT NULL,
				  reg_dateReg timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				  PRIMARY KEY (reg_id),
				  FULLTEXT KEY reg_fullName (reg_fullName)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;";
		
		dbDelta( $sql );
		
		# Create table for reservations		
		$sql = "CREATE TABLE {$wpdb->prefix}bookaroom_reservations (
				  res_id int(10) unsigned NOT NULL AUTO_INCREMENT,
				  res_created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				  ev_desc text NOT NULL,
				  ev_maxReg int(10) unsigned DEFAULT NULL,
				  ev_amenity text,
				  ev_waitingList int(11) DEFAULT NULL,
				  ev_presenter varchar(255) DEFAULT NULL,
				  ev_privateNotes text,
				  ev_publicEmail varchar(128) DEFAULT NULL,
				  ev_publicName varchar(255) DEFAULT NULL,
				  ev_publicPhone varchar(15) DEFAULT NULL,
				  ev_noPublish tinyint(1) NOT NULL DEFAULT '0',
				  ev_regStartDate timestamp NULL DEFAULT NULL,
				  ev_regType enum('yes','no','staff') DEFAULT NULL,
				  ev_submitter varchar(255) NOT NULL,
				  ev_title varchar(255) DEFAULT NULL,
				  ev_website varchar(255) DEFAULT NULL,
				  ev_webText varchar(255) DEFAULT NULL,
				  me_amenity text NOT NULL,
				  me_contactAddress1 varchar(128) NOT NULL,
				  me_contactAddress2 varchar(128) DEFAULT NULL,
				  me_contactCity varchar(64) NOT NULL,
				  me_contactEmail varchar(255) NOT NULL,
				  me_contactName varchar(128) NOT NULL,
				  me_contactPhonePrimary varchar(15) NOT NULL,
				  me_contactPhoneSecondary varchar(15) DEFAULT NULL,
				  me_contactState varchar(255) NOT NULL,
				  me_contactWebsite varchar(255) DEFAULT NULL,
				  me_contactZip varchar(10) NOT NULL,
				  me_desc text NOT NULL,
				  me_eventName varchar(255) NOT NULL,
				  me_nonProfit tinyint(1) NOT NULL DEFAULT '0',
				  me_numAttend smallint(5) unsigned NOT NULL,
				  me_notes text NOT NULL,
				  me_status set('pending','pendPayment','approved','denied','archived') NOT NULL DEFAULT 'pending',
				  me_salt varchar(40) NOT NULL,
				  me_creditCardPaid tinyint(1) DEFAULT NULL,
				  me_libcardNum varchar(64) DEFAULT NULL,
				  me_social tinyint(1) DEFAULT NULL,
				  UNIQUE KEY res_id (res_id),
				  FULLTEXT KEY ev_presenter (ev_presenter,ev_privateNotes,ev_publicEmail,ev_publicName,ev_submitter,ev_title,ev_website,ev_webText,ev_desc),
				  FULLTEXT KEY me_contactEmail (me_contactEmail,me_contactName,me_desc,me_eventName,me_notes)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;";
		dbDelta( $sql );

		# Create table for deleted reservations
		$sql = "CREATE TABLE {$wpdb->prefix}bookaroom_reservations_deleted (
				  del_id int(11) NOT NULL AUTO_INCREMENT,
				  del_changed timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
				  res_id int(10) unsigned NOT NULL,
				  res_created timestamp NULL DEFAULT NULL,
				  ev_desc text NOT NULL,
				  ev_maxReg int(10) unsigned DEFAULT NULL,
				  ev_amenity text,
				  ev_waitingList int(11) DEFAULT NULL,
				  ev_presenter varchar(255) DEFAULT NULL,
				  ev_privateNotes text,
				  ev_publicEmail varchar(128) DEFAULT NULL,
				  ev_publicName varchar(255) DEFAULT NULL,
				  ev_publicPhone varchar(15) DEFAULT NULL,
				  ev_noPublish tinyint(1) NOT NULL DEFAULT '0',
				  ev_regStartDate timestamp NULL DEFAULT NULL,
				  ev_regType enum('yes','no','staff') DEFAULT NULL,
				  ev_submitter varchar(255) NOT NULL,
				  ev_title varchar(255) DEFAULT NULL,
				  ev_website varchar(255) DEFAULT NULL,
				  ev_webText varchar(255) DEFAULT NULL,
				  me_amenity text NOT NULL,
				  me_contactAddress1 varchar(128) NOT NULL,
				  me_contactAddress2 varchar(128) DEFAULT NULL,
				  me_contactCity varchar(64) NOT NULL,
				  me_contactEmail varchar(255) NOT NULL,
				  me_contactName varchar(128) NOT NULL,
				  me_contactPhonePrimary varchar(15) NOT NULL,
				  me_contactPhoneSecondary varchar(15) DEFAULT NULL,
				  me_contactState varchar(255) NOT NULL,
				  me_contactWebsite varchar(255) DEFAULT NULL,
				  me_contactZip varchar(10) NOT NULL,
				  me_desc text NOT NULL,
				  me_eventName varchar(255) NOT NULL,
				  me_nonProfit tinyint(1) NOT NULL DEFAULT '0',
				  me_numAttend smallint(5) unsigned NOT NULL,
				  me_notes text NOT NULL,
				  me_status set('pending','pendPayment','approved','denied','archived') NOT NULL DEFAULT 'pending',
				  PRIMARY KEY  (del_id),
				  FULLTEXT KEY ev_presenter (ev_presenter,ev_privateNotes,ev_publicEmail,ev_publicName,ev_submitter,ev_title,ev_website,ev_webText,ev_desc)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;";
		
		dbDelta( $sql );
				
		# Create table for meeting room containers. This is the top 
		# level for rooms, and is used in case you have rooms that
		# can be made of two or more other rooms.
		$sql = "CREATE TABLE {$wpdb->prefix}bookaroom_roomConts (
				  roomCont_ID int(11) unsigned NOT NULL AUTO_INCREMENT,
				  roomCont_desc varchar(64) NOT NULL,
				  roomCont_branch int(11) NOT NULL,
				  roomCont_occ int(11) NOT NULL,
				  roomCont_isPublic tinyint(1) NOT NULL DEFAULT '1',
				  roomCont_hideDaily tinyint(1) NOT NULL DEFAULT '0',
				  PRIMARY KEY  (roomCont_ID)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;";

		dbDelta( $sql );

		# create tale for room container members (which rooms are
		# in each room container
		$sql = "CREATE TABLE {$wpdb->prefix}bookaroom_roomConts_members (
				  rcm_roomContID int(10) unsigned NOT NULL AUTO_INCREMENT,
				  rcm_roomID int(10) unsigned NOT NULL,
				  KEY rcm_roomContID (rcm_roomContID,rcm_roomID)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;";
		
		dbDelta( $sql );
		
		# Create table for meeting rooms		
		$sql = "CREATE TABLE {$wpdb->prefix}bookaroom_rooms (
				  roomID int(11) unsigned NOT NULL AUTO_INCREMENT,
				  room_desc varchar(64) NOT NULL,
				  room_amenityArr text COMMENT 'This is a CSV array of amenities',
				  room_branchID int(11) unsigned NOT NULL,
				  PRIMARY KEY  (roomID),
				  KEY roomID (roomID)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;";

		dbDelta( $sql );

		# Create table for times for each reservation
		$sql = "CREATE TABLE {$wpdb->prefix}bookaroom_times (
				  ti_id int(10) unsigned NOT NULL AUTO_INCREMENT,
				  ti_type enum('event','meeting') NOT NULL,
				  ti_extID int(10) unsigned NOT NULL,
				  ti_created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
				  ti_startTime timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
				  ti_endTime timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
				  ti_roomID int(10) unsigned DEFAULT NULL,
				  ti_extraInfo text NOT NULL,
				  ti_noLocation_branch int(11) DEFAULT NULL,
				  ti_attendance int(11) unsigned DEFAULT NULL,
				  ti_attNotes text NOT NULL,
				  PRIMARY KEY  (ti_id),
				  KEY ti_type (ti_type),
				  KEY ti_id (ti_id,ti_type,ti_extID),
				  KEY ti_extID (ti_extID),
				  KEY ti_startTime (ti_startTime)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;";
		
		dbDelta( $sql );
		
		# Create table for deleted times for each reservation	
		$sql = "CREATE TABLE {$wpdb->prefix}bookaroom_times_deleted (
				  del_id int(10) unsigned NOT NULL AUTO_INCREMENT,
				  del_changed timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
				  ti_id int(10) unsigned NOT NULL,
				  ti_type enum('event','meeting') NOT NULL,
				  ti_extID int(10) unsigned NOT NULL,
				  ti_created timestamp NULL DEFAULT NULL,
				  ti_startTime timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
				  ti_endTime timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
				  ti_roomID int(10) unsigned DEFAULT NULL,
				  ti_extraInfo text NOT NULL,
				  ti_noLocation_branch int(11) DEFAULT NULL,
				  ti_attendance int(11) DEFAULT NULL,
				  ti_attNotes int(11) DEFAULT NULL,
				  PRIMARY KEY  (del_id)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;";
		
		dbDelta( $sql );
		
		if( $dbOnly ) {
			update_option( "bookaroom_db_version", $bookaroom_db_version );
			return true;
		}
		# TODO
		# add defaults only if empty
		# update the DB creation
		

		# defaults
		add_option( "bookaroom_db_version", $bookaroom_db_version );
		add_option( "bookaroom_alertEmail", '' );
		add_option( "bookaroom_bufferSize", '' );
		add_option( "bookaroom_content_contract", '' );
		add_option( "bookaroom_defaultEmailDaily", '' );
		add_option( "bookaroom_nonProfitDeposit", '' );
		add_option( "bookaroom_nonProfitIncrementPrice", '' );
		add_option( "bookaroom_eventLink", '' );
		add_option( "bookaroom_profitDeposit", '' );
		add_option( "bookaroom_profitIncrementPrice", '' );
		add_option( "bookaroom_baseIncrement", '30' );
		add_option( "bookaroom_cleanupIncrement", '1' );
		add_option( "bookaroom_reserveAllowed", '90' );
		add_option( "bookaroom_reserveBuffer", '2' );
		add_option( "bookaroom_reservedColor", '#448' );
		add_option( "bookaroom_reservedFont", '#FFF' );
		add_option( "bookaroom_setupColor", '#BBF' );
		add_option( "bookaroom_setupFont", '#000' );
		add_option( "bookaroom_setupIncrement", '0' );
		add_option( "bookaroom_waitingListDefault", '10' );
		add_option( "bookaroom_reservation_URL", '' );
		add_option( "bookaroom_installing", 'yes' );
		add_option( "bookaroom_daysBeforeRemind", '5' );
		add_option( 'bookaroom_paymentLink', '' );
		add_option( 'bookaroom_libcardRegex', '' );
		add_option( 'bookaroom_obfuscatePublicNames', '' );

		add_option( 'bookaroom_addressType', 'usa' );
		add_option( 'bookaroom_address1_name', __( 'Street Address 1', 'book-a-room' ) );
		add_option( 'bookaroom_address2_name', __( 'Street Address 2', 'book-a-room' ) );
		add_option( 'bookaroom_city_name', __( 'City', 'book-a-room' ) );
		add_option( 'bookaroom_state_name', __( 'State/Province/Territory', 'book-a-room' ) );
		add_option( 'bookaroom_defaultState_name', '' );
		add_option( 'bookaroom_zip_name', __( 'Post Code', 'book-a-room' ) );
		add_option( 'bookaroom_hide_contract', false );
		
		# searches
		add_option( 'bookaroom_search_events_page_num', '1' );
		add_option( 'bookaroom_search_events_per_page', '20' );
		add_option( 'bookaroom_search_events_order_by', 'event_id' );
		add_option( 'bookaroom_search_events_sort_order', 'desc' );
		
		# default mails
		add_option( 'bookaroom_newAlert_subject', 					__( 'Confirmation of Meeting Room Request', 'book-a-room' ) );
		add_option( 'bookaroom_newInternal_subject', 				__( 'Staff Reciept', 'book-a-room' ) );
		add_option( 'bookaroom_nonProfit_pending_subject',			__( 'Meeting Room Request - 501(c)(3) Information Needed', 'book-a-room' ) );
		add_option( 'bookaroom_profit_pending_subject',				__( 'Meeting Room Request - Payment Needed', 'book-a-room' ) );
		add_option( 'bookaroom_regChange_subject', 					__( 'Event Status Change.', 'book-a-room' ) );
		add_option( 'bookaroom_requestAcceptedNonprofit_subject', 	__( 'Request Accepted (Nonprofit)', 'book-a-room' ) );
		add_option( 'bookaroom_requestAcceptedProfit_subject', 		__( 'Request Accepted (Profit)', 'book-a-room' ) );
		add_option( 'bookaroom_requestDenied_subject', 				__( 'Request Denied', 'book-a-room' ) );
		add_option( 'bookaroom_requestPayment_subject', 			__( 'Payment Received', 'book-a-room' ) );
		add_option( 'bookaroom_requestReminder_subject', 			__( 'Request Reminder', 'book-a-room' ) );
		
		$lb = "\n";
		# +--------------------------------------------------------------------
		
		$transAlert = '<h3>' . __( 'Confirmation of Meeting Room Request', 'book-a-room' ) . '</h3>' . $lb . $lb . 
		__( "Thank you. Your request has been submitted. This is just a confirmation that you have submitted a request", 'book-a-room' ) . $lb . $lb . 
		'<strong>' . __( 'Branch Name', 'book-a-room' ) . ":</strong> {branchName}" . $lb . 
		'<strong>' . __( 'Room Name', 'book-a-room' ) . ":</strong> {roomName}" . $lb . 
		'<strong>' . __( 'Date', 'book-a-room' ) . ":</strong> {date}" . $lb . 
		'<strong>' . __( 'Time', 'book-a-room' ) . ":</strong> {startTime} to {endTime}" . $lb . 
		'<strong>' . __( 'Event Name', 'book-a-room' ) . ":</strong> {eventName}" . $lb . 
		'<strong>' . __( 'Number of attendees', 'book-a-room' ) . ":</strong> {numAttend}" . $lb . 

		add_option( 'bookaroom_newAlert_body', 	$transAlert );
		
		# +--------------------------------------------------------------------
		
		$transAlert = '<h3>' . __( 'Confirmation of Meeting Room Request', 'book-a-room' ) . '</h3>' . $lb . 
		__( "Thank you. Your request has been submitted. Since this is a staff event, it is considered approved.", 'book-a-room' ) . $lb . $lb . 
		'<strong>' . __( 'Event Name', 'book-a-room' ) . ":</strong> {eventName}" . $lb . 
		'<strong>' . __( 'Branch Name', 'book-a-room' ) . ":</strong> {branchName}" . $lb . 
		'<strong>' . __( 'Room Name', 'book-a-room' ) . ":</strong> {roomName}" . $lb . 
		'<strong>' . __( 'Date', 'book-a-room' ) . ":</strong> {date}" . $lb . 
		'<strong>' . __( 'Time', 'book-a-room' ) . ":</strong> {startTime} to {endTime}" . $lb . 
		'<strong>' . __( 'Event Name', 'book-a-room' ) . ":</strong> {eventName}" . $lb . 
		'<strong>' . __( 'Number of attendees', 'book-a-room' ) . ":</strong> {numAttend}" . $lb . 
		add_option( 'bookaroom_newInternal_body', $transAlert );
		
		# +--------------------------------------------------------------------		
						 
		add_option( 'bookaroom_nonProfit_pending_body',  			
			__( "Your meeting room request is pending until we receive proper documentation of 501(c)(3) status. Please note that we need the IRS letter that confirms your group's nonprofit status, not the EIN or State Tax Exempt form.", 'book-a-room' ) . $lb . 
			__( "Your nonprofit information is due before {paymentDate}", 'book-a-room' ) );
		
		# +--------------------------------------------------------------------
			   
		add_option( 'bookaroom_profit_pending_body', 
			__( "Your meeting room request is pending until we receive payment. Your payment is due before {paymentDate}", 'book-a-room' ) . $lb );
		
		# +--------------------------------------------------------------------
		
		add_option( 'bookaroom_regChange_body', 					
			__( "Due to a cancellation, you have been moved from the waiting list and can attend the following event:{$lb}{$lb}{eventName}{$lb}{date} at {startTime} at {branchName}", 'book-a-room' ) );
		
		# +--------------------------------------------------------------------
		
		
		$transAlert = __( "Your meeting room request has been accepted for event: {desc} on {date}.", 'book-a-room' ) . $lb . $lb .

		'<strong>' . __( 'Branch Name', 'book-a-room' ) . ": </strong> {branchName}" . $lb . 
		'<strong>' . __( 'Company Name', 'book-a-room' ) . ": </strong> {contactName}" . $lb . 
		'<strong>' . __( 'Date', 'book-a-room' ) . ": </strong> {date}" . $lb . 
		'<strong>' . __( 'End Time', 'book-a-room' ) . ": </strong> {endTime}" . $lb . 
		'<strong>' . __( 'Event Name', 'book-a-room' ) . ": </strong> {eventName}" . $lb . 
		'<strong>' . __( 'Number of attendees', 'book-a-room' ) . ":</strong> {numAttend}" . $lb . 
		'<strong>' . __( 'Room Name', 'book-a-room' ) . ":</strong> {roomName}" . $lb . 
		'<strong>' . __( 'Start Time', 'book-a-room' ) . ":</strong> {startTime}" . $lb . 
		
		add_option( 'bookaroom_requestAcceptedNonprofit_body', $transAlert );
		
		# +--------------------------------------------------------------------
		
		$transAlert = __( "Your meeting room request has been accepted for event: {desc} on {date}.", 'book-a-room' ) . $lb . 
		'<strong>' . __( 'Date', 'book-a-room' ) . ":</strong> {date}" . $lb . 
		'<strong>' . __( 'End Time', 'book-a-room' ) . ":</strong> {endTime}" . $lb . 
		'<strong>' . __( 'Event Name', 'book-a-room' ) . ":</strong> {eventName}" . $lb . 
		'<strong>' . __( 'Number of attendees', 'book-a-room' ) . ":</strong> {numAttend}" . $lb . 
		'<strong>' . __( 'Room Name', 'book-a-room' ) . ":</strong> {roomName}" . $lb . 
		'<strong>' . __( 'Start Time', 'book-a-room' ) . ":</strong> {startTime}" . $lb . 
		
		add_option( 'bookaroom_requestAcceptedProfit_body', $transAlert );
				
		# +--------------------------------------------------------------------
		
		add_option( 'bookaroom_requestDenied_body', __( "Your meeting room request has been denied for event: {desc} on {date}.", 'book-a-room' ) . $lb . $lb . __( "If you have questions please contact us", 'book-a-room' ) );
		
		# +--------------------------------------------------------------------
		
		add_option( 'bookaroom_requestPayment_body', __( "Payment has been received and your meeting room request is approved and completed.", 'book-a-room' ) );
		
		# +--------------------------------------------------------------------
		
		add_option( 'bookaroom_requestReminder_body', __( "Request Reminder body", 'book-a-room' ) );
	
	}

	public static function plugin_activation_message()
	{
		global $bookaroom_db_version;
		if( $bookaroom_db_version !== get_option( "bookaroom_db_version" ) ) {
			DsolBookingPluginHooks::on_activate( true );
			
		}
		
		if( get_option( "bookaroom_installing" ) == 'yes' ) {
			update_option( 'bookaroom_installing', 'no' );
			$html = '<div class="updated">' . __( '(Please install the events calendar plugin. You will not be able to view the calendar until it is installed and configured.)', 'book-a-room' ) .  '</p>' . 
				 '<p>' . __( 'To set up your meeting rooms, first click on Meeting Room Settings on the left hand menu. There are descriptions of each option at the bottom of the page.', 'book-a-room' ) . '</p>' . 
				 '<p>' . __( 'Next, set up your amenities. These should include any extras that can be reserved with the room like coffee urns, dry erase boards and projectors.', 'book-a-room' ) . '</p>' .  
				 '<p>' . __( 'Once you\'ve got your amenities set, add your Branches. This is also where you configure the hours, address and image for each branch.', 'book-a-room' ) . '</p>' . 
				 '<p>' . __( 'Next, add in your Rooms. A room is a <em><strong>physical</strong></em> space that can be reserved. If you have 2 meetings rooms, even if they can be reserved together, you would only add the two physical locations as a room.', 'book-a-room' ) . '</p>' . 
				 '<p>' . __( 'Finally, add in your Room Containers. Room containers are <em><strong>virtual</strong></em> spaces that are actually being reserved. If you have two rooms that can be reserved separately or together as one larger space, you would add 3 containers; two would each contain one room and the third would contain both rooms.', 'book-a-room' ) . '</p>' . 
				 '<p>' . __( 'To configure alerts and content, make sure you edit the Email Admin and Content Admin!', 'book-a-room' ) . '</p>' . 
				 '</div><!-- /.updated -->';
			echo $html;
		}
	}
	
    public static function on_deactivate()
	# this is only run when hooked by de-activating plugin
    {
		# TODO fix deactivation and uninstall
		#update_option( "bookaroom_installing", 'yes' );
		


    }

    public static function on_uninstall()
	# this is only run when hooked by uninstalling plugin
    {
        // important: check if the file is the one that was registered with the uninstall hook (function)
       
		#if ( __FILE__ != WP_UNINSTALL_PLUGIN )
        #    return;

		global $wpdb;
		global $bookaroom_db_version;
	
		$wpdb->query( "DROP TABLE {$wpdb->prefix}bookaroom_amenities" );
		$wpdb->query( "DROP TABLE {$wpdb->prefix}bookaroom_branches" );
		$wpdb->query( "DROP TABLE {$wpdb->prefix}bookaroom_cityList" );
		$wpdb->query( "DROP TABLE {$wpdb->prefix}bookaroom_closings" );
		$wpdb->query( "DROP TABLE {$wpdb->prefix}bookaroom_eventAges" );
		$wpdb->query( "DROP TABLE {$wpdb->prefix}bookaroom_eventCats" );
		$wpdb->query( "DROP TABLE {$wpdb->prefix}bookaroom_event_ages" );
		$wpdb->query( "DROP TABLE {$wpdb->prefix}bookaroom_event_categories" );
		$wpdb->query( "DROP TABLE {$wpdb->prefix}bookaroom_registrations" );
		$wpdb->query( "DROP TABLE {$wpdb->prefix}bookaroom_reservations" );
		$wpdb->query( "DROP TABLE {$wpdb->prefix}bookaroom_reservations_deleted" );
		$wpdb->query( "DROP TABLE {$wpdb->prefix}bookaroom_roomConts" );
		$wpdb->query( "DROP TABLE {$wpdb->prefix}bookaroom_roomConts_members" );
		$wpdb->query( "DROP TABLE {$wpdb->prefix}bookaroom_rooms" );
		$wpdb->query( "DROP TABLE {$wpdb->prefix}bookaroom_times" );
		$wpdb->query( "DROP TABLE {$wpdb->prefix}bookaroom_times_deleted" );
				
		delete_option( "bookaroom_db_version" );
		delete_option( "bookaroom_alertEmail" );
		delete_option( "bookaroom_bufferSize" );
		delete_option( "bookaroom_content_contract" );
		delete_option( "bookaroom_defaultEmailDaily" );
		delete_option( "bookaroom_nonProfitDeposit" );
		delete_option( "bookaroom_nonProfitIncrementPrice" );
		delete_option( "bookaroom_eventLink" );
		delete_option( "bookaroom_profitDeposit" );
		delete_option( "bookaroom_profitIncrementPrice" );
		delete_option( "bookaroom_baseIncrement" );
		delete_option( "bookaroom_cleanupIncrement" );
		delete_option( "bookaroom_reserveAllowed" );
		delete_option( "bookaroom_reserveBuffer" );
		delete_option( "bookaroom_reservedColor" );
		delete_option( "bookaroom_reservedFont" );
		delete_option( "bookaroom_setupColor" );
		delete_option( "bookaroom_setupFont" );
		delete_option( "bookaroom_setupIncrement" );
		delete_option( "bookaroom_waitingListDefault" );
		delete_option( "bookaroom_reservation_URL" );
		delete_option( "bookaroom_daysBeforeRemind" );
		
		delete_option( 'bookaroom_addressType' );
		delete_option( 'bookaroom_address1_name' );
		delete_option( 'bookaroom_address2_name' );
		delete_option( 'bookaroom_city_name' );
		delete_option( 'bookaroom_state_name' );
		delete_option( 'bookaroom_zip_name' );
		

		delete_option( 'bookaroom_newAlert_subject' );
		delete_option( 'bookaroom_newInternal_subject' );
		delete_option( 'bookaroom_nonProfit_pending_subject' );
		delete_option( 'bookaroom_profit_pending_subject' );
		delete_option( 'bookaroom_regChange_subject' );
		delete_option( 'bookaroom_requestAcceptedNonprofit_subject' );
		delete_option( 'bookaroom_requestAcceptedProfit_subject' );
		delete_option( 'bookaroom_requestDenied_subject' );
		delete_option( 'bookaroom_requestPayment_subject' );
		delete_option( 'bookaroom_requestReminder_subject' );
		delete_option( 'bookaroom_paymentLink' );
		delete_option( 'bookaroom_libcardRegex' );
		delete_option( 'bookaroom_obfuscatePublicNames' );
		
		delete_option( 'bookaroom_newAlert_body' );
		delete_option( 'bookaroom_newInternal_body' );
		delete_option( 'bookaroom_nonProfit_pending_body' );
		delete_option( 'bookaroom_profit_pending_body' );
		delete_option( 'bookaroom_regChange_body' );
		delete_option( 'bookaroom_requestAcceptedNonprofit_body' );
		delete_option( 'bookaroom_requestAcceptedProfit_body' );
		delete_option( 'bookaroom_requestDenied_body' );
		delete_option( 'bookaroom_requestPayment_body' );
		delete_option( 'bookaroom_requestReminder_body' );
		
		delete_option( 'bookaroom_search_events_page_num' );
		delete_option( 'bookaroom_search_events_per_page' );
		delete_option( 'bookaroom_search_events_order_by' );
		delete_option( 'bookaroom_search_events_sort_order' );
		
    }

}


class bookaroom_settings
# main settings functions
{
	public static function add_settingsPage()
	{
		require_once( BOOKAROOM_PATH . 'bookaroom-meetings-admin.php' );
		require_once( BOOKAROOM_PATH . 'bookaroom-meetings-amenities.php' );
		require_once( BOOKAROOM_PATH . 'bookaroom-meetings-branches.php' );
		require_once( BOOKAROOM_PATH . 'bookaroom-meetings-closings.php' );
		require_once( BOOKAROOM_PATH . 'bookaroom-meetings-content.php' );
		require_once( BOOKAROOM_PATH . 'bookaroom-meetings-email.php' );
		require_once( BOOKAROOM_PATH . 'bookaroom-meetings.php' );
		require_once( BOOKAROOM_PATH . 'bookaroom-meetings-meetingsSearch.php' );
		require_once( BOOKAROOM_PATH . 'bookaroom-meetings-rooms.php' );
		require_once( BOOKAROOM_PATH . 'bookaroom-meetings-roomConts.php' );
		require_once( BOOKAROOM_PATH . 'bookaroom-events.php' );
		require_once( BOOKAROOM_PATH . 'bookaroom-events-manage.php' );
		require_once( BOOKAROOM_PATH . 'bookaroom-events-staff.php' );
		require_once( BOOKAROOM_PATH . 'bookaroom-meetings-reports.php' );
		require_once( BOOKAROOM_PATH . 'bookaroom-meetings-customerSearch.php' );
		require_once( BOOKAROOM_PATH . 'bookaroom-meetings-help.php' );
		require_once( BOOKAROOM_PATH . 'bookaroom-meetings-cityManagement.php' );
		require_once( BOOKAROOM_PATH . 'bookaroom-events-manage-age.php' );
		require_once( BOOKAROOM_PATH . 'bookaroom-events-manage-categories.php' );
		require_once( BOOKAROOM_PATH . 'bookaroom-meetings-locale.php' );
		
		
		# Room management pages

		$pendingCount = !empty( $pendingList['status']['pending'] ) ? count( $pendingList['status']['pending'] ) : 0;
		$pendingPayCount = !empty( $pendingList['status']['pendPayment'] ) ? count( $pendingList['status']['pendPayment'] ) : 0;
		$pending501C3Count = !empty( $pendingList['status']['501C3'] ) ? count( $pendingList['status']['501C3'] ) : 0;
		$deniedCount = !empty( $pendingList['status']['denied'] ) ? count( $pendingList['status']['denied'] ) : 0;
		$approvedCount = !empty( $pendingList['status']['approved'] ) ? count( $pendingList['status']['approved'] ) : 0;

		# create and event
		add_menu_page( __( 'Book a Room Event Management', 'book-a-room' ), __( 'Create/Manage Events', 'book-a-room' ), 'read', 'bookaroom_event_management',  array( 'bookaroom_events', 'bookaroom_adminEvents' ), '', 200 );
		
		add_submenu_page( 'bookaroom_event_management', __( 'Create/Manage Events', 'book-a-room' ), __( 'Create Event', 'book-a-room' ), 'read', 'bookaroom_event_management',  array( 'bookaroom_events', 'bookaroom_adminEvents' ) );
		
		add_submenu_page( 'bookaroom_event_management', __( 'Create/Manage Events', 'book-a-room' ), __( 'Manage Events', 'book-a-room' ), 'read', 'bookaroom_event_management_upcoming',  array( 'bookaroom_events_manage', 'bookaroom_manageEvents' ) );
		
		add_submenu_page( 'bookaroom_event_management', __( 'Search Registrations', 'book-a-room' ), __( 'Search Registrations', 'book-a-room' ), 'read', 'bookaroom_event_management_customerSearch',  array( 'bookaroom_customerSearch', 'bookaroom_findUser' ) );
		
		add_submenu_page( 'bookaroom_event_management', __( 'View Staff Events', 'book-a-room' ), __( 'View Staff Events', 'book-a-room' ), 'read', 'bookaroom_event_management_staff',  array( 'bookaroom_events_staff', 'bookaroom_staffCalendar' ) );
		
		# manage reservations
		add_menu_page( __( 'Book a Room Management', 'book-a-room' ), __( 'Manage Reservations', 'book-a-room' ), 'read', 'bookaroom_meetings', array( 'book_a_room_meetings', 'bookaroom_pendingRequests' ) );
		
		#add_submenu_page( 'bookaroom_meetings', "", '<span style="display:block; margin:1px 0 1px -5px; padding:0; height:2px; line-height:1px; background:#DDD;"></span>', "manage_options", "#" );
		
		add_submenu_page( 'bookaroom_meetings', __( 'Meeting Room Meetings - Pending Requests', 'book-a-room' ), sprintf( __( 'Pending [%s]', 'book-a-room' ), $pendingCount ), 'read', 'bookaroom_meetings',  array( 'book_a_room_meetings', 'bookaroom_pendingRequests' ) );
		
		add_submenu_page( 'bookaroom_meetings', __( 'Meeting Room Meetings - Pending Payments', 'book-a-room' ), sprintf( __( 'Pend. Payments [%s]', 'book-a-room' ), $pendingPayCount ), 'read', 'bookaroom_meetings_pendingPayment',  array( 'book_a_room_meetings', 'bookaroom_pendingPayment' ) );
		
		add_submenu_page( 'bookaroom_meetings', __( 'Meeting Room Meetings - Pending 501(c)3', 'book-a-room' ), sprintf( __( 'Pend. 501(c)3 [%s]', 'book-a-room' ), $pending501C3Count ), 'read', 'bookaroom_meetings_pending501C3',  array( 'book_a_room_meetings', 'bookaroom_pending501C3' ) );
		
		add_submenu_page( 'bookaroom_meetings', __( 'Meeting Room Meetings - Approved Requests',  'book-a-room' ), sprintf( __( 'Approved [%s]', 'book-a-room' ), $approvedCount ), 'read', 'bookaroom_meetings_approvedRequests',  array( 'book_a_room_meetings', 'bookaroom_approvedRequests' ) );
		
		add_submenu_page( 'bookaroom_meetings', __( 'Meeting Room Meetings - Denied Requests', 'book-a-room' ), sprintf( __( 'Denied [%s]', 'book-a-room' ), $deniedCount ), 'read', 'bookaroom_meetings_deniedRequests',  array( 'book_a_room_meetings', 'bookaroom_deniedRequests' ) );
		
		add_submenu_page( 'bookaroom_meetings', __( 'Meeting Room Meetings - Archived Requests', 'book-a-room' ), __( 'Archived', 'book-a-room' ), 'read', 'bookaroom_meetings_archivedRequests',  array( 'book_a_room_meetings', 'bookaroom_archivedRequests' ) );
		
		add_submenu_page( 'bookaroom_meetings', __( 'Meeting Room Meetings - Search Requests', 'book-a-room' ), __( 'Search', 'book-a-room' ), 'read', 'bookaroom_meetings_search',  array( 'book_a_room_meetingsSearch', 'bookaroom_searchRequests' ) );
		
		# Reports		
		#add_submenu_page( 'bookaroom_meetings', "", '<span style="display:block; margin:1px 0 1px -5px; padding:0; height:2px; line-height:1px; background:#DDD;"></span>', "read", "#" );
		
		#add_submenu_page( 'bookaroom_meetings', 'Meeting Room Settings - Reports', 'Reports', 'manage_options', 'bookaroom_settings_reports',  array( 'bookaroom_reports', 'bookaroom_reportsAdmin' ) );

		# Daily Schedule
		add_menu_page( __( 'Book a Room Daily Schedules', 'book-a-room' ), __( 'Daily Schedules', 'book-a-room' ), 'manage_options', 'bookaroom_daily_schedules', array( 'book_a_room_meetings', 'bookaroom_contactList' )  );

		add_submenu_page( 'bookaroom_daily_schedules', __( 'Meeting Room Meetings - Contact List', 'book-a-room' ), __( 'Contact List', 'book-a-room' ), 'read', 'bookaroom_daily_schedules',  array( 'book_a_room_meetings', 'bookaroom_contactList' ) );
		
		add_submenu_page( 'bookaroom_daily_schedules', __( 'Meeting Room Meetings - Daily Meetings', 'book-a-room' ), __( 'Daily Meetings', 'book-a-room' ), 'read', 'bookaroom_daily_schedules_meetings',  array( 'book_a_room_meetings', 'bookaroom_dailyMeetings' ) );
		
		add_submenu_page( 'bookaroom_daily_schedules', __( 'Meeting Room Meetings - Daily Room Signs' ), __( 'Daily Room Signs', 'book-a-room' ), 'read', 'bookaroom_daily_schedules_signs',  array( 'book_a_room_meetings', 'bookaroom_dailyRoomSigns' ) );

#		add_submenu_page( 'bookaroom_meetings', "", '<span style="display:block; margin:1px 0 1px -5px; padding:0; height:2px; line-height:1px; background:#DDD;"></span>', "read", "#" );

		# Manage events
		add_menu_page( __( 'Book a Room Event Settings', 'book-a-room' ), __( 'Event Settings', 'book-a-room' ), 'manage_options', 'bookaroom_event_settings', array( 'bookaroom_settings_age', 'showFormAge' ) );
		
		add_submenu_page( 'bookaroom_event_settings', __( 'Manage Age Groups', 'book-a-room' ), __( 'Manage Age Groups', 'book-a-room' ), 'manage_options', 'bookaroom_event_settings',  array( 'bookaroom_settings_age', 'showFormAge' ) );

		add_submenu_page( 'bookaroom_event_settings', __( 'Manage Categories', 'book-a-room' ), __( 'Manage Categories', 'book-a-room' ), 'manage_options', 'bookaroom_event_settings_categories',  array( 'bookaroom_settings_categories', 'showFormCategories' ) );
				
		# Manage Meeting Room settings
		add_menu_page( __( 'Book a Room Settings', 'book-a-room' ), __( 'Meeting Room Settings', 'book-a-room' ), 'manage_options', 'bookaroom_Settings', array( 'bookaroom_settings_admin', 'bookaroom_admin_admin' ) );

		add_submenu_page( 'bookaroom_Settings', __( 'Meeting Room Settings', 'book-a-room' ), __( 'Settings', 'book-a-room' ), 'manage_options', 'bookaroom_Settings',  array( 'bookaroom_settings_admin', 'bookaroom_admin_admin' ) );
		
		add_submenu_page( 'bookaroom_Settings', __( 'Meeting Room Settings - Amenities', 'book-a-room' ), __( 'Amenities Admin', 'book-a-room' ), 'manage_options', 'bookaroom_Settings_Amenities',  array( 'bookaroom_settings_amenities', 'bookaroom_admin_amenities' ) );
		
		add_submenu_page( 'bookaroom_Settings', __( 'Meeting Room Settings - Branches', 'book-a-room' ), __( 'Branch Admin', 'book-a-room' ), 'manage_options', 'bookaroom_Settings_Branches',  array( 'bookaroom_settings_branches', 'bookaroom_admin_branches' ) );

		add_submenu_page( 'bookaroom_Settings', __( 'Meeting Room Settings - Rooms', 'book-a-room' ), __( 'Room Admin', 'book-a-room' ), 'manage_options', 'bookaroom_Settings_Rooms',  array( 'bookaroom_settings_rooms', 'bookaroom_admin_rooms' ) );

		add_submenu_page( 'bookaroom_Settings', __( 'Meeting Room Settings - Room Containers', 'book-a-room' ), __( 'Containers Admin', 'book-a-room' ), 'manage_options', 'bookaroom_Settings_RoomCont',  array( 'bookaroom_settings_roomConts', 'bookaroom_admin_roomCont' ) );
				
		add_submenu_page( 'bookaroom_Settings', __( 'Meeting Room Settings - City Management', 'book-a-room' ), __( 'City Admin', 'book-a-room' ), 'manage_options', 'bookaroom_Settings_cityManagement',  array( 'bookaroom_settings_cityManagement', 'bookaroom_admin_mainCityManagement' ) );
				
		add_submenu_page( 'bookaroom_Settings', "", '<span style="display:block; margin:1px 0 1px -5px; padding:0; height:2px; line-height:1px; background:#DDD;"></span>', "manage_options", "#" );
		
		add_submenu_page( 'bookaroom_Settings', __( 'Meeting Room Settings - Email', 'book-a-room' ), __( 'Email Admin', 'book-a-room' ), 'manage_options', 'bookaroom_Settings_Email',  array( 'bookaroom_settings_email', 'bookaroom_admin_email' ) );
		
		add_submenu_page( 'bookaroom_Settings', __( 'Meeting Room Settings - Content', 'book-a-room' ), __( 'Content Admin', 'book-a-room' ), 'manage_options', 'bookaroom_Settings_Content',  array( 'bookaroom_settings_content', 'bookaroom_admin_content' ) );

		add_submenu_page( 'bookaroom_Settings', __( 'Meeting Room Settings - Closings', 'book-a-room' ), __( 'Closings Admin', 'book-a-room' ), 'manage_options', 'bookaroom_Settings_Closings',  array( 'bookaroom_settings_closings', 'bookaroom_admin_closings' ) );
		
		add_submenu_page( 'bookaroom_Settings', __( 'Meeting Room Settings - Locale', 'book-a-room' ), __( 'Locale Admin', 'book-a-room' ), 'manage_options', 'bookaroom_Settings_Locale',  array( 'bookaroom_settings_locale', 'bookaroom_admin_locale' ) );


		# Help files
		add_menu_page( __( 'Bookaroom Help', 'book-a-room' ), __( 'Bookaroom Help', 'book-a-room' ), 'manage_options', 'Bookaroom_Help', array( 'bookaroom_help', 'showHelp' ) );

		add_submenu_page( 'Bookaroom_Help', __( 'Meeting Room Help', 'book-a-room' ), __( 'Main Help', 'book-a-room' ), 'manage_options', 'Bookaroom_Help',  array( 'bookaroom_help', 'showHelp' ) );
		
		#add_submenu_page( 'Bookaroom_Help', 'Meeting Room Help', 'Setup Help', 'manage_options', 'Bookaroom_Help_Setup',  array( 'bookaroom_help', 'showHelp_setup' ) );
		
		#initialize		
		add_action( 'admin_init', array( 'bookaroom_settings', 'bookaroom_init' ) );
	}
	

	
	public static function bookaroom_init()
	{
		register_setting( 'bookaroom_options', 'bookaroom_alertEmail' );
		register_setting( 'bookaroom_options', 'bookaroom_baseIncrement' );
		register_setting( 'bookaroom_options', 'bookaroom_baseIncrement' );
		register_setting( 'bookaroom_options', 'bookaroom_bufferSize' );
		register_setting( 'bookaroom_options', 'bookaroom_cleanupIncrement' );
		register_setting( 'bookaroom_options', 'bookaroom_reserveAllowed' );
		register_setting( 'bookaroom_options', 'bookaroom_reserveBuffer' );
		register_setting( 'bookaroom_options', 'bookaroom_reservedColor' );
		register_setting( 'bookaroom_options', 'bookaroom_reservedFont' );
		register_setting( 'bookaroom_options', 'bookaroom_setupColor' );
		register_setting( 'bookaroom_options', 'bookaroom_setupFont' );
		register_setting( 'bookaroom_options', 'bookaroom_setupIncrement' );
		register_setting( 'bookaroom_options', 'bookaroom_reservation_URL' );
		register_setting( 'bookaroom_options', 'bookaroom_defaultEmailDaily' );
		register_setting( 'bookaroom_options', 'bookaroom_daysBeforeRemind' );
		
		register_setting( 'bookaroom_options', 'bookaroom_profitDeposit' );
		register_setting( 'bookaroom_options', 'bookaroom_nonProfitDeposit' );
		register_setting( 'bookaroom_options', 'bookaroom_profitIncrementPrice' );
		register_setting( 'bookaroom_options', 'bookaroom_nonProfitIncrementPrice' );
		
		register_setting( 'bookaroom_options', 'bookaroom_eventLink' );
		
		register_setting( 'bookaroom_options', 'bookaroom_content_contract' );
	
		
	}
	
	public static function checkID( $curID, $itemList, $multi = FALSE )
	# make sure that ID isn't empty and that there is a corresponding
	# entry in the branch list
	{
		if( !isset( $curID ) or empty( $itemList ) or count( $itemList ) == 0 ) {
			return FALSE;
		}
		
		# if multidimensional, run on each sub array
		if( $multi == TRUE ) {
			$isInArray = FALSE;
			foreach( $itemList as $key => $val ) {
				# is it in there?
				if( array_key_exists( $curID, $val ) ) {
					$isInArray = TRUE;
				}
			}
			
			# if not found, return FALSE
			if( $isInArray == FALSE ) {
				return FALSE;
			}
		} else {
		# single dimensional array gets simple array search
			if( !array_key_exists( $curID, $itemList ) ) {
				return FALSE;
			}
		}
		return TRUE;
	}
	
	public static function dupeCheck( $itemList, $itemDesc, $itemID = NULL )
	# check for duplicate item name
	# if item ID is entered, ignore dupe for that ID so that,
	# when editing, you can you can use the same name as the currently
	# edited item
	{
		if( !is_array( $itemList ) ) {
			$final = FALSE;
		} elseif( ( $foundID = array_search( strtolower( $itemDesc ), array_map( 'strtolower', $itemList ) ) ) == FALSE ) {
			$final = FALSE;
		} elseif( !is_null( $itemID ) and $itemID == $foundID ) {
			$final = FALSE;
		} else {
			$final = TRUE;
		}
		return $final;
	}
}
?>