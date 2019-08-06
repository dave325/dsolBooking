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

require_once( DSOL_BOOKING_PATH . 'bookaroom-meetings-public.php' );
//require_once( DSOL_BOOKING_PATH . 'bookaroom-company-profile.php' );
require_once( DSOL_BOOKING_PATH . 'sharedFunctions.php' );
require_once(DSOL_BOOKING_PATH . 'rest_api.php');
/**
 * Change init functions and class
 */
register_activation_hook( __FILE__, array( 'DsolBookingPluginHooks', 'on_activate' ) );
register_deactivation_hook( __FILE__, array( 'DsolBookingPluginHooks', 'on_deactivate' ) );
register_uninstall_hook( __FILE__, array( 'DsolBookingPluginHooks', 'on_uninstall' ) );

add_action( 'init', 'dSol_enqueuer' );

#add_filter( 'the_content',  array( 'dsol_public', 'mainForm' ) );

add_action( 'admin_notices', array( 'DsolBookingPluginHooks', 'plugin_activation_message' ) ) ;

add_action( 'admin_menu', array( 'dsol_settings', 'add_settingsPage' ) );

#add_filter(		'gform_pre_render',			array( 'dsol_creditCardPayments', 'returnIncomingValidation' ) );
#add_action(		'gform_after_submission',	array( 'dsol_creditCardPayments', 'finishedSubmission' ));

