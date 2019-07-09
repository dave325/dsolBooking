<link href="<?php echo plugins_url(); ?>/book-a-room/css/bookaroom_meetings.css" rel="stylesheet" type="text/css"/>
<div class=wrap>
	<div id="icon-options-general" class="icon32"></div>
	<h2>
		<?php _e( 'Book a Room - Meeting Reservations', 'book-a-room' ); ?>
	</h2>
</div>
<h2>
	<?php _e( 'You have made the following changes:', 'book-a-room' ); ?>
</h2>
<p><?php printf( __( '%s reservation(s) were successfully changed.', 'book-a-room' ), count( $final['changed'] ) ); ?></p>
<p><?php printf( __( '%s reservation(s) failed to change.', 'book-a-room' ), count( $final['fail'] ) ); ?></p>
<p><?php printf( __( '%s reservation(s) didn\'t change because they were already set to the status you chose.', 'book-a-room' ), count( $final['noChange'] ) ); ?></p>
