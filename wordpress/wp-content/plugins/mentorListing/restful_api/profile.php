<?php 
require_once('Authentication.php');

class Profile_Posts_Controller 
{

  public function __construct()
  {
    $this->namespace = 'mentor-listing/v1';
    $this->resource_name = 'profiles';
  }

  public function register_routes()
  {
    register_rest_route($this->namespace, '/testprofile', array(
      // Here we register the readable endpoint for collections.
      array(
        'methods'   => 'GET',
        'callback'  => array($this, 'test')
      )
    ));

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

    register_rest_route($this->namespace, '/profile/(?P<id>\d+)', array(
      // Here we register the readable endpoint for collections.
      array(
          'methods'   => 'POST',
          'callback'  => array($this, 'get_profile')
      )
    ));

    register_rest_route($this->namespace, '/profile/edit', array(
      // Here we register the readable endpoint for collections.
      array(
          'methods'   => 'POST',
          'callback'  => array($this, 'edit_profile')
      )
    ));

    register_rest_route($this->namespace, '/profile/qualifications/edit', array(
      // Here we register the readable endpoint for collections.
      array(
          'methods'   => 'POST',
          'callback'  => array($this, 'edit_qualifications')
      )
    ));

    register_rest_route($this->namespace, '/profile/delete', array(
      // Here we register the readable endpoint for collections.
      array(
          'methods'   => 'POST',
          'callback'  => array($this, 'delete_profile')
      )
    ));

  }

  public function test(WP_REST_Request $request){
    $success = 'You have successfully connected to the profile TEST route';
    return $success;

  } 

  public function get_profiles_all(WP_REST_Request $request)
  {
    global $wpdb;
    // if (Authentication::authorization_status_code() == 200) {

      # Get certifications for all profiles
      $sql = "SELECT 
                m.mentorId, 
                m.mentor_name, 
                m.email, 
                JSON_ARRAYAGG(c.certificationId) AS certificationIds, 
                JSON_ARRAYAGG(c.certificationName) AS certificationNames
        FROM wp_Mentor m
        JOIN wp_certification c 
        ON m.mentorId = c.mentorId
        GROUP BY m.mentorId, m.mentor_name, m.email;";

      $cert_results = $wpdb->get_results($sql, ARRAY_A);

      # Get skills for all profiles
      $sql = "SELECT 
                m.mentorId, 
                m.mentor_name, 
                m.email, 
                JSON_ARRAYAGG(s.skillId) AS skillIds, 
                JSON_ARRAYAGG(s.skillName) AS skillNames 
        FROM wp_Mentor m
        JOIN wp_skill s
        ON m.mentorId = s.mentorId
        GROUP BY m.mentorId, m.mentor_name, m.email;";

      $skill_results = $wpdb->get_results($sql, ARRAY_A);

      $results = array 
      (
        $cert_results,
        $skill_results
      );

      if ($wpdb->num_rows > 0) {
        return rest_ensure_response($results);
      } 
    // }
  }

  public function create_profile(WP_REST_Request $request)
  {
    global $wpdb;
    // if (Authentication::authorization_status_code() == 200) {

    /**
     *   Required info: 
     * 
     *    - mentor_name
     *    - email
     * 
     *    - startTime
     *    - endTime
     *    - recurring
     * 
     *    - skillName
     * 
     *    - certificationName
     * 
     */

      $post_array = $request->get_json_params();
      
      $mentorName = $post_array['mentor']['mentorName'];
      
      $startTime = $post_array['availableTime']['startTime'];
      $endTime = $post_array['availableTime']['endTime'];
      $recurring = $post_array['availableTime']['recurring'];

      # Add into mentor
      $wpdb->insert(
        wp_Mentor, 
        array(
          "mentor_name" => $post_array['mentor']['mentorName'],
          "email" => $post_array['mentor']['email']
        )
      );

      $mentorId = $wpdb->insert_id;

      # Add into availableTime
      # time in YYYY-MM--DD HH:MM:SS format
      $wpdb->insert(
        wp_AvailableTime, 
        array(
          "startTime" => $post_array['availableTime']['startTime'],
          "endTime" => $post_array['availableTime']['endTime'],
          "recurring" => $post_array['availableTime']['recurring'],
          "mentorId" => $mentorId
        )
      );

      # Add into skills
      $skills_size = count($post_array['skills']);
      for ($i=0; $i<$skills_size; $i++){
        $wpdb->insert(
          wp_skill, 
          array(
            "skillName" => $post_array['skills'][$i]['skillName'],
            "mentorId" => $mentorId
          )
        );
      }

      # Add into certification
      $cert_size = count($post_array['certification']);
      for ($i=0; $i<$cert_size; $i++){
        $wpdb->insert(
          wp_certification, 
          array(
            "certificationName" => $post_array['certification'][$i]['certificationName'],
            "mentorId" => $mentorId
          )
        );
      }
     
      // # Return success
      return rest_ensure_response("Success");
    // }
  }

