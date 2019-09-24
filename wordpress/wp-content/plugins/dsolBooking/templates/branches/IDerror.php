<link href="<?php echo plugins_url(); ?>/dsolBooking/css/dsol_meetings.css" rel="stylesheet" type="text/css"/>
<div class=wrap>
	<div id="icon-options-general" class="icon32"></div>
	<h2>
		<?php _e( 'Book a Room Administration - Branches', 'book-a-room' ); ?>
	</h2>
</div>
<h2>
	<?php _e( 'Error!', 'book-a-room' ); ?>
</h2>
<p><?php _e( 'Invalid ID.', 'book-a-room' ); ?></p>
<pre><?php print_r($branchList) ?></pre>
<p><a href="?page=dsol_Settings_Branches"><?php _e( 'Please try again.', 'book-a-room' ); ?></a></p>