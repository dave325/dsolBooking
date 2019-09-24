<script type="text/javascript">
  jQuery( function() {
    var getRemanningDays = function() {
                        var date = new Date();
                        var time = new Date(date.getTime());
                        time.setMonth(date.getMonth() + 1);
                        time.setDate(0);
                        var days =time.getDate() > date.getDate() ? time.getDate() - date.getDate(): 0;
                        return days;
                }
                
    jQuery( "#datepicker" ).datepicker({
      numberOfMonths: 1,
      showButtonPanel: true,
      minDate:+0,
      maxDate:+getRemanningDays(),
      onSelect:function(){
        //var currentDate = this.datepicker( "getDate" );
        let time = new Date(jQuery(this).val()).getTime() / 1000;
        
        jQuery('#a-tag').attr('href', jQuery('#a-tag').attr('href') + "&timestamp="+ time);
        jQuery('#a-tag').html("Go");
      }
      
    });
    
  } );
  jQuery(document).ready(function(){
    jQuery('#resetHours').click(function(e){
    e.preventDefault();
    jQuery('#topSubmit').children('div').children('span').empty();
    document.forms[ "hoursForm" ].reset();
});
  });
  function checkSubmit() {
        var hourChecks = document.getElementsByName( 'hours[]' );

        var boxCount = 0;

        for ( var t = 0, checkLength = hourChecks.length; t < checkLength; t++ ) {
            if ( (hourChecks[ t ].type == 'checkbox') && (hourChecks[ t ].checked == true) ) {
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
        var boxArr = [];
        var boxCount = 0;
        var lastItem = false;
        // count total boxes checked
        for ( var t = 0, checkLength = hourChecks.length; t < checkLength; t++ ) {
            if ( (hourChecks[ t ].type == 'checkbox') && hourChecks[ t ].checked == true ) {
                boxArr[ boxCount++ ] = t;
            }
        }

        // is this unchecking - clear under
        if ( hourChecks[ curChecked ].checked == false && curChecked < boxArr[ 0 ] ) {
            hourChecks[ curChecked ].checked = false;
        } else if ( hourChecks[ curChecked ].checked == false ) {
            for ( var t = curChecked, checkLength = hourChecks.length; t < checkLength; t++ ) {
                // Check if box array contains still contains the invalid checkmarks and remove them
                if(boxArr.indexOf(t) > -1){
                    boxArr.splice(boxArr.indexOf(t),1);
                }
                hourChecks[ t ].checked = false;
            }
            // is checked box higher? clear underneath (after first)
        } else if ( hourChecks[ curChecked ].checked == true && boxArr[ 1 ] > curChecked ) {
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
                    alert( "Error!\nI'm sorry, but there is already a reservation in the time you've selected. Please make sure your reservation times don't overlap someone else's reservation." );
                    break;
                } else {
                    hourChecks[ s ].checked = true;
                }
            }
        }
        // If box has more than one item in it display the time 
        if(boxArr.length > 1){
            let start = jQuery('#hours_' +[boxArr[0]]).parent().parent().siblings('.calTime').children().text().trim().split('-')[0];
            let end = jQuery('#hours_' +[boxArr[boxArr.length - 1]]).parent().parent().siblings('.calTime').children().text().trim().split('-')[1];
            jQuery('#topSubmit').children('div').children('span').text('Time: ' + start + ' - ' + end );
        }else if (boxArr.length == 1){
            let start = jQuery('#hours_' +[boxArr[0]]).parent().parent().siblings('.calTime').children().text().trim().split('-')[0];
            let end = jQuery('#hours_' +[boxArr[0]]).parent().parent().siblings('.calTime').children().text().trim().split('-')[1];
            jQuery('#topSubmit').children('div').children('span').text('Time: ' + start + ' - ' + end );
        }
    }
