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

add_action( 'admin_menu', array( 'dsol_booking_path_settings', 'add_settingsPage' ) );

#add_filter(		'gform_pre_render',			array( 'bookaroom_creditCardPayments', 'returnIncomingValidation' ) );
#add_action(		'gform_after_submission',	array( 'bookaroom_creditCardPayments', 'finishedSubmission' ));

function my_script_enqueuer() {
	// Prefix later on
	add_shortcode( 'meetingRooms',	array( 'dsol_booking_path_public', 'mainForm' ) );
	add_shortcode( 'profile',	array( 'dsol_booking_path_company_profile', 'showBookings' ) );
	$width = get_option( 'dsol_booking_path_screenWidth' );

	if( !empty( $width ) || $width == 1 ) {
		wp_enqueue_style( 'book-a-room-style', plugin_dir_url( __FILE__ ) . 'css/dsol_booking_path_thin.css' );
	} else {
		wp_enqueue_style( 'book-a-room-style', plugin_dir_url( __FILE__ ) . 'css/dsol_booking_path_day.css' );
	}
	
	global $dsol_booking_path_db_version;
	
	wp_dequeue_script('jquery');
	wp_enqueue_style( 'jquery_ui_css', "https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" );
	wp_enqueue_script( 'dsol_booking_path_js', plugins_url( 'book-a-room/js/jstree/jquery.jstree.js' ), false );
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
		global $dsol_booking_path_db_version;
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        
		// my edit starts here
        # create table for branches		
		$sql = "CREATE TABLE {$wpdb->prefix}dsol_booking_path_branch (
				  b_id int(10) unsigned NOT NULL AUTO_INCREMENT,
				  branchName varchar(128) NOT NULL,
				  PRIMARY KEY (branchID)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;";

        dbDelta( $sql );

		# create table for branch schedules
		$sql = "CREATE TABLE {$wpdb->prefix}dsol_booking_path_branch_schedule (
					bs_id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
					b_id int(10) unsigned NOT NULL,
					open_time time DEFAULT NULL,
					close_time time DEFAULT NULL,
					PRIMARY KEY  (bs_id),
                    FOREIGN KEY (b_id) REFERENCES branch(b_id),
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;";
        dbDelta( $sql );
        
        # create table for room
		$sql = "CREATE TABLE {$wpdb->prefix}bookaroom_room(
					r_id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
					room_number int() UNSIGNED NOT NULL;
					b_id varchar(128) NOT NULL,
					PRIMARY KEY  (r_id),
                    FOREIGN KEY (b_id) REFERENCES branch(b_id)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;";
        dbDelta( $sql );

         # create table for time
		$sql = "CREATE TABLE {$wpdb->prefix}bookaroom_time (
					t_id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
				    start_time time DEFAULT NULL,
					end_time time DEFAULT NULL,
					PRIMARY KEY  (t_id),
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;";
		dbDelta( $sql );
        
         # create table for container
		$sql = "CREATE TABLE {$wpdb->prefix}bookaroom_room_container (
					c_id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
					r_id int() UNSIGNED NOT NULL;
					t_id varchar(128) NOT NULL,
					container_number tinyint unsigned NOT NULL,
					PRIMARY KEY  (c_id),
                    FOREIGN KEY (r_id) REFERENCES branch(r_id),
                    FOREIGN KEY (t_id) REFERENCES branch(t_id)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;";
        dbDelta( $sql );
        
         # create table for reservation
		$sql = "CREATE TABLE {$wpdb->prefix}bookaroom_reservation (
					res_id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                    c_id int(11) UNSIGNED NOT NULL, 
					modified_by TIMESTAMP NOT NULL,
					created_at TIMESTAMP NOT NULL,
					modified_at TIMESTAMP NOT NULL,
					created_by TIMESTAMP NOT NULL,
					company_name varchar(50) NOT NULL,
					email varchar(60) NOT NULL,
					attendance int(50) NOT NULL,
					notes varchar(255) NOT NULL,
					PRIMARY KEY  (res_id),
                    FOREIGN KEY (c_id) REFERENCES container(c_id)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;";
        dbDelta( $sql );
		// my edit ends here (Aung)

		if( $dbOnly ) {
			update_option( "dsol_booking_version", $dsol_booking_version );
			return true;
		}
		# TODO
		# add defaults only if empty
		# update the DB creation
		

		# defaults
		add_option( "bookaroom_db_version", $dsol_booking_version );
		add_option( "bookaroom_alertEmail", '' );
		add_option( "bookaroom_bufferSize", '' );
		add_option( "bookaroom_content_contract", '' );
		add_option( "bookaroom_defaultEmailDaily", '' );
		add_option( "bookaroom_baseIncrement", '30' );
		add_option( "bookaroom_cleanupIncrement", '1' );
		add_option( "bookaroom_reserveAllowed", '90' );
		add_option( "bookaroom_reserveBuffer", '2' );
		add_option( "bookaroom_reservedColor", '#448' );
		add_option( "bookaroom_reservedFont", '#FFF' );
		add_option( "bookaroom_setupColor", '#BBF' );
		add_option( "bookaroom_setupFont", '#000' );
		add_option( "bookaroom_setupIncrement", '0' );
		add_option( "bookaroom_reservation_URL", '' );
		add_option( "bookaroom_installing", 'yes' );
		add_option( "bookaroom_daysBeforeRemind", '5' );
		add_option( 'bookaroom_obfuscatePublicNames', '' );

		
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
		global $dsol_booking_version;
		if( $dsol_booking_version !== get_option( "dsol_booking_version" ) ) {
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
		global $dsol_booking_version;
	
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
		delete_option( "bookaroom_eventLink" );
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
		require_once( BOOKAROOM_PATH . 'bookaroom-meetings-branches.php' );
		require_once( BOOKAROOM_PATH . 'bookaroom-meetings-closings.php' );
		require_once( BOOKAROOM_PATH . 'bookaroom-meetings-content.php' );
		require_once( BOOKAROOM_PATH . 'bookaroom-meetings-email.php' );
		require_once( BOOKAROOM_PATH . 'bookaroom-meetings.php' );
		require_once( BOOKAROOM_PATH . 'bookaroom-meetings-meetingsSearch.php' );
		require_once( BOOKAROOM_PATH . 'bookaroom-meetings-rooms.php' );
		require_once( BOOKAROOM_PATH . 'bookaroom-meetings-roomConts.php' );
		require_once( BOOKAROOM_PATH . 'bookaroom-meetings-reports.php' );
		require_once( BOOKAROOM_PATH . 'bookaroom-meetings-customerSearch.php' );
		require_once( BOOKAROOM_PATH . 'bookaroom-meetings-help.php' );
		
		
		# Room management pages

		$pendingCount = !empty( $pendingList['status']['pending'] ) ? count( $pendingList['status']['pending'] ) : 0;
		$pendingPayCount = !empty( $pendingList['status']['pendPayment'] ) ? count( $pendingList['status']['pendPayment'] ) : 0;
		$pending501C3Count = !empty( $pendingList['status']['501C3'] ) ? count( $pendingList['status']['501C3'] ) : 0;
		$deniedCount = !empty( $pendingList['status']['denied'] ) ? count( $pendingList['status']['denied'] ) : 0;
		$approvedCount = !empty( $pendingList['status']['approved'] ) ? count( $pendingList['status']['approved'] ) : 0;

		# manage reservations
		add_menu_page( __( 'Book a Room Management', 'book-a-room' ), __( 'Manage Reservations', 'book-a-room' ), 'read', 'bookaroom_meetings', array( 'book_a_room_meetings', 'bookaroom_pendingRequests' ) );
		
		#add_submenu_page( 'bookaroom_meetings', "", '<span style="display:block; margin:1px 0 1px -5px; padding:0; height:2px; line-height:1px; background:#DDD;"></span>', "manage_options", "#" );
		
		/**
		 * Show a list containing bookings in one view and then a weekly view 
		 */
		add_submenu_page( 'bookaroom_meetings', __( 'Meeting Room Meetings - Pending Requests', 'book-a-room' ), sprintf( __( 'Pending [%s]', 'book-a-room' ), $pendingCount ), 'read', 'bookaroom_meetings',  array( 'book_a_room_meetings', 'bookaroom_pendingRequests' ) );
		
		add_submenu_page( 'bookaroom_meetings', __( 'Meeting Room Meetings - Search Requests', 'book-a-room' ), __( 'Search', 'book-a-room' ), 'read', 'bookaroom_meetings_search',  array( 'book_a_room_meetingsSearch', 'bookaroom_searchRequests' ) );
		
		# Manage Meeting Room settings
		add_menu_page( __( 'Book a Room Settings', 'book-a-room' ), __( 'Meeting Room Settings', 'book-a-room' ), 'manage_options', 'bookaroom_Settings', array( 'bookaroom_settings_admin', 'bookaroom_admin_admin' ) );

		add_submenu_page( 'bookaroom_Settings', __( 'Meeting Room Settings', 'book-a-room' ), __( 'Settings', 'book-a-room' ), 'manage_options', 'bookaroom_Settings',  array( 'bookaroom_settings_admin', 'bookaroom_admin_admin' ) );
				
		add_submenu_page( 'bookaroom_Settings', __( 'Meeting Room Settings - Branches', 'book-a-room' ), __( 'Branch Admin', 'book-a-room' ), 'manage_options', 'bookaroom_Settings_Branches',  array( 'bookaroom_settings_branches', 'bookaroom_admin_branches' ) );

		add_submenu_page( 'bookaroom_Settings', __( 'Meeting Room Settings - Rooms', 'book-a-room' ), __( 'Room Admin', 'book-a-room' ), 'manage_options', 'bookaroom_Settings_Rooms',  array( 'bookaroom_settings_rooms', 'bookaroom_admin_rooms' ) );

		add_submenu_page( 'bookaroom_Settings', __( 'Meeting Room Settings - Room Containers', 'book-a-room' ), __( 'Containers Admin', 'book-a-room' ), 'manage_options', 'bookaroom_Settings_RoomCont',  array( 'bookaroom_settings_roomConts', 'bookaroom_admin_roomCont' ) );
								
		add_submenu_page( 'bookaroom_Settings', "", '<span style="display:block; margin:1px 0 1px -5px; padding:0; height:2px; line-height:1px; background:#DDD;"></span>', "manage_options", "#" );
		
		add_submenu_page( 'bookaroom_Settings', __( 'Meeting Room Settings - Email', 'book-a-room' ), __( 'Email Admin', 'book-a-room' ), 'manage_options', 'bookaroom_Settings_Email',  array( 'bookaroom_settings_email', 'bookaroom_admin_email' ) );
		
		

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