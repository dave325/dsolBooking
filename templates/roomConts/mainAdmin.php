<link href="<?php echo plugins_url(); ?>/dsolBooking/css/dsol_meetings.css" rel="stylesheet" type="text/css" />
<div class="wrap">
	<div id="icon-options-general" class="icon32"></div>
	<h2>
		<?php _e('Book a Room Administration - Room Containers', 'book-a-room'); ?>
	</h2>
</div>
<h2>
	<?php _e('Current Room Containers by Branch', 'book-a-room'); ?>
</h2>
<?php
# Display Errors if there are any
if (!empty($errorMSG)) {
	?>
	<h3 style="color:red;"><strong><?php echo $errorMSG; ?></strong></h3>
<?php
}
if (count($branchList) == 0) {
	?>
	<h3>
		<?php _e('You haven\'t created any branches yet so you can\'t create any room containers.', 'book-a-room'); ?>
	</h3>
	<p>
		<a href="?page=dsol_Settings_Branches&amp;action=add"><?php _e('Create a new branch.', 'book-a-room'); ?></a>
	</p>
<?php
} elseif (count($roomList) == 0) {
	?>
	<h3>
		<?php _e('You haven\'t created any rooms yet so you can\'t create any room containers.', 'book-a-room');
		?>
	</h3>
	<p>
		<a href="?page=dsol_Settings_Rooms&amp;action=add">
			<?php _e('Create a new room.', 'book-a-room'); ?>
		</a>
	</p>

<?php
} else {
	?>
	<table class="tableMain">
		<tr>
			<td colspan="2"><?php _e('Branch List', 'book-a-room'); ?></td>
		</tr>
		<?php
		foreach ($branchList as $b_key => $b_val) {
			?>
			<tr>
				<td colspan="2" class="subHeader"><?php echo $b_val; ?><a href="?page=dsol_Settings_RoomCont&amp;action=add&b_id=<?php echo $b_key ?>">
							<?php _e('Create a new container.', 'book-a-room');
							?></td>
			</tr>
			<?php
			if (empty($roomList['room'][$b_key])) {
				?>
				<tr>
					<td colspan="2"><?php _e('No rooms in this branch.', 'book-a-room'); ?>
					</td>
				</tr>
			<?php
		} else {
			/*
					Kelvin: Remove the display of amenityList 
				*/

			foreach ($roomList['room'][$b_key] as $r_key => $r_val) {
				foreach ($roomContList as $rc_key => $rc_val) {

					if (is_null($roomContList[$rc_key]['roomId'][$r_val])) {
						echo $roomContList[$rc_key]['roomId'][$r_val];
						?>
							<tr>
								<td colspan="2">
									<?php _e('No rooms containers in this branch.', 'book-a-room'); ?>
									<a href="?page=dsol_Settings_RoomCont&amp;action=add&b_id=<?php echo $b_key ?>">
										<?php _e('Create a new container.', 'book-a-room');
										?>
								</td>
							</tr>
							<?php
							break;
						} else {
							?>
							<tr>
								<td class="subHeader">
									<strong>
										<?php
										echo $roomContList[$rc_key]['roomContDesc'];
										?>
									</strong>
								</td>
								<td width="250" class="subHeader" style="text-align: right"><a style="color: #FFF" href="?page=dsol_Settings_RoomCont&amp;&amp;roomContID=<?php echo $roomContList[$rc_key]['containerId']; ?>&amp;action=edit&amp;branchID=<?php echo $b_key ?>">Edit</a> | <a style="color: #FFF" href="?page=dsol_Settings_RoomCont&amp;&amp;roomContID=<?php echo $roomContList[$rc_key]['containerId']; ?>&amp;action=delete&amp;branchID=<?php echo $b_key ?>">Delete</a>
								</td>
							</tr>
							<?php
							if ((is_array($roomList['id']) and is_array($roomContList['id'][$rc_val]['rooms'])) and (count(array_intersect(array_keys($roomList['id']), $roomContList['id'][$rc_val]['rooms'])) == 0)) {
								?>
								<tr>
									<td colspan="2"><?php _e('This container has no rooms.', 'book-a-room'); ?>
									</td>
								</tr>

								<!-- Kelvin: Remove display of $amenityList -->

							<?php
							// 		}
							// 	}
						}
					}
				}
			}
		}
	}
}
?>
</table>
<p><a href="?page=dsol_Settings_RoomCont"><?php _e('Return to Room Container Home.', 'book-a-room'); ?></a></p>