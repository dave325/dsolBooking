<?PHP
class dsol_public
# main settings functions

{
    # main funcion - initialize and search for action variable.
    # if no action, return the regular content
    public static function form($val)
    {
        $calendarPage = get_option('dsol_reservation_URL');
        $perms = get_option('permalink_structure');

        $myURLRaw = parse_url($_SERVER['REQUEST_URI']);
        $myURL = $myURLRaw['path'];
        if ($perms) {
            if ($myURL !== $calendarPage) {
                return $val;
            }
        } else {
            parse_str($myURLRaw['query'], $queryVals);
            if ($queryVals['page_id'] !== $calendarPage) {
                return $val;
            }
        }
    }

    public static function mainForm()
    {
        # get external variables from GET and POST
        $externals = self::getExternalsPublic();
        # includes
        require_once DSOL_BOOKING_PATH . '/bookaroom-meetings-rooms.php';
        require_once DSOL_BOOKING_PATH . '/bookaroom-meetings-branches.php';
        require_once DSOL_BOOKING_PATH . '/bookaroom-meetings-roomConts.php';

        # vaiables from includes
        $roomContList = dsol_settings_roomConts::getRoomContList(true);
        $roomList = dsol_settings_rooms::getRoomList();
        $branchList = dsol_settings_branches::getBranchList(true, true);
        # check action
        switch ($externals['action']) {
            case 'checkForm':

                if (($errorMSG = self::showForm_checkHoursError($externals['startTime'], $externals['endTime'], $externals['roomID'], $roomContList, $branchList)) == true) {

                    return self::showForm_hoursError($errorMSG, $externals);
                } elseif (false !== ($errorArr = self::showForm_checkErrors($externals, $branchList, $roomContList, $roomList))) {
                    return self::showForm_publicRequest($externals['roomID'], $branchList, $roomContList, $roomList, $externals, $errorArr);
                } else {
                    self::sendAlertEmail($externals,  $roomContList, $branchList);
                    self::showForm_insertNewRequest($externals, null);
                    return self::sendCustomerReceiptEmail($externals,  $roomContList, $branchList);
                }

                break;

            case 'fillForm':
                $externals['action'] = 'checkForm';
                # setup times
                $hoursList = array_filter($externals['hours'], 'strlen');
                $baseIncrement = get_option('dsol_baseIncrement');
                $externals['startTime'] = current($hoursList);
                $externals['endTime'] = end($hoursList) + ($baseIncrement * 60);

                # check for SESSION variables

                if (!empty($_SESSION['savedMeetingFormVals']['sessionGo'])) {
                    self::getSession($externals);
                }

                if (($errorMSG = self::showForm_checkHoursError($externals['startTime'], $externals['endTime'], $externals['roomID'], $roomContList, $branchList)) == true) {
                    return self::showForm_hoursError($errorMSG, $externals);
                } else {
                    return self::showForm_publicRequest($externals['roomID'], $branchList, $roomContList, $roomList, $externals);
                }
                break;

            case 'calDate':
                $timestamp = self::makeTimestamp($externals);
                return self::showRooms_day($externals['roomID'], $externals['branchID'], $timestamp, $branchList, $roomContList, $roomList );
                break;
            case 'changeStatus':
                $externals = filter_input_array(INPUT_POST);
				self::changeStatus( $externals, $branchList, $roomContList  );				
				break;
				
            case 'reserve':
                if (empty($externals['roomID'])) {
                    return self::showRooms($branchList, $roomContList, $roomList );
                    break;
                }
                return self::showRooms_day($externals['roomID'], $externals['branchID'], $externals['timestamp'], $branchList, $roomContList, $roomList );

                break;

            default:
                $_SESSION['savedMeetingFormVals'] = null;
                return self::showRooms($branchList, $roomContList, $roomList );
                break;
        }
    }
    protected static
	function changeStatus( $externals, $branchList, $roomContList  ) {
		global $wpdb;
        $arr = array();
		for($i = 0; $i < count($externals['res_id']); $i++){
            $temp = array(
                'resid' => $externals['res_id'][$i],
                'timeid' => $externals['timeid'][$i]
            );
            array_push($arr, $temp);
        }

        $user = wp_get_current_user();
        $table_nameRes = $wpdb->prefix . 'dsol_reservations';
        $table_name = $wpdb->prefix . 'dsol_times';
        $where = "`res`.`me_contactName` = '{$user->data->display_name}' ";
		$option[ 'dsol_baseIncrement' ] = get_option( 'dsol_baseIncrement' );
        $sql = "SELECT 					`res`.`res_id`, 
        `ti`.`ti_id` as `id`, 
        `ti`.`ti_startTime` as `startTime`, 
        `ti`.`ti_endTime` as `endTime`, 
        `ti`.`ti_roomID` as `roomID`, 
        `ti`.`ti_created` as `created`, 
        `ti`.`ti_type` as `type`, 
        `ti`.`ti_noLocation_branch` as `noLocation_branch`, 
        
        IF( `ti`.`ti_type` = 'meeting', `res`.`me_eventName`, `res`.`ev_title` ) as `eventName`, 
        IF( `ti`.`ti_type` = 'meeting', `res`.`me_desc`, `res`.`ev_desc` ) as `desc`, 
        
        
        `res`.`me_numAttend` as `numAttend`, 
        IF( `ti`.`ti_type` = 'meeting', `res`.`me_contactName`, `res`.`ev_publicName` ) as `contactName`, 
                            
        `res`.`me_libcardNum` as `libcardNum`, 
        `res`.`me_social` as `isSocial`, 
        `res`.`me_contactPhonePrimary` as `contactPhonePrimary`, 
        `res`.`me_contactPhoneSecondary` as `contactPhoneSecondary`, 
        `res`.`me_contactAddress1` as `contactAddress1`, 
        `res`.`me_contactAddress2` as `contactAddress2`, 
        `res`.`me_contactCity` as `contactCity`, 
        `res`.`me_contactState` as `contactState`, 
        `res`.`me_contactZip` as `contactZip`, 
        `res`.`me_contactEmail` as `contactEmail`, 
        `res`.`me_contactWebsite` as `contactWebsite`, 
        `res`.`me_nonProfit` as `nonProfit`, 
        `res`.`me_amenity` as `amenity`, 
        `res`.`me_status` as `status`, 
        `res`.`me_notes` as `notes`,
        `branch`.`branchDesc` as `branchDesc`
        FROM `{$table_name}` as `ti` 
				LEFT JOIN `{$table_nameRes}` as `res` ON `ti`.`ti_extID` = `res`.`res_id` 
				LEFT JOIN `{$wpdb->prefix}dsol_roomConts` as `cont` ON `ti`.`ti_roomID` = `cont`.`roomCont_ID` 
                LEFT JOIN `{$wpdb->prefix}dsol_branches` as `branch` ON `cont`.`roomCont_branch` = `branch`.`branchID` 
        WHERE `res`.`me_contactName` = '{$user->data->display_name}'";
        $final = $wpdb->get_results($sql, ARRAY_A);
			# get correct status email
			# status that required an outgoing email: pendPayment, denied, accepted.

			$sendMail = FALSE;
			$needHash = FALSE;

			switch ( $externals[ 'status' ] ) {
				case 'delete':
					$delete = TRUE;
			}
            $i = 0;
			# SEND EMAIL
			foreach ( $arr as $val ) {
				if($delete == true){
					# UPDATE DATABASE
					$table_name = $wpdb->prefix . "dsol_times";
					$table_nameRes = $wpdb->prefix . "dsol_reservations";
				$wpdb->delete(	$table_nameRes, 
						array( 'res_id' => $val['resid'] ) );

				$wpdb->delete(	$table_name, 
						array( 
							'ti_id' => $val['timeid']
						));

				}
				
			}

		$_SESSION['showData'] = $final;
        echo "Successfully deleted!<br />";
        echo "<a href='https://techincubatorqc.com/https://techincubatorqc.com/elementor-2375/'>Go back to profile</a><br>";
        echo "<a href='https://techincubatorqc.com/members/'>Reserve a room</a>";
	}
    public static function showForm_checkHoursError($startTime, $endTime, $roomContID, $roomContList, $branchList, $res_id = null)
    {
        global $wpdb;
        $final = false;

        # hours bad?
        # check for common logical errors
        ################################################################
        $validStart = strtotime(date('Y-m-d')) + (get_option('dsol_reserveBuffer') * 24 * 60 * 60);
        $validEnd = $validStart + (get_option('dsol_reserveAllowed') * 24 * 60 * 60);

        # find room's settings

        $branchID = $branchList[$roomContList['id'][$roomContID]['branchID']];
        $dayOfWeek = date('w', $startTime);

        $branchStart = strtotime(date('Y-m-d', $startTime) . ' ' . $branchID['branchOpen_' . $dayOfWeek]);
        $branchEnd = strtotime(date('Y-m-d', $endTime) . ' ' . $branchID['branchClose_' . $dayOfWeek]);

        $admin = is_user_logged_in();
        # start or end empty or invalid?
        if ((!is_numeric($startTime) || !is_numeric($endTime))) {
            $final = __('Your start and/or end time is invalid. Please return to the time selection page and try again.', 'book-a-room');
        } elseif (($startTime <= $validStart || $endTime <= $validStart) && empty($res_id) && $admin == false) {
            # start or end come before valid start date (using reserveBuffer)?
            $final = __('Your start and/or end time comes before the allowed Start Date to reserve. Please return to the time selection page and try again.', 'book-a-room');
        } elseif (($startTime >= $validEnd || $endTime >= $validEnd) && empty($res_id) && $admin == false) {
            # start or end come after valid end date (using reserveAllowed)?
            $final = __('Your start and/or end time comes after the allowed End Date to reserve. Please return to the time selection page and try again.', 'book-a-room');
        } elseif ($startTime < $branchStart || $endTime < $branchStart) {
            # start or end come before branch open date?
            $final = __('Your start and/or end time comes after the allowed End Date to reserve. Please return to the time selection page and try again.', 'book-a-room');
        } elseif ($startTime > $branchEnd || $endTime > $branchEnd) {
            # start or end come after branch close date?
            $final = __('Your start and/or end time comes after the branch is closed. Please return to the time selection page and try again.', 'book-a-room');
        } elseif ($startTime == $endTime) {
            # start or end date identical?
            $final = __('Your start and end times are the same. Please return to the time selection page and try again.', 'book-a-room');
        } elseif ($startTime > $endTime) {
            # start come after end date?
            $final = __('Your start time comes after your end time. Please return to the time selection page and try again.', 'book-a-room');
        }

        $reservations = self::getReservations($roomContID, $startTime, $endTime, $res_id);

        if (count($reservations) !== 0) {
            $final = __('There is a meeting at that time. Please return to the time selection page and try again.', 'book-a-room');
        }

        if (!empty($final) && empty($res_id) && $admin == false) {
            $final .= '<br />' . __('Your form data has been saved. When you choose a new time, your form will retain the information you have already filled out.', 'book-a-room');
        }

        return $final;
    }

    protected static function getExternalsPublic()
    # Pull in POST and GET values
    {
        $final = array();

        # setup GET variables
        $getArr = array('action' => FILTER_SANITIZE_STRING,
            'roomID' => FILTER_SANITIZE_STRING,
            'branchID' => FILTER_SANITIZE_STRING,
            'calMonth' => FILTER_SANITIZE_STRING,
            'calYear' => FILTER_SANITIZE_STRING,
            'timestamp' => FILTER_SANITIZE_STRING,
            'resID' => FILTER_SANITIZE_STRING,
            'hash' => FILTER_SANITIZE_STRING);

        # pull in and apply to final
        if ($getTemp = filter_input_array(INPUT_GET, $getArr)) {
            $final = $getTemp;
        }

/**
 * Changed By David
 */
        # setup POST variables
        $postArr = array('action' => FILTER_SANITIZE_STRING,
            'contactEmail' => FILTER_SANITIZE_STRING,
            'contactName' => FILTER_SANITIZE_STRING,
            'desc' => FILTER_SANITIZE_STRING,
            'endTime' => FILTER_SANITIZE_STRING,
            'eventName' => FILTER_SANITIZE_STRING,
            'hours' => array('filter' => FILTER_SANITIZE_STRING,
                'flags' => FILTER_FORCE_ARRAY),
            'numAttend' => FILTER_SANITIZE_STRING,
            'roomID' => FILTER_SANITIZE_STRING,
            'session' => FILTER_SANITIZE_STRING,
            'startTime' => FILTER_SANITIZE_STRING,
            'timestamp' => FILTER_SANITIZE_STRING);
        
        # pull in and apply to final
        if ($postTemp = filter_input_array(INPUT_POST, $postArr)) {
            $final = array_merge($final, $postTemp);
        }
        $arrayCheck = array_unique(array_merge(array_keys($getArr), array_keys($postArr)));

        foreach ($arrayCheck as $key):
            if (empty($final[$key])):
                $final[$key] = null;
            elseif (is_array($final[$key])):
                $final[$key] = $final[$key];
            else:
                $final[$key] = trim($final[$key]);
            endif;
        endforeach;

        return $final;
    }


    public static function getReservations($roomID, $timestamp, $timeEnd = null, $res_id = null)
    # use timestamp only to get all day's reservation.
    # use timestamp as start time and timeEnd as end time to find reservations in a certain range.
    {
        global $wpdb;

        if (empty($timeEnd)) {
            $timeInfo = getdate($timestamp);

            $startTime = date('Y-m-d H:i:s', mktime(0, 0, 0, $timeInfo['mon'], $timeInfo['mday'], $timeInfo['year']));
            $endTime = date('Y-m-d H:i:s', mktime(0, 0, 0, $timeInfo['mon'], $timeInfo['mday'] + 1, $timeInfo['year']) - 1);
        } else {
           $increment = get_option('dsol_baseIncrement');
            $startTime = date('Y-m-d H:i:s', $timestamp );
            $endTime = date('Y-m-d H:i:s', $timeEnd );
        }
        if (!empty($res_id)) {
            $where = " AND `res`.`res_id` != '{$res_id}'";
        } else {
            $where = null;
        }
        /***
         * Rewrite By David
         *     Sumaita
         *     Removed part of the where clause
         */
        $sql = "SELECT `res`.*, `ti`.*, 'compName' FROM `{$wpdb->prefix}dsol_times` as `ti`
				LEFT JOIN `{$wpdb->prefix}dsol_reservations` as `res` ON `ti`.`ti_extID` = `res`.`res_id`
				WHERE ( `ti`.`ti_startTime`< '{$endTime}' AND `ti`.`ti_endTime` > '{$startTime}' )
				AND `ti`.`ti_roomID` IN (
					SELECT DISTINCT `roomMembers`.`rcm_roomContID`
					FROM `{$wpdb->prefix}dsol_roomConts_members` as `roomMembers`
					WHERE
					`rcm_roomID` IN (
						SELECT `roomMembers`.`rcm_roomID` FROM
						`{$wpdb->prefix}dsol_roomConts_members` as `roomMembers`
						WHERE rcm_roomContID = '{$roomID}'{$where} ) );";
        $final = $wpdb->get_results($sql, ARRAY_A);
        /*
        Removed so that site will show the name of person using the plugin
        if( get_option('dsol_obfuscatePublicNames') == true ) {
        foreach( $final as $key => &$val ) {
        if( $val['ti_type'] !== 'event' ) {
        $val['me_eventName'] = __( 'In use', 'book-a-room' );
        }
        }
        }
         */
        return $final;
    }

    protected static function getSession(&$externals)
    {
        // Removed options to refelect new data
        $valArr = array('action', 'contactEmail', 'contactName', 'desc', 'notes', 'eventName', 'numAttend', 'roomID');

        foreach ($valArr as $key) {
            if (!empty($_SESSION['savedMeetingFormVals'][$key])) {
                $externals[$key] = $_SESSION['savedMeetingFormVals'][$key];
            } else {
                $externals[$key] = null;
            }
        }

        $_SESSION['savedMeetingFormVals']['sessionGo'] = false;

        return $externals;
    }

    protected static function makeTimestamp($externals)
    {
        $curTimeInfo = getdate(current_time('timestamp'));
        # check for month
        if (empty($externals['calMonth']) || !is_numeric($externals['calMonth']) || ($externals['calMonth'] < 1 || $externals['calMonth'] > 12)) {
            $month = $curTimeInfo['mon'];
        } else {
            $month = $externals['calMonth'];
        }

        # check year
        if (empty($externals['calYear']) || !is_numeric($externals['calYear']) || ($externals['calYear'] < $curTimeInfo['year'] - 1 || $externals['calYear'] > $curTimeInfo['year'] + 3)) {
            $year = $curTimeInfo['year'];
        } else {
            $year = $externals['calYear'];
        }

        $timestamp = mktime(0, 0, 0, $month, 1, $year);
        return $timestamp;

    }

    public static function showForm_checkErrors(&$externals, $branchList, $roomContList, $roomList)
    {
        $final = array();

        # event name
        if (empty($externals['eventName'])) {
            $final['classes']['eventName'] = 'error';
            $final['errorMSG'][] = __('You must enter an event name in the <em>Event/Organization name</em> field.', 'book-a-room');
        } elseif (!mb_check_encoding($externals['eventName'], 'ASCII')) {
            $final['errorMSG'][] = __('Your <em>Event/Organization name</em> field contains invalid characters. Make sure, if you are copying from Word, you clean up your quotes, single quotes and apostrophes.', 'book-a-room');
            $final['classes']['eventName'] = 'error';
        }

        # event name
        if (empty($externals['numAttend'])) {
            $final['classes']['numAttend'] = 'error';
            $final['errorMSG'][] = __('You must enter how many people you expect to attend in <em>Number of attendees</em> field.', 'book-a-room');
        } elseif (!is_numeric($externals['numAttend'])) {
            $final['classes']['numAttend'] = 'error';
            $final['errorMSG'][] = __('You must enter valid number <em>Number of attendees</em> field.', 'book-a-room');
        } elseif ($externals['numAttend'] < 1 or $externals['numAttend'] > $roomContList['id'][$externals['roomID']]['occupancy']) {
            $final['classes']['numAttend'] = 'error';
            $final['errorMSG'][] = sprintf(__('The maximum occupancy of this room is <em>%s</em>. Please enter a valid <em>Number of attendees</em> field.', 'book-a-room'), $roomContList['id'][$externals['roomID']]['occupancy']);
        }

        # purposr of meeting (desc)
        if (empty($externals['desc'])) {
            $final['classes']['desc'] = 'error';
            $final['errorMSG'][] = __('You must enter a value in the <em>Purpose of meeting</em> field.', 'book-a-room');
        } elseif (!mb_check_encoding($externals['desc'], 'ASCII')) {
            $final['errorMSG'][] = __('Your <em>Purpose of meeting</em> contains invalid characters. Make sure, if you are copying from Word, you clean up your quotes, single quotes and apostrophes.', 'book-a-room');
            $final['classes']['desc'] = 'error';
        }

        # contact name
        if (empty($externals['contactName'])) {
            $final['classes']['contactName'] = 'error';
            $final['errorMSG'][] = __('You must enter a value in the <em>Contact name</em> field.', 'book-a-room');
        }

        # email address
        if (empty($externals['contactEmail'])) {
            $final['classes']['contactEmail'] = 'error';
            $final['errorMSG'][] = __('You must enter a value in the <em>Email address</em> field.', 'book-a-room');
        } elseif (!filter_var($externals['contactEmail'], FILTER_VALIDATE_EMAIL)) {
            $final['classes']['contactEmail'] = 'error';
            $final['errorMSG'][] = __('You must enter a valid address in the <em>Email address</em> field.', 'book-a-room');
        }

        # NEW
        if (empty($final)) {
            $final = false;
        }

        return $final;
    }

    protected static function showForm_hoursError($errorMSG, $externals)
    {
        // Removed options to refelect new data
        $valArr = array('action', 'contactEmail', 'contactName', 'desc', 'eventName', 'numAttend', 'roomID');

        foreach ($valArr as $key) {
            $_SESSION['savedMeetingFormVals'][$key] = $externals[$key];
        }

        $_SESSION['savedMeetingFormVals']['sessionGo'] = true;

        ob_start();
        require DSOL_BOOKING_PATH . 'templates/public/error.php';
        $final = ob_get_contents();
        ob_end_clean();

        return $final;
    }

    public static function showForm_insertNewRequest($externals, $event = null)
    {
        global $wpdb;

        $currentTimeMySQL = date_i18n('Y-m-d H:i:s', current_time('timestamp'));
/**
 * Changed By David
 *     Sumaita make changes based on the db tables created
 */
        $table_name = $wpdb->prefix . "dsol_reservations";
        $pending = false;
        if( !empty( $event ) ) {
			$event = TRUE;
			$pending = 'approved';
		} else {
			$event = TRUE;
			$pending = 'approved';
		}
        $final = $wpdb->insert($table_name, array(
            'res_created' => $currentTimeMySQL,
            'me_numAttend' => $externals['numAttend'],
            'me_eventName' => esc_textarea($externals['eventName']),
            'me_desc' => esc_textarea($externals['desc']),
            'me_contactName' => esc_textarea($externals['contactName']),
            'me_contactEmail' => esc_textarea($externals['contactEmail']),
            'me_status' => $pending));
        $table_name = $wpdb->prefix . "dsol_times";
        $final = $wpdb->insert($table_name, array(
            'ti_created' => $currentTimeMySQL,
            'ti_startTime' => date('Y-m-d H:i:s', $externals['startTime']), #
            'ti_endTime' => date('Y-m-d H:i:s', $externals['endTime']),
            'ti_roomID' => $externals['roomID'],
            'ti_type' => 'meeting',
            'ti_extID' => $wpdb->insert_id));
        return true;
    }

    public static function showForm_publicRequest($roomContID, $branchList, $roomContList, $roomList, $externals, $errorArr = null, $edit = null)
    {

        ob_start();

        if (!empty($edit)) {
            // Need to edit based on existing forms and data
            require DSOL_BOOKING_PATH . 'templates/meetings/pending_edit.php';
        } else {
            // Need to edit based on existing forms and data
            require DSOL_BOOKING_PATH . 'templates/public/publicRequest.php';
        }

        $final = ob_get_contents();
        ob_end_clean();

        return $final;
    }

    public static function showRooms($branchList, $roomContList, $roomList )
    {
        ob_start();
        // Initial page that will show a list of rooms available to book
        // Need to change some of the info on this file
        // e.g. remove amenities and things like that
        require DSOL_BOOKING_PATH . 'templates/public/publicShowRooms.html';
        $final = ob_get_contents();
        ob_end_clean();

        return $final;

    }

    public static function showSubmitPage($branchList, $roomContList, $roomList )
    {
        ob_start();
        // Initial page that will show a list of rooms available to book
        // Need to change some of the info on this file
        // e.g. remove amenities and things like that
        require DSOL_BOOKING_PATH . 'templates/public/submitPage.html';
        $final = ob_get_contents();
        ob_end_clean();

        return $final;

    }

    public static function showRooms_day($roomID, $branchID, $timestamp, $branchList, $roomContList, $roomList)
    {
        # get branch and room
        # if Room ID is not empty and is valid
        if (!empty($roomContList) and !empty($roomID) and in_array($roomID, array_keys($roomContList['id']))) {
            # find that room's branch
            # if there is no valid branch, both are null (can't have a
            # valid room that has no branch)
            if (empty($roomContList['id'][$roomID]['branchID'])) {
                $branchID = null;
                $roomID = null;
                # if valid branch, map it.
            } else {
                $branchID = $roomContList['id'][$roomID]['branchID'];
            }
            # if the room is empty, check for a branch ID, make sure it's valid
        } elseif (!empty($branchID) and in_array($branchID, array_keys($branchList))) {
            # since we have a valid branch, lets see if there are any rooms (make sure that
            # the room list is an array and not empty )
            if (!empty($roomContList['branch'][$branchID]) and is_array($roomContList['branch'][$branchID])) {
                # make the room ID the first room
                $roomID = current($roomContList['branch'][$branchID]);
            } else {
                # if no rooms, show no available rooms
                $roomID = null;
            }
        } else {
            $branchID = null;
            $roomID = null;
        }
        ob_start();
        // Need to edit based on existing forms and data
        require DSOL_BOOKING_PATH . 'templates/public/publicShowRooms_day.php';
        $final = ob_get_contents();
        ob_end_clean();

        return $final;
    }

    /**
     * Removed By David
     *
     * @param [type] $externals
     * @param [type] 
     * @param [type] $roomContList
     * @param [type] $branchList
     * @param boolean $admin
     * @return void
     */
    public static function sendAlertEmail( $externals,  $roomContList, $branchList, $admin = false )
    {
    $filename = DSOL_BOOKING_PATH . 'templates/public/adminNewRequestAlert.html';
    $handle = fopen( $filename, "r" );
    $contents = fread( $handle, filesize( $filename ) );
    fclose( $handle );

    $contents = str_replace( '#pluginLocation#', plugins_url( '', __FILE__ ), $contents );



    $roomCount = 1;

    # times
    $externals['formDate'] = date_i18n( 'l, F jS, Y', $externals['startTime'] );
    $externals['startTimeDisp'] = date( 'g:i a', $externals['startTime'] );
    $externals['endTimeDisp'] = date( 'g:i a', $externals['endTime'] );

    # branch name
    $roomID = $externals['roomID'];

    $externals['roomName'] = $roomContList['id'][$externals['roomID']]['desc'];
    $externals['branchName'] = $branchList[$roomContList['id'][$externals['roomID']]['branchID']]['branchDesc'];

    #
    # array of all values
    $valArr = array('branchName','branchNames','contactEmail','contactName','desc','endTimeDisp','eventName','formDate','numAttend','roomID','roomName','roomTotal','startTimeDisp' );

    foreach( $valArr as $key => $val ) {
    $contents = @str_replace( "#{$val}#", $externals[$val], $contents );
    }

    $email = get_option( 'dsol_alertEmail' );;

    $fromName    = get_option( 'dsol_alertEmailFromName' );
    $fromEmail    = get_option( 'dsol_alertEmailFromEmail' );

    $replyToOnly    = ( true == get_option( 'dsol_emailReplyToOnly' ) ) ? "From: {$fromName}\r\nReply-To: {$fromName} <{$fromEmail}>\r\n" : "From: {$fromName} <{$fromEmail}>\r\n";

    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
    $replyToOnly .
    'X-Mailer: PHP/' . phpversion();
    /* translators: Book a Room Event Created: BRANCH_NAME [ROOM_NAME] by CONTACT_NAME on START_DATE from START_TIME to END_TIME
    $subject = sprintf( __( 'Book a Room Event Created: %s [%s] by %s on %s from %s to %s', 'book-a-room' ), $externals['branchName'], $externals['roomName'], $externals['contactName'], $externals['formDate'], $externals['startTimeDisp'], $externals['endTimeDisp'] );

    mail( $email, $subject, $contents, $headers );
    */
    if( $admin == true ) {
    $subject = sprintf( __( 'Your Book a Room Event Details: %s [%s] by %s on %s from %s to %s', 'book-a-room' ), $externals['branchName'], $externals['roomName'], $externals['contactName'], $externals['formDate'], $externals['startTimeDisp'], $externals['endTimeDisp'] );
    mail( $contactEmail, $subject, $contents, $headers );
    }
    }
     

    public static function sendCustomerReceiptEmail($externals,  $roomContList, $branchList, $internal = false)
    {
        $fromName = get_option('dsol_alertEmailFromName');
        $fromEmail = get_option('dsol_alertEmailFromEmail');

        $replyToOnly = (true == get_option('dsol_emailReplyToOnly')) ? "From: {$fromName}\r\nReply-To: {$fromName} <{$fromEmail}>\r\n" : "From: {$fromName} <{$fromEmail}>\r\n";

        if ($internal == true) {
            $subject = get_option('dsol_newInternal_subject');
            $contents = html_entity_decode(get_option('dsol_newInternal_body'));
        } else {
            $subject = get_option('dsol_newAlert_subject');
            $contents = html_entity_decode(get_option('dsol_newAlert_body'));
        }

        $subject = self::replaceItems($subject, $externals,  $roomContList, $branchList);
        $contents = self::replaceItems($contents, $externals,  $roomContList, $branchList);

        $option['dsol_baseIncrement'] = get_option('dsol_baseIncrement');

        $roomCount = count($roomContList['id'][$externals['roomID']]['rooms']);
        // Removed pricing info

        # create email
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
        $replyToOnly .
        'X-Mailer: PHP/' . phpversion();

        mail($externals['contactEmail'], $subject, $contents, $headers);

        return $contents;
    }

    public static function replaceItems($contents, $externals,  $roomContList, $branchList)
    {
        $oldExternals = $externals;

        # date 1 - two weeks from now
        $timeArr = getdate(time());
        $date1 = mktime(0, 0, 0, $timeArr['mon'], $timeArr['mday'] + 14, $timeArr['year']);
        $date3 = mktime(0, 0, 0, $timeArr['mon'], $timeArr['mday'] + 1, $timeArr['year']);

        # two weeks before event
        $timeArr = getdate($externals['startTime']);
        $date2 = mktime(0, 0, 0, $timeArr['mon'], $timeArr['mday'] - 14, $timeArr['year']);

        # check dates
        $mainDate = min($date1, $date2);

        if ($mainDate < $date3) {
            $mainDate = $date3;
        }

        # times
        $externals['date'] = date_i18n('l, F jS, Y', $externals['startTime']);
        $externals['startTime'] = date('g:i a', $externals['startTime']);
        $externals['endTime'] = date('g:i a', $externals['endTime']);

        # branch name
        $roomID = $externals['roomID'];

        $externals['roomName'] = $roomContList['id'][$externals['roomID']]['desc'];
        $externals['branchName'] = $branchList[$roomContList['id'][$externals['roomID']]['branchID']]['branchDesc'];

        # final
        $goodArr = array('branchName', 'contactEmail', 'contactName', 'date', 'desc', 'endTime', 'eventName', 'numAttend', 'roomName', 'startTime', 'resID');

        #
        foreach ($goodArr as $val) {
            $name = '{' . $val . '}';
            $contents = str_replace($name, $externals[$val], $contents);
        }

        return nl2br($contents);
    }
}
?>