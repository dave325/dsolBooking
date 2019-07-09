<?PHP
class dsol_meetings
{
	############################################
	#
	# Meetings managment
	#
	############################################
	public static function dsol_admin()
	{
		self::dsol_pendingRequests( 'all' );
	}
	
	public static function dsol_pendingRequests( $pendingType )
	{	
		$typeArr = array( 'pending', 'pendPayment', '501C3', 'approved', 'denied', 'archived', 'all' );
		$pendingType = trim( $pendingType );
		
		if( empty( $pendingType ) or !in_array( $pendingType, $typeArr) ) {
			$pendingType = 'pending';
		}
		
		# make lines
		require_once( DSOL_BOOKING_PATH . '/dsol-meetings-rooms.php' );
		require_once( DSOL_BOOKING_PATH . '/dsol-meetings-branches.php' );
		require_once( DSOL_BOOKING_PATH . '/dsol-meetings-roomConts.php' );
		require_once( DSOL_BOOKING_PATH . '/dsol-meetings-public.php' );
		# vaiables from includes
		$roomContList = dsol_settings_roomConts::getRoomContList();
		$roomList = dsol_settings_rooms::getRoomList();
		$branchList = dsol_settings_branches::getBranchList( TRUE );
		
		$pendingList = self::getPending();
		$externals = self::getExternals();
		if( empty( $_SESSION['dsol_res_id'] ) ) $_SESSION['dsol_res_id'] = null;
		switch( $externals['action'] ) {
			case 'changeStatusShow':
				self::changeStatusShow();
				break;
				
			case 'changeReservation':
				# check hours
				$baseIncrement = get_option( 'dsol_baseIncrement' );
			
				if( !empty( $externals['hours'] ) ) {
					$externals['startTime'] = current( $externals['hours'] );
				} else {
					$externals['startTime'] = $externals['timestamp'];
				}
				
				if( !empty( $externals['hours'] ) ) {
					$externals['endTime'] = end( $externals['hours'] ) + ( $baseIncrement * 60 );
				} else {
					$externals['endTime'] = $externals['timestamp'];
				}
				
				
				if( empty( $externals['roomID'] ) && !empty( $externals['branchID'] ) ) {
					$externals['roomID'] = ( !empty( $roomContList['branch'][$externals['branchID']][0] ) ) ? $roomContList['branch'][$externals['branchID']][0] : null;
				}
				 
				if( ( $errorMSG = dsol_public::showForm_checkHoursError( $externals['startTime'], $externals['endTime'], $externals['roomID'], $roomContList, $branchList, $_SESSION['dsol_res_id'] ) ) == TRUE ) {
					self::showChangeReservationMeeting( $externals, $errorMSG, $_SESSION['dsol_res_id'] );
					break;
				}
				$requestInfo = $pendingList['id'][$_SESSION['dsol_res_id']];
				
				$requestInfo['startTime'] = $externals['startTime'];
				$requestInfo['endTime'] = $externals['endTime'];

				echo dsol_public::showForm_publicRequest( $externals['roomID'], $branchList, $roomContList, $roomList, $amenityList, $cityList, $requestInfo, array(), $_SESSION['dsol_res_id'] );
				break;
				
			case 'changeReservationSetup':
				$_SESSION['dsol_res_id'] = $externals['res_id'];
				$final = $pendingList['id'][$externals['res_id']];
				$final['timestamp'] = strtotime( $final['startTime'] );
				# setup times
				$baseIncrement = get_option( 'dsol_baseIncrement' );

				for($i = strtotime( $final['startTime'] ); $i< strtotime( $final['endTime'] ); $i += ( $baseIncrement*60 ) ) {
					$final['hours'][] = $i;
				}
				
				$externals = $final;

				self::showChangeReservationMeeting( $externals );
				break;
				
			case 'changeStatus':
				if( empty( $externals['res_id'] ) ) {
					self::showError( 'res_id', $externals['res_id'] );
					break;
				}

				$statusArr = array( 'pending', 'pendPayment', 'approved', 'denied', 'archived', 'delete' );
				$requestInfo = $pendingList['id'][$externals['res_id']];
				if( !in_array( $externals['status'], $statusArr ) ) {
					# BAD STATUS ERROR MESSAGE
					require( DSOL_BOOKING_PATH . 'templates/meetings/error_badStatus.php' );
					break;
				}
				if( !is_array( $externals['res_id'] ) ) {
					$externals['resList'] = array( $externals['res_id'] );
				} else {
					$externals['resList'] = $externals['res_id'];
				}
				
				self::changeStatus( $externals, $pendingList, $branchList, $roomContList, $amenityList );				
				break;
				
			case 'edit':
				if( !array_key_exists( $externals['res_id'], $pendingList['id'] ) ) {
					self::showError( 'res_id', $externals['res_id'] );
					break;
				}
				$requestInfo = $pendingList['id'][$externals['res_id']];
				$_SESSION['dsol_res_id'] = $externals['res_id'];
				$requestInfo['startTime'] = strtotime( $requestInfo['startTime'] );
				$requestInfo['endTime'] = strtotime( $requestInfo['endTime'] );
				$requestInfo['amenity'] = unserialize( $requestInfo['amenity'] );
				
				echo dsol_public::showForm_publicRequest( $requestInfo['roomID'], $branchList, $roomContList, $roomList, $amenityList, $cityList, $requestInfo, array(), $externals['res_id'] );
				break;
				
			case 'editCheck':
				self::showForm_updateRequest( $externals, $branchList, $roomContList, $roomList, $amenityList, $_SESSION['dsol_res_id'] );
				break;
				
			case 'editCheckShow':
				if( empty( $_SESSION['dsol_temp_search_settings'] ) ) {
					$_SESSION['dsol_temp_search_settings'] = NULL;
				}
				self::showForm_updateRequestShow( $externals['res_id'], $_SESSION['dsol_temp_search_settings'] );
				break;
				
			case 'view':
				if( !array_key_exists( $externals['res_id'], $pendingList['id'] ) ) {
					self::showError( 'res_id', $externals['res_id'] );
					break;
				}

				self::showView( $externals['res_id'], $pendingList, $roomContList, $roomList, $branchList, $amenityList );
				break;
			
			case 'showPendPayment':
				self::showPending( 'pendPayment', $pendingList, $roomContList, $roomList, $branchList, $amenityList );
				break;
				
			default:
				self::showPending( $pendingList, $roomContList, $roomList, $branchList, $amenityList, $pendingType );
				break;				
		}		
	}
    /**
     * Aung 
     * 
     * Removed public static function dsol_pendingRequests
     */
    
