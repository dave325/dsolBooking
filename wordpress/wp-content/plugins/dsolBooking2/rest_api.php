<?php
class My_REST_Posts_Controller
{
    // Here initialize our namespace and resource name.
    public function __construct()
    {
        $this->namespace     = 'dsol-booking/v1';
        $this->resource_name = 'posts';
    }

    // Register our routes.
    public function register_routes()
    {
        register_rest_route($this->namespace, '/test', array(
            // Here we register the readable endpoint for collections.
            array(
                'methods'   => 'POST',
                'callback'  => array($this, 'get_items')
            )
        ));
        register_rest_route($this->namespace, '/getRoomInfo', array(
            // Notice how we are registering multiple endpoints the 'schema' equates to an OPTIONS request.
            array(
                'methods'   => 'POST',
                'callback'  => array($this, 'getRoomInfo')

            )
        ));
        register_rest_route($this->namespace, '/bookRoom', array(
            // Notice how we are registering multiple endpoints the 'schema' equates to an OPTIONS request.
            array(
                'methods'   => 'POST',
                'callback'  => array($this, 'bookRoom')

            )
        ));

        register_rest_route($this->namespace, '/getReservations', array(
            // Here we register the readable endpoint for collections.
            array(
                'methods'   => 'POST',
                'callback'  => array($this, 'getReservations')
            )
        ));

        register_rest_route($this->namespace, '/editUserReservation', array(
            // Here we register the readable endpoint for collections.
            array(
                'methods'   => 'POST',
                'callback'  => array($this, 'editUserReservation')
            )
        ));

        register_rest_route($this->namespace, '/deleteUserResrvation', array(
            // Here we register the readable endpoint for collections.
            array(
                'methods'   => 'POST',
                'callback'  => array($this, 'deleteUserResrvation')
            )
        ));
    }

    /**
     * Check permissions for the posts.f
     *
     * @param WP_REST_Request $request Current request.
     */
    public function get_items_permissions_check($request)
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
    public function param(WP_REST_Request $request)
    {
        return rest_ensure_response($request->get_body());
    }

    /**
     * Grabs the five most recent posts and outputs them as a rest response.
     *
     * @param WP_REST_Request $request Current request.
     */
    public function get_items(WP_REST_Request $request)
    {
        global $wpdb;

        /***
         * Rewrite By David
         *     Sumaita
         *     Removed part of the where clause
         */
        $table_name_reservation = $wpdb->prefix . 'dsol_booking_reservation';
        $table_name_room = $wpdb->prefix . 'dsol_booking_room';
        $table_name_container = $wpdb->prefix . 'dsol_booking_container';
        $table_name_time = $wpdb->prefix . 'dsol_booking_time';
        $table_name_branch = $wpdb->prefix . 'dsol_booking_branch';
        // Get information from frontend POST request
        $room =  $request->get_json_params();
        // If the room value is set and the room is valid check for specific value
        if (isset($room['room']) && $room['room'] > 0) {
            $where = "WHERE {$table_name_reservation}.res_id IS NOT NULL AND {$table_name_reservation}.c_id = {$room['room']}";
        } else {
            $where = "WHERE {$table_name_reservation}.res_id IS NOT NULL";
        }
        $curMonth = date('M');
        // $where .= " AND month(JSON_EXTRACT(JSON_ARRAYAGG({$table_name_time}.start_time) , '$[0]')) = {$curMonth})";
        $sql = "SELECT {$table_name_reservation}.res_id,
                        {$table_name_reservation}.company_name,
                        {$table_name_reservation}.email,
                        {$table_name_reservation}.attendance,
                        {$table_name_reservation}.notes,
                        {$table_name_container}.container_number,
                        {$table_name_container}.c_id,
                        {$table_name_room}.room_number,
                        {$table_name_branch}.b_name,
                        JSON_ARRAYAGG({$table_name_time}.start_time) AS start_time,
                        JSON_ARRAYAGG({$table_name_time}.end_time) AS end_time
        FROM {$table_name_branch}
        LEFT JOIN {$table_name_room} ON {$table_name_branch}.b_id = {$table_name_room}.b_id
        LEFT JOIN {$table_name_container} ON {$table_name_room}.r_id = {$table_name_container}.r_id
        LEFT JOIN {$table_name_reservation} ON {$table_name_container}.c_id = {$table_name_reservation}.c_id
        LEFT JOIN {$table_name_time} ON {$table_name_time}.res_id = {$table_name_reservation}.res_id
        {$where}
        GROUP BY {$table_name_reservation}.res_id,{$table_name_container}.container_number,{$table_name_room}.room_number,{$table_name_branch}.b_name
        ORDER BY JSON_EXTRACT(JSON_ARRAYAGG({$table_name_time}.start_time) , '$[0]');";
        // Return the sql query as an associative array
        $final = $wpdb->get_results($sql, ARRAY_A);
        if ($wpdb->last_error !== '') {
            return new WP_Error(400, ($wpdb->last_error));
        }
        // Loop through each result set
        for ($i = 0; $i < count($final); $i++) {
            // temp variable to store time array
            $temp_time = array();
            // decode results saved from json array in query
            $decode_end_time = json_decode($final[$i]['end_time']);
            $decode_start_time = json_decode($final[$i]['start_time']);
            // Store start and end time in appropriate pairings
            for ($j = 0; $j < sizeof($decode_start_time); $j++) {
                array_push($temp_time, array(
                    "start_time" => $decode_start_time[$j],
                    "end_time" => $decode_end_time[$j]
                ));
            }
            // Push filtered array set to official time set
            $final[$i]['time'] = $temp_time;
        }
        // Return all of our comment response data.
        return rest_ensure_response($final);
    }

