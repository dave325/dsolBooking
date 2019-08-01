<?php
require_once('Authentication.php');

class Listing_Posts_Controller
{

  public function __construct()
  {
    $this->namespace = 'mentor-listing/v1';
    $this->resource_name = 'listings';
  }

  public function register_routes()
  { 
    register_rest_route($this->namespace, '/testlisting', array(
      // Here we register the readable endpoint for collections.
      array(
        'methods'   => 'GET',
        'callback'  => array($this, 'test')
      )
    ));

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

    register_rest_route($this->namespace, '/listing/(?P<id>\d+)', array(
      // Here we register the readable endpoint for collections.
      array(
        'methods'   => 'POST',
        'callback'  => array($this, 'get_listing')
      )
    ));

    register_rest_route($this->namespace, '/listing/edit', array(
      // Here we register the readable endpoint for collections.
      array(
        'methods'   => 'POST',
        'callback'  => array($this, 'edit_listing')
      )
    ));

    register_rest_route($this->namespace, '/listing/delete', array(
      // Here we register the readable endpoint for collections.
      array(
        'methods'   => 'POST',
        'callback'  => array($this, 'delete_listing')
      )
    ));
  }

  public function test(WP_REST_Request $request){
    $success = 'You have successfully connected to the listing TEST route';
    return $success;

  } 


  public function get_listings_all(WP_REST_Request $request)
  {
    global $wpdb;
    // if (Authentication::authorization_status_code() == 200) {
      $sql = "SELECT m.mentorId, 
                    m.mentor_name, 
                    m.email AS mentorEmail, 
                    a.startTime AS openTime, 
                    a.endTime AS closeTime, 
                    a.recurring,
                    c.mapId, 
                    c.date, 
                    r.reservationId, 
                    r.email, 
                    r.name, 
                    r.phone, 
                    rt.timeId, 
                    rt.startTime, 
                    rt.endTime
                FROM wp_Mentor m
                JOIN wp_AvailableTime a
                ON m.mentorId = a.mentorId
                JOIN wp_ReservationMentorMap c
                ON a.mentorId = c.mentorId
                JOIN wp_reservation r
                ON c.reservationId = r.reservationId
                JOIN wp_reservationTime rt
                ON r.timeId = rt.timeId
                ORDER BY m.mentorId;";

      $results = $wpdb->get_results($sql, ARRAY_A);

      if ($wpdb->num_rows > 0) {
        return rest_ensure_response($results);
      }

    // }

  }

  /**
   * 
   */
  public function create_listing(WP_REST_Request $request)
  {
    global $wpdb;

    // if (Authentication::authorization_status_code() == 200) {

    /**
     *   Required info:
     *    - mentorId 
     *    - startTime
     *    - endTime
     *    - email
     *    - name
     *    - phone
     */

      $post_array = $request->get_json_params();
      
      // Time: YYYY-MM--DD HH:MM:SS
      $wpdb->insert(
        wp_reservationTime, 
        array(
          "startTime" => $post_array['startTime'],
          "endTime" => $post_array['endTime'],
        )
      );

      $timeId = $wpdb->insert_id;

      $wpdb->insert(
        wp_reservation,
        array (
          "email" => $post_array['email'],
          "name" => $post_array['name'],
          "phone" => $post_array['phone'],
          "timeId" => $timeId
        )
      );

      $resId = $wpdb->insert_id;
      
      $date = explode(" ", $startTime)[0];

      $wpdb->insert(
        wp_ReservationMentorMap,
        array (
          "mentorId" => $post_array['mentorId'],
          "reservationId" => $resId,
          "date" => $date
        )
      );

      # Return success
      return rest_ensure_response("Success");

    // }
  }

  /**
   * 
   */


  public function get_listing(WP_REST_Request $request)
  {
    global $wpdb;
    // if (Authentication::authorization_status_code() == 200) {

    /**
     *   Required info: 
     *   - mentorId
     * 
     */

     $mentorId = is_numeric($request['id']) ? intval($request['id']) : null;
    
     if ($mentorId){
            $sql = "SELECT m.mentorId, 
            a.recurring,
            c.mapId, 
            c.date, 
            r.reservationId, 
            r.email, 
            r.name, 
            r.phone, 
            rt.timeId, 
            rt.startTime, 
            rt.endTime
      FROM wp_Mentor m
      JOIN wp_AvailableTime a
      ON m.mentorId = a.mentorId
      JOIN wp_ReservationMentorMap c
      ON a.mentorId = c.mentorId
      JOIN wp_reservation r
      ON c.reservationId = r.reservationId
      JOIN wp_reservationTime rt
      ON r.timeId = rt.timeId
      WHERE m.mentorId = $mentorId
      ORDER BY m.mentorId;";

      $results = $wpdb->get_results($sql, ARRAY_A);

      if ($wpdb->num_rows > 0) {
        return rest_ensure_response($results);
      }

     } else {
      return 'MentorId is NULL';
     }
     
     // }

  }


  public function edit_listing(WP_REST_Request $request)
  { 
    global $wpdb;
    // if (Authentication::authorization_status_code() == 200) {

    /**
     *   Required info: 
     * 
     *    - timeId
     *    - startTime AND/OR endTime
     * 
     */

    

        // user is logged in 
        $current_user = wp_get_current_user();
        $mentorId = $current_user->ID;

        $post_array = $request->get_json_params();  
        $times = $post_array['times'];
        $times_size = count($post_array['times']);

        for ($i=0; $i<$times_size; $i++){
          $timeId = $times[$i]['timeId'];
          if ($timeId){
            // update startTime
            $startTime = $times[$i]['startTime'];
            $sql = "UPDATE wp_reservationTime rt SET rt.startTime = \"$startTime\" WHERE rt.timeId = $timeId;";
            $wpdb->query($sql);

            // update endTime
            $endTime = $times[$i]['endTime'];
            $sql = "UPDATE wp_reservationTime rt SET rt.endTime = \"$endTime\" WHERE rt.timeId = $timeId;";
            $wpdb->query($sql);

            // convert timestamp to date format & GET only the YYYY-MM--DD
            $date = explode(" ", $times[$i]['startTime'])[0];

            // date: YYYY-MM--DD 
            $sql = "UPDATE wp_ReservationMentorMap c SET c.date = \"$date\" WHERE c.reservationId IN ( SELECT r.reservationId FROM reservation r WHERE r.timeId = $timeId) AND c.mentorId = $mentorId;";
            $wpdb->query($sql);

          } 
        }

        # Return success
        return rest_ensure_response("Success");

      // } else {
        // user is not logged in as the current user
      // }

  }

  public function delete_listing(WP_REST_Request $request)
  {
    global $wpdb;
    // if (Authentication::authorization_status_code() == 200) {

    /**
     *   Required info: 
     *    - timeId
     */

    $current_user = wp_get_current_user();
    $mentorId = $current_user->ID;
    
    $post_array = $request->get_json_params();
    $timeId = $post_array['time'];

    // if ($mentorId){

      $sql = "DELETE FROM wp_ReservationMentorMap WHERE reservationId IN (SELECT r.reservationId FROM wp_reservation r WHERE r.timeId = $timeId) AND mentorId = $mentorId;";
      $wpdb->query($sql);

      $sql = "DELETE FROM wp_reservationTime WHERE timeId = $timeId;";
      $wpdb->query($sql);

      $sql = "DELETE FROM wp_reservation WHERE timeId = $timeId;";
      $wpdb->query($sql);

      # Return success
      return rest_ensure_response("Success");

    // } else {
      // not current user
      
    // }
    // }
   }
}
?>