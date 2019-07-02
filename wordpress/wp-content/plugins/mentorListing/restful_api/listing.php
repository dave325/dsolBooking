<?php 
class Listing_Posts_Controller 
{

  public function __construct()
  {
    $this->namespace = 'mentor-listing/v1';
    $this->resource_name = 'listings';
  }

  public function register_routes()
  {

    register_rest_route($this->namespace, '/listings', array(
      // Here we register the readable endpoint for collections.
      array(
          'methods'   => 'POST',
          'callback'  => array($this, 'get_listings_all')
      )
    ));

    register_rest_route($this->namespace, '/listing/create', array(
      // Here we register the readable endpoint for collections.
      array(
          'methods'   => 'POST',
          'callback'  => array($this, 'create_listing')
      )
    ));

    register_rest_route($this->namespace, '/listing/:id', array(
      // Here we register the readable endpoint for collections.
      array(
          'methods'   => 'POST',
          'callback'  => array($this, 'get_listing')
      )
    ));

    register_rest_route($this->namespace, '/listing/:id/edit', array(
      // Here we register the readable endpoint for collections.
      array(
          'methods'   => 'POST',
          'callback'  => array($this, 'edit_listing')
      )
    ));

    register_rest_route($this->namespace, '/listing/:id/delete', array(
      // Here we register the readable endpoint for collections.
      array(
          'methods'   => 'POST',
          'callback'  => array($this, 'delete_listing')
      )
    ));

  }

  public function get_listings_all(WP_REST_Request $request)
  {

  }

  public function create_listing(WP_REST_Request $request)
  {

  }

  public function get_listing(WP_REST_Request $request)
  {

  }

  public function edit_listing(WP_REST_Request $request)
  {

  }

  public function delete_listing(WP_REST_Request $request)
  {

  }
  
}