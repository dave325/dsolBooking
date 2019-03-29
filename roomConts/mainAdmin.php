<link href="<?php echo plugins_url(); ?>/book-a-room/css/bookaroom_meetings.css" rel="stylesheet" type="text/css"/>
<div class="wrap">
	<div id="icon-options-general" class="icon32"></div>
	<h2>
		<?php _e( 'Book a Room Administration - Room Containers', 'book-a-room' ); ?>
	</h2>
</div>
<h2>
	<?php _e( 'Current Room Containers by Branch', 'book-a-room' ); ?>
</h2>
<?php 
# Display Errors if there are any
if ( !empty( $errorMSG ) ) {
 ?>
<h3 style="color:red;"><strong><?php echo $errorMSG; ?></strong></h3>
<?php 
}
if ( count( $branchList ) == 0 ) {
 ?>
 <h3>
	<?php _e( 'You haven\'t created any branches yet so you can\'t create any room containers.', 'book-a-room' ); ?>
</h3>
<p>
	<a href="?page=bookaroom_Settings_Branches&amp;action=add"><?php  _e( 'Create a new branch.', 'book-a-room' ); ?></a>
</p>
<?php
} elseif ( count( $roomList ) == 0 ) {
	?>
	<h3>
		<?php _e( 'You haven\'t created any rooms yet so you can\'t create any room containers.', 'book-a-room' ); 
		?>
		</h3>
		<p>
			<a href="?page=bookaroom_Settings_Rooms&amp;action=add">
				<?php _e( 'Create a new room.', 'book-a-room' ); ?>
			</a>
		</p>
		<?php
	} else {
		foreach ( $branchList as $key => $val ) {
			?>
			<table class="tableMain">
				<tr>
					<td>
						<?php 
			echo 'TESTING OUT '. $val;
						?>
					</td>
					<td style="text-align: right">
						<?php 
			if ( empty( $roomList[ 'room' ][ $key ] ) or count( $roomList[ 'room' ][ $key ] ) == 0 ) {
				_e( 'No rooms in this branch.', 'book-a-room' ); 
			} else {
				?><a href="?page=bookaroom_Settings_RoomCont&amp;branchID=<?php echo $key; ?>&amp;action=add"><?php 
_e( 'Add a new container', 'book-a-room' ); ?></a><?php 
			}
						?>
					</td>
				</tr>
				<?php 
			if ( empty( $roomContList[ 'branch' ][ $key ] ) ) {
					?>
				<tr>
					<td colspan="2">
						<?php _e( 'No rooms containers in this branch.', 'book-a-room' ); ?>
					</td>
				</tr>
				<?php 
			} else {
				foreach( $roomContList['branch'][$key] as $rc_key => $rc_val ) {
						?>
				<tr>
					<td class="subHeader">
						<strong>
							<?php 
					echo $roomContList['id'][$rc_val]['desc'];
					if( !empty( $roomContList['id'][$rc_val]['isPublic'] ) ) {
						echo ' ' . __( '[Public]', 'book-a-room' ); 
					}
					if( !empty( $roomContList['id'][$rc_val]['hideDaily'] ) ) {
						echo ' ' . __( '[Hide]', 'book-a-room' );													
					}
							?>
						</strong>
					</td>
					<td width="250" class="subHeader" style="text-align: right"><a style="color: #FFF" href="?page=bookaroom_Settings_RoomCont&amp;&amp;roomContID=<?php echo $rc_val; ?>&amp;action=edit">Edit</a> | <a style="color: #FFF" href="?page=bookaroom_Settings_RoomCont&amp;&amp;roomContID=<?php echo $rc_val; ?>&amp;action=delete">Delete</a>
					</td>
				</tr>
				<?php 
					if ( ( is_array( $roomList[ 'id' ] ) and is_array( $roomContList[ 'id' ][ $rc_val ][ 'rooms' ] ) ) and ( count( array_intersect( array_keys( $roomList[ 'id' ] ), $roomContList[ 'id' ][ $rc_val ][ 'rooms' ] ) ) == 0 ) ) {
 							?>
				<tr>
					<td colspan="2"><?php _e( 'This container has no rooms.', 'book-a-room' ); ?></td>
				</tr>

					<!-- Kelvin: Remove display of $amenityList -->
					
				<?php 
					// 		}
					// 	}
					}
				}
			}
 					?>
			</table>
			<br>
			<?php 
		}
}
 ?>
<p><a href="?page=bookaroom_Settings_RoomCont"><?php _e( 'Return to Room Container Home.', 'book-a-room'); ?></a></p>