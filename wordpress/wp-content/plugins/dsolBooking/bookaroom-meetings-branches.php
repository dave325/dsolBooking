<?php
class dsol_settings_branches
{
    ############################################
    #
    # Branch Management
    #
    ############################################
    public static function dsol_admin_branches()
    {
        $branchList = self::getBranchList();
        # figure out what to do
        # first, is there an action?
        $externals = self::getExternalsBranch();

        switch ($externals['action']) {
            case 'deleteCheck': # check that there is an ID and it is valid
                if (dsol_settings::checkID($externals['b_id'], $branchList) == false) {
                    # show error page
                    require DSOL_BOOKING_PATH . 'templates/branches/IDerror.php';
                } else {
                    # show delete screen
                    $branchInfo = self::getBranchInfo($externals['b_id']);
                    $roomContList = dsol_settings_roomConts::getRoomContList();
                    $roomList = dsol_settings_rooms::getRoomList();
                    $container = self::makeRoomAndContList($branchInfo, $roomContList, $roomList);
                    self::deleteBranch($branchInfo, $container);
                    require DSOL_BOOKING_PATH . 'templates/branches/deleteSuccess.php';
                }
                break;

            case 'delete':
                # check that there is an ID and it is valid
                if (dsol_settings::checkID($externals['b_id'], $branchList) == false) {
                    # show error page
                    require DSOL_BOOKING_PATH . 'templates/branches/IDerror.php';
                } else {
                    # show delete screen

                    $branchInfo = self::getBranchInfo($externals['b_id']);
                    $roomContList = dsol_settings_roomConts::getRoomContList();
                    $roomList = dsol_settings_rooms::getRoomList();
                    self::showBranchDelete($branchInfo, $roomContList, $roomList);
                }

                break;

            case 'addCheck':
                # check entries
                if (($errors = self::checkEditBranch($externals, $branchList)) == null) {
                    $hi = self::addBranch($externals);
                    require DSOL_BOOKING_PATH . 'templates/branches/addSuccess.php';
                    break;
                }

                $externals['errors'] = $errors;
                # show edit screen
                self::showBranchEdit($externals, 'addCheck', 'Add');

                break;

            case 'add':
                self::showBranchEdit(null, 'addCheck', 'Add');
                break;

            case 'editCheck':
                # check entries
                if (($errors = self::checkEditBranch($externals, $branchList)) == null) {
                    self::editBranch($externals);
                    require DSOL_BOOKING_PATH . 'templates/branches/editSuccess.php';
                    break;
                }

                $externals['errors'] = $errors;

                # check that there is an ID and it is valid
                if (dsol_settings::checkID($externals['b_id'], $branchList) == false) {
                    # show error page
                    require DSOL_BOOKING_PATH . 'templates/branches/IDerror.php';
                } else {
                    # show edit screen
                    self::showBranchEdit($externals, 'editCheck', 'Edit', $externals);
                }

                break;

            case 'edit':

                # check that there is an ID and it is valid

                if (dsol_settings::checkID($externals['b_id'], $branchList) == false) {
                    # show error page
                    require DSOL_BOOKING_PATH . 'templates/branches/IDerror.php';
                } else {
                    # show edit screen
                    $branchInfo = self::getBranchInfo($externals['b_id']);

                    self::showBranchEdit($branchInfo, 'editCheck', 'Edit', $externals);
                }

                break;

            default:
                self::showBranchList($branchList);
                break;
        }
    }

    # sub functions:
    ############################################