    /**
     * Aung
     * 
     * Removed $amenityList, $pendingList from changeStatus
     */
	protected static
	function changeStatus( $externals, $branchList, $roomContList) {
		global $wpdb;
		# check that there is a non empty array for res list
		if ( empty( $externals[ 'resList' ] ) || !is_array( $externals[ 'resList' ] ) ) {
			# BAD STATUS ERROR MESSAGE
			require( DSOL_BOOKING_PATH . 'templates/meetings/error_badReservation.php' );
			return false;
		}
		$final = array( 'fail' => array(), 'noChange' => array(), 'changed' => array() );

		# cycle through res list.
		foreach ( $externals[ 'resList' ] as $val ) {

            # double check ID
            /**
             * Aung
             * 
             * Removed $pendingList[ 'id' ]  from !array_key_exits(...)
             */
			if ( !array_key_exists($pendingList[ 'id' ], $val) ) {
				$final[ 'fail' ][] = $val;
				# check if status is different
            } 

            /**
             *  Aung
             * 
             * Removing elseif ( $pendingList[ 'id' ][ $val ][ 'status' ] == $externals[ 'status' ] )
             */
            else {
				$final[ 'changed' ][] = $val;
				# mail alerts
			}
		}

		if ( !empty( $final[ 'changed' ] ) ) {
			# get correct status email
			# status that required an outgoing email: pendPayment, denied, accepted.

			$sendMail = FALSE;
			$needHash = FALSE;

			switch ( $externals[ 'status' ] ) {
                /**
                 * Aung
                 * 
                 * Removed case 'pending'
                 */
				case 'archive':
				case 'accepted':
					break;

				case 'approved':
					$sendMail = TRUE;
					break;

				case 'denied':
					$sendMail = 'denied';

					$subject = get_option( 'dsol_requestDenied_subject' );

					$body = nl2br( get_option( 'dsol_requestDenied_body' ) );
					$costIncrement = 0;
					break;
                /**
                 * Aung
                 * 
                 * Removed case 'pendPayment':
                 */

				case 'delete':
					$delete = TRUE;
			}
			$i = 0;
			# SEND EMAIL
			foreach ( $final[ 'changed' ] as $val ) {
				if($delete == true){
					# UPDATE DATABASE
					$table_name = $wpdb->prefix . "dsol_times";
					$table_nameRes = $wpdb->prefix . "dsol_reservations";
				$wpdb->delete(	$table_nameRes, 
						array( 'res_id' => $val ) );

                /**
                 * Aung
                 * 
                 * Removed $wpdb->delete ... 'ti_id' => $pendingList['id'][$val]['id']
                 */
				if ( $sendMail == true ) {
					if ( $sendMail !== 'denied' ) {
                        /**
                         * Aung
                         * 
                         * Removed $pendingList[ 'id' ][ $val ][ 'nonProfit' ] == TRUE
                         * of if-logic case
                         */
                        if ( $externals[ 'status' ] == 'pendPayment' ) {
                            $subject = get_option( 'dsol_profit_pending_subject' );
                            $body = nl2br( get_option( 'dsol_profit_pending_body' ) );
                        } else {
                            $subject = get_option( 'dsol_requestAcceptedProfit_subject' );
                            $body = nl2br( get_option( 'dsol_requestAcceptedProfit_body' ) );
                        }

                        /**
                         * Aung
                         * 
                         * Removed $pendingList[ 'id' ][ $val ][ 'roomDeposit' ] = get_option( 'dsol_profitDeposit' );
                         */
                        $costIncrement = get_option( 'dsol_profitIncrementPrice' );
                    }
                    
                    /**
                     * Aung
                     * 
                     * Removed if-block of $pendingList[ 'id' ][ $val ][ 'roomDeposit' ] = '0';
                     */

                     /**
                      * Aung
                      *
                      * Remove $roomCount = count( $roomContList[ 'id' ][ $pendingList[ 'id' ][ $val ][ 'roomID' ] ][ 'rooms' ] );
                      */
                    /**
                     * Aung 
                     * 
                     * Removed udating pendingList
                     */


                    /**
                     * Aung
                     * 
                     * Removed $amenity = array() and updating $amenity
                     */
                    
					$fromName = get_option( 'dsol_alertEmailFromName' );
					$fromEmail = get_option( 'dsol_alertEmailFromEmail' );
					$replyToOnly = ( true == get_option( 'dsol_emailReplyToOnly' ) ) ? "From: {$fromName}\r\nReply-To: {$fromName} <{$fromEmail}>\r\n" : "From: {$fromName} <{$fromEmail}>\r\n";

                    /**
                     * Aung
                     * 
                     * Removed pendingList payment link
                     */

                    /**
                     *  Aung
                     * 
                     * 'amenity', 'amenityVal',  were in below array
                     */
					$valArr = array('branchName', 'ccLink', 'contactAddress1', 'contactAddress2', 'contactCity', 'contactEmail', 'contactName', 'contactPhonePrimary', 'contactPhoneSecondary', 'contactState', 'contactWebsite', 'contactZip', 'date', 'desc', 'endTime', 'endTimeDisp', 'eventName', 'formDate', 'nonProfit', 'nonProfitDisp', 'numAttend', 'paymentLink', 'roomDeposit', 'roomID', 'roomName', 'roomPrice', 'startTime', 'startTimeDisp', 'totalPrice' );
					foreach ( $valArr as $val2 ) {

                        /**
                         * Aung
                         * 
                         * Removed if-block replacing $body with pendingList 
                         */
						$body = str_replace( "{{$val2}}", NULL, $body );
					}

                    # date 1 - two weeks from now
                    /**
                     * Aung
                     * 
                     * Removed $timeArr = getdate( strtotime( $pendingList[ 'id' ][ $val ][ 'created' ] ) );
                     * Related with pendingList
                     */
					$date1 = mktime( 0, 0, 0, $timeArr[ 'mon' ], $timeArr[ 'mday' ] + 14, $timeArr[ 'year' ] );
					$date3 = mktime( 0, 0, 0, $timeArr[ 'mon' ], $timeArr[ 'mday' ] + 1, $timeArr[ 'year' ] );

                    # two weeks before event	
                    /***
                     * Aung
                     * 
                     * Removed $timeArr = getdate( strtotime( $pendingList[ 'id' ][ $val ][ 'date' ] . ' ' . $pendingList[ 'id' ][ $val ][ 'startTime' ] ) );
                     */
					$date2 = mktime( 0, 0, 0, $timeArr[ 'mon' ], $timeArr[ 'mday' ] - 14, $timeArr[ 'year' ] );


					# check dates
					$mainDate = min( $date1, $date2 );
					if ( $mainDate < $date3 ) {
						$mainDate = $date3;
					}
					$body = str_replace( '{paymentDate}', date( 'm-d-Y', $mainDate ), $body );
					$headers  = 'MIME-Version: 1.0' . "\r\n";
					$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
						$replyToOnly .
						'X-Mailer: PHP/' . phpversion();

                    /**
                     * Aung
                     * 
                     * Removed mail( $pendingList['id'][$val]['contactEmail'], $subject, $body, $headers );
                     */
                }	
            }			
				
				# UPDATE DATABASE
				$table_name = $wpdb->prefix . "dsol_reservations";
				
				$wpdb->update(	$table_name, 
						array( 'me_status'				=> $externals['status'] ), 
						array( 'res_id' => $val ) );
						$i++;
			}
		}

		$_SESSION['showData'] = $final;
		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=?page=dsol_meetings&action=changeStatusShow">';
    }
    
    
    public static function changeStatusShow()
	{	
		$final = @$_SESSION['showData'];
		unset( $_SESSION['showData'] );
		require( DSOL_BOOKING_PATH . 'templates/meetings/changeStatus.php' );
	}
	
