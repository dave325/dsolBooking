<link href="<?php echo plugins_url(); ?>/book-a-room/css/bookaroom_meetings.css" rel="stylesheet" type="text/css"/>
<div class=wrap>
	<div id="icon-options-general" class="icon32"></div>
	<h2 id="top">
		<?php _e( 'Book a Room - Meeting Reservations', 'book-a-room' ); ?>
	</h2>
</div>
<h2><?php _e( 'Book a Room Administration - Error!', 'book-a-room' ); ?>
</h2>
</div>
<h2><?php _e( 'Invalid status.', 'book-a-room' ); ?></h2>
<p><?php printf( __( 'You have selected an invalid status. %s does not exist.', 'book-a-room' ), $externals['status'] ); ?></p>
<p><a href="?page=bookaroom_meetings"><?php _e( 'Return to pending request home.', 'book-a-room' ); ?></a></p>