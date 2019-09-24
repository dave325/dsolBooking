<link href="<?php echo plugins_url(); ?>/dsolBooking/css/dsol_meetings.css" rel="stylesheet" type="text/css"/>
<div class=wrap>
	<div id="icon-options-general" class="icon32"></div>
	<h2>
		<?php _e( 'Book a Room Administration - Rooms', 'book-a-room' ); ?>
	</h2>
</div>
<h2>
	<?php _e( 'Delete Room', 'book-a-room' ); ?>
</h2>
<table class="tableMain">
	<tr>
		<td><?php _e( 'Option', 'book-a-room' ); ?></td>
		<td><?php _e( 'Setting', 'book-a-room' ); ?></td>
	</tr>
	<tr>
		<td><strong><?php _e( 'Room Name', 'book-a-room' ); ?></strong>
		</td>
		<td><?php echo $roomList[ 'id' ][ $roomID ][ 'desc' ]; ?></td>
	</tr>
	<tr>
		<td><strong><?php _e( 'Branch', 'book-a-room' ); ?></strong>
		</td>
		<td><?php echo $branchList[ $roomList[ 'id' ][ $roomID ][ 'branch' ] ]; ?></td>
	</tr>

	<!-- Kelvin: Remove the display of ammenities -->

</table>
<p><?php _e( 'Deleting a room is permanent and cannot be undone.', 'book-a-room' ); ?></p>
<p><a class="errorText" href="?page=dsol_Settings_Rooms&amp;roomID=<?php echo $roomID; ?>&amp;action=deleteCheck"><?php _e( 'Click here to permanantly delete this room.', 'book-a-room' ); ?></a></p> 
</form>
<p><a href="?page=dsol_Settings_Rooms"><?php _e( 'Return to Rooms Home.', 'book-a-room' ); ?></a></p>