<script type="text/javascript">
	function toggle( source ) {
		checkboxes = document.getElementsByName( 'res_id[]' );
		for (var i = 0; i < checkboxes.length; i++) {
			checkboxes[ i ].checked = source.checked;
		}
	}

	jQuery( function () {
		//----- OPEN
		jQuery( '[data-popup-open]' ).on( 'click', function ( e ) {
			var targeted_popup_class = jQuery( this ).attr( 'data-popup-open' );
			jQuery( '[data-popup="' + targeted_popup_class + '"]' ).fadeIn( 350 );

			e.preventDefault();
		} );

		//----- CLOSE
		jQuery( '[data-popup-close]' ).on( 'click', function ( e ) {
			var targeted_popup_class = jQuery( this ).attr( 'data-popup-close' );
			jQuery( '[data-popup="' + targeted_popup_class + '"]' ).fadeOut( 350 );

			e.preventDefault();
		} );
	} );

	jQuery( document ).keyup( function ( e ) {
		if ( e.keyCode == 27 ) jQuery( '.popup' ).fadeOut( 350 );
	} );

	jQuery( document ).mouseup( function ( e ) {
		if ( e.target.className == "popup" ) {
			jQuery( '.popup' ).fadeOut( 350 );
		}
	} );
</script>
<link href="<?php echo plugins_url(); ?>/book-a-room/css/bookaroom_meetings.css" rel="stylesheet" type="text/css"/>
<div class=wrap>
	<div id="icon-options-general" class="icon32"></div>
	<h2 id="top">
		<?php _e( 'Book a Room - Meeting Reservations', 'book-a-room' ); ?>
	</h2>
</div>
<h2>
	<?php echo $title; ?>
