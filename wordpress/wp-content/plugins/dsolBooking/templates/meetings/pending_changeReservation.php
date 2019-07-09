<script language="javascript">
	function checkSubmit() {
		var hourChecks = document.getElementsByName( 'hours[]' );
		var boxCount = 0;
		for ( var t = 0, checkLength = hourChecks.length; t < checkLength; t++ ) {
			if ( hourChecks[ t ].type == 'checkbox' and hourChecks[ t ].checked == true ) {
				boxCount++;
			}
		}

		if ( boxCount > 0 ) {
			document.forms[ "hoursForm" ].submit();
		} else {
			alert( "Error!\nYou haven't selected any times to reserve." );
		}
	}

	function checkHours( curChecked ) {
		/* are there only two checked boxes? */
		//alert();
		var hourChecks = document.getElementsByName( 'hours[]' );
		var boxArr = new Array();
		var boxCount = 0;
		var lastItem = false;

		// count total boxes checked
		for ( var t = 0, checkLength = hourChecks.length; t < checkLength; t++ ) {
			if ( hourChecks[ t ].type == 'checkbox' and hourChecks[ t ].checked == true ) {
				boxArr[ boxCount++ ] = t;
			}
		}

		// is this unchecking - clear under
		if ( hourChecks[ curChecked ].checked == false and curChecked < boxArr[ 0 ] ) {
			hourChecks[ curChecked ].checked = false;
		} else if ( hourChecks[ curChecked ].checked == false ) {
			for ( var t = curChecked, checkLength = hourChecks.length; t < checkLength; t++ ) {
				hourChecks[ t ].checked = false;
			}
			// is checked box higher? clear underneath (after first)
		} else if ( hourChecks[ curChecked ].checked == true and boxArr[ 1 ] > curChecked ) {
			var chkstat = true;
			for ( var t = curChecked, checkLength = hourChecks.length; t < checkLength; t++ ) {
				hourChecks[ t ].checked = chkstat;
				chkstat = false;

			}
			// are there multiple and this is the first? just uncheck it
		} else if ( boxArr.length > 1 ) {
			for ( var s = boxArr[ 0 ] + 1, e = boxArr[ boxArr.length - 1 ]; s < e; s++ ) {
				var curHour = document.getElementById( 'hours_' + s );

				if ( curHour.value == false ) {
					hourChecks[ curChecked ].checked = false;
					alert( "<?php _e( 'Error!\nI\m sorry, but there is already a reservation in the time you\'ve selected. Please make sure your reservation times don\'t overlap someone else\'s reservation.', 'book-a-room' ); ?>" );
					break;
				} else {
					hourChecks[ s ].checked = true;
				}
			}
		}
	}
</script>
<div class=wrap>
	<div id="icon-options-general" class="icon32"></div>
	<h2><?php _e( 'Book a Room - Change Reservation', 'book-a-room' ); ?></h2>
