<?php

/**
 * 
 *  Kelvin: Will add more later on
 * 
 * 
 */

class Authentication{

  /**
   * Check permissions for the posts.f
   *
   * @param WP_REST_Request $request Current request.
   */
  function get_items_permissions_check($request)
  {
      if (!current_user_can('read')) {
          return new WP_Error('rest_forbidden', esc_html__('You cannot view the post resource.'), array('status' => $this->authorization_status_code()));
      }
      return true;
  }

  /**
   * Check permissions for the posts.f
   *
   * @param WP_REST_Request $request Current request.
   */
 function param(WP_REST_Request $request)
  {
      return rest_ensure_response($request->get_body());
  }

  /**
   *  Sets up the proper HTTP status code for authorization.
   */
  public static function authorization_status_code()
  {

      $status = 401;

      if (!is_user_logged_in()) {
          $status = 403;
      }else{
          $statud = 2000;
      }

      return $status;
  }

}
?>