<?PHP
class dsol_settings_admin
{
	############################################
	#
	# Amenity managment
	#
	############################################
	public static function dsol_admin_admin()
	{
		
		# first, is there an action?
		$externals = self::getExternalsAdmin();
		switch( $externals['action'] ) {
			case 'updateSettings':
				if( ( $error = self::checkSettings( $externals ) ) == TRUE ) {
					self::showMeetingRoomAdmin( $externals, $error );
					break;
				} else {
					self::updateSettings( $externals );
					self::showMeetingRoomUpdateSuccess();
					break;
				}

			default:
				$externals = self::getDefaults();
				self::showMeetingRoomAdmin( $externals );
				break;
		}
	}
	
	# sub functions:
	
	public static function updateSettings( $externals )
	{
		update_option( 'dsol_baseIncrement', 					$externals['dsol_baseIncrement'] );
		update_option( 'dsol_setupIncrement', 					$externals['dsol_setupIncrement'] );
		update_option( 'dsol_cleanupIncrement', 				$externals['dsol_cleanupIncrement'] );
		update_option( 'dsol_reservation_URL', 				$externals['dsol_reservation_URL'] );
		update_option( 'dsol_setupColor', 			strtoupper(	$externals['dsol_setupColor'] ) );
		update_option( 'dsol_setupFont',			strtoupper(	$externals['dsol_setupFont'] ) );
		update_option( 'dsol_reservedColor',		strtoupper(	$externals['dsol_reservedColor'] ) );
		update_option( 'dsol_reservedFont',		strtoupper(	$externals['dsol_reservedFont'] ) );
		update_option( 'dsol_reserveBuffer',		strtoupper(	$externals['dsol_reserveBuffer'] ) );
		update_option( 'dsol_reserveAllowed',		strtoupper(	$externals['dsol_reserveAllowed'] ) );
		update_option( 'dsol_defaultEmailDaily',	strtolower(	$externals['dsol_defaultEmailDaily'] ) );
		update_option( 'dsol_daysBeforeRemind', 	strtolower(	$externals['dsol_daysBeforeRemind'] ) );
		
		update_option( 'dsol_waitingListDefault', 				$externals['dsol_waitingListDefault'] );
		update_option( 'dsol_profitDeposit', 					$externals['dsol_profitDeposit'] );
		update_option( 'dsol_nonProfitDeposit', 				$externals['dsol_nonProfitDeposit'] );
		update_option( 'dsol_profitIncrementPrice', 			$externals['dsol_profitIncrementPrice'] );
		
		update_option( 'dsol_nonProfitIncrementPrice',			$externals['dsol_nonProfitIncrementPrice'] );
		update_option( 'dsol_eventLink',						$externals['dsol_eventLink'] );
		update_option( 'dsol_paymentLink', 					$externals['dsol_paymentLink'] );
		update_option( 'dsol_libcardRegex', 					$externals['dsol_libcardRegex'] );
		update_option( 'dsol_obfuscatePublicNames', 			$externals['dsol_obfuscatePublicNames'] );
		
		update_option( 'dsol_hide_contract', 					$externals['dsol_hide_contract'] );
		
		
		if( !empty( $externals['dsol_screenWidth'] ) ) {
			update_option( 'dsol_screenWidth', 1 );
		} else {
			update_option( 'dsol_screenWidth', 0 );
		}		
	}
	
