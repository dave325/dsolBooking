<?php
class dsol_settings_rooms {
	############################################
	#
	# Room managment
	#
	############################################
	public static
	function bookaroom_admin_rooms() {
		$roomList = self::getRoomList();
		$branchList = dsol_settings_branches::getBranchList();
		
		/*
			Kelvin: Remove $amenityList
		*/

		# figure out what to do
		# first, is there an action?
		$externals = self::getExternalsRoom();
		$error = NULL;

		switch ( $externals[ 'action' ] ) {
			case 'deleteCheck':
				if ( dsol_settings::checkID( $externals[ 'r_id' ], $roomList[ 'room' ], TRUE ) == FALSE ) {
					# show error page
					require( 'templates/rooms/IDerror.php' );
				} else {
					# delete room
					$roomContList = dsol_settings_roomConts::getRoomContList();

					self::deleteRoom( $externals[ 'r_id' ], $roomContList );
					require( 'templates/rooms/deleteSuccess.php' );
				}

				break;

			case 'delete':

			/*
					Kelvin: remove $amenityList
			*/

				# check that there is an ID and it is valid
				if ( dsol_settings::checkID( $externals[ 'r_id' ], $roomList[ 'room' ], TRUE ) == FALSE ) {
					# show error page
					require( 'templates/rooms/IDerror.php' );
				} else {
					# show delete screen
					self::showRoomDelete( $externals[ 'r_id' ], $roomList, $branchList);
				}
				break;

			case 'editCheck':

			/*
					Kelvin: remove $amenityList
			*/
				# check entries
				if ( ( $errors = self::checkEditRoom( $externals, $branchList, $roomList, $externals[ 'r_id' ] ) ) == NULL ) {
					self::editRoom( $externals);
					require( 'templates/rooms/editSuccess.php' );
					break;
				}

				$externals[ 'errors' ] = $errors;

				# check that there is an ID and it is valid
				if ( dsol_settings::checkID( $externals[ 'r_id' ], $roomList[ 'room' ], TRUE ) == FALSE ) {
					# show error page
					require( 'templates/rooms/IDerror.php' );
				} else {
					# show edit screen
					self::showRoomEdit( $externals, $branchList, 'editCheck', 'Edit' );
				}
				break;

			case 'edit':
				# check that there is an ID and it is valid

				if ( dsol_settings::checkID( $externals[ 'r_id' ], $roomList[ 'room' ], TRUE ) == FALSE ) {
					# show error page
					require( 'templates/rooms/IDerror.php' );
				} else {
					# show edit screen
					$roomInfo = self::getRoomInfo( $externals[ 'r_id' ] );
					self::showRoomEdit( $roomInfo, $branchList, 'editCheck', 'Edit' );
				}

				break;

			case 'addCheck':

			/*
				Kelvin: Remove $amenityList
			*/
				if ( ( $error = self::checkEditRoom( $externals, $branchList, $roomList, NULL ) ) == TRUE ) {
					$externals[ 'errors' ] = $error;
					self::showRoomEdit( $externals, $branchList, 'addCheck', 'Add' );
				} else {
					self::addRoom( $externals);
					require( 'templates/rooms/addSuccess.php' );
				}
				break;

			case 'add':

			/*
				Kelvin: Remove $amenityList
			*/

				self::showRoomEdit( NULL, $branchList, 'addCheck', 'Add' );
				break;

			default:

			/*
				Kelvin: Remove $amenityList
			*/
				self::showRoomList( $roomList, $branchList);
				break;

		}
	}
	

	# sub functions:

	/*
			Kelvin: Remove $amenityList
	*/
	public static
	function addRoom( $externals)
	# add a new branch
	{
		global $wpdb;

		$table_name = $wpdb->prefix . "dsol_booking_room";

		$final = $wpdb->insert( $table_name,
			array( 'room_number' => $externals[ 'room_Number' ],
				'b_id' => $externals[ 'branch' ] ) );
	}



/*
		Kelvin: remove $amenityList
*/

	public static
	function checkEditRoom( $externals, $branchList, $roomList, $r_id )
	# check the room to make sure everything is filled out
	# there are no duplicate names in the same branch
	# and the amenities are valid
	{
		$error = array();
		$final = NULL;
		# check name is filled and isn't duped in the same branch
		# check for empty room name
		if ( empty( $externals[ 'room_Number' ] ) ) {
			$error[] = __( 'You must enter a room name.', 'book-a-room' );
		}

		# fix array if none

		if ( empty( $externals[ 'branch' ] ) ) {
			$error[] = __( 'You must choose a branch.', 'book-a-room' );
		} else {
			# check dupe name FOR THAT BRANCH - first, are there any rooms?
			if ( !empty( $roomList[ 'room' ] ) and array_key_exists( $externals[ 'branch' ], $roomList[ 'room' ] ) ) {
				if ( dsol_settings::dupeCheck( $roomList[ 'room' ][ $externals[ 'branch' ] ], $externals[ 'room_Number' ], $externals[ 'r_id' ] ) == 1 ) {
					$error[] = __( 'That room name is already in use at that branch. Please choose another.', 'book-a-room' );
				}
			}
		}
		# if errors, implode and return error messages
		if ( count( $error ) !== 0 ) {
			$final = implode( "<br />", $error );
		}

		return $final;
	}