	public static function getExternals()
	# Pull in POST and GET values
	{
		$final = array();

		# setup GET variables
		$getArr = array(	'action'					=> FILTER_SANITIZE_STRING, 
							'roomID'					=> FILTER_SANITIZE_STRING, 
							'branchID'					=> FILTER_SANITIZE_STRING, 
							'timestamp'					=> FILTER_SANITIZE_STRING, 
							'submitCal'					=> FILTER_SANITIZE_STRING, 
							'calYear'					=> FILTER_SANITIZE_STRING, 
							'calMonth'					=> FILTER_SANITIZE_STRING, 
							'calDay'					=> FILTER_SANITIZE_STRING, 
							'ccLink'					=> FILTER_SANITIZE_STRING, 
							'res_id'					=> FILTER_SANITIZE_STRING);

		# pull in and apply to final
		if( $getTemp = filter_input_array( INPUT_GET, $getArr ) ){
			$final = array_merge( $final, $getTemp );
		}

        /**
         * Aung
         * 
         * 'amenit => array(	'filter'    => FILTER_SANITIZE_STRING,
		 * 'flags'     => FILTER_REQUIRE_ARRAY ),
         */
		# setup POST variables
		$postArr = array(	'action'					=> FILTER_SANITIZE_STRING, 
							'contactAddress1'			=> FILTER_SANITIZE_STRING,
							'contactAddress2'			=> FILTER_SANITIZE_STRING,
							'contactCity'				=> FILTER_SANITIZE_STRING,
							'contactEmail'				=> FILTER_SANITIZE_STRING,
							'contactName'				=> FILTER_SANITIZE_STRING,
							'contactPhonePrimary'		=> FILTER_SANITIZE_STRING,
							'contactPhoneSecondary'		=> FILTER_SANITIZE_STRING,
							'contactState'				=> FILTER_SANITIZE_STRING,
							'contactWebsite'			=> FILTER_SANITIZE_STRING,
							'contactZip'				=> FILTER_SANITIZE_STRING,
							'desc'						=> FILTER_SANITIZE_STRING,
							'emailAddress'				=> FILTER_SANITIZE_STRING,
							'endTime'					=> FILTER_SANITIZE_STRING,
							'eventName'					=> FILTER_SANITIZE_STRING,
							'libcardNum'				=> FILTER_SANITIZE_STRING,	
							'isSocial'					=> FILTER_SANITIZE_STRING,
							'nonProfit'					=> FILTER_SANITIZE_STRING,
							'notes'						=> FILTER_SANITIZE_STRING,
							'numAttend'					=> FILTER_SANITIZE_STRING,
							'roomID'					=> FILTER_SANITIZE_STRING,
							'status'					=> FILTER_SANITIZE_STRING,
							'startTime'					=> FILTER_SANITIZE_STRING,
							'timestamp'					=> FILTER_SANITIZE_STRING, 
							'branchID'					=> FILTER_SANITIZE_STRING, 
							'viewAll'					=> FILTER_SANITIZE_STRING, 
							'res_id'					=> array(	'filter'    => FILTER_SANITIZE_STRING,
																	'flags'     => FILTER_REQUIRE_ARRAY ), 
							'hours'						=> array(	'filter'    => FILTER_SANITIZE_STRING,
																	'flags'     => FILTER_REQUIRE_ARRAY ) );

	

		# pull in and apply to final
		if( $postTemp = filter_input_array( INPUT_POST, $postArr ) ) {
			$final = array_merge( $final, $postTemp );
		}

		$arrayCheck = array_unique( array_merge( array_keys( $getArr ), array_keys( $postArr ) ) );
        
        /**
         * Aung
         * 
         * Remove $key == 'amenity' || 
         */
		foreach( $arrayCheck as $key ) {
			if( empty( $final[$key] ) ) {
				$final[$key] = NULL;
			} elseif( is_array( $final[$key] ) && ( $key == 'hours' || $key = 'res_id' ) ) {
				$final[$key] = array_filter( $final[$key], 'strlen' );
			} elseif( is_array( $final[$key] ) ) {
				$final[$key] = array_keys( $final[$key] );
			} else {
				$final[$key] = trim( $final[$key] );
			}
		}
		
		# calendar timestamp
		if( !empty( $final['submitCal'] ) ) {
			$final['timestamp'] = mktime( 0, 0, 0, $final['calMonth'], 1, $final['calYear'] );
		}

		return $final;
	}
    
