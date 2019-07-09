<link href="<?php echo plugins_url(); ?>/book-a-room/css/bookaroom_meetings.css" rel="stylesheet" type="text/css"/>
<div class=wrap>
	<div id="icon-options-general" class="icon32"></div>
	<h2>
		<?php _e( 'Book a Room - Meeting Reservations', 'book-a-room' ); ?>
	</h2>
</div>
<h2>
	<?php _e( 'Pending Reservations', 'book-a-room' ); ?>
</h2>
<form id="form1" name="form1" method="post" action="">
	<table class="tableMain">
		<tr>
			<td><?php _e( 'Name', 'book-a-room' ); ?></td>
			<td><?php _e( 'Value', 'book-a-room' ); ?></td>
		</tr>
		<tr>
			<td><strong><?php _e( 'Branch and Room', 'book-a-room' ); ?></strong>
			</td>
			<td><?php echo $pendingList[ 'id' ][ $res_id ][ 'branchName' ]; ?><br/> <?php echo $pendingList[ 'id' ][ $res_id ][ 'roomName' ]; ?>
			</td>
		</tr>
		<tr>
			<td><strong><?php _e( 'Date', 'book-a-room' ); ?></strong>
			</td>
			<td><?php echo $pendingList[ 'id' ][ $res_id ][ 'formDate' ]; ?></td>
		</tr>
		<tr>
			<td><strong><?php _e( 'Requested times', 'book-a-room' ); ?></strong>
			</td>
			<td><?php echo $pendingList[ 'id' ][ $res_id ][ 'startTimeDisp' ]; ?> - <?php echo $pendingList[ 'id' ][ $res_id ][ 'endTimeDisp' ]; ?></td>
		</tr>
		<tr>
			<td><strong><?php _e( 'Event / Organization name', 'book-a-room' ); ?></strong>
			</td>
			<td><?php echo $pendingList[ 'id' ][ $res_id ][ 'eventName' ]; ?></td>
		</tr>
		<tr>
			<td><strong><?php _e( 'Number of attendees', 'book-a-room' ); ?></strong>
			</td>
			<td><?php echo $pendingList[ 'id' ][ $res_id ][ 'numAttend' ]; ?></td>
		</tr>
		<tr>
			<td><strong><?php _e( 'Purpose of meeting', 'book-a-room' ); ?></strong>
			</td>
			<td><?php echo $pendingList[ 'id' ][ $res_id ][ 'desc' ]; ?></td>
		</tr>
		<tr>
			<td><strong><?php _e( 'Contact name', 'book-a-room' ); ?></strong>
			</td>
			<td><?php echo $pendingList[ 'id' ][ $res_id ][ 'contactName' ]; ?></td>
		</tr>
		<tr>
			<td><strong><?php _e( 'Primary phone', 'book-a-room' ); ?></strong>
			</td>
			<td><?php echo $pendingList[ 'id' ][ $res_id ][ 'contactPhonePrimary' ]; ?></td>
		</tr>
		<tr>
			<td><strong><?php _e( 'Alternative phone', 'book-a-room' ); ?></strong>
			</td>
			<td><?php echo $pendingList[ 'id' ][ $res_id ][ 'contactPhoneSecondary' ]; ?></td>
		</tr>
		<tr>
			<td><strong><?php echo $address1_name; ?></strong>
			</td>
			<td><?php echo $pendingList[ 'id' ][ $res_id ][ 'contactAddress1' ]; ?></td>
		</tr>
		<tr>
			<td><strong><?php echo $address2_name; ?></strong>
			</td>
			<td><?php echo $pendingList[ 'id' ][ $res_id ][ 'contactAddress2' ]; ?></td>
		</tr>
		<tr>
			<td><strong><?php echo $city_name; ?></strong>
			</td>
			<td><?php echo $pendingList[ 'id' ][ $res_id ][ 'contactCity' ]; ?></td>
		</tr>
		<tr>
			<td><strong><?php echo $state_name; ?></strong>
			</td>
			<td><?php echo $pendingList[ 'id' ][ $res_id ][ 'contactState' ]; ?></td>
		</tr>
		<tr>
			<td><strong><?php echo $zip_name; ?></strong>
			</td>
			<td><?php echo $pendingList[ 'id' ][ $res_id ][ 'contactZip' ]; ?></td>
		</tr>
		<tr>
			<td><strong><?php _e( 'Email Address', 'book-a-room' ); ?></strong>
			</td>
			<td><a href="mailto:<?php echo $pendingList[ 'id' ][ $res_id ][ 'contactEmail' ]; ?>"><?php echo $pendingList[ 'id' ][ $res_id ][ 'contactEmail' ]; ?></a>
			</td>
		</tr>
		<tr>
			<td><strong><?php _e( 'Non Profit 501(c)(3)', 'book-a-room' ); ?></strong>
			</td>
			<td><?php echo $pendingList[ 'id' ][ $res_id ][ 'nonProfitDisp' ]; ?></td>
		</tr>
		<tr>
			<td><strong><?php _e( 'Organization Website', 'book-a-room' ); ?></strong>
			</td>
			<td><?php
				if( !empty( $pendingList[ 'id' ][ $res_id ][ 'contactWebsite' ] ) ) {
					?><a href="<?php echo $pendingList[ 'id' ][ $res_id ][ 'contactWebsite' ]; ?>" target="_new"><?php echo $pendingList[ 'id' ][ $res_id ][ 'contactWebsite' ]; ?></a>
					<?php
				}
				?>
			</td>
		</tr>
		<tr>
			<td><strong><?php _e( 'Amenities', 'book-a-room' ); ?></strong>
			</td>
			<td><?php echo $pendingList[ 'id' ][ $res_id ][ 'amenityVal' ]; ?></td>
		</tr>
		<tr>
			<td><strong><?php _e( 'Notes', 'book-a-room' ); ?></strong>
			</td>
			<td><?php echo $pendingList[ 'id' ][ $res_id ][ 'notes' ]; ?></td>
		</tr>
		<tr>
			<td><strong><?php _e( 'Deposit', 'book-a-room' ); ?></strong>
			</td>
			<td><?php printf( __( '$ %s', 'book-a-room' ), $deposit ); ?></td>
		</tr>
		<tr>
			<td><strong><?php _e( 'Room cost', 'book-a-room' ); ?></strong>
			</td>
			<td><?php printf( __( '$ %s', 'book-a-room' ), $roomPrice ); ?></td>
		</tr>
		<tr>
			<td><strong><?php _e( 'Total', 'book-a-room' ); ?></strong>
			</td>
			<td><?php printf( __( '$ %s', 'book-a-room' ), $roomPrice + $deposit ); ?></td>
		</tr>
		<tr>
			<td><strong><?php _e( 'Due Date', 'book-a-room' ); ?></strong>
			</td>
			<td><?php echo date( 'm-d-Y', $mainDate ); ?></td>
		</tr>
		<tr>
			<td><strong><?php _e( 'Social?', 'book-a-room' ); ?></strong>
			</td>
			<td><?php echo $pendingList[ 'id' ][ $res_id ][ 'socialDisp' ]; ?></td>
		</tr>
		<tr>
			<td><strong><?php _e( 'Library Card', 'book-a-room' ); ?></strong>
			</td>
			<td><?php echo $pendingList[ 'id' ][ $res_id ][ 'libcardNum' ]; ?></td>
		</tr>
		<tr>
			<td colspan="2" align="center"><a href="?page=bookaroom_meetings&amp;action=edit&amp;res_id=<?php echo $res_id; ?>"><?php _e( 'Click here to edit this reservation.', 'book-a-room' ); ?></a>
			</td>
		</tr>
		<tr>
			<td><strong><?php _e( 'Status', 'book-a-room' ); ?></strong>
			</td>
			<td>
				<select name="status" id="status">
					<?php
					$dropArr = array( 'pending' => __( 'New Pending', 'book-a-room' ), 'pendPayment' => __( 'Pending Payment/501(c)3', 'book-a-room' ), 'approved' => __( 'Accepted with Payment/501(c)3', 'book-a-room' ), 'denied' => __( 'Denied', 'book-a-room' ), 'archived' => __( 'Archived', 'book-a-room' ) );
					
					foreach ( $dropArr as $key => $val ) {
						$selected = ( $pendingList[ 'id' ][ $res_id ][ 'status' ] == $key ) ? 'selected="selected" ' : NULL;
					?>
					<option value="<?php echo $key; ?>"<?php echo $selected; ?>><?php echo $val; ?></option>
					<?php
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input name="res_id[]" type="hidden" id="res_id[<?php echo $res_id; ?>]" value="<?php echo $res_id; ?>"/>
				<input name="action" type="hidden" id="action" value="changeStatus"/> <input type="submit" name="button" id="button" value="Submit"/>
			</td>
		</tr>
	</table>
</form>