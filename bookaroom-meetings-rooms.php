<?php
class bookaroom_settings_rooms {
	############################################
	#
	# Room managment
	#
	############################################
	public static
	function bookaroom_admin_rooms() {
		$roomList = self::getRoomList();
		$branchList = bookaroom_settings_branches::getBranchList();
		
		/*
			Kelvin: Remove $amenityList
		*/

		# figure out what to do
		# first, is there an action?
		$externals = self::getExternalsRoom();
		$error = NULL;

		switch ( $externals[ 'action' ] ) {
			case 'deleteCheck':
				if ( bookaroom_settings::checkID( $externals[ 'roomID' ], $roomList[ 'room' ], TRUE ) == FALSE ) {
					# show error page
					require( 'templates/rooms/IDerror.php' );
				} else {
					# delete room
					$roomContList = bookaroom_settings_roomConts::getRoomContList();

					self::deleteRoom( $externals[ 'roomID' ], $roomContList );
					require( 'templates/rooms/deleteSuccess.php' );
				}

				break;

			case 'delete':

			/*
					Kelvin: remove $amenityList
			*/

				# check that there is an ID and it is valid
				if ( bookaroom_settings::checkID( $externals[ 'roomID' ], $roomList[ 'room' ], TRUE ) == FALSE ) {
					# show error page
					require( 'templates/rooms/IDerror.php' );
				} else {
					# show delete screen
					self::showRoomDelete( $externals[ 'roomID' ], $roomList, $branchList);
				}
				break;

			case 'editCheck':

			/*
					Kelvin: remove $amenityList
			*/
				# check entries
				if ( ( $errors = self::checkEditRoom( $externals, $branchList, $roomList, $externals[ 'roomID' ] ) ) == NULL ) {
					self::editRoom( $externals);
					require( 'templates/rooms/editSuccess.php' );
					break;
				}

				$externals[ 'errors' ] = $errors;

				# check that there is an ID and it is valid
				if ( bookaroom_settings::checkID( $externals[ 'roomID' ], $roomList[ 'room' ], TRUE ) == FALSE ) {
					# show error page
					require( 'templates/rooms/IDerror.php' );
				} else {
					# show edit screen
					self::showRoomEdit( $externals, $branchList, 'editCheck', 'Edit' );
				}
				break;

			case 'edit':
				# check that there is an ID and it is valid

				if ( bookaroom_settings::checkID( $externals[ 'roomID' ], $roomList[ 'room' ], TRUE ) == FALSE ) {
					# show error page
					require( 'templates/rooms/IDerror.php' );
				} else {
					# show edit screen
					$roomInfo = self::getRoomInfo( $externals[ 'roomID' ] );
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

		$table_name = $wpdb->prefix . "bookaroom_rooms";

		$amenityArr = NULL;
		
		$final = $wpdb->insert( $table_name,
			array( 'room_desc' => $externals[ 'roomDesc' ],
				'room_amenityArr' => $amenityArr,
				'room_branchID' => $externals[ 'branch' ] ) );
	}



/*
		Kelvin: remove $amenityList
*/

	public static
	function checkEditRoom( $externals, $branchList, $roomList, $roomID )
	# check the room to make sure everything is filled out
	# there are no dulicate names in the same branch
	# and the amenities are valid
	{
		$error = array();
		$final = NULL;
		# check name is filled and isn't duped in the same branch
		# check for empty room name
		if ( empty( $externals[ 'roomDesc' ] ) ) {
			$error[] = __( 'You must enter a room name.', 'book-a-room' );
		}

		# fix array if none

		if ( empty( $externals[ 'branch' ] ) ) {
			$error[] = __( 'You must choose a branch.', 'book-a-room' );
		} else {
			# check dupe name FOR THAT BRANCH - first, are there any rooms?
			if ( !empty( $roomList[ 'room' ] ) and array_key_exists( $externals[ 'branch' ], $roomList[ 'room' ] ) ) {
				if ( bookaroom_settings::dupeCheck( $roomList[ 'room' ][ $externals[ 'branch' ] ], $externals[ 'roomDesc' ], $externals[ 'roomID' ] ) == 1 ) {
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
	function deleteRoom( $roomID, $roomContList )
	# delete room
	{
		global $wpdb;
		# Delete actual room
		# ***
		$table_name = $wpdb->prefix . "bookaroom_rooms";

		$sql = "DELETE FROM `{$table_name}` WHERE `roomID` = '{$roomID}' LIMIT 1";
		$wpdb->query( $sql );

		# Search containers for that room, remove
		$table_name = $wpdb->prefix . "bookaroom_roomConts";

		$sql = "DELETE FROM `{$table_name}` WHERE `rcm_roomID` = '{$roomID}'";
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

		$table_name = $wpdb->prefix . "bookaroom_rooms";

		$amenityArr = NULL;
	

		$final = $wpdb->update( $table_name,
			array( 'room_desc' => $externals[ 'roomDesc' ],
				'room_amenityArr' => $amenityArr,
				'room_branchID' => $externals[ 'branch' ] ),
			array( 'roomID' => $externals[ 'roomID' ] ) );
	}

	public static
	function getExternalsRoom()
	# Pull in POST and GET values
	{
		$final = array();

		# setup GET variables
		$getArr = array( 'roomID' => FILTER_SANITIZE_STRING,
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
						 'roomID' => FILTER_SANITIZE_STRING,
						 'branch' => FILTER_SANITIZE_STRING,
						 'roomDesc' => FILTER_SANITIZE_STRING
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
	function getRoomInfo( $roomID )
	# get information about branch from daabase based on the ID
	{

		/*
			Kelvin: Remove $amenityList from $roomInfo
		*/

		global $wpdb;

		$table_name = $wpdb->prefix . "bookaroom_rooms";
		$final = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM `$table_name` WHERE `roomID` = %d", $roomID ) );
		$roomInfo = array( 'roomID' => $roomID, 'roomDesc' => $final->room_desc,
			'branch' => $final->room_branchID );

		return $roomInfo;
	}


	/*
			Kelvin: Remove $amenityList from query 
	*/
	public static
	function getRoomList() {
		global $wpdb;
		$roomList = array();

		$table_name = $wpdb->prefix . "bookaroom_rooms";
		$sql = "SELECT `roomID`, `room_desc`, `room_branchID` FROM `$table_name` ORDER BY `room_branchID`, `room_desc`";

		$count = 0;

		/*
			Kelvin: Remove $amenityList from $roomList 
		*/

		$cooked = $wpdb->get_results( $sql, ARRAY_A );
		if ( count( $cooked ) == 0 ) {
			return array();
		}
		foreach ( $cooked as $key => $val ) {
			$roomList[ 'room' ][ $val[ 'room_branchID' ] ][ $val[ 'roomID' ] ] = $val[ 'room_desc' ];
			$roomList[ 'id' ][ $val[ 'roomID' ] ] = array( 'branch' => $val[ 'room_branchID' ], 'desc' => $val[ 'room_desc' ] );
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
		require( BOOKAROOM_PATH . 'templates/rooms/mainAdmin.php' );
	}


/*
		Kelvin: remove $amenityList from parameter
*/

	public static
	function showRoomDelete( $roomID, $roomList, $branchList)
	# show delete page
	{
		require( BOOKAROOM_PATH . 'templates/rooms/delete.php' );
	}

/*
		Kelvin: remove $amenityList from parameter
*/

	public static
	function showRoomEdit( $roomInfo, $branchList, $action, $actionName )
	# show edit page and fill with values
	{
		require( BOOKAROOM_PATH . 'templates/rooms/edit.php' );
	}
}
?>