    /**
     * Aung
     * 
     * Removed public static function getPending( $date = NULL, $statusArr = array(), $isEvent = NULL, $showHidden = false, $approvedOnly = false, $viewAll = false )
     */
	
	public static function getDaily( $date )
	{
		global $wpdb;
		
		$roomContList = dsol_settings_roomConts::getRoomContList();
		$roomList = dsol_settings_rooms::getRoomList();
		$branchList = dsol_settings_branches::getBranchList( TRUE );
		
		$table_name = $wpdb->prefix . "dsol_times";
		$table_nameRes = $wpdb->prefix . "dsol_reservations";
		
		$where = array();
		
		$startDateStamp = date( 'Y-m-d H:i:s', $date );
		$endDateStamp = date( 'Y-m-d H:i:s', $date + 86400 );
		$where[] = "`ti_startTime` < '{$endDateStamp}' and `ti_endTime` >= '{$startDateStamp}'";
		$where[] = "( `ti`.`ti_type` = 'meeting' AND `res`.`me_status` = 'approved' ) OR ( `ti`.`ti_type` = 'event' AND `res`.`ev_noPublish` = 0 )";
		
			
		if( count( $where ) > 0 ) {
			$where = ' WHERE (' . implode( ') AND (', $where ) . ')';
		} else {
			$where = NULL;
        }
        /**
         * Aung
         * 
         * Removed `res`.`me_amenity` as `amenity`
         */
		$sql = "	SELECT 
					`res`.`res_id`, 
					`ti`.`ti_startTime` as `startTime`, 
					`ti`.`ti_endTime` as `endTime`, 
					`ti`.`ti_roomID` as `roomID`, 
					`ti`.`ti_created` as `created`, 
					`ti`.`ti_type` as `type`, 
					`ti`.`ti_noLocation_branch` as `noLocation_branch`, 
					`res`.`me_numAttend` as `numAttend`, 
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
					`res`.`me_status` as `status`, 
					`res`.`me_notes` as `notes` 
					IF( `ti`.`ti_type` = 'meeting', `res`.`me_contactName`, `res`.`ev_publicName` ) as `contactName`, 
					IF( `ti`.`ti_type` = 'meeting', `res`.`me_eventName`, `res`.`ev_title` ) as `eventName`, 
					IF( `ti`.`ti_type` = 'meeting', `res`.`me_desc`, `res`.`ev_desc` ) as `desc`, 

				FROM `{$table_name}` as `ti` 
				LEFT JOIN `{$table_nameRes}` as `res` ON `ti`.`ti_extID` = `res`.`res_id` 
				LEFT JOIN `{$wpdb->prefix}dsol_roomConts` as `cont` ON `ti`.`ti_roomID` = `cont`.`roomCont_ID` 
				{$where} 
				ORDER BY `res`.`me_status`, `ti`.`ti_startTime`";
		$temp = $wpdb->get_results( $sql, ARRAY_A );
		
		if( empty( $temp ) ) {
			 $final = NULL;
		} else {
			foreach( $temp as $key => $val ) {
				if( $val['status'] == 'pendPayment' and $val['nonProfit']  == true ) {
					$val['status'] = '501C3';
				}
				
				$final['status'][$val['status']][$val['res_id']] = $val['res_id'];
				$final['id'][$val['res_id']] = $val;
				$final['status']['all'][$val['res_id']] = $val['res_id'];
				
				if( empty( $val['noLocation_branch'] )) {
					@$final['location'][$branchList[$roomContList['id'][$val['roomID']]['branchID']]['branchDesc']][$roomContList['id'][$val['roomID']]['desc']][] = $val;
				} else {
					@$final['location'][$branchList[$val['noLocation_branch']]['branchDesc']]['No location specified'][] = $val;
				}
			}
		}
		
		return $final;
	}
	
