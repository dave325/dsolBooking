<html>

<body style="font-family: Verdana, Geneva, sans-serif">
	<div style="font-size: 1.25em; font-weight: bold; padding-bottom: 10px;">
		<?php printf( __( 'Meeting room schedule for %s.', 'book-a-room' ), date( 'l, F jS, Y', $timestamp ) ); ?>
	</div>
	</div>
	<?php
	if ( count( $pendingList[ 'location' ] ) == 0 ) {
		# No meetings
		?>
	<div class="noMeeting">
		<?php _e( 'There are no meetings on this date.', 'book-a-room' ); ?>
	</div>
	<?php
	} else {
		?>
	<table width="100%" style="border: thin solid #666; font-size: 1.1em;">
		<tr>
			<td colspan="5" style="border: thin solid #666; padding: 6px; background: #44F; font-weight: bold; text-align: center; color: #FFF;">
				<?php echo date( 'l, F jS, Y', $timestamp ); ?>
			</td>
		</tr>
		<tr>
			<td style="border: thin solid #666; padding: 6px; background: #333; font-weight: bold; text-align: center; color: #FFF;">
				<?php _e( 'Location', 'book-a-room' ); ?>
			</td>
			<td style="border: thin solid #666; padding: 6px; background: #333; font-weight: bold; text-align: center; color: #FFF;">
				<?php _e( 'Time', 'book-a-room' ); ?>
			</td>
			<td style="border: thin solid #666; padding: 6px; background: #333; font-weight: bold; text-align: center; color: #FFF;">
				<?php _e( 'Description', 'book-a-room' ); ?>
			</td>
			<td style="border: thin solid #666; padding: 6px; background: #333; font-weight: bold; text-align: center; color: #FFF;">
				<?php _e( 'Organization', 'book-a-room' ); ?>
			</td>
			<td style="border: thin solid #666; padding: 6px; background: #333; font-weight: bold; text-align: center; color: #FFF;">
				<?php _e( 'Amenities', 'book-a-room' ); ?>
			</td>
		</tr>
		<?php
		$count = 1;
		$bgCount = 1;
		ksort( $pendingList[ 'location' ] );
		foreach ( $pendingList[ 'location' ] as $key => $val ) {
			ksort( $val );
			foreach ( $val as $rmKey => $room ) {
				$eventName = htmlspecialchars_decode( $room[ 0 ][ 'eventName' ] );
				$eventDesc = htmlspecialchars_decode( $room[ 0 ][ 'desc' ] );

				if ( $room[ 0 ][ 'type' ] == 'meeting' ) {
					if ( $room[ 0 ][ 'status' ] == 'pending' ) {
						$eventName = '<em><strong>Pending:</strong></em><br />' . $eventName;
						$eventDesc = '<em><strong>Pending:</strong></em><br />' . $eventDesc;
					} elseif ( $room[ 0 ][ 'status' ] == 'pendPayment' ) {
						$eventName = '<em><strong>Pending Payment:</strong></em><br />' . $eventName;
						$eventDesc = '<em><strong>Pending Payment:</strong></em><br />' . $eventDesc;
					} elseif ( $room[ 0 ][ 'status' ] == '501C3' ) {
						$eventName = '<em><strong>Pending Payment 501(3)(c):</strong></em><br />' . $eventName;
						$eventDesc = '<em><strong>Pending Payment 501(3)(c):</strong></em><br />' . $eventDesc;
					}
				}

				if ( count( $room ) == 1 ) {
					if ( empty( $room[ 0 ][ 'amenity' ] ) ) {
						$amenities = '&nbsp;';
					} else {
						$amenFinal = array();
						foreach ( unserialize( $room[ 0 ][ 'amenity' ] ) as $amenity ) {
							$amenFinal[] = $amenityList[ $amenity ];
						}
						$amenities = implode( ', ', $amenFinal );
					}
					# branch and room
					if ( empty( $room[ 0 ][ 'roomID' ] ) and!empty( $room[ 0 ][ 'noLocation_branch' ] ) ) {
						$branchName = $branchList[ $room[ 0 ][ 'noLocation_branch' ] ][ 'branchDesc' ];
						$roomName = __( 'No location specified.', 'book-a-room' );
					} else {
						$branchName = $key;
						$roomName = $rmKey;
					}
					?>
		<tr style="background-color: <?php echo ( $bgCount++ % 2 ) ? '#FFF' : '#DDD'; ?>">
			<td style="border: thin solid #666; padding: 6px;">
				<strong>
					<?php echo $branchName; ?>
				</strong><br/>
				<?php echo $roomName; ?>
			</td>
			<td style="border: thin solid #666; padding: 6px;">
				<?php echo date( 'g:i a', strtotime( $room[0]['startTime'] ) ) . ' - ' . date( 'g:i a', strtotime( $room[0]['endTime'] ) ); ?>
			</td>
			<td style="border: thin solid #666; padding: 6px;">
				<?php echo $eventName; ?>
			</td>
			<td style="border: thin solid #666; padding: 6px;">
				<?php echo $eventDesc; ?>
			</td>
			<td style="border: thin solid #666; padding: 6px;">
				<?php echo $amenities; ?>
			</td>
		</tr>
		<?php
		} else {
			$first = true;
			$count = 1;

			foreach ( $room as $roomVal ) {
				
				
				$eventName = htmlspecialchars_decode( $roomVal[ 'eventName' ] );
				$eventDesc = htmlspecialchars_decode( $roomVal[ 'desc' ] );

				if ( $roomVal[ 'type' ] == 'meeting' ) {
					if ( $roomVal[ 'status' ] == 'pending' ) {
						$eventName = '<em><strong>Pending:</strong></em><br />' . $eventName;
						$eventDesc = '<em><strong>Pending:</strong></em><br />' . $eventDesc;
					} elseif ( $roomVal[ 'status' ] == 'pendPayment' ) {
						$eventName = '<em><strong>Pending Payment:</strong></em><br />' . $eventName;
						$eventDesc = '<em><strong>Pending Payment:</strong></em><br />' . $eventDesc;
					} elseif ( $roomVal[ 'status' ] == '501C3' ) {
						$eventName = '<em><strong>Pending Payment 501(3)(c):</strong></em><br />' . $eventName;
						$eventDesc = '<em><strong>Pending Payment 501(3)(c):</strong></em><br />' . $eventDesc;
					}
				}

				if ( count( $room ) == 1 ) {
					if ( empty( $roomVal[ 'amenity' ] ) ) {
						$amenities = '&nbsp;';
					} else {
						$amenFinal = array();
						foreach ( unserialize( $roomVal[ 'amenity' ] ) as $amenity ) {
							$amenFinal[] = $amenityList[ $amenity ];
						}
						$amenities = implode( ', ', $amenFinal );
					}
					# branch and room
					if ( empty( $roomVal[ 'roomID' ] ) and!empty( $roomVal[ 'noLocation_branch' ] ) ) {
						$branchName = $branchList[ $roomVal[ 'noLocation_branch' ] ][ 'branchDesc' ];
						$roomName = __( 'No location specified.', 'book-a-room' );
					} else {
						$branchName = $key;
						$roomName = $rmKey;
					}
				}

				if ( empty( $roomVal[ 'amenity' ] ) ) {
					$amenities = '&nbsp;';
				} else {
					$amenFinal = array();
					foreach ( unserialize( $roomVal[ 'amenity' ] ) as $amenity ) {
						$amenFinal[] = $amenityList[ $amenity ];
					}
					$amenities = implode( ', ', $amenFinal );
				}
				if ( $first == true ) {
					$first = false;
					?>
		<tr style="background-color: <?php echo ( $bgCount++ % 2 ) ? '#FFF' : '#DDD'; ?>">
			<td rowspan="<?php echo count( $room ); ?>" style="border: thin solid #666; padding: 6px;">
				<strong>
					<?php echo $key; ?>
				</strong><br/>
				<?php echo $rmKey; ?><br/>
				<strong><em><?php echo ucwords( $roomVal['type'] ); ?></em></strong>
			</td>
			<td style="border: thin solid #666; padding: 6px;">
				<?php 
				if( date( 'g:i a', strtotime( $roomVal['startTime'] ) ) == '12:00 am' and date( 'g:i a', strtotime( $roomVal['endTime'] ) ) == '11:59 pm' ) {
					_e( 'All Day', 'book-a-room' );
				} else {
					echo date( 'g:i a', strtotime( $roomVal['startTime'] ) ) . ' - ' . date( 'g:i a', strtotime( $roomVal['endTime'] ) ); 
				}
				?>
			</td>
			<td style="border: thin solid #666; padding: 6px;">
				<?php echo $eventName; ?>
			</td>
			<td style="border: thin solid #666; padding: 6px;">
				<?php echo $eventDesc; ?>
			</td>
			<td style="border: thin solid #666; padding: 6px;">
				<?php echo $amenities; ?>
			</td>
		</tr>
		<?php
		continue;
		}

		?>
		<tr style="background-color: <?php echo ( $bgCount++ % 2 ) ? '#FFF' : '#DDD'; ?>">
			<td nowrap="nowrap" style="border: thin solid #666; padding: 6px;">
				<?php 
				if( date( 'g:i a', strtotime( $roomVal['startTime'] ) ) == '12:00 am' and date( 'g:i a', strtotime( $roomVal['endTime'] ) ) == '11:59 pm' ) {
					_e( 'All Day', 'book-a-room' );
				} else {
					echo date( 'g:i a', strtotime( $roomVal['startTime'] ) ) . ' - ' . date( 'g:i a', strtotime( $roomVal['endTime'] ) ); 
				}
				?>
			</td>
			<td nowrap="nowrap" style="border: thin solid #666; padding: 6px;">
				<?php echo $eventName; ?>
			</td>
			<td style="border: thin solid #666; padding: 6px;">
				<?php echo $eventDesc; ?>
			</td>
			<td style="border: thin solid #666; padding: 6px;">
				<?php echo $amenities; ?>
			</td>
		</tr>
		<?php
				}		
			}
		}
	}
?>
	</table>
	<?php

	}

	?>
</body>

</html>