	public static function getDefaults()
	{
		$option = array();
		$option['dsol_baseIncrement']				= get_option( 'dsol_baseIncrement' );
		$option['dsol_setupIncrement']				= get_option( 'dsol_setupIncrement' );
		$option['dsol_cleanupIncrement']			= get_option( 'dsol_cleanupIncrement' );
		$option['dsol_reservation_URL']			= get_option( 'dsol_reservation_URL' );
		$option['dsol_daysBeforeRemind']			= get_option( 'dsol_daysBeforeRemind' );
		$option['dsol_setupColor']					= get_option( 'dsol_setupColor' );
		$option['dsol_setupFont']					= get_option( 'dsol_setupFont' );
		$option['dsol_reservedColor']				= get_option( 'dsol_reservedColor' );
		$option['dsol_reservedFont']				= get_option( 'dsol_reservedFont' );
		$option['dsol_reserveBuffer']				= get_option( 'dsol_reserveBuffer' );
		$option['dsol_reserveAllowed']				= get_option( 'dsol_reserveAllowed' );
		$option['dsol_alertEmail']					= get_option( 'dsol_alertEmail' );
		$option['dsol_defaultEmailDaily']			= get_option( 'dsol_defaultEmailDaily' );
		$option['dsol_waitingListDefault']			= get_option( 'dsol_waitingListDefault' );

		$option['dsol_profitDeposit']				= get_option( 'dsol_profitDeposit' );
		$option['dsol_nonProfitDeposit']			= get_option( 'dsol_nonProfitDeposit' );
		$option['dsol_profitIncrementPrice']		= get_option( 'dsol_profitIncrementPrice' );
		$option['dsol_nonProfitIncrementPrice']	= get_option( 'dsol_nonProfitIncrementPrice' );
		$option['dsol_eventLink']					= get_option( 'dsol_eventLink' );
		
		$option['dsol_screenWidth']				= get_option( 'dsol_screenWidth' );
		
		$option['dsol_paymentLink']				= get_option( 'dsol_paymentLink' );
		$option['dsol_libcardRegex']				= get_option( 'dsol_libcardRegex' );
		$option['dsol_obfuscatePublicNames']		= get_option( 'dsol_obfuscatePublicNames' );		
		$option['dsol_addressType']				= get_option( 'dsol_addressType' );
		
		$option['dsol_hide_contract']				= get_option( 'dsol_hide_contract' );
				
		return $option;
	}
	
	public static function checkSettings( $externals )
	{
		# check for each item - if NULL
		$error = array();
		$final = NULL;
		
		$checkArray = array( 'dsol_reservation_URL' => 'Reservation URL', 'dsol_setupColor' => 'Setup Background Color ', 'dsol_setupFont' => 'Setup Font Color', 'dsol_reservedColor' => 'Reserved Background Color', 'dsol_reservedFont' => 'Reserved Font Color' );
        # , 'dsol_daysBeforeRemind' => 'Days before meeting to send reminders'
		foreach( $checkArray as $key => $val ) {
			if( is_null( $externals[$key] ) ) {
				$error[] = sprintf( __( 'Please enter a value for the <strong>%s</strong>', 'book-a-room' ), $val );
			}
		}
		
		# check colors
		$checkArray = array( 'dsol_setupColor' => 'Setup Background Color ', 'dsol_setupFont' => 'Setup Font Color', 'dsol_reservedColor' => 'Reserved Background Color', 'dsol_reservedFont' => 'Reserved Font Color');
		foreach( $checkArray as $key => $val ) {
			if( !is_null( $externals[$key] ) and !preg_match('/^#[a-f0-9]{6}$/i', $externals[$key] ) and !preg_match('/^#[a-f0-9]{3}$/i', $externals[$key] ) ) {
				$error[] = sprintf( __( 'Please enter a valid HEX value for the <strong>%s</strong>', 'book-a-room' ), $val );
			}
		}
		
		# numbers
		$checkArray = array(	
							'dsol_baseIncrement'				=> __( 'Base Increment', 'book-a-room' ), 
							'dsol_setupIncrement'				=> __( 'Setup Increment', 'book-a-room' ), 
							'dsol_cleanupIncrement'			=> __( 'Cleanup Increment', 'book-a-room' ), 
							'dsol_reserveBuffer'				=> __( 'Days Buffer for Reserve', 'book-a-room' ), 
							'dsol_reserveAllowed'				=> __( 'Days Allowed for Reserve', 'book-a-room' ), 
							'dsol_profitDeposit'				=> __( 'For Profit Room Deposit', 'book-a-room' ), 
							'dsol_nonProfitDeposit'			=> __( 'Non-profit Room Deposit', 'book-a-room' ), 
							'dsol_profitIncrementPrice'		=> __( 'For Profit price per increment', 'book-a-room' ), 
							'dsol_nonProfitIncrementPrice'		=> __( 'Non-profit price per increment', 'book-a-room' ), 
							'dsol_waitingListDefault'			=> __( 'Waiting list default', 'book-a-room' ), 
							'dsol_screenWidth'					=> __( 'Screen width', 'book-a-room' ), 
							'dsol_paymentLink'					=> __( 'Credit card payment link' )
						   );
		foreach( $checkArray as $key => $val ) {
			if( !empty( $externals[$key] ) and !is_numeric( $externals[$key] ) ) {
				$error[] = sprintf( __( 'Please enter a valid number for the <strong>%s</strong>', 'book-a-room' ), $val );
			}
		}
		
		if( !empty( $externals['dsol_defaultEmailDaily'] ) && !filter_var( $externals['dsol_defaultEmailDaily'], FILTER_VALIDATE_EMAIL ) ) {
			$error[] = __( 'Please enter a valid email address for the <strong>Default Email for Daily Reservations</strong> field.', 'book-a-room' );
		}
		
		if( count( $error ) > 0 ) {
			$final = implode( '<br /><br />', $error );
		}
		
		return $final;	
	}
	