</script>
<?php
# get reservations
if(isset($_GET['timestamp'])){
    $timestamp = $_GET['timestamp'];
}else{
    $timestamp = current_time('timestamp');
}
?>
<div id="_main_container">
    <div id="topRow">
        <div class="col">
            <div class="instructions">
               
            <span class="header">
                    <?php _e('Room List', 'book-a-room');?>
                </span>
            </div>
            <div class="options">
                <?php
if (empty($roomContList['branch'][$branchID])) {
    ?>
                <div class="normalItem">
                    <?php _e('There are no rooms available in this branch.', 'book-a-room');?>
                </div>
                <?php
} else {
    foreach ($roomContList['branch'][$branchID] as $key => $val) {
        if (count($branchList) == 0) {
            ?>
                <div class="normalItem">
                    <?php _e('There are no branches or rooms available.', 'book-a-room');?>
                </div>
                <?php
} else {
            # is this the current room?
            if ($roomID == $val) {
                ?>
                <div class="itemCont">
                    <div class="selectedItem">
                        <?php echo $roomContList['id'][$val]['desc']; ?>
                    </div>
                    <div class="itemDesc">
                        <?php printf(__('Occupancy: %s', 'book-a-room'), $roomContList['id'][$val]['occupancy']);?>
                    </div>
                </div>
                <?php
} else {
                ?>
                <div class="itemCont">
                    <div class="normalItem">
                        <a href="<?php echo CHUH_BaR_Main_permalinkFix(); ?>action=reserve&amp;roomID=<?php echo $val; ?>&amp;timestamp=<?php echo $timestamp; ?>">
                            <?php echo $roomContList['id'][$val]['desc']; ?>
                        </a>
                    </div>
                    <div class="itemDesc">
                        <?php printf(__('Occupancy: %s', 'book-a-room'), $roomContList['id'][$val]['occupancy']);?>
                    </div>
                </div>
                <?php
}
        }
    }
}
?>
            </div>
        </div>
        <div class="col">
            <div class="instructions">
                <span class="header">
                    <?php _e('Choose Date', 'book-a-room');?>
                </span>
            </div>
            <p>Select a date</p>
            <?php  if(isset($_GET['timestamp'])){ ?>
            <input type="text" id="datepicker" value="<?php echo $newDate = date("m/d/y",$_GET['timestamp']) ?>">
            <?php }else{ ?>
                <input type="text" id="datepicker" value="<?php echo $newDate = date("m/d/y",current_time('timestamp'))?>">
            <?php } ?>
            <a href="<?php echo CHUH_BaR_Main_permalinkFix(); ?>action=reserve&amp;roomID=<?php echo $roomID; ?>" id="a-tag"></a>
             </div>
</div>
    <?php
$reserveBuffer = get_option('_reserveBuffer');
$allowedBuffer = get_option('_reserveAllowed');
# get reservations
if(isset($_GET['timestamp'])){
    $timestamp = $_GET['timestamp'];
}else{
    $timestamp = current_time('timestamp');
}
?>

    <form action="<?php echo makeLink_correctPermaLink(get_option('_reservation_URL')); ?>action=reserve" method="post" id="hoursForm">
       
                    <div id="topSubmit">  
                        <div>
                            <p>Day: <?php echo date('m/d/y',$timestamp);?></p>
                             <span>Time:</span>
                            
                        </div>

                        <input type="submit" name="submitHours" id="submitHours" value="<?php _e('Submit', 'book-a-room');?>" onclick="checkSubmit(); return false;"/>
                        <input type="reset" name="Reset" id="resetHours" value="<?php _e('Clear', 'book-a-room');?>"/>
            </div>
            <?php
