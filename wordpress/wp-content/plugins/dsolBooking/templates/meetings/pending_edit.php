<link href="<?php echo plugins_url(); ?>/book-a-room/css/bookaroom_meetings.css" rel="stylesheet" type="text/css"/>
<div class=wrap>
	<div id="icon-options-general" class="icon32"></div>
	<h2>
		<?php _e( 'Book a Room Administration - Add/Edit Reservations', 'book-a-room' ); ?>
	</h2>
</div>
<?php
# Display Errors if there are any
if ( !empty( $errorMSG ) ) {
	?>
	<p>
		<h3 style="color: red;"><strong><?php echo $errorMSG; ?></strong></h3>
	</p>
	<?php
}
?>
<form name="form1" method="post" action="?page=bookaroom_meetings">
	<p>
		<table border="0" class="tableMain">
			<tr>
				<td>
					<?php _e( 'Name', 'book-a-room' ); ?>
				</td>
				<td>
					<?php _e( 'Value', 'book-a-room' ); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php _e( 'Branch and Room', 'book-a-room' ); ?>
				</td>
				<td>
					<strong>
						<?php echo $branchList[$roomContList['id'][$roomContID]['branchID']]['branchDesc']; ?>
					</strong><br/>
					<em>
						<?php echo $roomContList['id'][$roomContID]['desc']; ?>
					</em>
				</td>
			</tr>
			<tr>
				<td>
					<?php _e( 'Date', 'book-a-room' ); ?>
				</td>
				<td>
					<?php echo date( 'l, F jS, Y', $externals['startTime'] ); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php _e( 'Requested times', 'book-a-room' ); ?>
				</td>
				<td>
					<strong>
						<?php echo date( 'g:i a', $externals['startTime'] ); ?>
					</strong> -
					<strong>
						<?php echo date( 'g:i a', $externals['endTime'] ); ?>
					</strong>
				</td>
			</tr>
			<tr>
				<td>
					<?php _e( 'Change Reservation', 'book-a-room' ); ?>
				</td>
				<td>
					<a href="?page=bookaroom_meetings&amp;action=changeReservationSetup&amp;res_id=<?php echo $edit; ?>">
						<?php _e( 'Change Reservation (Time and/or Room)', 'book-a-room' ); ?>
					</a>
				</td>
			</tr>
			<tr>
				<td>
					<label for="eventName">
						<?php _e( 'Event / Organization name', 'book-a-room' ); ?> *
					</label>
				</td>
				<td<?php if( !empty( $errorArr[ 'errorBG'][ 'eventName'] ) ) echo ' class="error"'; ?>><input name="eventName" type="text" id="eventName" value="<?php echo $externals['eventName']; ?>" size="64" maxlength="255"/>
					</td>
			</tr>
			<tr>
				<td>
					<label for="numAtend">
						<?php _e( 'Number of attendees', 'book-a-room' ); ?> *
					</label>
				</td>
				<td<?php if( !empty( $errorArr[ 'errorBG'][ 'numAttend'] ) ) echo ' class="error"'; ?>><input name="numAttend" type="text" id="numAttend" value="<?php echo $externals['numAttend']; ?>" size="3" maxlength="3"/>
					</td>
			</tr>
			<tr>
				<td>
					<label for="notes">
						<?php _e( 'Purpose of meeting', 'book-a-room' ); ?> *
					</label>
				</td>
				<td<?php if( !empty( $errorArr[ 'errorBG'][ 'desc'] ) ) echo ' class="error"'; ?>>
					<textarea cols="60" rows="5" id="desc" name="desc" style="resize: vertical"><?php echo htmlspecialchars_decode( $externals['desc'] ); ?>
					</textarea>
					</td>
			</tr>
			<tr>
				<td>
					<label for="contactName">
						<?php _e( 'Contact name', 'book-a-room' ); ?> *
					</label>
				</td>
				<td<?php if( !empty( $errorArr[ 'errorBG'][ 'contactName'] ) ) echo ' class="error"'; ?>><input name="contactName" type="text" id="contactName" value="<?php echo $externals['contactName']; ?>" size="32" maxlength="64"/>
					</td>
			</tr>
			<tr>
				<td>
					<label for="contactPhonePrimary">
						<?php _e( 'Primary phone', 'book-a-room' ); ?> *
					</label>
				</td>
				<td<?php if( !empty( $errorArr[ 'errorBG'][ 'contactPhonePrimary'] ) ) echo ' class="error"'; ?>><input name="contactPhonePrimary" type="text" id="contactPhonePrimary" value="<?php echo $externals['contactPhonePrimary']; ?>" size="14" maxlength="14"/>
					</td>
			</tr>
			<tr>
				<td>
					<label for="contactPhoneSecondary">
						<?php _e( 'Alternative phone', 'book-a-room' ); ?>
					</label>
				</td>
				<td<?php if( !empty( $errorArr[ 'errorBG'][ 'contactPhoneSecondary'] ) ) echo ' class="error"'; ?>><input name="contactPhoneSecondary" type="text" id="contactPhoneSecondary" value="<?php echo $externals['contactPhoneSecondary']; ?>" size="14" maxlength="14"/>
					</td>
			</tr>
			<tr>
				<td>
					<span class="question">
						<label for="contactAddress1"><?php echo $address1_name; ?> *</label>
					</span>
				
				</td>
				<td<?php if( !empty( $errorArr[ 'errorBG'][ 'contactAddress1'] ) ) echo ' class="error"'; ?>><input name="contactAddress1" type="text" id="contactAddress1" value="<?php echo $externals['contactAddress1']; ?>" size="64" maxlength="255"/>
					</td>
			</tr>
			<tr>
				<td>
					<span class="question">
						<?php echo $address2_name; ?>
					</span>
				</td>
				<td><input name="contactAddress2" type="text" id="contactAddress2" value="<?php echo $externals['contactAddress2']; ?>" size="64" maxlength="255"/>
				</td>
			</tr>
			<tr>
				<td>
					<span class="question">
						<?php echo $city_name; ?>
					</span>
				</td>
				<td<?php if( !empty( $errorArr[ 'errorBG'][ 'contactCity'] ) ) echo ' class="error"'; ?>><input name="contactCity" type="text" id="contactCity" value="<?php echo $externals['contactCity']; ?>" maxlength="255"/>
					</td>
			</tr>
			<tr>
				<td>
					<span class="question">
						<?php echo $state_name; ?>
					</span>
				</td>
				<td<?php if( !empty( $errorArr[ 'errorBG'][ 'contactState'] ) ) echo ' class="error"'; ?>>
					<?php
					if ( get_option( 'bookaroom_addressType' ) == 'usa' ) {
						?>
					<select name="contactState" id="contactState">
						<?php
						$selected = ( empty( $externals[ 'contactState' ] ) or !array_key_exists( $stateList, $externals[ 'contactState' ] ) ) ? ' selected="selected"' : null;						
						
						?><option value="" <?php echo $selected; ?>><?php _e( 'Choose a state', 'book-a-room' ); ?></option><?php
						
						$stateList = self::getStates();
						foreach ( $stateList as $key => $val ) {
							$selected = ( !empty( $externals[ 'contactState' ] ) && $externals[ 'contactState' ] == $key ) ? ' selected="selected"' : NULL;
							?>
						<option value="<?php echo $key; ?>" <?php echo $selected; ?>>
							<?php echo $val; ?>
						</option>
						<?php
						}
						?>
					</select>
					<?php
					} else {
						?>
					<input name="contactState" type="text" id="contactState" value="<?php echo $externals['contactState']; ?>" name="contactState" maxlength="255"/>
					<?php
					}
					?>
					</td>
			</tr>
			<tr>
				<td>
					<span class="question">
						<?php echo $zip_name; ?>
					</span>
				</td>
				<td<?php if( !empty( $errorArr[ 'errorBG'][ 'contactZip'] ) ) echo ' class="error"'; ?>><input name="contactZip" type="text" id="contactZip" value="<?php echo $externals['contactZip']; ?>" size="10" maxlength="10"/>
					</td>
			</tr>
			<tr>
				<td>
					<label for="contactEmail">
						<?php _e( 'Email Address', 'book-a-room' );?> *</label>
				</td>
				<td<?php if( !empty( $errorArr[ 'errorBG'][ 'contactEmail'] ) ) echo ' class="error"'; ?>><input name="contactEmail" type="text" id="contactEmail" value="<?php echo $externals['contactEmail']; ?>" size="64" maxlength="255"/>
					</td>
			</tr>
			<tr>
				<td>
					<?php _e( 'Non Profit 501(c)(3)', 'book-a-room' ); ?> *</td>
				<td><input name="nonProfit" type="radio" id="radio" value="TRUE" <?php echo $NPyesChecked; ?> />
					<?php _e( 'Yes', 'book-a-room' ); ?>&nbsp;&nbsp;&nbsp;&nbsp;
					<input name="nonProfit" type="radio" id="radio" value="" <?php echo $NPnoChecked; ?> />
					<?php _e( 'No', 'book-a-room' ); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php _e( 'Organization Website', 'book-a-room' ); ?>
				</td>
				<td><input name="contactWebsite" type="text" id="contactWebsite" value="<?php echo $externals['contactWebsite']; ?>" size="64" maxlength="255"/>
				</td>
			</tr>
			<tr>
				<td>
					<?php _e( 'Amenities', 'book-a-room' ); ?>
				</td>
				<td>
					<?php
					$amenitiesCur = array();
					foreach( $roomContList['id'][$roomContID]['rooms'] as $key => $val) {
						if( !empty( $roomList['id'][$val]['amenity'] ) ) {
							$amenitiesCur += $roomList['id'][$val]['amenity'];
						}
					}
					$amenitiesCur = array_unique( $amenitiesCur );
					if( empty( $amenitiesCur ) ) {
						_e( 'No amenities are available for this room.', 'book-a-room' ); 
					} else {
						if( !array( $externals['amenity'] ) ) {
							$externals['amenity'] = array();
						}
						foreach( $amenitiesCur as $key => $val ) {
							if( !array_key_exists( $val, $amenityList ) ) {
								continue;
							}
							$checked = ( !is_array( $externals['amenity'] ) || !in_array( $val, $externals['amenity'] ) ) ? NULL : ' checked="checked"';
					?>
					<input type="checkbox" value="<?php echo $val; ?>" name="amenity[]" id="amenity_<?php echo $val; ?>"<?php echo $checked; ?>/>
				<label for="amenity_<?php echo $val; ?>"><?php echo $amenityList[$val]; ?></label><br>
					<?php
						}
					}
					?>
				</td>
			</tr>
			<tr>
				<td><?php _e( 'Social?', 'book-a-room' ); ?></td>
				<td><input name="isSocial" type="radio" id="isSocialNo" value="TRUE"<?php echo $SOyesChecked;?>/><?php _e( 'Yes', 'book-a-room' ); ?>&nbsp;&nbsp;&nbsp;&nbsp;<input name="isSocial" type="radio" id="isSocialYes" value="TRUE"<?php echo $SOnoChecked; ?>/><?php _e( 'No', 'book-a-room' ); ?></td>
			</tr>
			<tr>
				<td><?php _e( 'Library Card', 'book-a-room' ); ?></td>
				<td><input name="libcardNum" type="text" id="libcardNum" value="<?php echo $externals['libcardNum']; ?>" size="64" maxlength="255"/>
				</td>
			</tr>
			<tr>
				<td><?php _e( 'Notes', 'book-a-room' ); ?></td>
				<td<?php if( !empty( $errorArr[ 'errorBG'][ 'notes'] ) ) echo ' class="error"'; ?>><textarea name="notes" cols="60" rows="5" id="desc2" name="desc"><?php echo $externals['notes']; ?></textarea>
				</td>
			</tr>
			<tr>
				<td>&nbsp;&nbsp;</td>
				<td><input name="startTime" type="hidden" id="startTime" value="<?php echo  $externals['startTime']; ?>"/>
					<input name="endTime" type="hidden" id="endTime" value="<?php echo  $externals['endTime']; ?>"/>
					<input name="roomID" type="hidden" id="roomID" value="<?php echo $roomContID; ?>"/>
					<input name="action" type="hidden" id="action" value="editCheck"/>
					<input type="submit" name="button" id="button" value="<?php _e( 'Submit', 'book-a-room' ); ?>"/>
				</td>
			</tr>
		</table>
</form>