    public static function addBranch($externals)
    # add a new branch
    {
        global $wpdb;

        $table_name = $wpdb->prefix . "dsol_booking_branch";
        $table_name_sch = $wpdb->prefix . "dsol_booking_branch_schedule";
        $finalTime = array();
        $query = "INSERT INTO {$table_name_sch} (b_id, open_time,close_time) VALUES ";
        $place_holders = array();
        foreach (array('Open', 'Close') as $type) {
            for ($d = 0; $d <= 6; $d++) {
                #open time
                $name = "branch{$type}_{$d}";
                $pmName = $name . 'PM';

                if (empty($externals[$name])) {
                    $finalTime[$type][$d] = null;
                } else {
                    list($h, $m) = explode(":", $externals[$name]);
                    $timeVal = ($h * 60) + $m;

                    if (!empty($externals[$pmName])) {
                        $timeVal += 720;
                    }

                    $finalTime[$type][$d] = date('G:i:s', strtotime('1/1/2000 00:00:00') + ($timeVal * 60));
                    //$place_holders[] = "('%s', '%d', '%d')";
                }
            }
        }


        /*
        * Deleted by: Jazmyn  
        * 
        * Branch_isPublic isn't needed
        */

        /*
         * Jazmyn
         * 
         * Deleted if statment from branch_hasNoLoc
         */

        $final = $wpdb->insert(
            $table_name,
            array(
                /**
                 * Jazmyn
                 * 
                 * Deleted: branchMapLink, branchImageURL,
                 * branch_isPublic, branch_HasNoLoc
                 * 
                 * David -> Reflect for new time set up 
                 */
                'b_name' => $externals['b_name']
            )
        );
        $lastid = $wpdb->insert_id;
        $values = [];
        $i = 0;
        for ($i; $i < 7; $i++) {
            $temparray = array($lastid, $finalTime['Open'][$i], $finalTime["Close"][$i]);
            array_push($values, $lastid, $finalTime['Open'][$i], $finalTime["Close"][$i]);

            $place_holders[] = "('%s', '%d', '%d')";
        }
        $query .= implode(', ', $place_holders);
        $wpdb->query($wpdb->prepare("{$query}", $values));
        return $finalTime;
    }

