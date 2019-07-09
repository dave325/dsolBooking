<link href="<?php echo plugins_url(); ?>/book-a-room/css/bookaroom_meetings.css" rel="stylesheet" type="text/css"/>
<style type="text/css">
	@media print {
		#adminmenu {
			display: none;
		}
		#adminmenu {
			display: none;
		}
		#adminmenushadow {
			display: none;
		}
		#adminmenuwrap {
			display: none;
		}
		#adminmenuback {
			display: none;
		}
		#wpadminbar {
			display: none;
		}
		#mainHeader {
			display: none;
		}
		#wpfooter {
			display: none;
		}
		#wpcontent {
			position: absolute;
			top: 0px;
			left: -165px;
			width: 100%;
		}
		#printForm {
			display: none;
		}
	}
	
	.contactHeader {
		font-size: 1.25em;
		font-weight: bold;
		padding-bottom: 10px;
	}
	
	.noMeeting {
		width: 600px;
		padding-top: 4px;
		padding-bottom: 4px;
	}
	
	#mainTable tr td {
		border: thin solid #666;
		padding: 6px;
		font-size: 1.1em;
	}
	
	#mainTable tr:nth-of-type(odd) {
		background: #DDD;
	}
	
	#mainTable tr:nth-of-type(1) {
		background: #44F;
		font-weight: bold;
		text-align: center;
		color: #FFF;
	}
	
	#mainTable tr:nth-of-type(2) {
		background: #333;
		font-weight: bold;
		text-align: center;
		color: #FFF;
	}
	
	#emailError {
		color: #F00;
		font-weight: bold;
		font-style: italic;
	}
</style>
<!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" /> -->
<script src="//code.jquery.com/jquery-1.9.1.js"></script>
<script src="//code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script language="JavaScript" type="text/javascript">
	$( function () {
		// Setup date drops
		$( '#date' ).datepicker( {
			dateFormat: 'mm/dd/yy'
		} );
	} );
</script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css"/>
<div id="mainHeader">
	<div class=wrap>
		<div id="icon-options-general" class="icon32"></div>
		<h2>
			<?php _e( 'Book a Room - Daily Meetings', 'book-a-room' ); ?>
		</h2>
	</div>
	<form action="" method="post">
		<p>
			<?php _e( 'Show all (including Pending and Pending Payment)?', 'book-a-room' ); ?>
			<label for="viewAll">
        <input name="viewAll" type="checkbox" id="viewAll" value="TRUE"<?php echo $viewAllChecked; ?> />
      </label>
		

		</p>
		<p>
			<strong>
				<?php _e( 'Choose a date:', 'book-a-room' ); ?>
			</strong><br>
			<input name="timestamp" type="text" id="date" value="<?php echo date( 'm/d/Y', $timestamp ); ?>" size="30">
			<input type="submit" name="button" id="button" value="Submit">
		</p>
	</form>
	<p><em><strong><?php _e( 'This page is printer friendly! Just hit print to get a print-formatted page.', 'book-a-room' ); ?></strong></em>
	</p>
