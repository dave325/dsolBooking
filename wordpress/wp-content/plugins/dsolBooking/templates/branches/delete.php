<link href="<?php echo plugins_url(); ?>/dsolBooking/css/dsol_meetings.css" rel="stylesheet" type="text/css"/>
<div class=wrap>
	<div id="icon-options-general" class="icon32"></div>
	<h2>
		<?php _e( 'Book a Room Administration - Branches', 'book-a-room' ); ?>
	</h2>
</div>
<?php
# Display Errors if there are any
if ( !empty( $errorMSG ) ) {
	?>
	<p>
		<h3 style="color: red;"><strong><?php echo $errorMSG; ?></strong></h3>
	</p> <?php
}
?>
<h2>
	<?php _e( 'New branch', 'book-a-room' ); ?>
</h2>
<table class="tableMain">
	<tr>
		<td>
			<?php _e( 'Option', 'book-a-room' ); ?>
		</td>
		<td>
			<?php _e( 'Setting', 'book-a-room' ); ?>
		</td>
	</tr>

	<tr>
		<td>
			<strong>
				<?php _e( 'Branch Name', 'book-a-room' ); ?>
			</strong>
		</td>
		<td>
			<?php echo $branchInfo['branchDesc']; ?>
		</td>
	</tr>
	<tr>
		<td>
			<strong>
				<?php _e( 'Address', 'book-a-room' ); ?>
			</strong>
		</td>
		<td>
			<?php echo nl2br( $branchInfo['branchAddress'] ); ?>
		</td>
	</tr>
	<tr>
		<td>
			<strong>
				<?php _e( 'Map Link', 'book-a-room' ); ?>
			</strong>
		</td>
		<td>
			<a href="<?php echo $branchInfo['branchMapLink']; ?>" target="_blank">
				<?php _e( 'Click here to view map link.', 'book-a-room' ); ?>
			</a>
		</td>
	</tr>
	<tr>
		<td>
			<strong>
				<?php _e( 'Image URL', 'book-a-room' ); ?>
			</strong>
		</td>
		<td>
			<a href="<?php echo $branchInfo['branchImageURL']; ?>" target="_blank"><img src="<?php echo $branchInfo['branchImageURL']; ?>"><br><?php echo $branchInfo['branchImageURL']; ?></a>
		</td>
	</tr>
	<tr>
		<td>
			<strong>
				<?php _e( 'Sunday', 'book-a-room' ); ?>
			</strong>
		</td>
		<td>
			<?php echo $timeDisp[0]; ?>
		</td>
	</tr>
	<tr>
		<td>
			<strong>
				<?php _e( 'Monday', 'book-a-room' ); ?>
			</strong>
		</td>
		<td>
			<?php echo $timeDisp[1]; ?>
		</td>
	</tr>
	<tr>
		<td>
			<strong>
				<?php _e( 'Tuesday', 'book-a-room' ); ?>
			</strong>
		</td>
		<td>
			<?php echo $timeDisp[2]; ?>
		</td>
	</tr>
	<tr>
		<td>
			<strong>
				<?php _e( 'Wednesday', 'book-a-room' ); ?>
			</strong>
		</td>
		<td>
			<?php echo $timeDisp[3]; ?>
		</td>
	</tr>
	<tr>
		<td>
			<strong>
				<?php _e( 'Thursday', 'book-a-room' ); ?>
			</strong>
		</td>
		<td>
			<?php echo $timeDisp[4]; ?>
		</td>
	</tr>
	<tr>
		<td>
			<strong>
				<?php _e( 'Friday', 'book-a-room' ); ?>
			</strong>
		</td>
		<td>
			<?php echo $timeDisp[5]; ?>
		</td>
	</tr>
	<tr>
		<td>
			<strong>
				<?php _e( 'Saturday', 'book-a-room' ); ?>
			</strong>
		</td>
		<td>
			<?php echo $timeDisp[6]; ?>
		</td>
	</tr>
</table>
<p style="font-weight: bold; width: 600px">
	<?php _e( 'Deleting a branch is permanent and cannot be undone. This will not remove any meeting reservations, but it will make people unable to reserve rooms at this branch and will delete all rooms and room containers that exist at this branch.', 'book-a-room' ); ?>
</p>
<h2>
	<?php _e( 'Rooms and Containers Affected', 'book-a-room' ); ?>
</h2>
<p>
	<?php printf( __( 'If you delete %s, you will also delete the following room containers and rooms.', 'book-a-room' ), $branchInfo['branchDesc'] ); ?>
</p>
<table class="tableMain">
	<tr>
		<td>
			<?php _e( 'Container', 'book-a-room' ); ?>
		</td>
		<td>
			<?php _e( 'Rooms', 'book-a-room' ); ?>
		</td>
	</tr>
	<?php
	$container = self::makeRoomAndContList( $branchInfo, $roomContList, $roomList );

	# show room list based on empty or not		
	if ( count( $container ) == 0 ) {
		?>
	<tr>
		<td colspan="2">
			<?php _e( 'No rooms or containers would be affected by deleting this branch', 'book-a-room' ); ?>
		</td>
	</tr>
	<?php
	} else {
		foreach ( $container as $s_key => $s_val ) {
			?>

	<tr>
		<td valign="top">
			<?php
			if ( $s_key == NULL ) {
				echo $s_val[ 'name' ];
			} else {
				?>
			<a href="/wp-admin/admin.php?page=dsol_Settings_RoomCont&amp;&amp;roomContID=<?php echo $s_key; ?>&amp;action=edit">
				<?php echo $s_val['name']; ?>
			</a>
			<?php
			}
			?>
		</td>
		<td>
			<?php
			foreach ( $s_val[ 'rooms' ] as $r_val ) {
				?>
			<a href="/wp-admin/admin.php?page=dsol_Settings_Rooms&amp;branchID=<?php echo $branchInfo['branchID']; ?>&amp;roomID=<?php echo $r_val; ?>&amp;action=edit">
				<?php echo $roomList['id'][$r_val]['desc']; ?>
			</a>&nbsp;
			<?php
			}
			?>
		</td>
	</tr>
	<?php
		}
	}
	?>
</table>
<h3><a class="errorText" href="?page=dsol_Settings_Branches&amp;branchID=<?php echo $branchInfo['branchID']; ?>&amp;action=deleteCheck"><?php _e( 'Click here to permanantly delete this branch.', 'book-a-room' ); ?></a></h3>