	public static
	function deleteRoom( $r_id, $roomContList )
	# delete room
	{
		global $wpdb;
		# Delete actual room
		# ***
		$table_name = $wpdb->prefix . "dsol_booking_room";

		$sql = "DELETE FROM `{$table_name}` WHERE `r_id` = '{$r_id}' LIMIT 1";
		$wpdb->query( $sql );

		# Search containers for that room, remove
		$table_name = $wpdb->prefix . "bookaroom_roomConts";

		$sql = "DELETE FROM `{$table_name}` WHERE `rcm_r_id` = '{$r_id}'";
		$wpdb->query( $sql );
	}



/*
		Kelvin: set $amenityList to NULL when updating database
*/
	public static
	function editRoom( $externals)
	# change the room settings
	{
		global $wpdb;

		$table_name = $wpdb->prefix . "dsol_booking_room";
	

		$final = $wpdb->update( $table_name,
			array( 'room_number' => $externals[ 'room_Number' ],
				'b_id' => $externals[ 'branch' ] ),
			array( 'r_id' => $externals[ 'r_id' ] ) );
	}

	public static
	function getExternalsRoom()
	# Pull in POST and GET values
	{
		$final = array();

		# setup GET variables
		$getArr = array( 'r_id' => FILTER_SANITIZE_STRING,
			'action' => FILTER_SANITIZE_STRING );
		# pull in and apply to final
		if ( $getTemp = filter_input_array( INPUT_GET, $getArr ) ) {
			$final += $getTemp;
		}
		# setup POST variables

		/*
			Kelvin: remove ammenities from $postArr
		*/
		$postArr = array( 'action' => FILTER_SANITIZE_STRING,
						 'r_id' => FILTER_SANITIZE_STRING,
						 'branch' => FILTER_SANITIZE_STRING,
						 'room_Number' => FILTER_SANITIZE_STRING
						);

		# pull in and apply to final
		if ( $postTemp = filter_input_array( INPUT_POST, $postArr ) ) {
			$final += $postTemp;
		}
		
		$arrayCheck = array_unique( array_merge( array_keys( $getArr ), array_keys( $postArr ) ) );

		foreach ( $arrayCheck as $key ) {
			if ( empty( $final[ $key ] ) ) {
				$final[ $key ] = NULL;
			}
		}

		/*
			Kelvin: Remove check for $amenityList in $final
		*/

		return $final;
	}

	public static
	function getRoomInfo( $r_id )
	# get information about branch from daabase based on the ID
	{

		/*
			Kelvin: Remove $amenityList from $roomInfo
		*/

		global $wpdb;

		$table_name = $wpdb->prefix . "dsol_booking_room";
		$final = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM `$table_name` WHERE `r_id` = %d", $r_id ) );
		$roomInfo = array( 'r_id' => $r_id, 'room_Number' => $final->room_number,
			'branch' => $final->b_id );

		return $roomInfo;
	}


	/*
			Kelvin: Remove $amenityList from query 
	*/
	public static
	function getRoomList() {
		global $wpdb;
		$roomList = array();

		$table_name = $wpdb->prefix . "dsol_booking_room";
		$sql = "SELECT `r_id`, `room_number`, `b_id` FROM `$table_name` ORDER BY `b_id`, `room_number`";

		$count = 0;

		/*
			Kelvin: Remove $amenityList from $roomList 
		*/

		$cooked = $wpdb->get_results( $sql, ARRAY_A );
		if ( count( $cooked ) == 0 ) {
			return array();
		}
		foreach ( $cooked as $key => $val ) {
			$roomList[ 'room' ][ $val[ 'b_id' ] ][ $val[ 'r_id' ] ] = $val[ 'room_number' ];
			$roomList[ 'id' ][ $val[ 'r_id' ] ] = array( 'branch' => $val[ 'b_id' ], 'desc' => $val[ 'room_number' ] );
		}
		return $roomList;
	}

	/*
			Kelvin: Remove $amenityList from parameter
	*/

	public static
	function showRoomList( $roomList, $branchList)
	# show a list of rooms with edit and delete links, or, if none 
	# a message stating there are no branches
	{
		require( DSOL_BOOKING_PATH . 'templates/rooms/mainAdmin.php' );
	}


/*
		Kelvin: remove $amenityList from parameter
*/

	public static
	function showRoomDelete( $r_id, $roomList, $branchList)
	# show delete page
	{
		require( DSOL_BOOKING_PATH . 'templates/rooms/delete.php' );
	}

/*
		Kelvin: remove $amenityList from parameter
*/

	public static
	function showRoomEdit( $roomInfo, $branchList, $action, $actionName )
	# show edit page and fill with values
	{
		require( DSOL_BOOKING_PATH . 'templates/rooms/edit.php' );
	}
}
?>