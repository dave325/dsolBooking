<?php

require('./listing.php');
require('./profile.php');
 
/**
 * Register the new routes from MY_REST_Posts_Controller
 * 
 */
function prefix_register_my_rest_routes()
{

  $listing_controller = new Listing_Posts_Controller();
  $listing_controller->register_routes();

  $profile_controller = new Profile_Posts_Controller();
  $profile_controller->register_routes();


}

add_action('rest_api_init', 'prefix_register_my_rest_routes');