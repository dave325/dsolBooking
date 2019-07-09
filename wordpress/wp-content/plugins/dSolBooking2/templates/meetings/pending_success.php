<link href="<?php echo plugins_url(); ?>/book-a-room/css/bookaroom_meetings.css" rel="stylesheet" type="text/css"/>
<div class=wrap>
	<div id="icon-options-general" class="icon32"></div>
	<h2>
		<?php _e( 'Book a Room - Meeting Reservations', 'book-a-room' ); ?>
	</h2>
</div>
<h2>
	<?php _e( 'Edit Reservations', 'book-a-room' ); ?>
</h2>
<p><?php _e( 'You have successfully edited this reservation.', 'book-a-room' ); ?></p>
<p><a href="?page=bookaroom_meetings&amp;action=view&amp;res_id=<?php echo $res_id; ?>"><?php _e( 'View this reservation.', 'book-a-room' ); ?></a></p>
<p><a href="?page=bookaroom_meetings&amp;action=edit&amp;res_id=<?php echo $res_id; ?>"><?php _e( 'Edit this reservation again.', 'book-a-room' ); ?></a></p>