	public static function dsol_approvedRequests()
	{
		self::dsol_pendingRequests( 'approved' );
	}
	
	public static function dsol_archivedRequests()
	{
		self::dsol_pendingRequests( 'archived' );
	}

	public static function dsol_contactList()
	{
        /**
         * Aung
         * 
         * Removed require_once( DSOL_BOOKING_PATH . '/dsol-meetings-amenities.php' );
         */
		require_once( DSOL_BOOKING_PATH . '/dsol-meetings-rooms.php' );
		require_once( DSOL_BOOKING_PATH . '/dsol-meetings-branches.php' );
		require_once( DSOL_BOOKING_PATH . '/dsol-meetings-roomConts.php' );
		require_once( DSOL_BOOKING_PATH . '/dsol-meetings-closings.php' );
		require_once( DSOL_BOOKING_PATH . '/dsol-meetings-public.php' );
		
		# vaiables from includes
		$roomContList = dsol_settings_roomConts::getRoomContList();
		$roomList = dsol_settings_rooms::getRoomList();
        $branchList = dsol_settings_branches::getBranchList( TRUE );
        
        /**
         * Aung
         * 
         * Removed $amenityList = dsol_settings_amenities::getAmenityList();
         */
		$externals = self::getExternals();
		
		# date
		if ( empty( $externals[ 'timestamp' ] ) or strtotime( $externals[ 'timestamp' ] ) == false ) {
			$timestamp = time();
		} else {
			$timestamp = strtotime( $externals[ 'timestamp' ] );
		}
		
		# get reservations for date
		$pendingList = self::getPending( $timestamp );
		$typeArr = array( 'pending' => __( 'New Pend.', 'book-a-room' ), 'pendPayment' => __( 'Pend. Payment', 'book-a-room' ), '501C3' => __( 'Pend 501(c)3', 'book-a-room' ), 'approved' => __( 'Approved', 'book-a-room' ), 'denied' => __( 'Denied', 'book-a-room' ), 'archived' => __( 'Archived', 'book-a-room' ) );
		
		require( DSOL_BOOKING_PATH . 'templates/meetings/contactList.php' );
		
		
	}
	
	public static function dsol_dailyMeetings()
	{
		/**
         * Aung
         * 
         * Removed require_once( DSOL_BOOKING_PATH . '/dsol-meetings-amenities.php' );
         */
		require_once( DSOL_BOOKING_PATH . '/dsol-meetings-rooms.php' );
		require_once( DSOL_BOOKING_PATH . '/dsol-meetings-branches.php' );
		require_once( DSOL_BOOKING_PATH . '/dsol-meetings-roomConts.php' );
		require_once( DSOL_BOOKING_PATH . '/dsol-meetings-closings.php' );
		require_once( DSOL_BOOKING_PATH . '/dsol-meetings-public.php' );
		
		# vaiables from includes
		$roomContList = dsol_settings_roomConts::getRoomContList();
		$roomList = dsol_settings_rooms::getRoomList();
        $branchList = dsol_settings_branches::getBranchList( TRUE );
        
        /**
         * Aung
         * 
         * Removed $amenityList = dsol_settings_amenities::getAmenityList();
         */
		$externals = self::getExternals();
		$defaultEmail = get_option( 'dsol_defaultEmailDaily' );
		
		$viewAllChecked = ( !empty( $externals['viewAll'] ) ) ? ' checked="checked"' : null;
		# date
		if ( empty( $externals[ 'timestamp' ] ) or strtotime( $externals[ 'timestamp' ] ) == false ) {
			$timestamp = time();
		} else {
			$timestamp = strtotime( $externals[ 'timestamp' ] );
		}
		$timeInfo = getdate( $timestamp );
        $timestamp = mktime( 0,0,0, $timeInfo['mon'], $timeInfo['mday'], $timeInfo['year'] );
        /***
         * Aung
         * 
         * Removed $pendingList = self::getPending(  $timestamp, array('approved'), true );
         */
		
		require( DSOL_BOOKING_PATH . 'templates/meetings/dailyMeetings.php' );
	}	
	
