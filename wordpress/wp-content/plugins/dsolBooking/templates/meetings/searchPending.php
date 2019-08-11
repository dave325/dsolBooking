<link rel="stylesheet" href="https://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="https://code.jquery.com/jquery-1.9.1.js"></script>
<script src="https://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script language="javascript">
	$(function() {
		$("#editView").click(function(e) {
			e.preventDefault();
			var urlParams = new URLSearchParams($(this).attr('href'));

			fetch(urlParams.get('url') + '/wp-json/dsol-booking/v1/adminEditReservations', {
				method: "POST",
				body: "{'res_id': " + urlParams.get('res_id') + "}"
			}).then( (red) => {

				console.log(red)
				return;

				window.sessionStorage.setItem('userData', JSON.stringify(data));
			})
		});
		// Setup date drops
		$('#startDate, #endDate').datepicker({
			dateFormat: 'mm/dd/yy'
		});

		$("#hideToggle").click(function() {
			$(".searchArea").toggle();
		});
	});

	function toggle(source) {
		checkboxes = document.getElementsByName('res_id[]');
		for (var i in checkboxes)
			checkboxes[i].checked = source.checked;
	}

	jQuery(function() {
		//----- OPEN
		jQuery('[data-popup-open]').on('click', function(e) {
			var targeted_popup_class = jQuery(this).attr('data-popup-open');
			jQuery('[data-popup="' + targeted_popup_class + '"]').fadeIn(350);

			e.preventDefault();
		});

		//----- CLOSE
		jQuery('[data-popup-close]').on('click', function(e) {
			var targeted_popup_class = jQuery(this).attr('data-popup-close');
			jQuery('[data-popup="' + targeted_popup_class + '"]').fadeOut(350);

			e.preventDefault();
		});
	});

	jQuery(document).keyup(function(e) {
		if (e.keyCode == 27) jQuery('.popup').fadeOut(350);
	});
</script>
<link href="<?php echo plugins_url(); ?>/dsolBooking/css/dsol_meetings.css" rel="stylesheet" type="text/css" />
<?php
print_r($branchList);
?>
<div class=wrap>
	<div id="icon-options-general" class="icon32"></div>
	<h2>
		<?php _e('Book a Room - Search Reservations', 'book-a-room'); ?>
	</h2>