    public static function checkEditBranch(&$externals, $branchList)
    # check the name for duplicates, the times for correct format and non-equal
    # or close after open
    {

        # check times
        $timeArr = array();
        $dayname = array(0 => 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
        $final = null;
        $error = array();

        foreach ($externals as $key => $val) {
            $day = null;
            $type = null;
            $timeVal = null;
            # check for open or close
            if (stristr($key, 'branchOpen') or stristr($key, 'branchClose')) {

                switch (substr($key, 0, 10)) {
                    case 'branchOpen':
                        $type = 'open';
                        $errorType = 'opening';
                        break;
                    case 'branchClos':
                        $type = 'close';
                        $errorType = 'closing';
                        break;
                    default:
                        die('Error!');
                        break;
                }
                # is checkbox?

                if (substr($key, -2) == 'PM') {
                    $day = substr($key, -3, 1);
                    if (!is_null($val)) {
                        # get day val
                        $timeVal = 720;
                    }

                    if ($externals[substr($key, 0, -2)] == '12:00') {
                        $timeVal = null;
                    }
                } else {
                    #find day of the week
                    $day = substr($key, -1, 1);
                    # is valid value?
                    if (empty($val)) {
                        continue;
                    }

                    if (count(explode(":", $val)) !== 2) {
                        $error[] = "The {$errorType} time for {$dayname[$day]} is invalid.";
                        continue;
                    }

                    list($h, $m) = explode(":", $val);

                    # not numeric?
                    if (!is_numeric($h) or !is_numeric($m)) {
                        $error[] = "The {$errorType} time for {$dayname[$day]} is invalid.";
                        continue;
                    }

                    # invalid times?
                    if (($h <= 12 and $h >= 0) and ($m <= 59 and $m >= 0)) {
                        # get day
                        $timeVal = ($h * 60) + $m;
                    } else {
                        $error[] = "The {$errorType} time for {$dayname[$day]} is invalid.";
                        continue;
                    }
                }

                # check for, and create, array entry if empty
                if (empty($timeArr[$day][$type])) {
                    $timeArr[$day][$type] = $timeVal;
                } else {
                    $timeArr[$day][$type] += $timeVal;
                }
            }
        }

        # check close-before-opens and closed days
        for ($d = 0; $d <= 6; $d++) {
            # first, clear check if empty
            foreach (array('Open', 'Close') as $val) {
                $name = "branch{$val}_{$d}";
                $namePM = $name . 'PM';

                if (empty($externals[$name])) {
                    $externals[$namePM] = null;
                    $typeName = strtolower($val);
                    $timeArr[$d][$typeName] = null;
                }
            }

            if (!$timeArr[$d]['close'] and !$timeArr[$d]['open']) {
                #
            } elseif (empty($timeArr[$d]['close']) or empty($timeArr[$d]['open'])) {
                $error[] = "Your must enter both a close and open time on {$dayname[$d]} or leave both blank if the branch is closed.";
            } elseif ($timeArr[$d]['close'] <= $timeArr[$d]['open']) {
                $error[] = "Your close time must come after your opening time on {$dayname[$d]}.";
            }
        }
        /*
        * 
        * Deleted by: Jazmyn  
        * 
        * Branch_isPublic isn't needed
        * 
        * 
        */
        # check for public
        // if (empty($externals['branch_isPublic'])) {
        //     $error[] = 'You must choose if this branch is availble for public scheduling.';
        // }
        /*
        *
        * Jazmyn Fuller
        *
        * Deleted branch_hasNoLoc because you can check if there's an address value or not
        */
        // # check for noloc
        // if (empty($externals['branch_hasNoloc'])) {
        //     $error[] = 'You must choose if this branch is has a "No location" option.';
        // }


        /**
         * Jazmyn
         * Deleted check for branchMapLink
         */

        /**
         * Jazmyn
         * Deleted check for b_name
         */



        # check dupe name 
        // b_name needs to be handled here because I deleted it
        if (dsol_settings::dupeCheck($branchList, $externals['b_name'], $externals['b_id']) == 1) {
            $error[] = 'That branch name is already in use. Please choose another.';
        }

        # if errors, implode and return error messages

        if (count($error) !== 0) {
            $final = implode("<br />", $error);
        }

        return $final;
    }

    public static function deleteBranch($branchInfo, $container)
    # add a new branch
    {
        global $wpdb;

        $table_name = $wpdb->prefix . "dsol_booking_branch";

        $sql = "DELETE FROM `{$table_name}` WHERE `b_id` = '{$branchInfo['b_id']}' LIMIT 1";
        $wpdb->query($sql);

        $finalRooms = array();
        foreach ($container as $key => $val) {
            $finalRooms = array_unique(array_merge($finalRooms, $val['rooms']));
        }

        if (!empty($finalRooms)) {
            $table_name = $wpdb->prefix . "dsol_booking_room";

            $finalRoomsImp = implode(',', $finalRooms);
            $sql = "DELETE FROM `{$table_name}` WHERE `roomID` IN ({$finalRoomsImp}) LIMIT 1";
            $wpdb->query($sql);
        }

        unset($container[null]);

        $finalRoomConts = array_keys($container);

        if (!empty($finalRoomConts)) {
            $table_name = $wpdb->prefix . "dsol_booking_container";

            $finalRoomsContsImp = implode(',', $finalRoomConts);
            $sql = "DELETE FROM `{$table_name}` WHERE `roomCont_ID` IN ({$finalRoomsContsImp}) LIMIT 1";
            $wpdb->query($sql);
        }

        return false;
    }

    public static function editBranch($externals)
    # change the branch settings
    {
        global $wpdb;

        $table_name = $wpdb->prefix . "dsol_booking_branch";

        $finalTime = array();

        foreach (array('Open', 'Close') as $type) {
            for ($d = 0; $d <= 6; $d++) {
                #open time
                $name = "branch{$type}_{$d}";
                $pmName = $name . 'PM';
                if (empty($externals["schedule"][$d])) {
                    $finalTime[$type][$d] = null;
                    $typeCast[$type][$d] = null;
                } else {
                    list($h, $m) = explode(":", $externals["schedule"][$d]);
                    # check for noon
                    $timeVal = ($h * 60) + $m;
                    if ($type != 'Open') {
                        if (!empty($externals[$pmName])) {
                            if ($h !== '12') {
                                $timeVal += 720;
                            }
                        }
                    }
                    $finalTime[$type][$d] = date('G:i:s', strtotime('1/1/2000 00:00:00') + ($timeVal * 60));
                    $typeCast[$type][$d] = '%s';
                }
            }
        }
        /**
         * Jazmyn
         * Deleted if statements that check is branch_isSocial and branch_showSocial is true
         */

        /*
        * 
        * Deleted by: Jazmyn  
        * 
        * removed if statments for branch_isPublic
        */

        /*
        *
        * Jazmyn Fuller
        *
        * Deleted branch_hasNoLoc because you can check if there's an address value or not
        */
        // if ($externals['branch_hasNoloc'] == 'true') {
        //     $branch_hasNoloc = 1;
        // } else {
        //     $branch_hasNoloc = 0;
        // }



        $final = $wpdb->update(
            $table_name,
            array(
                /*
                 * 
                 * Deleted by: Jazmyn  
                 * 
                 * Branch_isPublic isn't needed
                 * removed isSocial and showSocial
                 * removed branchImageURL
                 * removed branchMapLink
                 * removed b_name
                 * removed branch_hasNoLoc
                 * 
                 * David -> Reflect for new time set up
                 */
                'b_name' => $externals['b_name']
            ),
            array('b_id' => $externals['b_id']),
            array('%s', '%s', '%s', '%s', '%s', '%s')
        );
    }



    public static function getBranchInfo($b_id)
    # get information about branch from database based on the ID
    {
        global $wpdb;

        $table_name = $wpdb->prefix . "dsol_booking_branch";
        $table_name_sch = $wpdb->prefix . "dsol_booking_branch_schedule";

        $final = $wpdb->get_row($wpdb->prepare("SELECT * FROM `$table_name` WHERE `b_id` = %d", $b_id));
        $schedule = $wpdb->get_results($wpdb->prepare("SELECT * FROM `$table_name_sch` WHERE `b_id` = %d", $b_id), ARRAY_A);
        /*
        *
        * Jazmyn Fuller
        *
        * Deleted branch_hasNoLoc because you can check if there's an address value or not
        * also deleted branch_isPublic because it's not needed, branchImageURL, branchMapLink
        * branch_isSocial and showSocial
        */
        $branchInfo = array(
            'b_id' => $final->b_id,
            'b_name' => $final->b_name,
            'schedule' => $schedule
        );

        //$branchInfo['b_name'] = array('b_id' => $final->b_name);


        /*
            David took out -> Using information present in db instead

        # parse the times and convert from 24:00:00 to a 12:00 with a bit for PM
        foreach ($final as $key => $val) {
            if (!in_array(substr($key, 0, 10), array('branchOpen', 'branchClos'))) {
                continue;
            }

            if (empty($val) || $val == '00:00:00') {
                $branchInfo[$key] = null;
            } else {
                # make name for PM
                $name = $key . 'PM';
                $convTime = strtotime('1/1/2000 ' . $val);

                $branchInfo[$key] = date("g:i", $convTime);
                $branchInfo[$name] = date("a", $convTime) == 'pm' ? true : false;
                // print_r($branchInfo);
            }
        }
*/
        /*
        * Deleted by: Jazmyn  
        * Branch_isPublic isn't needed
        */

        /**
         * Deleted isSocial and showSocial if statements below because I deleted those
         * variables above
         */

        /*
        * Jazmyn Fuller
        * Deleted branch_hasNoLoc because you can check if there's an address value or not
        */
        return $branchInfo;
    }

    /*
    * 
    * Deleted by: Jazmyn  
    * 
    * I'm removing all of branch_isPublic instances
    * 
    * 
    */

    public static function getBranchList($full = null)
    # get a list of all of the branches. Return NULL on no branches
    # otherwise, return an array with the unique ID of each branch
    # as the key and the description as the val
    {
        global $wpdb;
        $final = array();

        $table_name = $wpdb->prefix . "dsol_booking_branch";
        $table_nameSch = $wpdb->prefix . "dsol_booking_branch_schedule";
        /** 
         *
         * Jazmyn Fuller
         *
         * Deleted branch_hasNoLoc, branchMapLink, branchImageURL, branch_isPublic,
         * branch_isSocial, and branch_showSocial

         * David -> Refactor for new time DB set up
         */
        $sql = "SELECT `$table_name`.b_id, `$table_name`.b_name, `$table_nameSch`.open_time, `$table_nameSch`.close_time
                FROM `$table_name` INNER JOIN  `$table_nameSch` ON `$table_name`.b_id = `$table_nameSch`.b_id ORDER BY `$table_name`.b_id, `$table_name`.b_name ";

        $count = 0;

        $cooked = $wpdb->get_results($sql, ARRAY_A);
        if (count($cooked) == 0) {
            return array();
        }

        foreach ($cooked as $key => $val) {
            if ($full) {
                $final[$val['b_id']] = $val;
            } else {
                $final[$val['b_id']] = $val['b_name'];
            }
        }

        return $final;
    }

    public static function getExternalsBranch()
    # Pull in POST and GET values
    {
        $final = array();

        # setup GET variables
        $getArr = array(
            'b_id' => FILTER_SANITIZE_STRING,
            'action' => FILTER_SANITIZE_STRING
        );
        # pull in and apply to final
        if ($getTemp = filter_input_array(INPUT_GET, $getArr)) {
            $final += $getTemp;
        }

        # setup POST variables
        $postArr = array(
            'action' => FILTER_SANITIZE_STRING,
            'b_id' => FILTER_SANITIZE_STRING,
            /*
            * 
            * Deleted by: Jazmyn  
            *
            * Branch_isPublic, Branch_isSocial, Branch_showSocial, branchMapLink, branchImageURL, branch_hasNoLoc
            */
            'b_name' => FILTER_SANITIZE_STRING,
            'branchOpen_0' => FILTER_SANITIZE_STRING,
            'branchOpen_0PM' => FILTER_SANITIZE_STRING,
            'branchClose_0' => FILTER_SANITIZE_STRING,
            'branchClose_0PM' => FILTER_SANITIZE_STRING,
            'branchOpen_1' => FILTER_SANITIZE_STRING,
            'branchOpen_1PM' => FILTER_SANITIZE_STRING,
            'branchClose_1' => FILTER_SANITIZE_STRING,
            'branchClose_1PM' => FILTER_SANITIZE_STRING,
            'branchOpen_2' => FILTER_SANITIZE_STRING,
            'branchOpen_2PM' => FILTER_SANITIZE_STRING,
            'branchClose_2' => FILTER_SANITIZE_STRING,
            'branchClose_2PM' => FILTER_SANITIZE_STRING,
            'branchOpen_3' => FILTER_SANITIZE_STRING,
            'branchOpen_3PM' => FILTER_SANITIZE_STRING,
            'branchClose_3' => FILTER_SANITIZE_STRING,
            'branchClose_3PM' => FILTER_SANITIZE_STRING,
            'branchOpen_4' => FILTER_SANITIZE_STRING,
            'branchOpen_4PM' => FILTER_SANITIZE_STRING,
            'branchClose_4' => FILTER_SANITIZE_STRING,
            'branchClose_4PM' => FILTER_SANITIZE_STRING,
            'branchOpen_5' => FILTER_SANITIZE_STRING,
            'branchOpen_5PM' => FILTER_SANITIZE_STRING,
            'branchClose_5' => FILTER_SANITIZE_STRING,
            'branchClose_5PM' => FILTER_SANITIZE_STRING,
            'branchOpen_6' => FILTER_SANITIZE_STRING,
            'branchOpen_6PM' => FILTER_SANITIZE_STRING,
            'branchClose_6' => FILTER_SANITIZE_STRING,
            'branchClose_6PM' => FILTER_SANITIZE_STRING
        );

        # pull in and apply to final
        if ($postTemp = filter_input_array(INPUT_POST, $postArr)) {
            $final += $postTemp;
        }

        /**
         * possible call to convert to 24hr time for databse
         * 
         * echo date("h:i", strtotime($time))
         * 
         */

        // if(strpos($num, 'PM') !== false) {
        //     date("h:i", strtotime($num));
        // } 



        $arrayCheck = array_unique(array_merge(array_keys($getArr), array_keys($postArr)));

        foreach ($arrayCheck as $key) {
            if (!isset($final[$key])) {
                $final[$key] = null;
            } else {
                $final[$key] = trim($final[$key]);
            }
        }

        return $final;
    }

    public static function makeRoomAndContList($branchInfo, $roomContList, $roomList)
    {
        $b_id = $branchInfo['b_id'];
        $container = array();

        # rooms and room containers
        # cycle through each room and map to container (or none), then

        # cycle through any containers that don't have rooms.

        # first cycle containers
        $containers = array();
        $doneRoomList = array();
        if (!empty($roomContList['names'][$b_id]) && count($roomContList['names'][$b_id]) !== 0) {
            foreach ($roomContList['names'][$b_id] as $key => $val) {
                $container[$key]['name'] = $val;
                $container[$key]['rooms'] = $roomContList['id'][$key]['rooms'];
                $doneRoomList = array_merge($doneRoomList, $roomContList['id'][$key]['rooms']);
                sort($container[$key]['rooms']);
            }
            $doneRoomList = array_unique($doneRoomList);
            sort($doneRoomList);
        }

        # check for any rooms not in final room list
        $allRoomsBranch = array();
        if (!empty($roomList['room'][$b_id])) {
            $allRoomsBranch = array_keys($roomList['room'][$b_id]);
        }
        $unknown = array_diff($allRoomsBranch, $doneRoomList);

        if (count($unknown) !== 0) {
            $container[null] = array('name' => 'No container', 'rooms' => $unknown);
        }

        return $container;
    }

    public static function showBranchDelete($branchInfo, $roomContList, $roomList)
    # show delete page and fill with values
    {
        # setup times
        $timeDisp = array();
        $am = __('AM', 'book-a-room');
        $pm = __('PM', 'book-a-room');

        for ($d = 0; $d <= 6; $d++) {
            # find if closed
            if (empty($branchInfo["branchOpen_{$d}"]) or empty($branchInfo["branchClose_{$d}"])) {
                $timeDisp[$d] = 'Closed';
            } else {
                # get open and close time
                $openTime = $branchInfo["branchOpen_{$d}"];
                $openTime .= (empty($branchInfo["branchOpen_{$d}PM"])) ? " {$am}" : " {$pm}";
                $closeTime = $branchInfo["branchClose_{$d}"];
                $closeTime .= (empty($branchInfo["branchClose_{$d}PM"])) ? " {$am}" : " {$pm}";
                $timeDisp[$d] = $openTime . ' to ' . $closeTime;
            }
        }

        require DSOL_BOOKING_PATH . 'templates/branches/delete.php';
    }

    public static function showBranchEdit($branchInfo, $action, $actionName, $externals = array())
    # show edit page and fill with values
    {
        /*
        * Jazmyn  
        * 
        * Removed if statements for Branch_isPublic, branch is social,
        * branch_showSocial, and branch_hasNoLoc 
        */
        require DSOL_BOOKING_PATH . 'templates/branches/edit.php';
    }

    public static function showBranchList($branchList)
    # show a list of branches with edit and delete links, or, if none
    # a message stating there are no branches
    {
        require DSOL_BOOKING_PATH . 'templates/branches/mainAdmin.php';
    }
}
?>