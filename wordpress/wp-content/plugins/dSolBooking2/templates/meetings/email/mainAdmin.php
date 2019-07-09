<link href="<?php echo plugins_url(); ?>/book-a-room/css/bookaroom_meetings.css" rel="stylesheet" type="text/css"/>
<?php
	require( BOOKAROOM_PATH . 'templates/mainSettings/helpTableSetup.php' );
?>
<div class="wrap">
	<div id="icon-options-general" class="icon32"></div>
	<h2>
		<?php _e( 'Book a Room Administration - Email', 'book-a-room' ); ?>
	</h2>
</div>
<div class="mainText">
	<p>
		<?php _e( 'Enter the employee addresses for who should be notified of new requests in the Email Alerts field. (If there is more than one, use a comma to separate them.)', 'book-a-room' ); ?>
	</p>
	<p>
		<?php _e( 'In the email settings, if you would like to insert information from the original request, click on the <strong>Help!</strong> link at the top right of each email area. This will give you a list of variables you can use. For instance, if, in the email, you want to display the branch and room for the request, you would type in {branch}, {roomName}.', 'book-a-room' ); ?>
	</p>
</div>
<?php
# error count
if ( !empty( $errorMSG[ 'count' ] ) ) {
	?>
	<p style="color:red; font-weight:bold">
		<?php printf( __( 'You have <em>%s</em> errors.', 'book-a-room' ), $errorMSG['count'] ); ?>
	</p>
	<?php
}
?>
<h2>
	<?php _e( 'Email Addresses', 'book-a-room' ); ?>
</h2>
<?php
if ( !empty( $errorMSG[ 'bookaroom_alertEmail' ] ) ) {
	?>
<p style="color:red; font-weight:bold">
	<?php echo $errorMSG['bookaroom_alertEmail']; ?>
</p>
<?php
}
?>
<form id="form1" name="form1" method="post" action="">

	<table class="tableMain">
		<tr>
			<td>
				<?php _e( 'Description', 'book-a-room' ); ?>
			</td>
			<td>
				<?php _e( 'Setting', 'book-a-room' ); ?>
			</td>
		</tr>
		<tr>
			<td>
				<label for="bookaroom_alertEmail">
					<?php _e( 'Email Alerts (comma separated addresses)', 'book-a-room' ); ?>
				</label>
			</td>
			<td>
				<textarea name="bookaroom_alertEmail" cols="64" rows="4" id="bookaroom_alertEmail"><?php echo $externals[ 'bookaroom_alertEmail' ]; ?>
				</textarea>
			</td>
		</tr>
		<tr>
			<td>
				<?php _e( 'From name', 'book-a-room' ); ?>
			</td>
			<td><input name="bookaroom_alertEmailFromName" type="text" id="bookaroom_alertEmailFromName" value="<?php echo $externals[ 'bookaroom_alertEmailFromName' ]; ?>" size="64"/>
			</td>
		</tr>
		<tr>
			<td>
				<?php _e( 'From address' ,'book-a-room' ); ?>
			</td>
			<td><input name="bookaroom_alertEmailFromEmail" type="text" id="bookaroom_alertEmailFromEmail" value="<?php echo $externals[ 'bookaroom_alertEmailFromEmail' ]; ?>" size="64"/>
			</td>
		</tr>
		
		
		<tr>
			<td>
				<?php _e( 'Reply-to name', 'book-a-room' ); ?>
			</td>
			<td><input name="bookaroom_alertEmailReplyName" type="text" id="bookaroom_alertEmailReplyName" value="<?php echo $externals[ 'bookaroom_alertEmailReplyName' ]; ?>" size="64"/>
			</td>
		</tr>
		<tr>
			<td>
				<?php _e( 'Reply-to address' ,'book-a-room' ); ?>
			</td>
			<td><input name="bookaroom_alertEmailReplyEmail" type="text" id="bookaroom_alertEmailReplyEmail" value="<?php echo $externals[ 'bookaroom_alertEmailReplyEmail' ]; ?>" size="64"/>
			</td>
		</tr>
				<tr>
			<td>
				<?php _e( 'CC address list', 'book-a-room' ); ?>
			</td>
			<td><input name="bookaroom_alertEmailCC" type="text" id="bookaroom_alertEmailCC" value="<?php echo $externals[ 'bookaroom_alertEmailCC' ]; ?>" size="64"/>
			</td>
		</tr>
		<tr>
			<td>
				<?php _e( 'BCC address list' ,'book-a-room' ); ?>
			</td>
			<td><input name="bookaroom_alertEmailBCC" type="text" id="bookaroom_alertEmailBCC" value="<?php echo $externals[ 'bookaroom_alertEmailBCC' ]; ?>" size="64"/>
			</td>
		</tr>
		
		<tr>
			<td>&nbsp;</td>
			<td><input name="action" type="hidden" id="action" value="updateAlertEmail"/>
				<input type="submit" name="submit" id="submit" value="<?php _e( 'Save Changes', 'book-a-room' ); ?>"/>
				<input type="submit" name="testEmail" id="testEmail" value="<?php _e( 'Test Email', 'book-a-room' ); ?>"/>
			</td>
		</tr>
	</table>
	<?php
	$goodArr = self::arrFormList();

	foreach ( $goodArr as $key => $val ) {
		?>
	<div class="helpFormMain">
		<h2>
			<?php echo $val; ?>
		</h2>
		<?php
		if ( !empty( $errorMSG[ $key ] ) ) {
			?>
		<p style="color:red; font-weight:bold">
			<?php echo $errorMSG[ $key ]; ?>
		</p>
		<?php
		}
		?>
		<table class="tableMain">
			<tr>
				<td>
					<?php _e( 'Description', 'book-a-room' ); ?>
				</td>
				<td>
					<?php _e( 'Setting', 'book-a-room' ); ?>
					<div id="helpButton" onclick="showHelp('emailHelp_<?php echo $key; ?>')" style="float:right; clear:left;">
						<?php _e( 'Help!', 'book-a-room' ); ?>
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<?php _e( 'Subject', 'book-a-room' ); ?>
				</td>
				<td><input name="<?php echo $key . '_subject'; ?>" type="text" id="<?php echo $key . '_subject'; ?>" value="<?php echo $externals[ $key . '_subject' ]; ?>" size="64"/>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<?php wp_editor( $externals[ $key . '_body' ], $key . '_body', $settings = array( 'editor_id' => $key . '_body' ) ); ?>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><input type="submit" name="submit" value="Save Changes"/>
				</td>
			</tr>
		</table>
	</div>
	<?php
	# set a name for the DIV ID so it can hide and show it.
	$helpID = 'emailHelp_' . $key;
	require( BOOKAROOM_PATH . 'templates/mainSettings/helpTable.php' );
}
?>
</form>