function dSol_enqueuer() {
	// Prefix later on
	add_shortcode( 'meetingRooms',	array( 'dsol_public', 'mainForm' ) );
	add_shortcode( 'dsol_submitPage',	array( 'dsol_public', 'showSubmitPage' ) );
	add_shortcode( 'dsol_profile',	array( 'dsol_public', 'showProfilePage' ) );
	$width = get_option( 'dsol_booking_path_screenWidth' );

	if( !empty( $width ) || $width == 1 ) {
		wp_enqueue_style( 'book-a-room-style', plugin_dir_url( __FILE__ ) . 'css/dsol_thin.css' );
	} else {
		wp_enqueue_style( 'book-a-room-style', plugin_dir_url( __FILE__ ) . 'css/dsol_day.css' );
	}
	
	global $dsol_booking_path_db_version;
	wp_enqueue_script('jquery');
	wp_enqueue_style('bootstrap-css', "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css");
	wp_enqueue_style('bookaroom-css', plugins_url( 'dsolBooking/css/dsol_thin.css'));
	wp_enqueue_script('bootstrap-js',"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js","","",false);
	wp_enqueue_style( 'jquery_ui_css', "https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" );
	wp_enqueue_style('angular-style',plugins_url( 'dsolBooking/css/angular-styles.css' ));
	wp_enqueue_script( 'dsol_booking_path_js', plugins_url( 'dsolBooking/js/jstree/jquery.jstree.js' ), false );
	wp_enqueue_script( 'jquery_ui', "https://code.jquery.com/ui/1.12.1/jquery-ui.min.js", 'jquery','',false );
	wp_enqueue_script( 'angular', "https://ajax.googleapis.com/ajax/libs/angularjs/1.7.8/angular.min.js", '','',false );
	wp_enqueue_script('angular-route',"https://code.angularjs.org/1.7.8/angular-route.min.js", "", "", false);
	wp_enqueue_script( "angular-animate", "https://code.angularjs.org/1.7.8/angular-animate.min.js", "", "", false );
	wp_enqueue_script('moment',"https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js");
	wp_enqueue_script('angular-script',plugins_url( 'dsolBooking/js/angular-script.js' ), "", "", false);
	wp_enqueue_script('angular-bootstrap', plugins_url('dsolBooking/js/ui-bootstrap-tpls-2.5.0.min.js'), "","",false);
	
	wp_localize_script('angular-script', 'localized',
            array(
								'partials' => plugins_url( 'dsolBooking/templates/partials/' ),
								"path" =>  get_site_url(),
								'nonce' => wp_create_nonce( 'wp_rest' ),
								'username' => wp_get_current_user(),
								'assets' =>plugins_url('dsolBooking')
                )
    );
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
		global $dsol_booking_version;
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        
		// my edit starts here
        # create table for branches		
		$sql = "CREATE TABLE {$wpdb->prefix}dsol_booking_branch (
				  b_id int(10) NOT NULL AUTO_INCREMENT,
				  b_name varchar(128) NOT NULL,
				  PRIMARY KEY (b_id)
				);";

        dbDelta( $sql );

		# create table for branch schedules
		$sql = "CREATE TABLE {$wpdb->prefix}dsol_booking_branch_schedule (
					bs_id int(11) NOT NULL AUTO_INCREMENT,
					b_id int(10) NOT NULL,
					open_time time,
					close_time time,
					PRIMARY KEY  (bs_id)
					);";
        dbDelta( $sql );
        
        # create table for room
		$sql = "CREATE TABLE {$wpdb->prefix}dsol_booking_room (
					r_id int(11) NOT NULL AUTO_INCREMENT,
					room_number varchar(60) NOT NULL,
					b_id varchar(128) NOT NULL,
					PRIMARY KEY (r_id)
					);";
        dbDelta( $sql );

         # create table for time
		$sql = "CREATE TABLE {$wpdb->prefix}dsol_booking_time (
					t_id int(11) NOT NULL AUTO_INCREMENT,
				    start_time timestamp,
					end_time timestamp,
					res_id int(11),
					PRIMARY KEY  (t_id)
					);";
		dbDelta( $sql );
        
         # create table for container
		$sql = "CREATE TABLE {$wpdb->prefix}dsol_booking_container (
					c_id int(11) NOT NULL AUTO_INCREMENT,
					r_id int(11)  NOT NULL,
					container_number varchar(255) NOT NULL,
					occupancy int(10) NOT NULL,
					PRIMARY KEY  (c_id)
				);";
        dbDelta( $sql );
        
         # create table for reservation
		$sql = "CREATE TABLE {$wpdb->prefix}dsol_booking_reservation (
					res_id int(11) NOT NULL AUTO_INCREMENT,
                    c_id int(11) NOT NULL, 
					modified_by varchar(255) NOT NULL,
					created_at TIMESTAMP NOT NULL,
					modified_at TIMESTAMP NOT NULL,
					created_by varchar(255) NOT NULL,
					company_name varchar(50) NOT NULL,
					email varchar(60) NOT NULL,
					attendance int(50) NOT NULL,
					notes varchar(255) NOT NULL,
					PRIMARY KEY  (res_id)
					);";
        dbDelta( $sql );
		// my edit ends here (Aung)

		$sql = "INSERT INTO {$wpdb->prefix}dsol_booking_branch (b_name) SELECT branchDesc FROM {$wpdb->prefix}bookaroom_branches;";
		dbDelta($sql);

		$sql = "INSERT INTO {$wpdb->prefix}dsol_booking_room (b_id, room_number) SELECT room_branchID, room_desc  FROM {$wpdb->prefix}bookaroom_rooms;";
		dbDelta($sql);

		$sql = "INSERT INTO {$wpdb->prefix}dsol_booking_container (r_id, container_number, occupancy) 
			SELECT m.rcm_roomID, rc.roomCont_desc, rc.roomCont_occ 
			FROM {$wpdb->prefix}bookaroom_roomConts rc 
			INNER JOIN {$wpdb->prefix}bookaroom_roomConts_members m 
			ON rc.roomCont_ID = m.rcm_roomContID;";
		dbDelta($sql);

		$sql = "INSERT INTO {$wpdb->prefix}dsol_booking_time (start_time, end_time, res_id) 
        SELECT bt.ti_startTime, bt.ti_endTime, res.res_id
        FROM {$wpdb->prefix}bookaroom_times bt
        INNER JOIN {$wpdb->prefix}bookaroom_reservations res
        ON res.res_id = bt.ti_extID
        RIGHT JOIN {$wpdb->prefix}bookaroom_roomConts_members m 
        ON bt.ti_roomID = m.rcm_roomID
		WHERE res.me_numAttend > 0 AND LENGTH(res.me_contactEmail) > 0 AND LENGTH(res.me_desc) > 0;";
		dbDelta($sql);

		$sql = "INSERT INTO {$wpdb->prefix}dsol_booking_reservation (res_id, c_id, modified_by, created_at, modified_at, created_by, company_name, email, attendance, notes) 
		SELECT res.res_id, m.rcm_roomContID, res.me_contactEmail, res.res_created, CURRENT_TIMESTAMP, res.me_contactName, res.me_contactName, res.me_contactEmail, res.me_numAttend, res.me_desc
		FROM {$wpdb->prefix}bookaroom_reservations res
		INNER JOIN  {$wpdb->prefix}bookaroom_times t
		ON res.res_id = t.ti_extID
		JOIN {$wpdb->prefix}bookaroom_roomConts_members m 
		ON  t.ti_roomID = m.rcm_roomID
		WHERE res.me_numAttend > 0 AND LENGTH(res.me_contactEmail) > 0 AND LENGTH(res.me_desc) > 0;";
		dbDelta($sql);
		
		if( $dbOnly ) {
			update_option( "dsol_booking_version", $dsol_booking_version );
			return true;
		}
		# TODO
		# add defaults only if empty
		# update the DB creation
		

		# defaults
		add_option( "dsol_db_version", $dsol_booking_version );
		add_option( "dsol_alertEmail", '' );
		add_option( "dsol_bufferSize", '' );
		add_option( "dsol_content_contract", '' );
		add_option( "dsol_defaultEmailDaily", '' );
		add_option( "dsol_baseIncrement", '30' );
		add_option( "dsol_cleanupIncrement", '1' );
		add_option( "dsol_reserveAllowed", '90' );
		add_option( "dsol_reserveBuffer", '2' );
		add_option( "dsol_reservedColor", '#448' );
		add_option( "dsol_reservedFont", '#FFF' );
		add_option( "dsol_setupColor", '#BBF' );
		add_option( "dsol_setupFont", '#000' );
		add_option( "dsol_setupIncrement", '0' );
		add_option( "dsol_reservation_URL", '' );
		add_option( "dsol_installing", 'yes' );
		add_option( "dsol_daysBeforeRemind", '5' );
		add_option( 'dsol_obfuscatePublicNames', '' );

		
		# searches
		add_option( 'dsol_search_events_page_num', '1' );
		add_option( 'dsol_search_events_per_page', '20' );
		add_option( 'dsol_search_events_order_by', 'event_id' );
		add_option( 'dsol_search_events_sort_order', 'desc' );
		
		# default mails
		add_option( 'dsol_newAlert_subject', 					__( 'Confirmation of Meeting Room Request', 'book-a-room' ) );
		add_option( 'dsol_newInternal_subject', 				__( 'Staff Reciept', 'book-a-room' ) );
		add_option( 'dsol_nonProfit_pending_subject',			__( 'Meeting Room Request - 501(c)(3) Information Needed', 'book-a-room' ) );
		add_option( 'dsol_profit_pending_subject',				__( 'Meeting Room Request - Payment Needed', 'book-a-room' ) );
		add_option( 'dsol_regChange_subject', 					__( 'Event Status Change.', 'book-a-room' ) );
		add_option( 'dsol_requestAcceptedNonprofit_subject', 	__( 'Request Accepted (Nonprofit)', 'book-a-room' ) );
		add_option( 'dsol_requestAcceptedProfit_subject', 		__( 'Request Accepted (Profit)', 'book-a-room' ) );
		add_option( 'dsol_requestDenied_subject', 				__( 'Request Denied', 'book-a-room' ) );
		add_option( 'dsol_requestPayment_subject', 			__( 'Payment Received', 'book-a-room' ) );
		add_option( 'dsol_requestReminder_subject', 			__( 'Request Reminder', 'book-a-room' ) );
		
		$lb = "\n";
		# +--------------------------------------------------------------------
		$transAlert = '';
		$transAlert = '<h3>' . __( 'Confirmation of Meeting Room Request', 'book-a-room' ) . '</h3>' . $lb . $lb . 
		__( "Thank you. Your request has been submitted. This is just a confirmation that you have submitted a request", 'book-a-room' ) . $lb . $lb . 
		'<strong>' . __( 'Branch Name', 'book-a-room' ) . ":</strong> {branchName}" . $lb . 
		'<strong>' . __( 'Room Name', 'book-a-room' ) . ":</strong> {roomName}" . $lb . 
		'<strong>' . __( 'Date', 'book-a-room' ) . ":</strong> {date}" . $lb . 
		'<strong>' . __( 'Time', 'book-a-room' ) . ":</strong> {startTime} to {endTime}" . $lb . 
		'<strong>' . __( 'Event Name', 'book-a-room' ) . ":</strong> {eventName}" . $lb . 
		'<strong>' . __( 'Number of attendees', 'book-a-room' ) . ":</strong> {numAttend}" . $lb . 

		add_option( 'dsol_newAlert_body', 	$transAlert );
		
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
		add_option( 'dsol_newInternal_body', $transAlert );
		
		# +--------------------------------------------------------------------		
						 
		add_option( 'dsol_nonProfit_pending_body',  			
			__( "Your meeting room request is pending until we receive proper documentation of 501(c)(3) status. Please note that we need the IRS letter that confirms your group's nonprofit status, not the EIN or State Tax Exempt form.", 'book-a-room' ) . $lb . 
			__( "Your nonprofit information is due before {paymentDate}", 'book-a-room' ) );
		
		# +--------------------------------------------------------------------
			   
		add_option( 'dsol_profit_pending_body', 
			__( "Your meeting room request is pending until we receive payment. Your payment is due before {paymentDate}", 'book-a-room' ) . $lb );
		
		# +--------------------------------------------------------------------
		
		add_option( 'dsol_regChange_body', 					
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
		
		add_option( 'dsol_requestAcceptedNonprofit_body', $transAlert );
		
		# +--------------------------------------------------------------------
		
		$transAlert = __( "Your meeting room request has been accepted for event: {desc} on {date}.", 'book-a-room' ) . $lb . 
		'<strong>' . __( 'Date', 'book-a-room' ) . ":</strong> {date}" . $lb . 
		'<strong>' . __( 'End Time', 'book-a-room' ) . ":</strong> {endTime}" . $lb . 
		'<strong>' . __( 'Event Name', 'book-a-room' ) . ":</strong> {eventName}" . $lb . 
		'<strong>' . __( 'Number of attendees', 'book-a-room' ) . ":</strong> {numAttend}" . $lb . 
		'<strong>' . __( 'Room Name', 'book-a-room' ) . ":</strong> {roomName}" . $lb . 
		'<strong>' . __( 'Start Time', 'book-a-room' ) . ":</strong> {startTime}" . $lb . 
		
		add_option( 'dsol_requestAcceptedProfit_body', $transAlert );
				
		# +--------------------------------------------------------------------
		
		add_option( 'dsol_requestDenied_body', __( "Your meeting room request has been denied for event: {desc} on {date}.", 'book-a-room' ) . $lb . $lb . __( "If you have questions please contact us", 'book-a-room' ) );
		
		# +--------------------------------------------------------------------
		
		add_option( 'dsol_requestPayment_body', __( "Payment has been received and your meeting room request is approved and completed.", 'book-a-room' ) );
		
		# +--------------------------------------------------------------------
		
		add_option( 'dsol_requestReminder_body', __( "Request Reminder body", 'book-a-room' ) );
		
	}

	public static function plugin_activation_message()
	{
		global $dsol_booking_version;
		if( $dsol_booking_version !== get_option( "dsol_booking_version" ) ) {
			DsolBookingPluginHooks::on_activate( true );
			
		}
		
		if( get_option( "dsol_installing" ) == 'yes' ) {
			update_option( 'dsol_installing', 'no' );
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
		#update_option( "dsol_installing", 'yes' );
		


    }

    public static function on_uninstall()
	# this is only run when hooked by uninstalling plugin
    {
        // important: check if the file is the one that was registered with the uninstall hook (function)
       
		#if ( __FILE__ != WP_UNINSTALL_PLUGIN )
        #    return;

		global $wpdb;
		global $dsol_booking_version;
	
		$wpdb->query( "DROP TABLE {$wpdb->prefix}dsol_booking_reservation" );
		$wpdb->query( "DROP TABLE {$wpdb->prefix}dsol_booking_time" );
		$wpdb->query( "DROP TABLE {$wpdb->prefix}dsol_booking_container" );
		$wpdb->query( "DROP TABLE {$wpdb->prefix}dsol_booking_room" );
		$wpdb->query( "DROP TABLE {$wpdb->prefix}dsol_booking_branch" );
		$wpdb->query( "DROP TABLE {$wpdb->prefix}dsol_booking_branch_schedule" );
				
		delete_option( "dsol_db_version" );
		delete_option( "dsol_alertEmail" );
		delete_option( "dsol_bufferSize" );
		delete_option( "dsol_content_contract" );
		delete_option( "dsol_defaultEmailDaily" );
		delete_option( "dsol_eventLink" );
		delete_option( "dsol_baseIncrement" );
		delete_option( "dsol_cleanupIncrement" );
		delete_option( "dsol_reserveAllowed" );
		delete_option( "dsol_reserveBuffer" );
		delete_option( "dsol_reservedColor" );
		delete_option( "dsol_reservedFont" );
		delete_option( "dsol_setupColor" );
		delete_option( "dsol_setupFont" );
		delete_option( "dsol_setupIncrement" );
		delete_option( "dsol_waitingListDefault" );
		delete_option( "dsol_reservation_URL" );
		delete_option( "dsol_daysBeforeRemind" );
		

		delete_option( 'dsol_newAlert_subject' );
		delete_option( 'dsol_newInternal_subject' );
		delete_option( 'dsol_nonProfit_pending_subject' );
		delete_option( 'dsol_profit_pending_subject' );
		delete_option( 'dsol_regChange_subject' );
		delete_option( 'dsol_requestAcceptedNonprofit_subject' );
		delete_option( 'dsol_requestAcceptedProfit_subject' );
		delete_option( 'dsol_requestDenied_subject' );
		delete_option( 'dsol_requestPayment_subject' );
		delete_option( 'dsol_requestReminder_subject' );
		delete_option( 'dsol_paymentLink' );
		delete_option( 'dsol_libcardRegex' );
		delete_option( 'dsol_obfuscatePublicNames' );
		
		delete_option( 'dsol_newAlert_body' );
		delete_option( 'dsol_newInternal_body' );
		delete_option( 'dsol_nonProfit_pending_body' );
		delete_option( 'dsol_profit_pending_body' );
		delete_option( 'dsol_regChange_body' );
		delete_option( 'dsol_requestAcceptedNonprofit_body' );
		delete_option( 'dsol_requestAcceptedProfit_body' );
		delete_option( 'dsol_requestDenied_body' );
		delete_option( 'dsol_requestPayment_body' );
		delete_option( 'dsol_requestReminder_body' );
		
		delete_option( 'dsol_search_events_page_num' );
		delete_option( 'dsol_search_events_per_page' );
		delete_option( 'dsol_search_events_order_by' );
		delete_option( 'dsol_search_events_sort_order' );
		
    }

}