  public function get_profile(WP_REST_Request $request)
  {
    global $wpdb;
    // if (Authentication::authorization_status_code() == 200) {

    /**
     *   Required info: 
     * 
     *    - mentorId
     * 
     */

      $mentorId = is_numeric($request['id']) ? intval($request['id']) : null;

      # Get certifications for all profiles
      $sql = "SELECT 
                m.mentorId, 
                m.mentor_name, 
                m.email, 
                JSON_ARRAYAGG(c.certificationId) AS certificationIds, 
                JSON_ARRAYAGG(c.certificationName) AS certificationNames
        FROM wp_Mentor m
        JOIN wp_certification c 
        ON m.mentorId = c.mentorId
        WHERE m.mentorId = $mentorId;";

      $cert_result = $wpdb->get_results($sql, ARRAY_A);

      # Get skills for all profiles
      $sql = "SELECT 
                m.mentorId, 
                m.mentor_name, 
                m.email, 
                JSON_ARRAYAGG(s.skillId) AS skillIds, 
                JSON_ARRAYAGG(s.skillName) AS skillNames 
        FROM wp_Mentor m
        JOIN wp_skill s
        ON m.mentorId = s.mentorId
        WHERE m.mentorId = $mentorId;";

      $skill_result = $wpdb->get_results($sql, ARRAY_A);

      $results = array 
      (
        $cert_result,
        $skill_result
      );

      if ($wpdb->num_rows > 0) {
        return rest_ensure_response($results);
      } 
    // }
  }

  public function edit_profile(WP_REST_Request $request)
  {
    global $wpdb;
    // if (Authentication::authorization_status_code() == 200) {

    /**
     *   Required info: 
     *   - name
     *   - email
     * 
     */

      $current_user = wp_get_current_user();
      $mentorId = $current_user->ID;

      // if ($mentorId){
        // user is logged in as the current user
        $post_array = $request->get_json_params();

        $name = $post_array['name'];
        $email = $post_array['email'];
        
        $sql = "UPDATE wp_Mentor m SET m.mentor_name = $name WHERE m.mentorId = $mentorId;";
        $wpdb->query($sql);

        $sql = "UPDATE wp_Mentor m SET m.email = $email WHERE m.mentorId = $mentorId;";
        $wpdb->query($sql);

        # Return success
        return rest_ensure_response("Success");

      // } else {
        // user is not logged in as the current user
      // }
    
  }

  public function edit_qualifications(WP_REST_Request $request)
  {
    global $wpdb;
    // if (Authentication::authorization_status_code() == 200) {

    /**
     *   Required info: 
     *   - skills []
     *   - certifications []
     * 
     */

      $current_user = wp_get_current_user();
      $mentorId = $current_user->ID;
      
      // if ($mentorId){
        // user is logged in as the current user
        $post_array = $request->get_json_params();

        $skills = $post_array['skills']; 
        $skills_length = count($skills);

        for ($i=0; $i<$skills_length; $i++){
          if ($skills[$i]['skillId']){
            // update skill
            $sql = "UPDATE wp_skill SET skillName = $skills[$i]['skillName'] WHERE mentorId = $mentorId";
            $wpdb->query($sql);
          } else {
            // create cert
            $wpdb->insert(
              wp_skill, 
              array(
                "skillName" => $skills[$i]['skillName'],
                "mentorId" => $mentorId
              )
            );
          }
        }

        $certifications = $post_array['certifications']; 
        $certs_length = count($certifications);

        for ($i=0; $i<$certs_length; $i++){
          if ($certifications[$i]['certificationId']){
            // update cert
            $sql = "UPDATE wp_certification SET certificationName = $certifications[$i]['certificationName'] WHERE mentorId = $mentorId";
            $wpdb->query($sql);
          } else {
            // create cert
            $wpdb->insert(
              wp_certification, 
              array(
                "certificationName" => $certifications[$i]['certificationName'],
                "mentorId" => $mentorId
              )
            );
          }
        }

        # Return success
        return rest_ensure_response("Success");

      // } else {
        // user is not logged in as the current user
      // }
    
  }

  
  public function delete_profile(WP_REST_Request $request)
  {
    global $wpdb;
    // if (Authentication::authorization_status_code() == 200) {


      /**
     *   Required info: 
     * 
     *    - mentorId
     * 
     */

      $current_user = wp_get_current_user();
      $mentorId = $current_user->ID;
    
    // if ($mentorId){
      // user is logged in as the current user
      $sql = "DELETE FROM wp_certification WHERE mentorId = $mentorId;";
      $wpdb->query($sql);
      
      $sql = "DELETE FROM wp_skill WHERE mentorId = $mentorId;";
      $wpdb->query($sql);

      $sql = "DELETE FROM wp_AvailableTime WHERE mentorId = $mentorId;";
      $wpdb->query($sql);

      $sql = "DELETE FROM wp_reservationTime 
              WHERE timeId IN (
                SELECT r.timeId 
                FROM wp_reservation r 
                WHERE r.reservationId IN (
                  SELECT c.reservationId 
                  FROM wp_ReservationMentorMap c 
                  WHERE c.mentorId = $mentorId
              ));";
      $wpdb->query($sql);
      
      $sql = "DELETE FROM wp_reservation
              WHERE reservationId IN (
                SELECT r.reservationId 
                FROM wp_ReservationMentorMap r
                WHERE r.mentorId = $mentorId
              );";
      $wpdb->query($sql);

      $sql = "DELETE FROM wp_ReservationMentorMap
              WHERE mentorId = $mentorId;";
      $wpdb->query($sql);
      
      $sql = "DELETE FROM wp_Mentor WHERE mentorId = $mentorId;";
      $wpdb->query($sql);

      # Return success
      return rest_ensure_response("Success");

    // } else {
      // user is not logged in as the current user

    // }   
    
    // }
  }
  
}
?>