</div>
<form action="?page=bookaroom_meetings_search" method="post" name="form" id="form">
	<br />
	<table width="100%" class="tableMain">
		<tr>
			<td colspan="2">
				<?php _e('Search/Filter Settings', 'book-a-room'); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<div style="float:right; cursor:pointer; text-decoration:underline" id="hideToggle">
					<?php _e('Hide/Show', 'book-a-room'); ?>
				</div>
			</td>
		</tr>
		<tr>
			<td>
				<?php _e('Branch/Room', 'book-a-room'); ?>
			</td>
			<td>
				<select name="roomID" id="roomID">
					<?php
					# Select one line
					$selected = (empty($externals['branchID'])) ? ' selected="selected"' : NULL;
					?><option value="" <?php echo $selected; ?>><?php _e('Do not filter', 'book-a-room'); ?></option><?php
																														foreach ($branchList as $key => $val) {
																															# branch
																															$branchName = $val['branchDesc'];
																															$selected = ($externals['branchID'] == $val['branchID']) ? ' selected="selected"' : NULL;
																															?><option class="disabled" value="<?php echo 'branch-' . $key; ?>" <?php echo $selected; ?>><?php echo $branchName; ?></option><?php
																																																																# rooms
																																																																$curRoomList = $roomContList['branch'][$val['branchID']];
																																																																foreach ($curRoomList as $roomContID) {
																																																																	$selected = ($externals['roomID'] == $roomContID) ? ' selected="selected"' : NULL;

																																																																	?>
							<option value="<?php echo $roomContID; ?>" <?php echo $selected; ?>><?php echo '&nbsp;&nbsp;&nbsp;&nbsp;' . $roomContList['id'][$roomContID]['desc'] . '&nbsp;[' . $roomContList['id'][$roomContID]['occupancy'] . ']'; ?></option>
						<?php
						}
					}
					?>
				</select>
			</td>
		</tr>
		<tr class="searchArea">
			<td><?php _e('Start Date', 'book-a-room'); ?></td>
			<td><input name="startDate" type="text" id="startDate" value="<?php echo $externals['startDate']; ?>" />
			</td>
		</tr>
		<tr class="searchArea">
			<td><?php _e('End Date', 'book-a-room'); ?></td>
			<td><input name="endDate" type="text" id="endDate" value="<?php echo $externals['endDate']; ?>" />
			</td>
		</tr>
		<tr class="searchArea">
			<td><?php _e('Type', 'book-a-room'); ?></td>
			<td>
				<select name="nonProfit" id="regType">
					<?php
					$selected = (empty($externals['nonProfit']) or !array_key_exists($externals['nonProfit'], $goodArr)) ? ' selected="selected"' : null;
					?>
					<option value="" <?php echo $selected; ?>><?php _e('Do not filter', 'book-a-room'); ?></option>
					<?php
					$goodArr = array('Profit' => __('Profit', 'book-a-room'), 'Non-profit' => __('Non-profit', 'book-a-room'));
					# nonprofit dropdown
					foreach ($goodArr as $key => $val) {
						$selected = (!empty($externals['nonProfit']) and $externals['nonProfit'] == $val) ? ' selected="selected"' : NULL;
						?>
						<option value="<?php echo $val; ?>" <?php echo $selected; ?>><?php echo $val; ?></option>
					<?php
					}
					?>
				</select>
			</td>
		</tr>
		<tr class="searchArea">
			<td>Status</td>
			<td>
				<select name="status" id="status">
					<?php
					$statusArr = array(
						'pending'			=> __('Pending', 'book-a-room'),
						'pendPayment'		=> __('Pend. Payment', 'book-a-room'),
						'approved'			=> __('Approved', 'book-a-room'),
						'denied'				=> __('Denied', 'book-a-room'),
						'archived'			=> __('Archived', 'book-a-room'),
						'delete'				=> __('Delete', 'book-a-room')
					);

					# status drop down
					$selected = (empty($externals['status']) or !array_key_exists($externals['status'], $goodArr)) ? ' selected="selected"' : null;
					?>
					<option value="" <?php echo $selected; ?>><?php _e('Do not filter', 'book-a-room'); ?></option>
					<?php

					foreach ($statusArr as $key => $val) {
						$selected = (!empty($externals['status']) and $externals['status'] == $val) ? ' selected="selected"' : null;
						?>
						<option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $val; ?></option>
					<?php
					}

					?>
				</select>
			</td>
		</tr>
		<tr class="searchArea">
			<td><?php _e('Search Terms', 'book-a-room'); ?></td>
			<td><input name="searchTerms" type="text" id="searchTerms" value="<?php echo $externals['searchTerms']; ?>" />
			</td>
		</tr>
		<tr class="searchArea">
			<td colspan="2" align="center"><input name="action" type="hidden" id="action" value="filterResults" />
				<input type="submit" name="button2" id="button2" value="<?php _e('Submit', 'book-a-room'); ?>" />
			</td>
		</tr>
	</table>