</h2>
<?php
if ( empty( $pendingList[ 'status' ][ $pendingType ] ) ) {
	?>
<table class="tableMain">
	<tr>
		<td><input type="checkbox" name="null" id="null" disabled="disabled"/>
		</td>
		<td>
			<?php _e( 'Branch & Room1', 'book-a-room' ); ?>
		</td>
		<td>
			<?php _e( 'Date & Time', 'book-a-room' ); ?>
		</td>
		<td>
			<?php _e( 'Created', 'book-a-room' ); ?>
		</td>
		<td>
			<?php _e( 'Event Name', 'book-a-room' ); ?>
		</td>
		<td>
			<?php _e( 'Contact Name', 'book-a-room' ); ?>
		</td>
		<td>
			<?php _e( 'Contact', 'book-a-room' ); ?>
		</td>
		<td>
			<?php _e( 'Nonprofit', 'book-a-room' ); ?>
		</td>
		<td>
			<?php _e( 'Status', 'book-a-room' ); ?>
		</td>
	</tr>
	<tr>
		<td colspan="9" align="center"><strong><?php _e( 'No pending reservations', 'book-a-room' ); ?></strong>
		</td>
	</tr>
</table>
<?php
} else {
	?>
	<form id="form1" name="form1" method="post" action="?page=bookaroom_meetings">
		<table class="tableMain freeWidth">
			<tr>
				<td><input type="checkbox" name="checkAll" id="checkAll" onClick="toggle(this)"/>
				</td>
				<td>
					<?php _e( 'Branch & Room', 'book-a-room' ); ?>
				</td>
				<td>
					<?php _e( 'Date & Time', 'book-a-room' ); ?>
				</td>
				<td>
					<?php _e( 'Created', 'book-a-room' ); ?>
				</td>
				<td>
					<?php _e( 'Event Name', 'book-a-room' ); ?>
				</td>
				<td>
					<?php _e( 'Contact Name', 'book-a-room' ); ?>
				</td>
				<td>
					<?php _e( 'Contact', 'book-a-room' ); ?>
				</td>
				<td>
					<?php _e( 'Nonprofit', 'book-a-room' ); ?>
				</td>
				<td>
					<?php _e( 'Status', 'book-a-room' ); ?>
				</td>
			</tr>
			<?php
			$count = 0;
			
			foreach ( $pendingList[ 'status' ][ $pendingType ] as $key => $mainKey ) {
			print_r( $pendingList); 

				$val = $pendingList[ 'id' ][ $mainKey ];

				$notes = 0;
				$noteField = self::noteInformation( $val[ 'contactName' ], $notes );
				$roomCount = count( $roomContList[ 'id' ][ $val[ 'roomID' ] ][ 'rooms' ] );
				if ( empty( $val[ 'nonProfit' ] ) ) {
					$nonProfit = __( 'No', 'book-a-room' );
					# find how many increments
					$minutes = ( ( strtotime( $val[ 'endTime' ] ) - strtotime( $val[ 'startTime' ] ) ) / 60 ) / $option[ 'bookaroom_baseIncrement' ];
					$roomPrice = $minutes * $option[ 'bookaroom_profitIncrementPrice' ] * $roomCount;
					$deposit = intval( $option[ 'bookaroom_profitDeposit' ] );
				} else {
					$nonProfit = __( 'Yes', 'book-a-room' );
					# find how many increments
					$minutes = ( ( strtotime( $val[ 'endTime' ] ) - strtotime( $val[ 'startTime' ] ) ) / 60 ) / $option[ 'bookaroom_baseIncrement' ];					
					$roomPrice = $minutes * $option[ 'bookaroom_nonProfitIncrementPrice' ] * $roomCount;
					$deposit = intval( $option[ 'bookaroom_nonProfitDeposit' ] );
				}
				
				# date 1 - two weeks from now
				$timeArr = getdate( strtotime( $val[ 'created' ] ) );
				$date1 = mktime( 0, 0, 0, $timeArr[ 'mon' ], $timeArr[ 'mday' ] + 14, $timeArr[ 'year' ] );
				$date3 = mktime( 0, 0, 0, $timeArr[ 'mon' ], $timeArr[ 'mday' ] + 1, $timeArr[ 'year' ] );
				# two weeks before event
				$timeArr = getdate( strtotime( $val[ 'startTime' ] ) );
				$date2 = mktime( 0, 0, 0, $timeArr[ 'mon' ], $timeArr[ 'mday' ] - 14, $timeArr[ 'year' ] );
				# check dates
				$mainDate = min( $date1, $date2 );
				if ( $mainDate < $date3 ) {
					$mainDate = $date3;
				}
				?>
			<tr>
				<td><input name="res_id[]" type="checkbox" id="res_id[<?php echo $val[ 'res_id' ]; ?>]" value="<?php echo $val[ 'res_id' ]; ?>"/>
				</td>
				<td>
					<strong>
						<?php echo $branchList[ $roomContList[ 'id' ][ $val[ 'roomID' ] ][ 'branchID' ] ][ 'branchDesc' ]; ?>
					</strong><br/>
					<?php echo $roomContList[ 'id' ][ $val[ 'roomID' ] ][ 'desc' ]; ?>
				</td>
				<td nowrap="nowrap">
					<?php echo date( 'M. jS, Y', strtotime( $val[ 'startTime' ] ) ); ?><br/>
					<?php echo date( 'g:i a', strtotime( $val[ 'startTime' ] ) ); ?> -
					<?php echo date( 'g:i a', strtotime( $val[ 'endTime' ] ) ); ?>
				</td>
				<td nowrap="nowrap">
					<?php echo date( 'M. jS, Y', strtotime( $val[ 'created' ] ) ); ?><br/>
					<?php echo date( 'g:i a', strtotime( $val[ 'created' ] ) ); ?>
				</td>
				<td>
					<?php echo htmlspecialchars_decode( $val[ 'eventName' ] ); ?>
				</td>
				<td><a class="btn" data-popup-open="popup-<?php echo $count; ?>" href="#"><?php echo $val[ 'contactName' ]; ?></a><?php if( !empty( $notes ) ) printf( ' (%s)', $notes); ?>
					<div class="popup" data-popup="popup-<?php echo $count; ?>">
						<div class="popup-inner">
							<h2><?php _e( 'Notes', 'book-a-room' ); ?></h2>
							<?php echo $noteField; ?>
							<p><a data-popup-close="popup-<?php echo $count; ?>" href="#"><?php _e( 'Close', 'book-a-room' ); ?></a>
							</p>
							<a class="popup-close" data-popup-close="popup-<?php echo $count; ?>" href="#">x</a> </div>
					</div>
				</td>
				<td>
					<p><a href="mailto:<?php echo $val[ 'contactEmail' ]; ?>"><?php echo $val[ 'contactEmail' ]; ?></a><br/> <?php echo self::prettyPhone( $val[ 'contactPhonePrimary' ] ); ?>
					</p>
				</td>
				<td nowrap="nowrap"><strong><?php echo $nonProfit; ?></strong><br/><?php /* translators: Abbreviation for Deposit */ printf( __( 'Dep: $%s<br/>Room: $%s<br/> Due: %s', 'book-a-room' ), $deposit, $roomPrice, date( 'm-d-Y', $mainDate ) ); ?></td>
				<td align="right" nowrap="nowrap">
					<p><?php echo $typeArr[ $val[ 'status' ] ]; ?><br/>
						<a href="?page=bookaroom_meetings&amp;action=view&amp;res_id=<?php echo $val[ 'res_id' ]; ?>"><?php _e( 'View', 'book-a-room' ); ?></a>| <a href="?page=bookaroom_meetings&amp;action=edit&amp;res_id=<?php echo $val[ 'res_id' ]; ?>"><?php _e( 'Edit', 'book-a-room' ); ?></a>
					</p>
				</td>
			</tr>
			<?php
			$count++;
			}
			?>
			<tr>
				<td colspan="9">
					<select name="status" id="status">
						<option selected="selected" value="pending"><?php _e( 'New Pending', 'book-a-room' ); ?></option>
						<option value="pendPayment"><?php _e( 'Pending Payment/501(c)3', 'book-a-room' ); ?></option>
						<option value="approved"><?php _e( 'Accepted with Payment/501(c)3', 'book-a-room' ); ?></option>
						<option value="denied"><?php _e( 'Denied', 'book-a-room' ); ?></option>
						<option value="archived"><?php _e( 'Archived', 'book-a-room' ); ?></option>
						<option value="delete"><?php _e( 'Delete', 'book-a-room' ); ?></option>
					</select>
					<input name="action" type="hidden" id="action" value="changeStatus"/>
					<input type="submit" name="button" id="button" value="Submit"/>
				</td>
			</tr>
		</table>
	</form> 
	<?php
}
?>