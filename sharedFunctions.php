<?PHP

function branch_and_room_id( &$roomID, $branchList, $roomContList )
# Convert
{
	
	if( !empty( $roomID ) and array_key_exists( $roomID, $roomContList['id'] ) ) {
		$branchID = $roomContList['id'][$roomID]['branchID'];
	# else check branchID
	} else {
		$branchID = NULL;
	}
	
	if( !array_key_exists( $roomID, $roomContList['id'] ) ) {
		$roomID = NULL;
	}	
	return $branchID;		
}

function getRoomInfo( $cur_roomID = NULL, $branchList, $roomContList, $selectBranch = FALSE, $noLocation = FALSE )
{
	$cur_branchID = $cur_noloc_branchID = NULL;
	# this section handles the 'no location' and entire branch selections.
	#
	# selectBranch is true, you can select the branch
	#
	# if noLocation is true, you can see and select a 'No location' for each
	# branch

	# Let's find out if, and what, room, location or branch is selected.
	#
	# first, lets see if there is a room ID selected and check that it's valid.
	if( !empty( $cur_roomID ) ) {
		# we have an ID. Lets see if noLocation is turned on and, if so, lets see				
		# is no location enabled?
		if( $noLocation == true ) {
			# is this is a "no location" room ID?
			if( substr( $cur_roomID, 0, 6 ) == 'noloc-' ) {
				# we have a no location ID. Lets find the branch
				$noLocBranchID = substr( $cur_roomID, 6 );
				# is it a valid branch? compare against the branch list keys
				if( array_key_exists( $noLocBranchID, $branchList ) ) {
					$cur_noloc_branchID = (int)$noLocBranchID;
					$cur_roomID = NULL;
				}
			}
		}
		# is selectBranch enabled?
		if( $selectBranch == true ) {
			# is this a 'branch' room ID?
			if( substr( $cur_roomID, 0, 7 ) == 'branch-' ) {
				# we have a branch selection. Lets find the branch ID
				$branchID = substr( $cur_roomID, 7 );
				# is it a valid branch? compare against the branch list keys
				if( array_key_exists( $branchID, $branchList ) ) {
					$cur_branchID = (int)$branchID;
					$cur_roomID = NULL;
				}
			}
		}
	}

	return array( 'cur_roomID' => $cur_roomID, 'cur_branchID' => $cur_branchID, 'cur_noloc_branchID' => $cur_noloc_branchID );
}

function make_brief($mess, $length=50)
# cut off text to nearest word based on length
{
	$mess = strip_tags($mess);
	if(!is_numeric($length) || strlen($mess) <= $length)
	{
		return $mess;
	}
	
	$mess = substr($mess, 0, $length);
	
	$chop = strrchr($mess, " ");
	$final = substr($mess, 0, strlen($mess) - strlen($chop)) . "...";
	return $final;
}

function br2nl($string)
{
    return preg_replace("/<br[^>]*>\s*\r*\n*/is", "\n", $string);
}
if( !function_exists( "preme" ) ) {
	function preme( $arr="-----------------+=+-----------------" ) // print_array
	{
		if( $arr === TRUE )	$arr = "**TRUE**";
		if( $arr === FALSE )	$arr = "**FALSE**";
		if( $arr === NULL )	$arr = "**NULL**";
		
		echo "<pre>";
		print_r( $arr );
		echo "</pre>";
	
	}
}

function makeLink_correctPermaLink() {
	$permStruc = get_option( 'permalink_structure' );
	$resURL = get_option( 'bookaroom_reservation_URL' );
	if( empty( $permStruc ) ) {
		return '?page_id=' . $resURL . '&';		
	} else {
		return $resURL . '?';
	}
}

if( !function_exists( "myStartSession" ) ) {
	function myStartSession() {
		if(!session_id()) {
			session_start();
		}
	}
}
if( !function_exists( "myEndSession" ) ) {
	function myEndSession() {
		session_destroy ();
	}
}
if( !function_exists( "encrypt" ) ) {
	function encrypt($input_string, $key){
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		$h_key = hash('sha256', $key, TRUE);
		return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $h_key, $input_string, MCRYPT_MODE_ECB, $iv));
	}
}
if( !function_exists( "decrypt" ) ) {
	function decrypt($encrypted_input_string, $key){
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		$h_key = hash('sha256', $key, TRUE);
		return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $h_key, base64_decode($encrypted_input_string), MCRYPT_MODE_ECB, $iv));
	}
}
function CHUH_BaR_Main_permalinkFix( $onlyID = false )
{
	$urlInfoRaw = parse_url( get_permalink() );
			
	$urlInfo = (!empty( $urlInfoRaw['query'] ) ) ? $urlInfoRaw['query'] : NULL;
	if( $onlyID ) {
		$finalArr = explode( '=', $urlInfo );
		if( !empty( $finalArr[1] ) ) {
			return $finalArr[1];
		} else {
			return NULL;
		}		
	}
	
	if( empty( $urlInfo ) ) {
		$permalinkCal = '?';
	} else {
		$permalinkCal = '?'.$urlInfo.'&';
	}
	
	return $permalinkCal;	
}
?>