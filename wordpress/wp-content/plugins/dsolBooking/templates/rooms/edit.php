<link href="<?php echo plugins_url(); ?>/dsolBooking/css/dsol_meetings.css" rel="stylesheet" type="text/css"/>
<div class="wrap">
	<div id="icon-options-general" class="icon32"></div>
	<h2>
		<?php _e( 'Book a Room Administration - Rooms', 'book-a-room' ); ?>
	</h2>
</div>
<h2>
	<?php
	switch ( $action ) {
		case 'addCheck':
		case 'add':
			_e( 'Add a Room', 'book-a-room' );
			break;
		case 'editCheck':
		case 'edit':
			_e( 'Edit a Room', 'book-a-room' );
			break;
		default:
			wp_die( "ERROR: BAD ACTION on room add/edit: " . $action );
			break;
	}
	?>
</h2>
<?php 
# Display Errors if there are any
if ( !empty( $roomInfo['errors'] ) ) {
 ?>
<h3 style="color:red;"><strong><?php echo $roomInfo['errors']; ?></strong></h3>
<?php 
}
?>
<!-- Kelvin: Remove the display of adding or displaying ammenities from the form -->

<form name="form1" method="post" action="?page=dsol_Settings_Rooms&action=<?php echo $action; ?>&roomID=<?php echo $roomInfo[ 'roomID' ]; ?>">
	<table class="tableMain">
		<tr>
			<td colspan="2"><?php _e( 'Room Information', 'book-a-room' ); ?></td>
		</tr>
		<tr>
			<td><label for="room_Number"><?php _e( 'Room Name', 'book-a-room' ); ?></label>
			</td>
			<td>
				<input name="room_Number" type="text" id="room_Number" value="<?php echo $roomInfo[ 'room_Number' ]; ?>" size="48" maxlength="64">
			</td>
		</tr>
		<tr>
			<pre>
				<?php print_r($branchList);
				?>
			</pre>
			<td><label for="branch"><?php _e( 'Branch', 'book-a-room' ); ?></label>
			</td>
			<td>
				<select name="branch" id="branch">
					<?php
					$checked = ( empty( $roomInfo[ 'branch' ] ) ) ? ' checked="checked"' : NULL;
					
					?>
					<option value=""<?php echo $checked; ?>><?php _e( 'Choose branch', 'book-a-room' ); ?></option>
					<?php
					foreach ( $branchList as $key => $val ) {
						$temp = $branch_line;
						$checked = ( !empty( $roomInfo[ 'branch' ] ) and $roomInfo[ 'branch' ] == $key ) ? ' selected="selected"' : NULL;
					?>
					<option value="<?php echo $key; ?>"<?php echo $checked; ?>><?php echo $val; ?></option>
					<?php
					}
					?>
				</select>
			</td>
		</tr>
			
		<tr>
			<td colspan="2"><input type="submit" name="button" id="button" value="<?php _e( 'Submit', 'book-a-room' ); ?>">
			</td>
		</tr>

	</table>
</form>