	public static function dsol_deniedRequests()
	{
		self::dsol_pendingRequests( 'denied' );
	}
	
	public static function dsol_pendingPayment()
	{
		self::dsol_pendingRequests( 'pendPayment' );

	}
	
	public static function dsol_pending501C3()
	{
		self::dsol_pendingRequests( '501C3' );

	}	
	
	public static
	function showChangeReservationMeeting( $externals, $errorMSG = NULL, $res_id = NULL )
	#, $branchList, $roomContList, $roomList, $amenityList, $errorMSG = NULL, $res_id = NULL, $contents = NULL )
	{

		$roomContList = dsol_settings_roomConts::getRoomContList();
		$roomList = dsol_settings_rooms::getRoomList();
		$branchList = dsol_settings_branches::getBranchList( TRUE );

		if ( empty( $res_id ) && !empty( $externals[ 'res_id' ] ) ) {
			$res_id = $externals[ 'res_id' ];
		}

		$roomID = $externals[ 'roomID' ];
		$timestamp = $externals[ 'timestamp' ];
		$hours = $externals[ 'hours' ];

		if ( empty( $timestamp ) ) {
			$timestamp = current_time( 'timestamp' );
		}

		if ( !empty( $roomContList ) && !empty( $roomID ) && in_array( $roomID, array_keys( $roomContList[ 'id' ] ) ) ) {
			# find that room's branch
			# if there is no valid branch, both are null (can't have a 
			# valid room that has no branch)
			if ( empty( $roomContList[ 'id' ][ $roomID ][ 'branchID' ] ) ) {
				$branchID = NULL;
				$roomID = NULL;
				# if valid branch, map it.
			} else {
				$branchID = $roomContList[ 'id' ][ $roomID ][ 'branchID' ];
			}

			# if the room is empty, check for a branch ID, make sure it's valid
		} elseif ( !empty( $branchID ) && in_array( $branchID, array_keys( $branchList ) ) ) {
			# since we have a valid branch, lets see if there are any rooms (make sure that
			# the room list is an array and not empty )
			if ( !empty( $roomContList[ 'branch' ][ $branchID ] ) && is_array( $roomContList[ 'branch' ][ $branchID ] ) ) {
				# make the room ID the first room
				$roomID = current( $roomContList[ 'branch' ][ $branchID ] );
			} else {
				# if no rooms, show no available rooms
				$roomID = NULL;
			}

		} else {
			$branchID = NULL;
			$roomID = NULL;
		}
		$urlInfoRaw = parse_url( get_permalink() );
		$urlInfo = (!empty( $urlInfoRaw['query'] ) ) ? $urlInfoRaw['query'] : null;
		if( empty( $urlInfo ) ) {
			$permalinkCal = '?';
		} else {
			$permalinkCal = '?'.$urlInfo.'&';
		}
		# full month timestamp
		$timestampInfo = getdate( $timestamp );
		$thisMonth = mktime( 0, 0, 0, $timestampInfo['mon'], 1, $timestampInfo['year'] );
		
		$nextMonth = mktime( 0, 0, 0, $timestampInfo['mon']+1, 1, $timestampInfo['year'] );
		$prevMonth = mktime( 0, 0, 0, $timestampInfo['mon']-1, 1, $timestampInfo['year'] );
		
		# On which day of the week does this month start?
		$dayOfWeek = date( 'w', $thisMonth );
		# How many weeks are there in this month?
		$daysInMonth =  date( 't', $thisMonth );
		$weeksInMonth = ceil( ( $daysInMonth + $dayOfWeek ) / 7 );
		
		require( DSOL_BOOKING_PATH . 'templates/meetings/pending_changeReservation.php' );
	}

	public static function dsol_dailyRoomSigns()
	{
		/**
         * Aung
         * 
         * Removed require_once( DSOL_BOOKING_PATH . '/dsol-meetings-amenities.php' );
         */
		require_once( DSOL_BOOKING_PATH . '/dsol-meetings-rooms.php' );
		require_once( DSOL_BOOKING_PATH . '/dsol-meetings-branches.php' );
		require_once( DSOL_BOOKING_PATH . '/dsol-meetings-roomConts.php' );
		require_once( DSOL_BOOKING_PATH . '/dsol-meetings-closings.php' );
		require_once( DSOL_BOOKING_PATH . '/dsol-meetings-public.php' );
		
		# vaiables from includes
		$roomContList = dsol_settings_roomConts::getRoomContList();
		$roomList = dsol_settings_rooms::getRoomList();
        $branchList = dsol_settings_branches::getBranchList( TRUE );
        
        /**
         * Aung
         * emoved 
         * $amenityList = dsol_settings_amenities::getAmenityList();
         */
		$externals = self::getExternals();
		
		require( DSOL_BOOKING_PATH . 'templates/meetings/dailyRoomSigns.php' );		
	}
		
