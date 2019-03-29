<link href="<?php echo plugins_url(); ?>/book-a-room/css/bookaroom_meetings.css" rel="stylesheet" type="text/css"/>
<div class="wrap">
	<div id="icon-options-general" class="icon32"></div>
	<h2>
		<?php _e( 'Book a Room Administration - Room Containers', 'book-a-room' ); ?>
	</h2>
</div>
<h2>
	<?php
	switch ( $action ) {
		case 'addCheck':
		case 'add':
			_e( 'Add a Room Container', 'book-a-room' );
			break;
		case 'editCheck':
		case 'edit':
			_e( 'Edit a Room Container', 'book-a-room' );
			break;
		default:
			wp_die( "ERROR: BAD ACTION on room container add/edit: " . $action );
			break;
	}
	?>
</h2>
<?php 
$isPublic = ( $roomContInfo['isPublic'] ) ? ' checked="checked"' : NULL;
$hideDaily = ( $roomContInfo['hideDaily'] ) ? ' checked="checked"' : NULL;

# Display Errors if there are any
if ( !empty( $roomContInfo['errors'] ) ) {
 ?>
<h3 style="color:red;"><strong><?php echo $roomContInfo['errors']; ?></strong></h3>
<?php 
}
?>
<form name="form1" method="post" action="?page=bookaroom_Settings_RoomCont&action=<?php echo $action; ?>&roomContID=<?php echo $roomContInfo['roomContID']; ?>&branchID=<?php echo $branchID; ?>">
	<table class="tableMain">
		<tr>
			<td colspan="2">
				<?php _e( 'Room Container Information', 'book-a-room' ); ?>
			</td>
		</tr>
		<tr>
			<td>
				<label for="roomContDesc">
					<?php _e( 'Container Name', 'book-a-room' ); ?>
				</label>
			</td>
			<td>
				<input name="roomContDesc" type="text" id="roomContDesc" value="<?php echo $roomContInfo['roomContDesc']; ?>" size="48" maxlength="64">
			</td>
		</tr>
		<tr>
			<td>
				<?php _e( 'Max Occupancy', 'book-a-room' ); ?>
			</td>
			<td><input name="occupancy" type="text" id="occupancy" value="<?php echo $roomContInfo['occupancy']; ?>" size="4" maxlength="4"/>
			</td>
		</tr>
		<tr>
			<td>
				<?php _e( 'Public?', 'book-a-room' ); ?>
			</td>
			<td><input name="isPublic" type="checkbox" id="isPublic" value="true" <?php echo $isPublic; ?> /></td>
		</tr>
		<tr>
			<td>
				<?php _e( 'Hide on daily?', 'book-a-room' ); ?>
			</td>
			<td><input name="hideDaily" type="checkbox" id="hideDaily" value="true" <?php echo $hideDaily; ?> /></td>
		</tr>
		<tr>
			<td>
				<?php _e( 'Branch', 'book-a-room' ); ?>
			</td>
			<td>
				<?php echo $branchList[$branchID]; ?>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<h3>
					<?php _e( 'Rooms', 'book-a-room' ); ?>
				</h3>
			</td>
		</tr>
		<?php
		foreach ( $roomList[ 'room' ][ $branchID ] as $r_key => $r_val ) {
			$checked = ( !empty( $roomContInfo[ 'room' ] ) and is_array( $roomContInfo[ 'room' ] ) and in_array( $r_key, $roomContInfo[ 'room' ] ) ) ? ' checked="checked"' : NULL;
			?>
		<tr>
			<td>
				<label for="room[<?php echo $r_key; ?>]">
					<?php echo $r_val; ?>
				</label>
			</td>
			<td>
				<input name="room[<?php echo $r_key; ?>]" type="checkbox" id="room[<?php echo $r_key; ?>]" <?php echo $checked; ?>></td>
		</tr>
		<?php
		}
		?>

		<tr>
			<td colspan="2"> <input type="submit" name="button" id="button" <?php _e( 'Submit', 'book-a-room' ); ?>>
			</td>
		</tr>
	</table>
</form>
<p>
	<a href="?page=bookaroom_Settings_RoomCont">
		<?php _e( 'Return to Room Container Home.', 'book-a-room' ); ?>
	</a>
</p>