    /**
     * Check permissions for the posts.
     *
     * @param WP_REST_Request $request Current request.
     */
    public function get_item_permissions_check($request)
    {
        if (!current_user_can('read')) {
            return new WP_Error('rest_forbidden', esc_html__('You cannot view the post resource.'), array('status' => $this->authorization_status_code()));
        }
        return true;
    }

    /**
     * Grabs the five most recent posts and outputs them as a rest response.
     *
     * @param WP_REST_Request $request Current request.
     */
    public function getRoomInfo(WP_REST_Request $request)
    {

        global $wpdb;

        /***
         * Rewrite By David
         *     Sumaita
         *     Removed part of the where clause
         */
        $table_nameRes = $wpdb->prefix . 'dsol_booking_reservation';
        $table_name_room = $wpdb->prefix . 'dsol_booking_container';
        $sql = "SELECT * FROM " . $table_name_room;
        $final = $wpdb->get_results($sql, ARRAY_A);
        // Return all of our comment response data.
        return rest_ensure_response($final);
    }

    /**
     * Grabs the five most recent posts and outputs them as a rest response.
     *
     * @param WP_REST_Request $request Current request.
     */
    public function bookRoom(WP_REST_Request $request)
    {
        global $wpdb;
        $wpdb->show_errors();
        if (is_user_logged_in()) {
            $data = $request->get_json_params();
            $table_name_reservation = $wpdb->prefix . 'dsol_booking_reservation';
            $table_name_time = $wpdb->prefix . 'dsol_booking_time';
            $start_time = date('Y-m-d H:i:s', $data["arr"][0]["start_time"]);
            $end_time = date('Y-m-d H:i:s', $data["arr"][sizeOf($data["arr"]) - 1]["end_time"]);
            $contNum = $data['room'];
            $timeCheck = "SELECT *
            FROM `$table_name_time`
            LEFT JOIN `$table_name_reservation` ON `$table_name_time`.res_id = `$table_name_reservation`.res_id
            WHERE `$table_name_reservation`=`$contNum` AND  date BETWEEN `$start_time` AND `$end_time` ";
            $res = $wpdb->get_results($timeCheck);
            if (sizeof($res) == 0) {
                if ($data['repeat']['id'] > 0) {
                    $values = array();
                    $place_holders = array();
                    $res_values = array();
                    $res_placeholders = array();
                    $time_sql = "";
                    $res_sql = "INSERT INTO `$table_name_reservation` ('c_id','t_id'.'modified_by','created_at','created_by','company_name','email','attendance','notes') VALUES ";
                    try {
                        //return rest_ensure_response( strtotime($start_time)  );
                        //$d = new DateTime(date('Y-m-d H:i:s', $start_time));
                        //return rest_ensure_response($d);
                        //$d->createFromFormat('Y-m-d H:i:s', $start_time);
                        $time_insert_arr = array();
                        $i = 0;
                        foreach ($data["multipleDates"] as $value) {
                            if ($data["isSeperate"] == 0) {
                                $temp_date = date("Y-m-d", $value);
                                $temp_end_time = date("H:i:s", strtotime($end_time));
                                $temp_start_time = date("H:i:s", strtotime($start_time));
                                $final_end_date = $temp_date . " " . $temp_end_time;
                                $final_start_date = $temp_date . " " . $temp_start_time;
                                $temp_end = date('Y-m-d H:i:s', strtotime("$final_end_date"));
                                $temp_start = date('Y-m-d H:i:s', strtotime("$final_start_date"));
                                $time_insert_arr[] = array(
                                    "start_time" => $temp_start,
                                    "end_time" => $temp_end
                                );
                            } else {
                                for ($i = 0; $i < sizeOf($data["seperateIndexes"]); $i++) {

                                    if ($i == (sizeOf($data['seperateIndexes']) - 1)) {
                                        $temp_date = date("Y-m-d", $value);
                                        $temp_start_time = date("H:i:s", $data["arr"][$data['seperateIndexes'][$i]]["start_time"]);
                                        $temp_end_time = date("H:i:s", strtotime($end_time));
                                        $final_end_date = $temp_date . " " . $temp_end_time;
                                        $final_start_date = $temp_date . " " . $temp_start_time;
                                        $temp_end = date('Y-m-d H:i:s', strtotime("$final_end_date"));
                                        $temp_start = date('Y-m-d H:i:s', strtotime("$final_start_date"));
                                    } else {
                                        $indexLength = $data["seperateIndexes"][$i + 1] - $data["seperateIndexes"][$i];
                                        if ($indexLength == 1) {
                                            $indexLength -= 1;
                                        }
                                        $temp_date = date("Y-m-d", $value);
                                        $temp_start_time = date("H:i:s", $data["arr"][$data['seperateIndexes'][$i]]["start_time"]);
                                        $temp_end_time = date("H:i:s", $data["arr"][$indexLength]["end_time"]);
                                        $final_end_date = $temp_date . " " . $temp_end_time;
                                        $final_start_date = $temp_date . " " . $temp_start_time;
                                        $temp_end = date('Y-m-d H:i:s', strtotime("$final_end_date"));
                                        $temp_start = date('Y-m-d H:i:s', strtotime("$final_start_date"));
                                    }

                                    $time_insert_arr[] = array(
                                        "start_time" => $temp_start,
                                        "end_time" => $temp_end
                                    );
                                }
                            }
                            $i++;
                            /*
                                Holds else statement for previous if $data['arr'] > 0
                            } else {
                                $temp_date = date("Y-m-d", $value);
                                $temp_end_time = date("H:i:s", strtotime($end_time));
                                $temp_start_time = date("H:i:s", strtotime($start_time));
                                $final_end_date = $temp_date . " " . $temp_end_time;
                                $final_start_date = $temp_date . " " . $temp_start_time;
                                $temp_end = date('Y-m-d H:i:s', strtotime("$final_end_date"));
                                $temp_start = date('U', strtotime("$final_start_date"));
                                $time_insert_arr[] = array(
                                    "start_time" => $temp_start,
                                    "end_time" => $temp_end
                                );
                            }
                            */
                        }
                    } catch (\UnexpectedValueException $e) {
                        return rest_ensure_response(array("error", $e));
                    }


                    $wpdb->insert($table_name_reservation, array(
                        "c_id" => $data["room"]["c_id"],
                        "modified_by" => wp_get_current_user()->display_name,
                        "created_at" => current_time('mysql', 1),
                        "modified_at" => current_time('mysql', 1),
                        "created_by" => wp_get_current_user()->user_email,
                        "company_name" => wp_get_current_user()->display_name,
                        "email" => wp_get_current_user()->user_email,
                        "attendance" => $data["numAttend"],
                        "notes" => $data["desc"]
                    ));
                    if ($wpdb->last_error !== '') {
                        $wpdb->query('ROLLBACK');
                        return new WP_Error(400, ('Error adding time'));
                    }
                    $temp_insert_id = $wpdb->insert_id;
                    foreach ($time_insert_arr as $time) {

                        $time['res_id'] = $temp_insert_id;
                        $wpdb->insert($table_name_time, $time);
                        if ($wpdb->last_error !== '') {
                            $wpdb->query('ROLLBACK');
                            return new WP_Error(400, ('Error adding time'));
                        }
                    }
                    /*
                    $wpdb->insert($table_name_time, array(
                        "start_time" => $start_time,
                        "end_time" => $end_time
                    ));
                    $insert_id = $wpdb->insert_id;
                    if ($wpdb->last_error !== '') {
                        return rest_ensure_response($wpdb->last_result);
                    }
                    $wpdb->insert($table_name_reservation, array(
                        "c_id" => $data["room"]["c_id"],
                        "t_id" => $insert_id,
                        "modified_by" => wp_get_current_user()->display_name,
                        "created_at" => current_time('mysql', 1),
                        "modified_at" => current_time('mysql', 1),
                        "created_by" => wp_get_current_user()->user_email,
                        "company_name" => wp_get_current_user()->display_name,
                        "email" => wp_get_current_user()->user_email,
                        "attendance" => $data["numAttend"],
                        "notes" => $data["desc"]
                    ));
                    if ($wpdb->last_error !== '') {
                        $wpdb->print_error();
                    }
                    */

                    return rest_ensure_response(array($data, $time_insert_arr));
                } else {
                    $time_sql = "";
                    $i = 0;
                    try {
                        if (sizeOf($data['arr']) > 1) {

                            if ($data["isSeperate"] == 0) {
                                $temp_date = date("Y-m-d", strtotime($start_time));
                                $temp_end_time = date("H:i:s", strtotime($end_time));
                                $temp_start_time = date("H:i:s", strtotime($start_time));
                                $final_end_date = $temp_date . " " . $temp_end_time;
                                $final_start_date = $temp_date . " " . $temp_start_time;
                                $temp_end = date('Y-m-d H:i:s', strtotime("$final_end_date"));
                                $temp_start = date('Y-m-d H:i:s', strtotime("$final_start_date"));
                                $time_insert_arr[] = array(
                                    "start_time" => $temp_start,
                                    "end_time" => $temp_end
                                );
                            } else {
                                for ($i = 0; $i < sizeOf($data["seperateIndexes"]); $i++) {

                                    if ($i == (sizeOf($data['seperateIndexes']) - 1)) {
                                        $temp_date = date("Y-m-d", strtotime($start_time));
                                        $temp_start_time = date("H:i:s", $data["arr"][$data['seperateIndexes'][$i]]["start_time"]);
                                        $temp_end_time = date("H:i:s", strtotime($end_time));
                                        $final_end_date = $temp_date . " " . $temp_end_time;
                                        $final_start_date = $temp_date . " " . $temp_start_time;
                                        $temp_end = date('Y-m-d H:i:s', strtotime("$final_end_date"));
                                        $temp_start = date('Y-m-d H:i:s', strtotime("$final_start_date"));
                                    } else {
                                        $indexLength = $data["seperateIndexes"][$i + 1] - $data["seperateIndexes"][$i];
                                        if ($indexLength == 1) {
                                            $indexLength -= 1;
                                        }
                                        $temp_date = date("Y-m-d", strtotime($start_time));
                                        $temp_start_time = date("H:i:s", $data["arr"][$data['seperateIndexes'][$i]]["start_time"]);
                                        $temp_end_time = date("H:i:s", $data["arr"][$indexLength]["end_time"]);
                                        $final_end_date = $temp_date . " " . $temp_end_time;
                                        $final_start_date = $temp_date . " " . $temp_start_time;
                                        $temp_end = date('Y-m-d H:i:s', strtotime("$final_end_date"));
                                        $temp_start = date('Y-m-d H:i:s', strtotime("$final_start_date"));
                                    }

                                    $time_insert_arr[] = array(
                                        "start_time" => $temp_start,
                                        "end_time" => $temp_end
                                    );
                                }
                            }
                        } else {
                            $time_insert_arr[] = array(
                                "start_time" => $start_time,
                                "end_time" => $end_time
                            );
                        }
                    } catch (Exceptions $e) {
                        return rest_ensure_response($e);
                    }
                    //return rest_ensure_response(array($data, $time_insert_arr, sizeOf($data['seperateIndexes'])));
                    $wpdb->insert($table_name_reservation, array(
                        "c_id" => $data["room"]["c_id"],
                        "modified_by" => wp_get_current_user()->display_name,
                        "created_at" => current_time('mysql', 1),
                        "modified_at" => current_time('mysql', 1),
                        "created_by" => wp_get_current_user()->user_email,
                        "company_name" => wp_get_current_user()->display_name,
                        "email" => wp_get_current_user()->user_email,
                        "attendance" => $data["numAttend"],
                        "notes" => $data["desc"]
                    ));
                    if ($wpdb->last_error !== '') {
                        return rest_ensure_response($wpdb->last_result);
                    }
                    $insert_id = $wpdb->insert_id;
                    $temp_insert_id = $wpdb->insert_id;
                    foreach ($time_insert_arr as $time) {

                        $time['res_id'] = $temp_insert_id;
                        $wpdb->insert($table_name_time, $time);
                        if ($wpdb->last_error !== '') {
                            $wpdb->query('ROLLBACK');
                            return new WP_Error(400, ('Error adding time'));
                        }
                    }
                    $insert_id = $wpdb->insert_id;
                    if ($wpdb->last_error !== '') {
                        return rest_ensure_response($wpdb->last_result);
                    }
                    return rest_ensure_response($data);
                }
            } else {
                return new WP_Error(400, ('The Time is already taken'), array($res, $timeCheck));
            }
            // Return all of our comment  res ponse data.

        } else {
            return rest_ensure_response("User not logged in");
        }
    }


