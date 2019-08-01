<?php

require_once(DSOL_MENTOR_PATH . 'restful_api/listing.php');
require_once(DSOL_MENTOR_PATH . 'restful_api/profile.php');
 
/**
 * Register the new routes from MY_REST_Posts_Controller
 * 
 */
function dsol_mentor_register_my_rest_routes()
{

  $listing_controller = new Listing_Posts_Controller();
  $listing_controller->register_routes();

  $profile_controller = new Profile_Posts_Controller();
  $profile_controller->register_routes();


}

add_action('rest_api_init', 'dsol_mentor_register_my_rest_routes');
?>