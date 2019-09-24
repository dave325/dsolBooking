<link href="<?php echo plugins_url(); ?>/dsolBooking/css/dsol_meetings.css" rel="stylesheet" type="text/css"/>
<div class="wrap">
	<div id="icon-options-general" class="icon32"></div>
	<h2>
		<?php _e( 'Book a Room Administration - Rooms', 'book-a-room' ); ?>
	</h2>
</div>
<h2>
	<?php _e( 'Current Room Containers by Branch', 'book-a-room' ); ?>
</h2>
<?php
if ( is_null( $branchList ) or!is_array( $branchList ) or count( $branchList ) == 0 ) {
	?>
<p><a href="#"> </a>
	<?php _e( 'You haven\'t created any branches yet so you can\'t create any rooms.', 'book-a-room' ); ?>
</p>
<p>
	<a href="?page=dsol_Settings_Branches&amp;action=add">
		<?php _e( 'Create a new branch.', 'book-a-room' ); ?>
	</a>
</p>
<?php
} else {
	?>
	<p>
		<a href="?page=dsol_Settings_Rooms&action=add">
			<?php _e( 'Create a new room.', 'book-a-room' ); ?>
		</a>
	</p>
	<?php
}
if ( is_null( $roomList ) or!is_array( $roomList ) or count( $roomList ) == 0 ) {
	?>
	<p><?php _e( 'You haven\'t created any rooms.', 'book-a-room' ); ?></p>
	<?php
} else {
	?>
	<pre>
		<?php
			print_r($roomList[ 'room' ]);
		?>
	</pre>

	<table class="tableMain">
		<tr>
			<td colspan="2"><?php _e( 'Branch List', 'book-a-room' ); ?></td>
		</tr>
		<?php
		foreach ( $branchList as $b_key => $b_val ) {
			?>
		<tr>
			<td colspan="2" class="subHeader"><?php echo $b_val['b_name']; ?></td>
		</tr>
		<?php
			if ( empty( $roomList[ 'room' ][ $b_key ] ) ) {
			?>
		<tr>
			<td colspan="2"><?php _e( 'No rooms in this branch.', 'book-a-room' ); ?>
			</td>
		</tr>
		<?php
			} else {
				/*
					Kelvin: Remove the display of amenityList 
				*/
				foreach ( $roomList[ 'room' ][ $b_key ] as $r_key => $r_val ) {
				?>
		<tr>
			<td class="bufferLeft"><strong><?php echo $r_val; ?></strong>
			</td>
			<td><a href="?page=dsol_Settings_Rooms&branchID=<?php echo $b_key; ?>&roomID=<?php echo $r_key; ?>&action=edit"><?php _e( 'Edit', 'book-a-room' ); ?></a> | <a href="?page=dsol_Settings_Rooms&branchID=<?php echo $b_key; ?>&roomID=<?php echo $r_key; ?>&action=delete"><?php _e( 'Delete', 'book-a-room' ); ?></a>
			</td>
		</tr>

		<?php
				}
			}
		}
		?>
	</table>
	<?php
}
?>