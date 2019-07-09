<link rel="stylesheet" href="<?php echo plugins_url(); ?>/book-a-room/scripts/zebra-dialog/css/default/zebra_dialog.css" type="text/css">
<script>
    jQuery(document).ready(function() {
        jQuery('#warn').hide();
        jQuery('#test').click(function() {
            jQuery('#collapseExample').slideToggle();
        });
        jQuery('#test1').click(function() {
            jQuery('#collapseExample1').slideToggle();
            jQuery('#warn').show();
        });
    });
    jQuery(function() {
        var getRemanningDays = function() {
            var date = new Date();
            var time = new Date(date.getTime());
            time.setMonth(date.getMonth() + 1);
            time.setDate(0);
            var days = time.getDate() > date.getDate() ? time.getDate() - date.getDate() : 0;
            return days;
        }

        jQuery("#datepicker").datepicker({
            numberOfMonths: 1,
            showButtonPanel: true,
            minDate: +0,
            maxDate: +getRemanningDays(),
            onSelect: function() {
                //var currentDate = this.datepicker( "getDate" );
                let time = new Date(jQuery(this).val()).getTime() / 1000;

                jQuery('#a-tag').attr('href', jQuery('#a-tag').attr('href') + "&timestamp=" + time);
                jQuery('#a-tag').html("Go");
            }

        });

    });
    jQuery(document).ready(function() {
        jQuery('#resetHours').click(function(e) {
            e.preventDefault();
            jQuery('#topSubmit').children('div').children('span').empty();
            document.forms["hoursForm"].reset();
        });
    });

    function checkSubmit() {
        var hourChecks = document.getElementsByName('hours[]');

        var boxCount = 0;

        for (var t = 0, checkLength = hourChecks.length; t < checkLength; t++) {
            if ((hourChecks[t].type == 'checkbox') && (hourChecks[t].checked == true)) {
                boxCount++;
            }
        }

        if (boxCount > 0) {
            document.forms["hoursForm"].submit();
        } else {
            alert("Error!\nYou haven't selected any times to reserve.");
        }
    }



    function checkHours(curChecked) {
        /* are there only two checked boxes? */
        //alert();
        var hourChecks = document.getElementsByName('hours[]');
        var boxArr = [];
        var boxCount = 0;
        var lastItem = false;
        // count total boxes checked
        for (var t = 0, checkLength = hourChecks.length; t < checkLength; t++) {
            if ((hourChecks[t].type == 'checkbox') && hourChecks[t].checked == true) {
                boxArr[boxCount++] = t;
            }
        }

        // is this unchecking - clear under
        if (hourChecks[curChecked].checked == false && curChecked < boxArr[0]) {
            hourChecks[curChecked].checked = false;
        } else if (hourChecks[curChecked].checked == false) {
            for (var t = curChecked, checkLength = hourChecks.length; t < checkLength; t++) {
                // Check if box array contains still contains the invalid checkmarks and remove them
                if (boxArr.indexOf(t) > -1) {
                    boxArr.splice(boxArr.indexOf(t), 1);
                }
                hourChecks[t].checked = false;
            }
            // is checked box higher? clear underneath (after first)
        } else if (hourChecks[curChecked].checked == true && boxArr[1] > curChecked) {
            var chkstat = true;
            for (var t = curChecked, checkLength = hourChecks.length; t < checkLength; t++) {
                hourChecks[t].checked = chkstat;
                chkstat = false;

            }
            // are there multiple and this is the first? just uncheck it
        } else if (boxArr.length > 1) {
            for (var s = boxArr[0] + 1, e = boxArr[boxArr.length - 1]; s < e; s++) {
                var curHour = document.getElementById('hours_' + s);

                if (curHour.value == false) {
                    hourChecks[curChecked].checked = false;
                    alert("Error!\nI'm sorry, but there is already a reservation in the time you've selected. Please make sure your reservation times don't overlap someone else's reservation.");
                    break;
                } else {
                    hourChecks[s].checked = true;
                }
            }
        }
        // If box has more than one item in it display the time 
        if (boxArr.length > 1) {
            let start = jQuery('#hours_' + [boxArr[0]]).parent().parent().siblings('.calTime').children().text().trim().split('-')[0];
            let end = jQuery('#hours_' + [boxArr[boxArr.length - 1]]).parent().parent().siblings('.calTime').children().text().trim().split('-')[1];
            jQuery('#topSubmit').children('div').children('span').text('Time: ' + start + ' - ' + end);
        } else if (boxArr.length == 1) {
            let start = jQuery('#hours_' + [boxArr[0]]).parent().parent().siblings('.calTime').children().text().trim().split('-')[0];
            let end = jQuery('#hours_' + [boxArr[0]]).parent().parent().siblings('.calTime').children().text().trim().split('-')[1];
            jQuery('#topSubmit').children('div').children('span').text('Time: ' + start + ' - ' + end);
        }
    }
</script>
<div id="dsol-book-room" class="container-fluid" ng-app="wp">
    <div id="view-container" ng-view autoscroll="true"></div>
</div>

<script type="text/javascript">
    //angular.element(document.getElementsByTagName('head')).append(angular.element('<base href="' + window.location.pathname + '" /!#>'));
    angular.element(document.getElementsByTagName('head')).append(angular.element('<base href="' + window.location.pathname + '" />'));
</script> 