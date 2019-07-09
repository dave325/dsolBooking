<link rel="stylesheet" href="//code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css"/>
<script src="//code.jquery.com/jquery-1.9.1.js"></script>
<script src="//code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script language="JavaScript" type="text/javascript">
	$( function () {
		// Setup date drops
		$( '#timestamp' ).datepicker( {
			dateFormat: 'mm/dd/yy'
		} );
	} );
</script>
<link href="<?php echo plugins_url(); ?>/book-a-room/css/bookaroom_meetings.css" rel="stylesheet" type="text/css"/>
<?php
# date
$timestamp = ( empty( $externals[ 'timestamp' ] ) or strtotime( $externals[ 'timestamp' ] ) == false ) ? strtotime( 'today' ) : strtotime( $externals[ 'timestamp' ] );
?>
<div id="printForm">
	<div class=wrap>
		<div id="icon-options-general" class="icon32"></div>
		<h2>
			<?php _e( 'Book a Room - Meeting Room Door Signs', 'book-a-room' ); ?>
		</h2>
	</div>
	<p>
		<?php _e( 'This page is formatted to print directly from the browser.', 'book-a-room' ); ?>
	</p>
	<form name="form1" method="post" action="">
		<table class="tableMain">
			<tr>
				<td colspan="2">
					<?php _e( 'Settings', 'book-a-room' ); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php _e( 'Date', 'book-a-room' ); ?>
				</td>
				<td><input name="timestamp" type="text" id="timestamp" value="<?php echo date( 'm/d/Y', $timestamp ); ?>">
				</td>
			</tr>
			<tr>
				<td>
					<?php _e( 'Branch', 'book-a-room' ); ?>
				</td>
				<td>
					<select name="branchID" id="branchID">
						<?php
						if ( empty( $externals[ 'branchID' ] ) or!array_key_exists( $externals[ 'branchID' ], $branchList ) ) {
							$externals[ 'branchID' ] = key( $branchList );
						}
						foreach ( $branchList as $key => $val ) {
							$selected = ( $externals[ 'branchID' ] == $key ) ? ' selected="selected"' : NULL;
							?>
						<option value="<?php echo $key; ?>" <?php echo $selected; ?>>
							<?php echo $val[ 'branchDesc' ]; ?>
						</option>
						<?php
						}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center"><input type="submit" name="button" id="button" value="<?php _e( 'Submit', 'book-a-room' );?>">
				</td>
			</tr>
		</table>
	</form>
	<hr>
</div>
<div style="text-align: center;">
	<h1>
		<?php _e( 'Meeting Room Schedule', 'book-a-room' ); ?>
	</h1>
	<p class="roomDate">
		<?php echo date( 'l, F jS, Y', $timestamp ); ?>
	</p>
	<?php
	$pendingList = self::getPending( $timestamp, array( 'approved' ), true, false, true );
	if ( empty( $pendingList[ 'location' ][ $branchList[ $externals[ 'branchID' ] ][ 'branchDesc' ] ] ) ) {
		$thisList = array();
	} else {
		$thisList = $pendingList[ 'location' ][ $branchList[ $externals[ 'branchID' ] ][ 'branchDesc' ] ];
		ksort( $thisList );
	}
	foreach ( $thisList as $key => $val ) {
		?>
	<p class="roomTitle"><?php echo $key; ?></p>
	<?php
		foreach ( $val as $smallKey => $smallVal ) {
			$startTime = date( 'g:i a', strtotime( $smallVal[ 'startTime' ] ) );
			$endTime = date( 'g:i a', strtotime( $smallVal[ 'endTime' ] ) );
			if ( $startTime == '12:00 am' and $endTime == '11:59 pm' ) {
				$times = 'All Day';
			} else {
				$times = $startTime . ' - ' . $endTime;
			}
			?>
	<p class="roomInfo"><strong><?php echo $times; ?></strong> &nbsp;&nbsp;&nbsp;<?php echo htmlspecialchars_decode( $smallVal[ 'eventName' ] ); ?></p>
		<?php
		}
	}
	?>
</div>