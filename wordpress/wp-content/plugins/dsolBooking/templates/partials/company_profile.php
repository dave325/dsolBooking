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
<link href="<?php echo plugins_url(); ?>/css/dsol_meetings.css" rel="stylesheet" type="text/css"/>
<div class=wrap>
	<div id="icon-options-general" class="icon32"></div>
	<h2 id="top">
		<?php _e( 'Book a Room - Meeting Reservations', 'book-a-room' ); ?>
	</h2>
</div>
<h1><?php echo $user->display_name ?> Profile </h1>
<?php
if ( empty( $final ) ) {
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
		<td colspan="9" align="center"><strong><?php _e( 'No reservations', 'book-a-room' ); ?></strong>
		</td>
	</tr>
</table>
<?php
} else {
	?>
	<form id="form1" name="form1" method="post" action="<?php echo makeLink_correctPermaLink( get_option( 'dsol_reservation_URL' ) ); ?>action=delete ?>">
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
			</tr>
			<?php
			$count = 0;
			
			foreach ( $final as $key => $mainKey ) {
			
                $val = $final[$count];
				$notes = 0;
				$noteField = self::noteInformation( $val[ 'contactName' ], $notes );
				$roomCount = count( $val['roomID']);
				if ( empty( $val[ 'nonProfit' ] ) ) {
					$nonProfit = __( 'No', 'book-a-room' );
					# find how many increments
					$minutes = ( ( strtotime( $val[ 'endTime' ] ) - strtotime( $val[ 'startTime' ] ) ) / 60 ) / $option[ 'dsol_baseIncrement' ];
					$roomPrice = $minutes * $option[ 'dsol_profitIncrementPrice' ] * $roomCount;
					$deposit = intval( $option[ 'dsol_profitDeposit' ] );
				} else {
					$nonProfit = __( 'Yes', 'book-a-room' );
					# find how many increments
					$minutes = ( ( strtotime( $val[ 'endTime' ] ) - strtotime( $val[ 'startTime' ] ) ) / 60 ) / $option[ 'dsol_baseIncrement' ];					
					$roomPrice = $minutes * $option[ 'dsol_nonProfitIncrementPrice' ] * $roomCount;
					$deposit = intval( $option[ 'dsol_nonProfitDeposit' ] );
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
					<input name="timeid[]" type="hidden" id="res_id[<?php echo $val[ 'id' ]; ?>]" value="<?php echo $val[ 'id' ]; ?>"/>
				</td>
				<td>
					<strong>
						<?php echo $val[ 'branchDesc' ]; ?>
					</strong><br/>
					<?php echo $val[ 'desc' ]; ?>
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
			</tr>
			<?php
			$count++;
			}
			?>
			<tr>
				<td colspan="9">
                <input name="status" value="delete" type="hidden" />
                <input name="action" type="hidden" id="action" value="changeStatus"/>
                <input name="submit" value="Delete" type="submit" />
				</td>
			</tr>
		</table>
	</form> 
	<?php
}
?>