	protected static function showError( $errorType, $extVal )
	{
		require(  DSOL_BOOKING_PATH . 'templates/meetings/pending_error.php' );
	}
    
    /**
     * Aung
     * 
     * Removed  $amenityList from showForm_updateRequest
     */
	protected static function showForm_updateRequest( $externals, $branchList, $roomContList, $roomList, $res_id )
	{
		global $wpdb;
		
		$nonProfit = ( empty( $externals['nonProfit'] ) ) ? FALSE : TRUE;
        
        /**
         * Aung
         * 
         * Removed if-block updating $amenity = NULL or $amenity = serialize( $externals['amenity'] );
         */
		
		$table_name = $wpdb->prefix . "dsol_times";
		
		$wpdb->update(	$table_name, 
						array( 
						
			'ti_startTime'				=> date( 'Y-m-d H:i:s', $externals['startTime'] ),
			'ti_endTime'				=> date( 'Y-m-d H:i:s', $externals['endTime'] ),
			'ti_roomID'					=> $externals['roomID'] ), 
						array( 'ti_extID' => $res_id ) );
						
		
		$social = ( empty( $externals['isSocial'] ) ) ? false : true;
		
        $table_name = $wpdb->prefix . "dsol_reservations";
        /**
         * Aung
         * 
         * Reomved these two lines
         * 'me_social'					=> $social,
		 * 'me_amenity'				=> $amenity  
         */
		$wpdb->update(	$table_name, 
						array( 
						
			'me_numAttend'				=> $externals['numAttend'],
			'me_eventName'				=> esc_textarea( $externals['eventName'] ),
			'me_desc'					=> esc_textarea( $externals['desc'] ),
			'me_contactName'			=> esc_textarea( $externals['contactName'] ),
			'me_contactPhonePrimary'	=> $externals['contactPhonePrimary'],
			'me_contactPhoneSecondary'	=> $externals['contactPhoneSecondary'],
			'me_contactAddress1'		=> esc_textarea( $externals['contactAddress1'] ),
			'me_contactAddress2'		=> esc_textarea( $externals['contactAddress2'] ),
			'me_contactCity'			=> esc_textarea( $externals['contactCity'] ),
			'me_contactState'			=> $externals['contactState'],
			'me_contactZip'				=> $externals['contactZip'],
			'me_contactEmail'			=> esc_textarea( $externals['contactEmail'] ),
			'me_contactWebsite'			=> esc_textarea( $externals['contactWebsite'] ),
			'me_notes'					=> esc_textarea( $externals['notes'] ),
			'me_nonProfit'				=> $nonProfit,
			'me_libcardNum'				=> esc_textarea( $externals['libcardNum'] ),), 
						array( 'res_id' => $res_id ) );

		echo '<META HTTP-EQUIV="Refresh" Content="0; URL=?page=dsol_meetings&action=editCheckShow&res_id='.$res_id.'">';
				
		return TRUE;
	}
	
	protected static function showForm_updateRequestShow( $res_id, $search_settings )
	{
		require( DSOL_BOOKING_PATH . 'templates/meetings/pending_success.php' );
	}
    
    /**
     * Aung
     * 
     * Removed 	public static function showPending
     */

	 public static function showPending(){
		require( BOOKAROOM_PATH . 'templates/meetings/pending.php' );
	 }
	
