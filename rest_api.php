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

        register_rest_route($this->namespace, '/checkParams', array(
            // Here we register the readable endpoint for collections.
            array(
                'methods'   => 'POST',
                'callback'  => array($this, 'param')
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
        $room =  $request->get_json_params();
        if (isset($room['room']) && $room['room'] > 0) {
            $where = "WHERE {$table_name_reservation}.res_id IS NOT NULL AND {$table_name_reservation}.c_id = {$room['room']}";
        } else {
            $where = "WHERE {$table_name_reservation}.res_id IS NOT NULL";
        }
        $sql = "SELECT {$table_name_reservation}.res_id,
                        {$table_name_reservation}.company_name,
                        {$table_name_reservation}.email,
                        {$table_name_reservation}.attendance,
                        {$table_name_reservation}.notes,
                        {$table_name_container}.container_number,
                        {$table_name_container}.c_id,
                        {$table_name_room}.room_number,
                        {$table_name_branch}.b_name,
                        {$table_name_time}.start_time,
                        {$table_name_time}.end_time
        FROM {$table_name_branch}
        LEFT JOIN {$table_name_room} ON {$table_name_branch}.b_id = {$table_name_room}.b_id
        LEFT JOIN {$table_name_container} ON {$table_name_room}.r_id = {$table_name_container}.r_id
        LEFT JOIN {$table_name_reservation} ON {$table_name_container}.c_id = {$table_name_reservation}.c_id
        LEFT JOIN {$table_name_time} ON {$table_name_time}.t_id = {$table_name_reservation}.t_id
        {$where}
        GROUP BY {$table_name_reservation}.res_id,{$table_name_container}.container_number,{$table_name_room}.room_number,{$table_name_branch}.b_name 
        ORDER BY {$table_name_time}.start_time;";
        $final = $wpdb->get_results($sql, ARRAY_A);
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
        $table_nameRes = $wpdb->prefix . 'bookaroom_reservations';
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
        if (is_user_logged_in()) {
            $data = $request->get_json_params();
            $table_name_reservation = $wpdb->prefix . 'dsol_booking_reservation';
            $table_name_time = $wpdb->prefix . 'dsol_booking_time';
            $start_time = date('Y-m-d H:i:s', $data["arr"][0]["start_time"]);
            $end_time = date('Y-m-d H:i:s', $data["arr"][sizeOf($data["arr"]) - 1]["end_time"]);
            $contNum = $data['room'];
            $timeCheck = "SELECT *
            FROM `$table_name_time`
            LEFT JOIN `$table_name_reservation` ON `$table_name_time`.t_id = `$table_name_reservation`.t_id
            WHERE `$table_name_reservation`=`$contNum` AND  date BETWEEN `$start_time` AND `$end_time` ";
            $res = $wpdb->get_results($timeCheck);
            if (sizeof($res) == 0) {
                if ($data['repeat']['id'] > 0) {
                    $values = array();
                    $place_holders = array();
                    $res_values = array();
                    $res_placeholders = array();
                    $time_sql = "INSERT INTO `$table_name_time` (start_time, end_time) VALUES ";
                    $res_sql = "INSERT INTO `$table_name_reservation` ('c_id','t_id'.'modified_by','created_at','created_by','company_name','email','attendance','notes') VALUES ";
                    try {
                        //return rest_ensure_response( strtotime($start_time)  );
                        //$d = new DateTime(date('Y-m-d H:i:s', $start_time));
                        //return rest_ensure_response($d);
                        //$d->createFromFormat('Y-m-d H:i:s', $start_time);
                        switch ($data['repeat']['id']) {
                            case 1:
                                $i = 0;
                                $X = date('t', strtotime($start_time . ' + 2 days')) - date('d', strtotime($start_time . ' + 2 days'));
                                $start_time = date('Y-m-d H:i:s', strtotime($start_time . "+1 days"));
                                $end_time = date('Y-m-d H:i:s', strtotime($end_time . "+1 days"));
                                array_push($values, $start_time, $end_time);
                                $place_holders[] = "('%s', '%s')";
                                while ($i <= $X) {
                                    $i++;
                                    $start_time = date('Y-m-d H:i:s', strtotime($start_time . "+1 days"));
                                    $end_time = date('Y-m-d H:i:s', strtotime($end_time . "+1 days"));
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
                                }
                                break;
                            case 2:
                                $i = 0;
                                $X = floor((date('t', strtotime($start_time . ' + 2 days')) - date('d', strtotime($start_time . ' + 2 days'))) / 7);
                                while ($i < $X) {
                                    $i++;
                                    $start = date('Y-m-d H:i:s', strtotime($start_time . "+1 weeks"));
                                    $end = date('Y-m-d H:i:s', strtotime($end_time . "+1 weeks"));
                                    array_push($values, $start, $end);
                                }
                                break;
                            case 3:
                                $i = 0;
                                $X = floor((date('t', strtotime(' + 2 days', $start_time)) - date('d', strtotime(' + 2 days', $start_time))) / 7);
                                while ($i < $X) {
                                    $i += 2;
                                    $start = date('Y-m-d H:i:s', strtotime($start_time . "+2 weeks"));
                                    $end = date('Y-m-d H:i:s', strtotime($end_time . "+2 weeks"));
                                    array_push($values, $start, $end);
                                }
                                break;
                            default:

                                break;
                        }
                    } catch (\UnexpectedValueException $e) {
                        return rest_ensure_response($e);
                    }
                    return rest_ensure_response(array("values" => $values, "week" => $X));
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
                    return rest_ensure_response(array($start_time, $end_time));
                } else {
                    return new WP_Error(400, ('The Time is already taken'), array($res, $timeCheck));
                }
            } else {
                return rest_ensure_response(wp_get_current_user());
            }
            // Return all of our comment response data.

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
