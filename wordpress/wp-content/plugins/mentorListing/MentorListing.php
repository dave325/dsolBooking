<?php
/*
Plugin Name: D Solutions Mentor Listing
Plugin URI: https://github.com/dave325/mentorListing
Description: Figure it out
Version: 1.0.0
Author: David Solutions 
Author URI: http://dataramsolutions.com
License: GPLv2 or later
Text Domain: dsol-booking
*/
global $dsol_mentor_version;
$dsol_mentor_version = "1";

define('DSOL_MENTOR_PATH', plugin_dir_path(__FILE__));

require_once(DSOL_MENTOR_PATH . 'restful_api/init_routes.php');
/**
 * Change init functions and class
 */
register_activation_hook(__FILE__, array('DsolMentorListingHooks', 'on_activate'));
register_deactivation_hook(__FILE__, array('DsolMentorListingHooks', 'on_deactivate'));
register_uninstall_hook(__FILE__, array('DsolMentorListingHooks', 'on_uninstall'));

add_action('init', 'dSol_mentor_enqueuer');

#add_filter( 'the_content',  array( 'dsol_public', 'mainForm' ) );

add_action('admin_notices', array('DsolMentorListingHooks', 'plugin_activation_message'));

//add_action('admin_menu', array('dsol_settings', 'add_settingsPage'));

#add_filter(		'gform_pre_render',			array( 'dsol_creditCardPayments', 'returnIncomingValidation' ) );
#add_action(		'gform_after_submission',	array( 'dsol_creditCardPayments', 'finishedSubmission' ));