    public function getReservations($request)
    {
        global $wpdb;
        $table_name_reservation = $wpdb->prefix . 'dsol_booking_reservation';
        $table_name_room = $wpdb->prefix . 'dsol_booking_room';
        $table_name_container = $wpdb->prefix . 'dsol_booking_container';
        $table_name_time = $wpdb->prefix . 'dsol_booking_time';
        $table_name_branch = $wpdb->prefix . 'dsol_booking_branch';
        if (is_user_logged_in()) {
            $data = $request->get_json_params();
            $userEmail = $data['user']['data']['user_email'];
            $sql = "SELECT {$table_name_reservation}.res_id,
            {$table_name_reservation}.company_name,
            {$table_name_reservation}.email,
            {$table_name_reservation}.attendance,
            {$table_name_reservation}.notes,
            {$table_name_container}.container_number,
            {$table_name_container}.c_id,
            {$table_name_container}.r_id,
            {$table_name_container}.occupancy,
            {$table_name_time}.start_time
            FROM {$table_name_reservation} 
            LEFT JOIN {$table_name_container} ON {$table_name_container}.c_id = {$table_name_reservation}.c_id
            LEFT JOIN {$table_name_time} ON {$table_name_time}.res_id = {$table_name_reservation}.res_id
            WHERE {$table_name_reservation}.email = '{$userEmail}' AND {$table_name_container}.r_id IS NOT NULL
            GROUP BY {$table_name_reservation}.res_id, {$table_name_container}.container_number
            ORDER BY {$table_name_time}.start_time DESC;";
            $final = $wpdb->get_results($sql, ARRAY_A);
            if ($wpdb->last_error !== '') {
                return rest_ensure_response($wpdb->last_result);
            }
            // Return all of our comment response data.
            return rest_ensure_response($final);
        } else {
            return new WP_Error(403, ('User not found'));
        }
    }

