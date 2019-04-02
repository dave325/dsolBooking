<link href="<?php echo plugins_url(); ?>/book-a-room/css/bookaroom_meetings.css" rel="stylesheet" type="text/css"/>
<div class="wrap">
	<div id="icon-options-general" class="icon32"></div>
	<h2>
		<?php _e( 'Book a Room Administration - Room Containers', 'book-a-room' ); ?>
	</h2>
</div>
<h2><?php _e( 'Delete Room Container', 'book-a-room' ); ?></h2>
<?php 
$isPublic = ( $roomContList['id'][$roomContID]['isPublic'] ) ? 'Yes' : 'No';
$hideDaily = ( $roomContList['id'][$roomContID]['hideDaily'] ) ? 'Yes' : 'No';
?>
<table class="tableMain">
	<tr>
		<td><strong><?php _e( 'Container Name', 'book-a-room' ); ?></strong>
		</td>
		<td><?php echo $roomContList['id'][$roomContID]['desc']; ?></td>
	</tr>
	<tr>
		<td><strong><?php _e( 'Branch', 'book-a-room' ); ?></strong>
		</td>
		<td><?php echo $branchList[$roomContList['id'][$roomContID]['branchID']]; ?></td>
	</tr>
	<tr>
		<td><strong><?php _e( 'Public?', 'book-a-room' ); ?></strong>
		</td>
		<td><?php echo $isPublic; ?></td>
	</tr>
	<tr>
		<td><strong><?php _e( 'Hide on Daily?', 'book-a-room' ); ?></strong>
		</td>
		<td><?php echo $hideDaily; ?></td>
	</tr>
<?php
	if( empty( $roomContList['id'][$roomContID]['rooms'] ) ) {
		?>
	<tr>
		<td>
			<strong><?php _e( 'Rooms', 'book-a-room' ); ?></strong>
		</td>
		<td><?php _e( 'No rooms', 'book-a-room' ); ?></td>
	</tr>
	<?php
	} elseif( count( $roomContList['id'][$roomContID]['rooms'] ) == 1 ) {
		?>
	<tr>
		<td><strong><?php _e( 'Rooms', 'book-a-room' ); ?></strong></td>
		<td><?php echo $roomList['id'][current( $roomContList['id'][$roomContID]['rooms'] )]['desc']; ?></td>
	</tr>
	<?php		
	} else {
		$first = true;
		foreach( $roomContList['id'][$roomContID]['rooms'] as $key => $val ) {
			# first room
			$rowspan = false;
			if( $first ) {
				$first = false;
			?>
	<tr>
		<td rowspan="<?php echo count( $roomContList['id'][$roomContID]['rooms'] ); ?>" valign="top"><strong><?php _e( 'Rooms', 'book-a-room' ); ?></strong>
		</td>
		<td><?php echo $roomList['id'][$val]['desc']; ?></td>
	</tr>
		<?php
			} else {
			?>
	<tr>
		<td><?php echo $roomList['id'][$val]['desc']; ?></td>
	</tr>
		<?php
			}
		}
	}
		?>
</table>
<p><?php _e( 'Deleting a room containeris permanent and cannot be undone.', 'book-a-room' ); ?></p>
<p><a class="errorText" href="?page=bookaroom_Settings_RoomCont&amp;roomContID=<?php echo $roomContID; ?>&amp;action=deleteCheck"><?php _e( 'Click here to permanantly delete this room container.', 'book-a-room' ); ?></a></p>
<p><a href="?page=bookaroom_Settings_RoomCont"><?php _e( 'Return to Room Container Home.', 'book-a-room' ); ?></a></p>