function dSol_mentor_enqueuer()
{

    // TODO - Set up application shortcodepages here 
    global $dsol_mentor_version;
    wp_enqueue_script('jquery');
    add_shortcode('dsol_app', array('DSOLPage', 'display'));
   // add_shortcode('dsol_app1', array('DSOLPage', 'display1'));
 
    //wp_enqueue_style('vuematerialcss',"https://unpkg.com/vue-material/dist/vue-material.min.css",array(), '');
    //wp_enqueue_style('vuematerialdefaultcss',"https://unpkg.com/vue-material/dist/theme/default.css",array(), '');   
    wp_enqueue_style('bootstrapcss',"https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css",array(), '');
    wp_enqueue_style('font','//fonts.googleapis.com/css?family=Roboto:400,500,700,400italic|Material+Icons',array(), '');

    wp_enqueue_style('css1',plugins_url("/mentorListing/mentor-listing-src/dist/css/app.bb0b1322.css"),array(), '');
    wp_enqueue_style('css2',plugins_url("/mentorListing/mentor-listing-src/dist/css/chunk-vendors.b6abd211.css"),array(), '');
    wp_enqueue_script('mentor-chunk1',plugins_url("/mentorListing/mentor-listing-src/dist/js/app.734a9601.js"),array(),'');
    wp_enqueue_script('mentor-chunk2',plugins_url("/mentorListing/mentor-listing-src/dist/js/chunk-vendors.ae68a97b.js"),array(),'');
    wp_enqueue_style('css3',plugins_url("/mentorListing/mentor-listing-src/dist/css/chunk-vendors.b6abd211.css"),array(), '');
    wp_enqueue_style('css4',plugins_url("/mentorListing/mentor-listing-src/dist/css/app.bb0b1322.css"),array(), '');

   // wp_enqueue_script('vuejs', 'https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.10/vue.min.js',array(), '', true);
     //wp_enqueue_script('vuematerialjs',"https://unpkg.com/vue-material",array(), '', true);   
    wp_enqueue_script('momentjs', 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js',array(), '');
   // wp_enqueue_script('mentor-frontend',plugins_url("/mentorListing/mentor-listing-src/src/phptest.js"),array('vuejs'),'',true);
    wp_enqueue_script('mentor-frontend',plugins_url("/mentorListing/mentor-listing-src/dist/js/chunk-vendors.ae68a97b.js"),array(),'',true);
    wp_enqueue_script('mentor-chunk',plugins_url("/mentorListing/mentor-listing-src/dist/js/app.734a9601.js"),array(),'',true);

    //wp_enqueue_script('mentor-test',plugins_url("/mentorListing/mentor-listing-src/dist/index.html"),array('vuejs'),'',true);
    wp_localize_script(
        'mentor-frontend',
        'info',
        array(
            'partials' => plugins_url('mentorListing/mentor-listing-src/'),
            "path" =>  get_site_url(),
            'nonce' => wp_create_nonce('wp_rest'),
            'username' => wp_get_current_user()
        )
    );
}

class DSOLPage{
    public static function display(){
        require_once(DSOL_MENTOR_PATH .'info.php');
/*
$v8 = new V8Js();

$v8->executeString('var process = { env: { VUE_ENV: "server", NODE_ENV: "production" }}; this.global = { process: process };');
$v8->executeString($vue_source);
$v8->executeString($renderer_source);
$v8->executeString($app_source);
*/
    }
    public static function display1(){
        require_once(DSOL_MENTOR_PATH .'info1.php');
    }
}
class DsolMentorListingHooks
# simple class for activating, deactivating and uninstalling plugin
{
    public static function on_activate($dbOnly = false)
    # this is only run when hooked by activating plugin
    {

        global $wpdb;
        global $dsol_mentor_version;
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        # create table for Mentor
        $sql = "CREATE TABLE {$wpdb->prefix}Mentor
        (
            `mentorId`    int NOT NULL AUTO_INCREMENT,
            `mentor_name` varchar(128) NOT NULL ,
            `email` varchar(45) NOT NULL,
        PRIMARY KEY (`mentorId`)
        );";
        dbDelta($sql);

        # create table for skill
        $sql = "CREATE TABLE {$wpdb->prefix}skill
        (
            `skillId` int NOT NULL AUTO_INCREMENT,
            `skillName` varchar(128) NOT NULL,
            `mentorId` int NOT NULL,
        PRIMARY KEY (`skillId`)
        );";
        dbDelta($sql);
        
        # create table for certification 
        $sql = "CREATE TABLE {$wpdb->prefix}certification
        (
            `certificationId` int NOT NULL AUTO_INCREMENT,
            `certificationName` varchar(128) NOT NULL,
            `mentorId` int NOT NULL,
        PRIMARY KEY (`certificationId`)
        );";
        dbDelta($sql);

         # create table for availableTime
         $sql = "CREATE TABLE {$wpdb->prefix}AvailableTime
         (
            `timeId`        int NOT NULL AUTO_INCREMENT,
            `startTime`     timestamp NOT NULL ,
            `endTime`       timestamp NOT NULL ,
            `recurring`     tinyint NOT NULL ,
            `mentorId`      int NOT NULL ,
            `reservationId` int NULL ,
        PRIMARY KEY (`timeId`)
        );";
        dbDelta($sql);

        # create table for reservationTime
        $sql = "CREATE TABLE {$wpdb->prefix}reservationTime
        (
            `timeId`    int NOT NULL AUTO_INCREMENT,
            `startTime` timestamp NOT NULL,
            `endTime`   timestamp NOT NULL,
        PRIMARY KEY (`timeId`)
        );";
        dbDelta($sql);

        # create table for reservation
        $sql = "CREATE TABLE {$wpdb->prefix}reservation
        (
            `reservationId` int NOT NULL AUTO_INCREMENT,
            `email`         varchar(45) NOT NULL ,
            `name`          varchar(45) NOT NULL ,
            `phone`         varchar(45) NOT NULL ,
            `timeId`        int NOT NULL,
        PRIMARY KEY (`reservationId`)
        );";
        dbDelta($sql);

        # create table for ReservationMentorMap
        $sql = "CREATE TABLE {$wpdb->prefix}ReservationMentorMap
        (   
            `mapId` int NOT NULL AUTO_INCREMENT,
            `mentorId`      int NOT NULL,
            `reservationId` int NOT NULL,
            `date`          date NOT NULL,
        PRIMARY KEY (`mapId`)
        );";
        dbDelta($sql);

        if ($dbOnly) {
            update_option("dsol_mentor_version", $dsol_mentor_version);
            return true;
        }
    }

    public static function plugin_activation_message()
    {
        global $dsol_mentor_version;
        if ($dsol_mentor_version !== get_option("dsol_mentor_version")) {
            DsolMentorListingHooks::on_activate(true);
        }

        if (get_option("dsol_installing") == 'yes') {
            update_option('dsol_installing', 'no');
            $html = '<div class="updated">' . __('(Please install the events calendar plugin. You will not be able to view the calendar until it is installed and configured.)', 'book-a-room') .  '</p>' .
                '<p>' . __('To set up your meeting rooms, first click on Meeting Room Settings on the left hand menu. There are descriptions of each option at the bottom of the page.', 'book-a-room') . '</p>' .
                '<p>' . __('Next, set up your amenities. These should include any extras that can be reserved with the room like coffee urns, dry erase boards and projectors.', 'book-a-room') . '</p>' .
                '<p>' . __('Once you\'ve got your amenities set, add your Branches. This is also where you configure the hours, address and image for each branch.', 'book-a-room') . '</p>' .
                '<p>' . __('Next, add in your Rooms. A room is a <em><strong>physical</strong></em> space that can be reserved. If you have 2 meetings rooms, even if they can be reserved together, you would only add the two physical locations as a room.', 'book-a-room') . '</p>' .
                '<p>' . __('Finally, add in your Room Containers. Room containers are <em><strong>virtual</strong></em> spaces that are actually being reserved. If you have two rooms that can be reserved separately or together as one larger space, you would add 3 containers; two would each contain one room and the third would contain both rooms.', 'book-a-room') . '</p>' .
                '<p>' . __('To configure alerts and content, make sure you edit the Email Admin and Content Admin!', 'book-a-room') . '</p>' .
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
        global $dsol_mentor_version;


        delete_option("dsol_db_version");
    }
}


class dsol_mentor_settings
# main settings functions
{
    public static function add_settingsPage()
    {



      
        #initialize		
        add_action('admin_init', array('dsol_settings', 'dsol_init'));
    }



    public static function dsol_init()
    {
       
    }
    
}
?>