    public function editUserReservation($request)
    {
        global $wpdb;
        $table_name_reservation = $wpdb->prefix . 'dsol_booking_reservation';
        $table_name_room = $wpdb->prefix . 'dsol_booking_room';
        $table_name_container = $wpdb->prefix . 'dsol_booking_container';
        $table_name_time = $wpdb->prefix . 'dsol_booking_time';
        $table_name_branch = $wpdb->prefix . 'dsol_booking_branch';
        $start_time = date('Y-m-d H:i:s', $data["arr"][0]["start_time"]);
        $end_time = date('Y-m-d H:i:s', $data["arr"][sizeOf($data["arr"]) - 1]["end_time"]);
        if (is_user_logged_in()) {
            $data = $request->get_json_params();
            $resId = $data['info']['res_id'];
            $sql = "SELECT t_id FROM {$table_name_reservation} WHERE res_id = {$resId}";
            $res = $wpdb->get_results($sql);
            $time_id = $res[0]->t_id;
            $time = array(
                "start_time" => $start_time,
                "end_time" => $end_time
            );
            $wpdb->update($table_name_time, $time, array("t_id" => $time_id));
            if ($wpdb->last_error !== '') {
                $wpdb->query('ROLLBACK');
                return new WP_Error(400, ('Error adding time'));
            }
            $wpdb->update($table_name_reservation, array(
                "c_id" => $data["room"]["c_id"],
                "t_id" => $time_id,
                "modified_by" => wp_get_current_user()->display_name,
                "created_at" => current_time('mysql', 1),
                "modified_at" => current_time('mysql', 1),
                "created_by" => wp_get_current_user()->user_email,
                "company_name" => wp_get_current_user()->display_name,
                "email" => wp_get_current_user()->user_email,
                "attendance" => $data["numAttend"],
                "notes" => $data["desc"]
            ), array("res_id" => $resId));
            if ($wpdb->last_error !== '') {
                $wpdb->query('ROLLBACK');
                return new WP_Error(400, ('Error adding time'));
            }
            // Return all of our comment response data.

        } else {
            return new WP_Error(403, ('User not found'));
        }
    }