</div>
<?php
# Display Errors if there are any
if ( !empty( $errorMSG ) ) {
	?>
	<p>
		<h3 style="color: red;"><strong><?php echo $errorMSG; ?></strong></h3>
	</p>
	<?php
}
?>
<div class="changeRes_table">
	<div class="changeRes_row">
		<div class="changeRes_cellHeader"><?php _e( 'Branch', 'book-a-room' ); ?></div>
		<div class="changeRes_cellHeader"><?php _e( 'Room', 'book-a-room' ); ?></div>
	</div>
	<div class="changeRes_row">
		<div class="changeRes_cell">
			<?php
			if( count( $branchList ) == 0 ) {
				_e( 'There are no branches available.', 'book-a-room' );
			} else {
				if( empty( $branchID ) ) {
					$branchID = key( $branchList );
				}
				foreach( $branchList as $key => $val ) {
					# is selected branch?
					if( !empty( $branchID ) and $branchID == $key ) {
						?><p><strong><?php echo $val['branchDesc']; ?></strong></p><?php
					} else {
						if( empty( $roomContList['branch'][$val['branchID']] ) ) {
							?><p><em><?php printf( __( '%s - No rooms available' ), $val['branchDesc'] ); ?></em></p><?php
						} else {
			?><p><a href="?page=bookaroom_meetings&amp;action=changeReservation&amp;branchID=<?php echo $val['branchID']; ?>&amp;timestamp=<?php echo $timestamp; ?>"><?php echo $val['branchDesc']; ?></a>
			</p>
			<?php
						}
					}
			
				}
			}			
			?>
		</div>
		<div class="changeRes_cell">
			<?php
			if( empty( $roomContList['branch'][$branchID] ) ) {
				_e( 'There are no rooms available in this branch.', 'book-a-room' );
			} else {
				if( empty( $roomID ) ) {
					$roomID = current( $roomContList['branch'][$branchID] );
				}
				# cycle rooms
				foreach( $roomContList['branch'][$branchID] as $key => $val ) {
					# if container is empty, skip
					if( empty( $roomContList['id'][$val]['rooms'] ) ) {
						continue;
					}
					# is this the current room?
					if( $roomID == $val ) {
						?><p><strong><?php echo $roomContList['id'][$val]['desc']; ?></strong><br/><?php printf( __( 'Occupancy: %s', 'book-a-room' ), $roomContList['id'][$val]['occupancy'] ); ?></p><?php
					} else {
						# non-selected branch
						?><p><a href="?page=bookaroom_meetings&amp;action=changeReservation&amp;roomID=<?php echo $val; ?>&amp;timestamp=<?php echo $timestamp; ?>"><?php echo $roomContList['id'][$val]['desc']; ?></a><br/><?php printf( __( 'Occupancy: %s', 'book-a-room' ), $roomContList['id'][$val]['occupancy'] ); ?></p><?php						
					}
				}
			}
			?>
		</div>
	</div>