</div>
<div class="contactHeader">
	<h2>
		<?php printf( __( 'Meeting room schedule for %s.', 'book-a-room' ), date( 'l, F jS, Y', $timestamp ) ); ?>
	</h2>
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
	# Some meetings, show list
	?>
	<table width="100%" id="mainTable">
		<tr>
			<td colspan="5">
				<?php echo date( 'l, F jS, Y', $timestamp ); ?>
			</td>
		</tr>
		<tr>
			<td nowrap>
				<?php _e( 'Location', 'book-a-room' ); ?>
			</td>
			<td nowrap>
				<?php _e( 'Time', 'book-a-room' ); ?>
			</td>
			<td>
				<?php _e( 'Organization', 'book-a-room' ); ?>
			</td>
			<td style="min-width: 200px;">
				<?php _e( 'Description', 'book-a-room' ); ?>
			</td>
			<td style="min-width: 150px;">
				<?php _e( 'Amenities', 'book-a-room' ); ?>
			</td>
		</tr>
		<?php
		$count = 1;
		ksort( $pendingList['location'] );
	
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
					if( empty( $room[0]['amenity'] ) ) {
						$amenities = '&nbsp;';
					} else {
						$amenFinal = array();
						foreach( unserialize( $room[0]['amenity'] ) as $amenity ) {
							$amenFinal[] = $amenityList[$amenity];
						}
						$amenities = implode( ', ', $amenFinal );
					}
					# branch and room
					if( empty( $room[0]['roomID']) and !empty( $room[0]['noLocation_branch'] ) ) {
						$branchName 	= $branchList[$room[0]['noLocation_branch']]['branchDesc'];
						$roomName		= __( 'No location specified.', 'book-a-room' );
					} else {
						$branchName		= $key;
						$roomName		= $rmKey;
					}
		?>
		<tr>
			<td nowrap>
				<strong><?php echo $branchName; ?></strong><br/>
				<?php echo $roomName; ?><br/>
				<strong><em><?php echo ucwords( $room[0]['type'] ); ?></em></strong>
			</td>
			<td nowrap="nowrap"><?php echo date( 'g:i a', strtotime( $room[0]['startTime'] ) ) . ' - ' . date( 'g:i a', strtotime( $room[0]['endTime'] ) ); ?>
			</td>
			<td><?php echo $eventName; ?></td>
			<td><?php echo $eventDesc; ?></td>
			<td><?php echo $amenities; ?></td>
		</tr>
		<?php
		} else {
		$first = true;
		$count = 1;
					
		foreach ( $room as $roomVal ) {
			$eventName = htmlspecialchars_decode( $roomVal['eventName'] );
			$eventDesc = htmlspecialchars_decode( $roomVal['desc'] );
				
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
					if( empty( $room[0]['amenity'] ) ) {
						$amenities = '&nbsp;';
					} else {
						$amenFinal = array();
						foreach( unserialize( $room[0]['amenity'] ) as $amenity ) {
							$amenFinal[] = $amenityList[$amenity];
						}
						$amenities = implode( ', ', $amenFinal );
					}
					# branch and room
					if( empty( $room[0]['roomID']) and !empty( $room[0]['noLocation_branch'] ) ) {
						$branchName 	= $branchList[$room[0]['noLocation_branch']]['branchDesc'];
						$roomName		= __( 'No location specified.', 'book-a-room' );
					} else {
						$branchName		= $key;
						$roomName		= $rmKey;
					}
				}
			
			if( empty( $roomVal['amenity'] ) ) {
				$amenities		= '&nbsp;';
			} else {
				$amenFinal = array();
				foreach( unserialize( $roomVal['amenity'] ) as $amenity ) {
					$amenFinal[] = $amenityList[$amenity];
				}
				$amenities		= implode( ', ', $amenFinal );
			}			
			if( $first == true ) {
				$first = false;
				?>
				<tr>
			<td rowspan="<?php echo count( $room ); ?>" nowrap>
				<strong><?php echo $key; ?></strong><br/>
				<?php echo $rmKey; ?><br/>
				<strong><em><?php echo ucwords( $roomVal['type'] ); ?></em></strong>
			</td>
			<td nowrap="nowrap"><?php 
				if( date( 'g:i a', strtotime( $roomVal['startTime'] ) ) == '12:00 am' and date( 'g:i a', strtotime( $roomVal['endTime'] ) ) == '11:59 pm' ) {
					_e( 'All Day', 'book-a-room' );
				} else {
					echo date( 'g:i a', strtotime( $roomVal['startTime'] ) ) . ' - ' . date( 'g:i a', strtotime( $roomVal['endTime'] ) ); 
				}
				?>
			</td>
			<td><?php echo $eventName; ?></td>
			<td><?php echo $eventDesc; ?></td>
			<td><?php echo $amenities; ?></td>
		</tr>
			<?php
				continue;
			}
			
			?>
		<tr>
			<td nowrap="nowrap"><?php 
				if( date( 'g:i a', strtotime( $roomVal['startTime'] ) ) == '12:00 am' and date( 'g:i a', strtotime( $roomVal['endTime'] ) ) == '11:59 pm' ) {
					_e( 'All Day', 'book-a-room' );
				} else {
					echo date( 'g:i a', strtotime( $roomVal['startTime'] ) ) . ' - ' . date( 'g:i a', strtotime( $roomVal['endTime'] ) ); 
				}
				?>
			</td>
			<td><?php echo $eventName; ?></td>
			<td><?php echo $eventDesc; ?></td>
			<td><?php echo $amenities; ?></td>
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
$emailError = null;

if( $externals['action'] == 'sendEmail' ) {
	ob_start();
	require( BOOKAROOM_PATH . 'templates/meetings/dailyMeetingsEmail.php' );
	$buffer = ob_get_contents();
	ob_end_clean();
	if( empty( $externals['emailAddress'] ) || !filter_var( $externals['emailAddress'], FILTER_VALIDATE_EMAIL ) ) {
		$emailError = __( 'Pease enter a valid email address for the <strong>Email</strong> field.', 'book-a-room' );
	} else {
		$fromName	= get_option( 'bookaroom_alertEmailFromName' );	
		$fromEmail	= get_option( 'bookaroom_alertEmailFromEmail' );
		
		$replyName	= get_option( 'bookaroom_alertEmailReplyName' );	
		$replyEmail	= get_option( 'bookaroom_alertEmailReplyEmail' );
		
		$CCEmail	= get_option( 'bookaroom_alertEmailCC' );	
		$BCEmail	= get_option( 'bookaroom_alertEmailBCC' );
				
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
					"From: {$fromName} <{$fromEmail}>" . "\r\n";
		if( !empty( $replyName ) and !empty( $replyEmail ) ) {
			$headers .= "Reply-To: {$replyName} <{$replyEmail}>" . "\r\n";
		}
		if( !empty( $CCEmail ) ) {
			$headers .= "CC: {$CCEmail}" . "\r\n";
		}
		if( !empty( $BCCEmail ) ) {
			$headers .= "BCC: {$BCCEmail}" . "\r\n";
		}
		$headers .=	'X-Mailer: PHP/' . phpversion();
		
		wp_mail( $externals['emailAddress'], sprintf( __( 'Meeting Room Schedule for %s', 'book-a-room' ), date( 'l, F jS, Y', $timestamp ) ), $buffer, $headers );
		
		$emailError = __( 'Your email has been sent.', 'book-a-room' );
	}
} else {
	ob_end_flush();
}
?>
<div id="printForm">
	<form action="" method="post">
		<p><strong><?php _e( 'Send this in an email:', 'book-a-room' ); ?></strong><br/>
			<input name="emailAddress" type="text" id="emailAddress" value="<?php echo $defaultEmail; ?>" size="30"/>
			<input name="timestamp" type="hidden" id="timestamp" value="<?php echo date( 'm/d/Y', $timestamp ); ?>"/>
			<input name="action" type="hidden" id="act" value="sendEmail"/>
			<input type="submit" name="button2" id="button2" value="<?php _e( 'Submit', 'book-a-room' ); ?>"/>
			<br/>
			<div id="emailError"><?php echo $emailError; ?></div>
	</form>
</div>