    public function deleteUserResrvation($request)
    {
        global $wpdb;
        $table_name_reservation = $wpdb->prefix . 'dsol_booking_reservation';
        $table_name_room = $wpdb->prefix . 'dsol_booking_room';
        $table_name_container = $wpdb->prefix . 'dsol_booking_container';
        $table_name_time = $wpdb->prefix . 'dsol_booking_time';
        $table_name_branch = $wpdb->prefix . 'dsol_booking_branch';
        if (is_user_logged_in()) {
            $data = $request->get_json_params();
            $items = implode(',', $data['items']);
            $sql = "DELETE {$table_name_reservation},
            {$table_name_time}
            FROM {$table_name_reservation} 
            INNER JOIN {$table_name_time} ON {$table_name_time}.res_id = {$table_name_reservation}.res_id
            WHERE {$table_name_reservation}.res_id IN ({$items});";
            $wpdb->query($sql);
            if ($wpdb->last_error !== '') {
                return new WP_Error(403, ('User not found'), $wpdb->last_result);
            }
            // Return all of our comment response data.
            return rest_ensure_response("Success");
        } else {
            return new WP_Error(403, ('User not found'));
        }
    }

    /**
     * Matches the post data to the schema we want.
     *
     * @param WP_Post $post The comment object whose response is being prepared.
     */
    public function prepare_item_for_response($post, $request)
    {
        $post_data = array();

        $schema = $this->get_item_schema($request);

        // We are also renaming the fields to more understandable names.
        if (isset($schema['properties']['id'])) {
            $post_data['id'] = (int)$post->ID;
        }

        if (isset($schema['properties']['content'])) {
            $post_data['content'] = apply_filters('the_content', $post->post_content, $post);
        }

        return rest_ensure_response($post_data);
    }