</form>
<br />
<?php
if (empty($cooked)) {

	?>
	<h3><?php _e('Results Found: 0', 'book-a-room'); ?></h3>
	<table class="tableMain">
		<tr>
			<td><input type="checkbox" name="null" id="null" disabled="disabled" />
			</td>
			<td nowrap="nowrap"><?php _e('Branch and Room', 'book-a-room'); ?></td>
			<td nowrap="nowrap"><?php _e('Date and Time', 'book-a-room'); ?></td>
			<td nowrap="nowrap"><?php _e('Created', 'book-a-room'); ?></td>
			<td nowrap="nowrap"><?php _e('Event Name', 'book-a-room'); ?></td>
			<td nowrap="nowrap"><?php _e('Contact Name', 'book-a-room'); ?></td>
			<td nowrap="nowrap"><?php _e('Email', 'book-a-room'); ?></td>
			<td nowrap="nowrap"><?php _e('Nonprofit?', 'book-a-room'); ?></td>
			<td nowrap="nowrap"><?php _e('Actions', 'book-a-room'); ?></td>
		</tr>
		<tr>
			<td colspan="9" align="center"><strong><?php _e('No search results', 'book-a-room'); ?></strong>
			</td>
		</tr>
	</table>
<?php
} else {
	?>
	<pre>
							<?php
							//print_r($cooked)
							?>
						</pre>
	<h3><?php printf(__('Results Found: %s', 'book-a-room'), count($cooked)); ?></h3>
	<form id="form1" name="form1" method="post" action="?page=bookaroom_meetings">
		<table class="tableMain freeWidth">
			<tr>
				<td><input type="checkbox" name="checkAll" id="checkAll" onClick="toggle(this)" />
				</td>
				<td><?php _e('Branch and Room', 'book-a-room'); ?></td>
				<td><?php _e('Date and Time', 'book-a-room'); ?></td>
				<td><?php _e('Created', 'book-a-room'); ?></td>
				<td><?php _e('Event Name', 'book-a-room'); ?></td>
				<td><?php _e('Contact Name', 'book-a-room'); ?></td>
				<td><?php _e('Purpose of meeting', 'book-a-room'); ?></td>
				<td></td>
			</tr>
			<?php
			foreach ($cooked as $key => $val) {

				$count++;
				$notes = 0;
				$noteInformation = $val['company_name']
				?>
				<tr>
					<td><input name="res_id[]" type="checkbox" id="res_id[<?php echo $val['res_id']; ?>]" value="<?php echo $val['res_id']; ?>" />
					</td>
					<td><strong><?php echo "Room #" . $val["room_number"] ?>
					</td>
					<td nowrap="nowrap">
						<?php
						echo date('M. jS, Y', strtotime($val["start_time"])); ?>
						<br />
						<?php echo date('g:i a', strtotime($val['start_time'])) . ' - ' . date('g:i a', strtotime($val['end_time'])); ?><br />

					</td>
					</td>
					<td><?php echo  $val['notes'] ?></td>
					<td><a class="btn" data-popup-open="popup-<?php echo $count; ?>" href="#"><?php echo $val['company_name']; ?></a> (<?php echo $notes; ?>)
						<div class="popup" data-popup="popup-<?php echo $count; ?>">
							<div class="popup-inner">
								<h2><?php _e('Notes', 'book-a-room'); ?></h2>
								<p><?php echo $noteInformation; ?></p>
								<p><a data-popup-close="popup-<?php echo $count; ?>" href="#"><?php _e('Close', 'book-a-room'); ?></a>
								</p>
								<a class="popup-close" data-popup-close="popup-<?php echo $count; ?>" href="#">x</a>
							</div>
						</div>
					</td>
					<td>
						<p><a href="mailto:<?php echo $val['email']; ?>" target="_new"><?php echo $val['email']; ?></a>
						</p>
					</td>
					<td>
						<p> <?php if ($val['notes'] == "Null") {
								echo "No description";
							} else {
								echo $val['notes'];
							} ?></p>
					</td>
					<td>
						<a id="editView" href="?url=<?php echo get_site_url()  ?>&amp;page=bookaroom_meetings_search&amp;res_id=<?php echo $val['res_id']; ?>&amp;nonce=<?php echo $nonce ?>&amp;action=edit">
							<?php _e('Edit', 'book-a-room'); ?>
						</a>

					</td>
				</tr>
			<?php
			}
			?>
			<tr>
				<td colspan="9">
					<select name="status" id="status">
						<option selected="selected" value="pending"><?php _e('New Pending', 'book-a-room'); ?></option>
						<option value="pendPayment"><?php _e('Pending Payment/501(c)3', 'book-a-room'); ?></option>
						<option value="approved"><?php _e('Accepted with Payment/501(c)3', 'book-a-room'); ?></option>
						<option value="denied"><?php _e('Denied', 'book-a-room'); ?></option>
						<option value="archived"><?php _e('Archived', 'book-a-room'); ?></option>
						<option value="delete"><?php _e('Delete', 'book-a-room'); ?></option>
					</select> <input name="action" type="hidden" id="action" value="changeStatus" />
					<input type="submit" name="button" id="button" value="<?php _e('Submit', 'book-a-room'); ?>" />
				</td>
			</tr>
		</table>
	</form>
<?php
}
?>