class dsol_settings
# main settings functions
{
	public static function add_settingsPage()
	{
		require_once( DSOL_BOOKING_PATH . 'bookaroom-meetings-admin.php' );
		require_once( DSOL_BOOKING_PATH . 'bookaroom-meetings-branches.php' );
		require_once( DSOL_BOOKING_PATH . 'bookaroom-meetings.php' );
		require_once( DSOL_BOOKING_PATH . 'bookaroom-meetings-meetingsSearch.php' );
		require_once( DSOL_BOOKING_PATH . 'bookaroom-meetings-rooms.php' );
		require_once( DSOL_BOOKING_PATH . 'bookaroom-meetings-roomConts.php' );
		
		
		# Room management pages

		$pendingCount = !empty( $pendingList['status']['pending'] ) ? count( $pendingList['status']['pending'] ) : 0;
		$pendingPayCount = !empty( $pendingList['status']['pendPayment'] ) ? count( $pendingList['status']['pendPayment'] ) : 0;
		$pending501C3Count = !empty( $pendingList['status']['501C3'] ) ? count( $pendingList['status']['501C3'] ) : 0;
		$deniedCount = !empty( $pendingList['status']['denied'] ) ? count( $pendingList['status']['denied'] ) : 0;
		$approvedCount = !empty( $pendingList['status']['approved'] ) ? count( $pendingList['status']['approved'] ) : 0;

		# manage reservations
		add_menu_page( __( 'Book a Room Management', 'book-a-room' ), __( 'Manage Reservations', 'book-a-room' ), 'read', 'dsol_meetings', array( 'dsol_meetings', 'dsol_pendingRequests' ) );
		
		#add_submenu_page( 'bookaroom_meetings', "", '<span style="display:block; margin:1px 0 1px -5px; padding:0; height:2px; line-height:1px; background:#DDD;"></span>', "manage_options", "#" );
		
		/**
		 * Show a list containing bookings in one view and then a weekly view 
		 */
		add_submenu_page( 'dsol_meetings', __( 'Meeting Room Meetings - Pending Requests', 'book-a-room' ), sprintf( __( 'Pending [%s]', 'book-a-room' ), $pendingCount ), 'read', 'dsol_meetings',  array( 'dsol_meetings', 'dsol_pendingRequests' ) );
		
		add_submenu_page( 'dsol_meetings', __( 'Meeting Room Meetings - Search Requests', 'book-a-room' ), __( 'Search', 'book-a-room' ), 'read', 'dsol_meetings_search',  array( 'dsol_meetingsSearch', 'dsol_searchRequests' ) );
		
		# Manage Meeting Room settings
		add_menu_page( __( 'Book a Room Settings', 'book-a-room' ), __( 'Meeting Room Settings', 'book-a-room' ), 'manage_options', 'dsol_Settings', array( 'dsol_settings_admin', 'dsol_admin_admin' ) );

		add_submenu_page( 'dsol_Settings', __( 'Meeting Room Settings', 'book-a-room' ), __( 'Settings', 'book-a-room' ), 'manage_options', 'dsol_Settings',  array( 'dsol_settings_admin', 'dsol_admin_admin' ) );
				
		add_submenu_page( 'dsol_Settings', __( 'Meeting Room Settings - Branches', 'book-a-room' ), __( 'Branch Admin', 'book-a-room' ), 'manage_options', 'dsol_Settings_Branches',  array( 'dsol_settings_branches', 'dsol_admin_branches' ) );

		add_submenu_page( 'dsol_Settings', __( 'Meeting Room Settings - Rooms', 'book-a-room' ), __( 'Room Admin', 'book-a-room' ), 'manage_options', 'dsol_Settings_Rooms',  array( 'dsol_settings_rooms', 'dsol_admin_rooms' ) );

		add_submenu_page( 'dsol_Settings', __( 'Meeting Room Settings - Room Containers', 'book-a-room' ), __( 'Containers Admin', 'book-a-room' ), 'manage_options', 'dsol_Settings_RoomCont',  array( 'dsol_settings_roomConts', 'dsol_admin_roomCont' ) );
								
		add_submenu_page( 'dsol_Settings', "", '<span style="display:block; margin:1px 0 1px -5px; padding:0; height:2px; line-height:1px; background:#DDD;"></span>', "manage_options", "#" );
		
		add_submenu_page( 'dsol_Settings', __( 'Meeting Room Settings - Email', 'book-a-room' ), __( 'Email Admin', 'book-a-room' ), 'manage_options', 'dsol_Settings_Email',  array( 'dsol_settings_email', 'dsol_admin_email' ) );
		
		

		# Help files
		add_menu_page( __( 'Bookaroom Help', 'book-a-room' ), __( 'Bookaroom Help', 'book-a-room' ), 'manage_options', 'Bookaroom_Help', array( 'dsol_help', 'showHelp' ) );

		add_submenu_page( 'Bookaroom_Help', __( 'Meeting Room Help', 'book-a-room' ), __( 'Main Help', 'book-a-room' ), 'manage_options', 'Bookaroom_Help',  array( 'dsol_help', 'showHelp' ) );
		
		#add_submenu_page( 'Bookaroom_Help', 'Meeting Room Help', 'Setup Help', 'manage_options', 'Bookaroom_Help_Setup',  array( 'dsol_help', 'showHelp_setup' ) );
		
		#initialize		
		add_action( 'admin_init', array( 'dsol_settings', 'dsol_init' ) );
	}
	

	
	public static function dsol_init()
	{
		register_setting( 'dsol_options', 'dsol_alertEmail' );
		register_setting( 'dsol_options', 'dsol_baseIncrement' );
		register_setting( 'dsol_options', 'dsol_baseIncrement' );
		register_setting( 'dsol_options', 'dsol_bufferSize' );
		register_setting( 'dsol_options', 'dsol_cleanupIncrement' );
		register_setting( 'dsol_options', 'dsol_reserveAllowed' );
		register_setting( 'dsol_options', 'dsol_reserveBuffer' );
		register_setting( 'dsol_options', 'dsol_reservedColor' );
		register_setting( 'dsol_options', 'dsol_reservedFont' );
		register_setting( 'dsol_options', 'dsol_setupColor' );
		register_setting( 'dsol_options', 'dsol_setupFont' );
		register_setting( 'dsol_options', 'dsol_setupIncrement' );
		register_setting( 'dsol_options', 'dsol_reservation_URL' );
		register_setting( 'dsol_options', 'dsol_defaultEmailDaily' );
		register_setting( 'dsol_options', 'dsol_daysBeforeRemind' );
		
		register_setting( 'dsol_options', 'dsol_eventLink' );
		
		register_setting( 'dsol_options', 'dsol_content_contract' );
	
		
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