    /**
     * Prepare a response for inserting into a collection of responses.
     *
     * This is copied from WP_REST_Controller class in the WP REST API v2 plugin.
     *
     * @param WP_REST_Response $response Response object.
     * @return array Response data, ready for insertion into collection data.
     */
    public function prepare_response_for_collection($response)
    {
        if (!($response instanceof WP_REST_Response)) {
            return $response;
        }

        $data = (array)$response->get_data();
        $server = rest_get_server();

        if (method_exists($server, 'get_compact_response_links')) {
            $links = call_user_func(array($server, 'get_compact_response_links'), $response);
        } else {
            $links = call_user_func(array($server, 'get_response_links'), $response);
        }

        if (!empty($links)) {
            $data['_links'] = $links;
        }

        return $data;
    }

    /**
     * Get our sample schema for a post.
     *
     * @param WP_REST_Request $request Current request.
     */
    public function get_item_schema($request)
    {
        $schema = array(
            // This tells the spec of JSON Schema we are using which is draft 4.
            '$schema'              => 'http://json-schema.org/draft-04/schema#',
            // The title property marks the identity of the resource.
            'title'                => 'post',
            'type'                 => 'object',
            // In JSON Schema you can specify object properties in the properties attribute.
            'properties'           => array(
                'id' => array(
                    'description'  => esc_html__('Unique identifier for the object.', 'my-textdomain'),
                    'type'         => 'integer',
                    'context'      => array('view', 'edit', 'embed'),
                    'readonly'     => true,
                ),
                'content' => array(
                    'description'  => esc_html__('The content for the object.', 'my-textdomain'),
                    'type'         => 'string',
                ),
            ),
        );

        return $schema;
    }

    // Sets up the proper HTTP status code for authorization.
    public function authorization_status_code()
    {

        $status = 401;

        if (is_user_logged_in()) {
            $status = 403;
        }

        return $status;
    }
}

// Function to register our new routes from the controller.
function prefix_register_my_rest_routes()
{
    $controller = new My_REST_Posts_Controller();
    $controller->register_routes();
}

add_action('rest_api_init', 'prefix_register_my_rest_routes');