	/**
     * Aung
     * 
     * 
     * Remove $amenityList, $pendingList from the argument list  of showView
     */
	protected static function showView( $res_id, $roomContList, $roomList, $branchList )
	{
        /**
         * Aung
         * 
         * Removed pendingList['id] branchName, endTimeDisp ... updating
         */
		
		if ( get_option( 'dsol_addressType' ) !== 'usa' ) {
			$address1_name = get_option( 'dsol_address1_name' );
			$address2_name = get_option( 'dsol_address2_name' );
			$city_name = get_option( 'dsol_city_name' );
			$state_name = get_option( 'dsol_state_name' );
			$zip_name = get_option( 'dsol_zip_name' );
		} else {
			$address1_name = 'Street Address';
			$address2_name = 'Address';
			$city_name = 'City';
			$state_name = 'State';
			$zip_name = 'Zip Code';
		}
		
		/**
         * Aung
         * 
         * Removed if-block updating $amenityArr = array() or $amenityArr = unserialize( $pendingList[ 'id' ][ $res_id ][ 'amenity' ] );
         */

        /**
         * Aung
        *
        * Removed if-block of 
        * $pendingList[ 'id' ][ $res_id ][ 'amenityVal' ] = implode( ', ', $temp )
        * $pendingList[ 'id' ][ $res_id ][ 'amenityVal' ] = 'None';
        */
		
		# cost
		$option[ 'dsol_profitDeposit' ] = get_option( 'dsol_profitDeposit' );
		$option[ 'dsol_nonProfitDeposit' ] = get_option( 'dsol_nonProfitDeposit' );
		$option[ 'dsol_profitIncrementPrice' ] = get_option( 'dsol_profitIncrementPrice' );
		$option[ 'dsol_nonProfitIncrementPrice' ] = get_option( 'dsol_nonProfitIncrementPrice' );
        $option[ 'dsol_baseIncrement' ] = get_option( 'dsol_baseIncrement' );
        
        /**
         * Aung
         * 
         * Removed $roomCount = count($roomContList[ 'id' ][ $pendingList[ 'id' ][ $res_id ][ 'roomID' ] ][ 'rooms' ])
         */

        /**
         * Aung
        *
        * Removed if-block if ( empty( $pendingList[ 'id' ][ $res_id ][ 'nonProfit' ] ) )
        * $minutes = ( ( strtotime( $pendingList[ 'id' ][ $res_id ][ 'endTime' ] ) - strtotime( $pendingList[ 'id' ][ $res_id ][ 'startTime' ] ) ) / 60 ) / $option[ 'dsol_baseIncrement' ];
        */
        # find how many increments
        $roomPrice = $minutes * $option[ 'dsol_nonProfitIncrementPrice' ] * $roomCount;
        $deposit = intval( $option[ 'dsol_nonProfitDeposit' ] );
		
        # date 1 - two weeks from now
        
        /**
         * Aung
         * 
         * Removed $timeArr = getdate( strtotime( $pendingList[ 'id' ][ $res_id ][ 'created' ] ) );
         */
		$date1 = mktime( 0, 0, 0, $timeArr[ 'mon' ], $timeArr[ 'mday' ] + 14, $timeArr[ 'year' ] );
		$date3 = mktime( 0, 0, 0, $timeArr[ 'mon' ], $timeArr[ 'mday' ] + 1, $timeArr[ 'year' ] );

        # two weeks before event
        /**
         *  Aung
         * 
         * Removed $timeArr = getdate( strtotime( $pendingList[ 'id' ][ $res_id ][ 'created' ] ) );
         */
		$date2 = mktime( 0, 0, 0, $timeArr[ 'mon' ], $timeArr[ 'mday' ] - 14, $timeArr[ 'year' ] );

		# check dates
		$mainDate = min( $date1, $date2 );

		if ( $mainDate < $date3 ) {
			$mainDate = $date3;
		}
		
		require( DSOL_BOOKING_PATH . 'templates/meetings/pending_view.php' );
	}
	
	public static function makePaymentLink( $totalPrice, $res_id )
	{
		$paymentLink = get_option( 'dsol_paymentLink' );
		
		if( empty( $paymentLink ) ) {
			return NULL;
		}
		
		if( empty( $totalPrice) || !is_numeric( $totalPrice ) ) {
			return 'No payment required';
		}

		global $wpdb;
		$table_name = $wpdb->prefix . "dsol_reservations";
		
		$isSaltRaw = $wpdb->get_row( "SELECT `me_salt` FROM `{$table_name}` WHERE `res_id` = '{$res_id}'", ARRAY_A );
		
		if( empty( $isSaltRaw['me_salt'] ) ) {
			$isSalt = NULL;
		} else {
			$isSalt = $isSaltRaw['me_salt'];
		}

		if( empty( $isSalt ) || strlen( $isSalt ) !== 32 ) {
			$salt = uniqid(mt_rand(), true);
			$wpdb->update( $table_name, array( 'me_salt' => $salt ), array( 'res_id' => $res_id ) );
		} else {
			$salt = $isSalt;
		}
	
		$hash = md5( $salt.$res_id );
		
		return "<a href=\"{$paymentLink}?hash={$hash}&res_id={$res_id}\">Click here to pay online with a credit card</a>.";
	}
	
	public static function noteInformation( $contactName, &$notesNumber )
	{
		$final = array();
		
		global $wpdb;
	
		$table_name = $wpdb->prefix . "dsol_reservations";
		
		# get row count
		$sql = "SELECT re.res_id, re.me_notes, re.me_eventName, ti.ti_startTime 
					FROM {$wpdb->prefix}dsol_reservations re 
					LEFT JOIN {$wpdb->prefix}dsol_times ti on re.res_id = ti.ti_extID 
					WHERE  re.me_contactName LIKE  '%{$contactName}%'
					ORDER BY ti.ti_startTime DESC";

		$newOrderArr = $wpdb->get_results( $sql, ARRAY_A );
		
		foreach( $newOrderArr as $val ) {
			if( empty( $val['me_notes'] ) ) {
				continue;
			}
			$niceDate = date_i18n( 'l, F jS, Y g:i a', strtotime( $val['ti_startTime'] ) );
			$notes = nl2br( htmlspecialchars_decode( $val['me_notes'] ) );
			$final[] = "<p><strong>{$val['me_eventName']} - <a href=\"?page=dsol_meetings&action=edit&res_id={$val['res_id']}\"  target=\"_blank\">Edit</a><br><em>{$niceDate}</em></strong><br>{$notes}</p>";
		}
		
		$notesNumber = count( $final );
		
		if( $notesNumber == 0 ) {
			return "No notes found for {$contactName}.";	
		} else {
			return implode( "<hr>\r\n", $final );
		}
		
		return $final;
	}
	
	public static function prettyPhone( $val )
	{
		$phone = preg_replace("/[^0-9,.]/", "", $val);
		return '(' . substr( $phone, 0, 3) . ') ' . substr( $phone, 3, 3 ) . '-' . substr( $phone, 6 );	
	}

}
