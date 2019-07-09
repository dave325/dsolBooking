<link href="<?php echo plugins_url(); ?>/book-a-room/css/bookaroom_meetings.css" rel="stylesheet" type="text/css"/>
<div class=wrap>
	<div id="icon-options-general" class="icon32"></div>
	<h2>
		<?php _e( 'Book a Room Administration - Email', 'book-a-room' ); ?>
	</h2>
</div>
<h2>
	<?php _e( 'Email Settings', 'book-a-room' ); ?>
</h2>
<p><?php _e( 'You have successfully sent out a test email to the following address(es):', 'book-a-room' ); echo ' ' . $bookaroom_alertEmail; ?></p>
<p><a href="?page=bookaroom_Settings_Email"><?php _e( 'Return to Email Admin.', 'book-a-room' ); ?></a></p>