</div>
<div class="changeRes_table">
	<div class="changeRes_row">
		<div class="changeRes_cellCalHeader"><?php _e( 'Calendar', 'book-a-room' ); ?></div>
		<div class="changeRes_cellHoursHeader"><?php _e( 'Hours', 'book-a-room' ); ?></div>
	</div>
	<div class="changeRes_row">
		<div class="changeRes_cellCal">
			<div class="calNav">
				<div class="prevMonth"><a href="?page=bookaroom_meetings&action=changeReservation&timestamp=<?php echo $prevMonth; ?>&roomID=<?php echo $roomID; ?>&res_id=<?php echo $res_id; ?>">&lt;&nbsp;<?php echo _e( 'Prev', 'book-a-room' ); ?></a>
				</div>
				<div class="curMonth"><?php echo date( 'F', $thisMonth ); ?> <?php echo date( 'Y', $thisMonth ); ?></div>
				<div class="nextMonth"><a href="?page=bookaroom_meetings&action=changeReservation&timestamp=<?php echo $nextMonth; ?>&roomID=<?php echo $roomID; ?>&res_id=<?php echo $res_id; ?>"><?php echo _e( 'Next', 'book-a-room' ); ?>&nbsp;&gt;</a>
				</div>
			</div>
			<div id="calDisplay">
				<div class="calWeek">
					<div class="calCell">
						<div class="dayHeader"><?php _e( 'Su', 'book-a-room' ); ?></div>
					</div>
					<div class="calCell">
						<div class="dayHeader"><?php _e( 'Mo', 'book-a-room' ); ?></div>
					</div>
					<div class="calCell">
						<div class="dayHeader"><?php _e( 'Tu', 'book-a-room' ); ?></div>
					</div>
					<div class="calCell">
						<div class="dayHeader"><?php _e( 'We', 'book-a-room' ); ?></div>
					</div>
					<div class="calCell">
						<div class="dayHeader"><?php _e( 'Th', 'book-a-room' ); ?></div>
					</div>
					<div class="calCell">
						<div class="dayHeader"><?php _e( 'Fr', 'book-a-room' ); ?></div>
					</div>
					<div class="calCell">
						<div class="dayHeader"><?php _e( 'Sa', 'book-a-room' ); ?></div>
					</div>
				</div>
				<?php
				$curDay = 1;
				for( $week = 0; $week < $weeksInMonth; $week++ ) {
					$curTimeInfo = getdate( current_time( 'timestamp' ) );
				?>
				<div class="calWeek">
					<?php
					for( $day = 1; $day <= 7; $day++ ) {
						$thisTimeStamp = mktime(  0, 0, 0, $timestampInfo['mon'], $curDay, $timestampInfo['year'] );
						# No day/buffer day
						if( !empty( $dayOfWeek ) or ( $curDay > $daysInMonth ) ) {
							$dayOfWeek--;
					?>
					<div class="calCell">
						<div class="calNoDay">&nbsp;</div>
					</div>
					<?php
							continue;
						} 
						if( date( 'm-d-y', $timestamp ) == date( 'm-d-y', $thisTimeStamp ) ) {
						# is date selected?
						?>
					<div class="calCell">
						<div class="calSelectedDay"><a href="?page=bookaroom_meetings&action=changeReservation&timestamp=<?php echo $thisTimeStamp; ?>&roomID=<?php echo $roomID; ?>&res_id=<?php echo $res_id; ?>"><?php echo $curDay; ?></a></div>
					</div>
					<?php
							$curDay++;
							continue;
						}
						if( $thisTimeStamp == mktime( 0, 0, 0, $curTimeInfo['mon'], $curTimeInfo['mday'], $curTimeInfo['year'] ) ) {
					?>
					<div class="calCell">
						<div class="calToday"><a href="?page=bookaroom_meetings&action=changeReservation&timestamp=<?php echo $thisTimeStamp; ?>&roomID=<?php echo $roomID; ?>&res_id=<?php echo $res_id; ?>"><?php echo $curDay; ?></a></div>
					</div>
					<?php
							$curDay++;
							continue;
						}						
					?>
					<div class="calCell">
						<div class="calContent"><a href="?page=bookaroom_meetings&action=changeReservation&timestamp=<?php echo $thisTimeStamp; ?>&roomID=<?php echo $roomID; ?>&res_id=<?php echo $res_id; ?>"><?php echo $curDay; ?></a></div>
					</div>
					<?php
						$curDay++;

					}
					?>
				</div>
				<?php
				}
				$serverURI = parse_url( $_SERVER['REQUEST_URI'] );
				$permStruc = get_option( 'permalink_structure' );
				if( empty( $permStruc ) ) {
					# if no permalink structure, compare ID
					parse_str( $serverURI['query'], $pageInfo );
					$page_action		= null;
				} else {
					$page_action		= rtrim( $serverURI['path'], '/' );
				}				
				?>
			</div>
			<div class="calNav">
				<div id="calFormCont">
					<form action="<?php echo $page_action; ?>" method="get" id="calForm">
						<div class="calContainAdmin">
							<select name="calMonth" id="calMonth">
								<?php
								for( $month = 1; $month <= 12; $month++ ) {
									$selected = ( $timestampInfo['mon'] == $month) ? ' selected="selected"' : null;
								?>
								<option value="<?php echo $month; ?>"<?php echo $selected; ?>><?php echo date( 'F', mktime( 0, 0, 0, $month, 1, $timestampInfo['year']) ); ?></option>
								<?php
								}
								?>
							</select>
						</div>
						<div class="calContainAdmin">
							<select name="calYear" id="calYear">
								<?php
								$curInfo = getdate( current_time('timestamp') );
								for( $year = $curInfo['year'] - 1; $year < $curInfo['year'] + 3; $year++ ) {
									$selected = ( $timestampInfo['year'] == $year ) ? ' selected="selected"' : null;
								?>
								<option value="<?php echo $year; ?>"<?php echo $selected; ?>><?php echo $year; ?></option>
								<?php
								}								
								?>
							</select>
						</div>
						<div class="calContainAdmin">
							<input name="roomID" type="hidden" value="<?php echo $roomID; ?>"/>
							<input name="page" type="hidden" value="bookaroom_meetings"/>
							<input name="action" type="hidden" value="changeReservation"/>
							<input type="submit" name="submitCal" id="submitCal" value="<?php _e( 'Go', 'book-a-room' ); ?>"/>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="changeRes_cellHours">
			<form action="" method="post" id="hoursForm">
				<table id="hoursTable" class="hoursTableClean">
					<tr class="calHours">
						<td><?php _e( 'Select', 'book-a-room' ); ?></td>
						<td class="calTime"><?php _e( 'Time', 'book-a-room' ); ?></td>
						<td><?php _e( 'Status', 'book-a-room' ); ?></td>
					</tr>
					<?php
					$closings = bookaroom_public::getClosings( $roomID, $timestamp, $roomContList );
					
					if( empty( $roomContList['branch'][$branchID] ) ) {
						# no hours 
						?>
					<tr class="calHours hoursTableClean">
         				<td class="calCheckBox">&nbsp;</td>
         				<td class="calTime"><?php _e( 'There are no rooms available to request at this branch.', 'book-a-room' ); ?></td>
         				<td class="calStatus">&nbsp;</td>
         			</tr>
				<?php
					} elseif( $closings !== false ) {
						?>
					<tr class="calHours">
						<td colspan="3"><?php _e( 'This room is closed today.', 'book-a-room' ); ?>
						</td>
					</tr>
					<?php
					} else {
						$timeInfo = getdate( $timestamp );
						$baseIncrement = get_option( 'bookaroom_baseIncrement' );
						$dayOfWeek = date( 'w', $timestamp );
						$openTime = strtotime( date( 'Y-m-d '.$branchList[$branchID][ 'branchOpen_'.$dayOfWeek], $timestamp ) );
						$closeTime = strtotime( date( 'Y-m-d '.$branchList[$branchID]['branchClose_'.$dayOfWeek], $timestamp ) );
						$increments = ( ( $closeTime - $openTime ) / 60 ) / $baseIncrement;
						$cleanupIncrements = get_option( 'bookaroom_cleanupIncrement' );
						$setupIncrements = get_option( 'bookaroom_setupIncrement' );
						$reservations = bookaroom_public::getReservations( $roomID, $timestamp, NULL, $res_id );
						
						for( $i = 0; $i < $increments; $i++) {							
							$curStart = $openTime + (  $baseIncrement * 60 * $i);
							$curEnd = $openTime + (  $baseIncrement * 60 * ($i+1) );
							$incrementList[$i]['checked'] = ( @in_array( $curStart, $hours ) ) ? ' checked="checked"' : null;
							
							if( $i + $cleanupIncrements >= $increments ) {
								$incrementList[$i]['type'] = 'last';				
							} else {
								if( empty( $reservations ) ) {
									$incrementList[$i]['type'] = 'regular';
								} else {
									foreach( $reservations as $resKey => $resVal ) {
										$resVal['timestampStart'] = strtotime( $resVal['ti_startTime'] );
										$resVal['timestampEnd'] = strtotime( $resVal['ti_endTime'] );
										
										# check if increment time is equal to or after start and before end
										if( $curStart >= $resVal['timestampStart'] and $curEnd <= $resVal['timestampEnd'] and $res_id !== $resVal['res_id'] ) {
											$incrementList[$i]['type'] = 'reserved';
											$incrementList[$i]['desc'] =  ( !empty( $resVal['ev_title'] ) ) ? $resVal['ev_title'] : $resVal['me_eventName'];
											if( $curStart == $resVal['timestampStart'] ) {
												# setup time
												for( $s = $i-1; $s > ( $i-1-$setupIncrements ); $s--) {
													if( !empty( $incrementList[$s]['type'] ) and $incrementList[$s]['type'] !== 'reserved' ) {
														$incrementList[$s]['type'] = 'setup';
													}
												}
											}
											#cleanup time
											$count = 1;
											if( $curEnd == $resVal['timestampEnd'] ) {
												for( $s = $i+1; $s < ( $i+1+$cleanupIncrements ); $s++) {
													if( $count++ > 20 ) {
														die();
													}
													$incrementList[$s]['type'] = 'setup';
												}
											}
										} else {
											if( empty( $incrementList[$i]['type'] ) ) {
												$incrementList[$i]['type'] = 'regular';
											}
										}
									}
								}
							}
						}
						
						for( $i = 0; $i < $increments; $i++) {
							$curStart = $openTime + (  $baseIncrement * 60 * $i);
							$curEnd = $openTime + (  $baseIncrement * 60 * ($i+1) );
							if( $incrementList[$i]['type'] == 'setup' ) {
					?>
					<tr class="calHoursSetup" style="background: <?php echo get_option( 'bookaroom_setupColor' ); ?>; color: <?php echo get_option( 'bookaroom_setupFont' ); ?>">
						<td><input id="hours_<?php echo $i; ?>" name="hours[]" type="hidden" value="" onchange="checkHours()"/>
						</td>
						<td class="calTime"><?php echo date( 'g:i a', $curStart ) .' - '. date( 'g:i a', $curEnd ); ?></td>
						<td>&nbsp;</td>
					</tr>
					<?php
							} elseif( $incrementList[$i]['type'] == 'reserved' ) {
					?>
					<tr class="calHoursReserved" style="background: <?php echo get_option( 'bookaroom_reservedColor' ); ?>; color: <?php echo get_option( 'bookaroom_reservedFont' ); ?>">
						<td><input id="hours_<?php echo $i; ?>" name="hours[]" type="hidden" value="" onchange="checkHours()"/>
						</td>
						<td class="calTime"><?php echo date( 'g:i a', $curStart ) .' - '. date( 'g:i a', $curEnd ); ?></td>
						<td><?php echo $incrementList[$i]['desc']; ?></td>
					</tr>
					<?php
							} elseif( $incrementList[$i]['type'] == 'regular' ) {
					?>
					<tr class="calHours">
						<td><label for="hours_<?php echo $i; ?>"><input id="hours_<?php echo $i; ?>" name="hours[]" type="checkbox" value="<?php echo $curStart; ?>" onchange="checkHours('<?php echo $i; ?>')"<?php echo $incrementList[$i]['checked']; ?> /></label>
						</td>
						<td class="calTime"><label for="hours_<?php echo $i; ?>"><?php echo date( 'g:i a', $curStart ) .' - '. date( 'g:i a', $curEnd ); ?></label>
						</td>
						<td><label for="hours_<?php echo $i; ?>"><?php _e( 'Open', 'book-a-room' ); ?></label>
						</td>
					</tr>
					<?php
							} elseif( $incrementList[$i]['type'] == 'last' ) {

								?>
				  <tr class="calHoursReserved">
					<td><input id="hours_<?php echo $i; ?>" name="hours[]" type="hidden" value="" onchange="checkHours()" /></td>
					<td class="calTime"><?php echo date( 'g:i a', $curStart ) .' - '. date( 'g:i a', $curEnd ); ?></td>
					<td>&nbsp;</td>
				  </tr>
	          		<?php
							}
						}
					}
					?>
				</table>
				<?php
				if( $closings == false ) {
					?>
					<input name="roomID" type="hidden" id="roomID" value="<?php echo $roomID; ?>"/> <input name="timestamp" type="hidden" id="timestamp" value="<?php echo $timestamp; ?>"/><input name="action" type="hidden" id="action" value="changeReservation"/><input type="submit" name="button" id="button" value="<?php _e( 'Submit', 'book-a-room' ); ?>"/>
					<?php
				}
				?>
			</form>
		</div>
	</div>
</div>