	public static function showMeetingRoomUpdateSuccess()
	{
		require( DSOL_BOOKING_PATH . 'templates/mainSettings/updateSuccess.php' );
	}
	
		
	public static function showMeetingRoomAdmin( $options, $errorMSG=NULL )
	{
		require( DSOL_BOOKING_PATH . 'templates/mainSettings/adminMain.php' );
	}
	
	public static function getExternalsAdmin()
	# Pull in POST and GET values
	{
		$final = array();
		
		# setup GET variables
		$getArr = array(	'action'				=> FILTER_SANITIZE_STRING);

		# pull in and apply to final
		if( $getTemp = filter_input_array( INPUT_GET, $getArr ) ) {
			$final = array_merge( $final, $getTemp );
		}

		# setup POST variables
		$postArr = array(	'action'								=> FILTER_SANITIZE_STRING, 
							'dsol_baseIncrement'				=> FILTER_SANITIZE_STRING, 
							'dsol_cleanupIncrement'			=> FILTER_SANITIZE_STRING, 
							'dsol_paymentLink'					=> FILTER_SANITIZE_STRING, 
							'dsol_libcardRegex'				=> FILTER_SANITIZE_STRING, 
							'dsol_obfuscatePublicNames'		=> FILTER_SANITIZE_STRING, 
							'dsol_daysBeforeRemind'			=> FILTER_SANITIZE_STRING, 
							'dsol_defaultEmailDaily'			=> FILTER_SANITIZE_STRING, 
							'dsol_nonProfitDeposit'			=> FILTER_SANITIZE_STRING, 
							'dsol_nonProfitIncrementPrice'		=> FILTER_SANITIZE_STRING, 
							'dsol_profitDeposit'				=> FILTER_SANITIZE_STRING, 
							'dsol_profitIncrementPrice'		=> FILTER_SANITIZE_STRING, 
							'dsol_reservation_URL'				=> FILTER_SANITIZE_STRING, 
							'dsol_reserveAllowed'				=> FILTER_SANITIZE_STRING, 
							'dsol_reserveBuffer'				=> FILTER_SANITIZE_STRING, 
							'dsol_reservedColor'				=> FILTER_SANITIZE_STRING, 
							'dsol_reservedFont'				=> FILTER_SANITIZE_STRING, 
							'dsol_screenWidth'					=> FILTER_SANITIZE_STRING, 
							'dsol_setupColor'					=> FILTER_SANITIZE_STRING, 
							'dsol_setupFont'					=> FILTER_SANITIZE_STRING, 
							'dsol_setupIncrement'				=> FILTER_SANITIZE_STRING, 
							'dsol_waitingListDefault'			=> FILTER_SANITIZE_STRING,
							'dsol_eventLink'					=> FILTER_SANITIZE_STRING,
						 	'dsol_hide_contract'				=> FILTER_SANITIZE_STRING,
						 
							);

			
		# pull in and apply to final
		if( $postTemp = filter_input_array( INPUT_POST, $postArr ) ) {
			$final = array_merge( $final, $postTemp );
		}

		$arrayCheck = array_unique( array_merge( array_keys( $getArr ), array_keys( $postArr ) ) );
		
		foreach( $arrayCheck as $key ) {
			if( empty( $final[$key] ) ) {
				$final[$key] = NULL;
			} else {
				$final[$key] = trim( $final[$key] );
			}
		}
		
		return $final;
	}
}
?>