$dayOfWeek = date_i18n('w', $timestamp);
$baseIncrement = get_option('_baseIncrement');
$cleanupIncrements = get_option('_cleanupIncrement');
$closeTime = strtotime(date_i18n('Y-m-d ' . $branchList[$branchID]["branchClose_{$dayOfWeek}"], $timestamp));
$closings = self::getClosings($roomID, $timestamp, $roomContList);
$openTime = strtotime(date_i18n('Y-m-d ' . $branchList[$branchID]["branchOpen_{$dayOfWeek}"], $timestamp));
$reservations = self::getReservations($roomID, $timestamp);
$setupIncrements = get_option('_setupIncrement');
if(empty($setupIncrements)){
    $setupIncrements = 0;
}
$timeInfo = getdate($timestamp);
$incrementList = array();
$increments = (($closeTime - $openTime) / 60) / $baseIncrement;

if (empty($roomContList['branch'][$branchID])) {
    # No rooms
    ?>
            <div class="col2">
                <div class="options">
                    <table id="hoursTable">
                        <tr class="calHours">
                            <td class="calCheckBox">
                                <?php _e('Select', 'book-a-room');?>
                            </td>
                            <td class="calTime">
                                <?php _e('Time', 'book-a-room');?>
                            </td>
                            <td class="calStatus">
                                <?php _e('Status', 'book-a-room');?>
                            </td>
                        </tr>
                        <tr class="calHours">
                            <td class="calCheckBox">&nbsp;</td>
                            <td class="calTime">
                                <?php _e('There are no rooms available to request at this branch.', 'book-a-room');?>
                            </td>
                            <td class="calStatus">&nbsp;</td>
                        </tr>
                    </table>
                </div>
            </div>
            <?php
} elseif (empty($branchList[$branchID]["branchOpen_{$dayOfWeek}"]) or empty($branchList[$branchID]["branchClose_{$dayOfWeek}"])) {
    # Room closed
    ?>
            <div class="col2">
                <div class="options">
                    <table id="hoursTable">
                        <tr class="calHours">
                            <td class="calCheckBox">Select </td>
                            <td class="calTime">Time</td>
                            <td class="calStatus">Status</td>
                        </tr>
                        <tr class="calHours">
                            <td class="calCheckBox">&nbsp;</td>
                            <td class="calTime">This branch isn't open today.</td>
                            <td class="calStatus">&nbsp;</td>
                        </tr>
                    </table>
                </div>
            </div>
            <?php
} else {
    # regular day
    ?>
            <div class="col2">
                <div class="options">
                    <table id="hoursTable">
                        <tr class="calHours">
                            <td class="calCheckBox">
                                <?php _e('Select', 'book-a-room');?>
                            </td>
                            <td class="calTime">
                                <?php _e('Time', 'book-a-room');?>
                            </td>
                            <td class="calStatus">
                                <?php _e('Status', 'book-a-room');?>
                            </td>
                        </tr>
                        <?php
$count = 1;
    for ($i = 0; $i < $increments; $i++) {
        #if( $count++ > 50) wp_die( 'loop' );
        # find increment offset  from start

        $curStart = $openTime + ($baseIncrement * 60 * $i);
        $curEnd = $openTime + ($baseIncrement * 60 * ($i + 1));

        #convert the curStart and current time to minutes
        $curStartHour = date('h', $curStart) * 60;
        $curTimeHour = date('h', current_time('timestamp')) * 60;

        if (date('d',$timestamp) === date('d', current_time('timestamp'))){
            $isSameDate = TRUE;
        }else{
            $isSameDate = FALSE;
        }
        $curStartMin = date('i', $curStart) + $curStartHour;
        $curTimeMin = date('i', current_time('timestamp')) + $curTimeHour;
        
        #subtract current time from curStart. go to line 544
        $reservation_constraint = abs($curStartMin - $curTimeMin);
        # last line?
        if ($i + $cleanupIncrements >= $increments) {
            $incrementList[$i]['type'] = 'last';
        } else {
            if (empty($reservations)) {
                
                if ($curStart < current_time('timestamp')) {
                    $incrementList[$i]['type'] = 'unavailable';
                } else if ($isSameDate and $reservation_constraint < 30) {
                    $incrementList[$i]['type'] = 'unavailable';
                } else {
                    $incrementList[$i]['type'] = 'regular';
                }
            } else {
                foreach ($reservations as $resKey => $resVal) {
                    $resVal['timestampStart'] = strtotime($resVal['ti_startTime']);
                    $resVal['timestampEnd'] = strtotime($resVal['ti_endTime']);
                    # check if increment time is equal to or after start and before end
                    if ($curStart >= $resVal['timestampStart'] and $curEnd <= $resVal['timestampEnd']) {
                        $incrementList[$i]['type'] = 'reserved';
                        # show by type
                        if ($resVal['ti_type'] == 'event') {
                            $incrementList[$i]['desc'] = $resVal['ev_title'];
                        } else {
                            $incrementList[$i]['desc'] = $resVal['me_eventName'];
                        }
                        if ($curStart == $resVal['timestampStart']) {
                            # This adds unavailable slots before each reservation if there are setup increments
                            if ((int) $cleanupIncrements !== 0) {
                                $incrementList[$i - 1]['type'] = 'unavailable';
                            }
                            # setup time
                            for ($s = $i - 1; $s > ($i - 1 - $setupIncrements); $s--) {
                                if (!empty($incrementList[$s]['type']) and $incrementList[$s]['type'] !== 'reserved') {
                                    $incrementList[$s]['type'] = 'setup';
                                }
                            }
                        }
                        #cleanup time
                        if ($curEnd == $resVal['timestampEnd']) {
                            for ($s = $i + 1; $s < ($i + 1 + $cleanupIncrements); $s++) {
                                $incrementList[$s]['type'] = 'setup';
                            }
                        }
                    } else {
                        $validStart = strtotime(date_i18n('Y-m-d')) + (get_option('_reserveBuffer') * 24 * 60 * 60);
                        $validEnd = $validStart + (get_option('_reserveAllowed') * 24 * 60 * 60); #reme();
                        $startTime = strtotime($resVal['ti_startTime']);
                        $endTime = strtotime($resVal['ti_endTime']);
                        
 
                        #subtract current time from curStart. go to line 544
    
                        $admin = current_user_can('activate_plugins');
                        if ($curStart < current_time('timestamp') || (($startTime <= $validStart || $endTime <= $validStart) && empty($res_id) && $admin == false) || (($startTime >= $validEnd || $endTime >= $validEnd) && empty($res_id) && $admin == false)) {

                            $incrementList[$i]['type'] = 'unavailable';
                        }
                        if (empty($incrementList[$i]['type'])) {
                            $incrementList[$i]['type'] = 'regular';
                        }
                    }
                }
            }
        }
    }
    for ($i = 0; $i < $increments; $i++) {
        $curStart = $openTime + ($baseIncrement * 60 * $i);
        $curEnd = $openTime + ($baseIncrement * 60 * ($i + 1));

        if ($curEnd > $closeTime) {
            $curEnd = $closeTime;
        }
        if ($closings !== false) {
            #Closed
            ?>
                        <tr class="calHoursReserved">
                            <td class="calCheckBox"><input id="hours_<?php echo $i; ?>" name="hours[]" type="hidden" value="" onchange="checkHours()" disabled="disabled"/>
                            </td>
                            <td class="calTime">
                                <?php echo date_i18n('g:i a', $curStart) . ' - ' . date_i18n('g:i a', $curEnd); ?>
                            </td>
                            <td class="calStatus">
                                <?php _e('Closed', 'book-a-room');?>
                            </td>
                        </tr>
                        <?php
continue;
        } else {
            switch ($incrementList[$i]['type']) {
                case 'setup':
                    # Setup
                    ?>
                        <tr class="calHoursSetup" style="background: <?php echo get_option('_setupColor'); ?>; color: <?php echo get_option('_setupFont'); ?>">
                            <td class="calCheckBox"><input id="hours_<?php echo $i; ?>" name="hours[]" type="hidden" value="" onchange="checkHours()"/>
                            </td>
                            <td class="calTime">
                                <?php echo date_i18n('g:i a', $curStart) . ' - ' . date_i18n('g:i a', $curEnd); ?>
                            </td>
                            <td class="calStatus">&nbsp;</td>
                        </tr>
                        <?php
break;

                case 'reserved':
                    # Reserved
                    ?>
                        <tr class="calHoursReserved" style="background: <?php echo get_option('_reservedColor'); ?>; color: <?php echo get_option('_reservedFont'); ?>">
                            <td class="calCheckBox"><input id="hours_<?php echo $i; ?>" name="hours[]" type="hidden" value="" onchange="checkHours()"/>
                            </td>
                            <td class="calTime">
                                <?php echo date_i18n('g:i a', $curStart) . ' - ' . date_i18n('g:i a', $curEnd); ?>
                            </td>
                            <td class="calStatus">
                                <?php echo htmlspecialchars_decode($incrementList[$i]['desc']); ?>
                            </td>
                        </tr>
                        <?php
break;

                case 'last':
                    # Last line
                    ?>
                        <tr class="calHoursReserved">
                            <td class="calCheckBox"><input id="hours_<?php echo $i; ?>" name="hours[]" type="hidden" value="" onchange="checkHours()"/>
                            </td>
                            <td class="calTime">
                                <?php echo date_i18n('g:i a', $curStart) . ' - ' . date_i18n('g:i a', $curEnd); ?>
                            </td>
                            <td class="calStatus">&nbsp;</td>
                        </tr>
                        <?php
break;

                case 'unavailable':
                    # Unavailable
                    ?>
                        <tr class="calHoursReserved">
                            <td class="calCheckBox"><input id="hours_<?php echo $i; ?>" name="hours[]" type="hidden" value="" onchange="checkHours()" disabled="disabled"/>
                            </td>
                            <td class="calTime">
                                <?php echo date_i18n('g:i a', $curStart) . ' - ' . date_i18n('g:i a', $curEnd); ?>
                            </td>
                            <td class="calStatus">
                                <?php _e('Unavailable*', 'book-a-room');?>
                            </td>
                        </tr>
                        <?php
break;

                case 'regular':
                default:
                    # Regular
                    ?>
                        <tr class="calHours">
                            <td class="calCheckBox"><label for="hours_<?php echo $i; ?>">
					  				<input id="hours_<?php echo $i; ?>" name="hours[]" type="checkbox" value="<?php echo $curStart; ?>" onchange="checkHours('<?php echo $i; ?>')" /></label>

                            </td>
                            <td class="calTime">
                                <label for="hours_<?php echo $i; ?>">
                                    <?php echo date_i18n('g:i a', $curStart) . ' - ' . date_i18n('g:i a', $curEnd); ?>
                                </label>
                            </td>
                            <td class="calStatus">
                                <label for="hours_<?php echo $i; ?>">
                                    <?php _e('Open', 'book-a-room');?>
                                </label>
                            </td>
                        </tr>
                        <?php
break;
            }
        }
    }
    ?>
                    </table>
                </div>
            </div>
            <div id="botSubmit">
                <div class="col1">
                    <div class="instructions">
                        <span class="header">
                            <?php _e('Step 4. continued', 'book-a-room');?>
                        </span>
                        <p>
                            <em>
                                <?php _e('Choose the hours you would like to reserve.', 'book-a-room');?>
                            </em>
                        </p>
                    </div>
                    <div class="options">
                        <p>
                            <input name="action" type="hidden" id="action" value="fillForm"/>
                            <input name="roomID" type="hidden" id="roomID" value="<?php echo $roomID; ?>"/>
                            <input type="submit" name="submitHours" id="submitHours" value="<?php _e('Click here when you are finished', 'book-a-room');?>" onclick="checkSubmit(); return false;"/>
                            <br/>
                            <input type="reset" name="Reset" id="resetHours" value="<?php _e('Clear the form', 'book-a-room');?>"/>
                        </p>
                    </div>
                </div>
            </div>
            <?php
}
?>
        </div>
    </form>
</div>