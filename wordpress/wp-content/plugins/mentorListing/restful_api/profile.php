<?php 
class Profile_Posts_Controller 
{

  public function __construct()
  {
    $this->namespace = 'mentor-listing/v1';
    $this->resource_name = 'profiles';
  }

  public function register_routes()
  {

    register_rest_route($this->namespace, '/profiles', array(
      // Here we register the readable endpoint for collections.
      array(
          'methods'   => 'POST',
          'callback'  => array($this, 'get_profiles_all')
      )
    ));

    register_rest_route($this->namespace, '/profile/create', array(
      // Here we register the readable endpoint for collections.
      array(
          'methods'   => 'POST',
          'callback'  => array($this, 'create_profile')
      )
    ));

    register_rest_route($this->namespace, '/profile/:id', array(
      // Here we register the readable endpoint for collections.
      array(
          'methods'   => 'POST',
          'callback'  => array($this, 'get_profile')
      )
    ));

    register_rest_route($this->namespace, '/profile/:id/edit', array(
      // Here we register the readable endpoint for collections.
      array(
          'methods'   => 'POST',
          'callback'  => array($this, 'edit_profile')
      )
    ));

    register_rest_route($this->namespace, '/profile/:id/delete', array(
      // Here we register the readable endpoint for collections.
      array(
          'methods'   => 'POST',
          'callback'  => array($this, 'delete_profile')
      )
    ));

  }

  public function get_profiles_all(WP_REST_Request $request)
  {

  }

  public function create_profile(WP_REST_Request $request)
  {

  }

  public function get_profile(WP_REST_Request $request)
  {

  }

  public function edit_profile(WP_REST_Request $request)
  {

  }

  public function delete_profile(WP_REST_Request $request)
  {

  }

  
}