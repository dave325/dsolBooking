<link href="<?php echo plugins_url(); ?>/dsolBooking/css/dsol_meetings.css" rel="stylesheet" type="text/css"/>
<div class=wrap>
	<div id="icon-options-general" class="icon32"></div>
	<h2>
		<?php _e( 'Book a Room Administration - Branches', 'book-a-room' ); ?>
	</h2>
</div>
<h2>
	<?php _e( 'Edit branch', 'book-a-room' ); ?>
</h2>
<pre>
	<?php print_r($hi) ?>
</pre>
<p><?php _e( 'You have successfully edited this branch.', 'book-a-room' ); ?></p>
<p><a href="?page=dsol_Settings_Branches&amp;action=add"><?php _e( 'Add another branch.', 'book-a-room' ); ?></a></p>
<p><a href="?page=dsol_Settings_Branches"><?php _e( 'View branch list.', 'book-a-room' ); ?></a></p>