<div id="_main_container">
    <form name="form1" method="post">
        <div id="topRow">
            <div class="col">
                <div class="instructionsSmooth">
                <?php
                                $user = wp_get_current_user();
                            ?>
                    <span class="header">
                        <?php _e( 'Step 6.', 'book-a-room' ); ?>
                    </span>
                    <p>
                        <em>
                            <?php _e( 'Complete the registration form.', 'book-a-room' ); ?><br />
                            <?php _e( 'Reservation for ' . $user->data->display_name, 'book-a-room' ); ?>
                        </em>
                    </p>
                    <p><em><strong><?php _e( 'Items marked with an asterisk* are required fields.', 'book-a-room' ); ?></strong></em>
                    </p>
                    <?php
                    # Display Errors if there are any
                    if ( !empty( $errorArr[ 'errorMSG' ] ) ) {
                        ?>
                    <p>
                        <h3 style="color: red;"><strong><?php echo implode( "<br>", $errorArr['errorMSG'] ); ?></strong></h3>
                    </p>
                    <?php
                    }
                    ?>
                </div>
                <div class="options">
                    <div id="formRow">
                        <div class="wideCol">
                            <div class="question">
                                <?php _e( 'Branch and Room', 'book-a-room' ); ?>
                            </div>
                            <div class="formInput">
                                <strong>
                                    5
                                    <?php echo $branchList[$roomContList['id'][$roomContID]['branchID']]['branchDesc']; ?>
                                </strong><br/>
                                <em>
                                    <?php echo $roomContList['id'][$roomContID]['desc']; ?>
                                </em>
                            </div>
                        </div>
                        <div class="wideCol">
                            <div class="question">
                                <?php _e( 'Date', 'book-a-room' ); ?>
                            </div>
                            <div class="formInput">
                                <?php echo date_i18n( 'l, F jS, Y', $externals['startTime'] ); ?>
                            </div>
                        </div>
                        <div class="wideCol">
                            <div class="question">
                                <?php _e( 'Requested times', 'book-a-room' ); ?>
                            </div>
                            <div class="formInput">
                                <strong>
                                    <?php echo date_i18n( 'g:i a', $externals['startTime'] ); ?>
                                </strong> -
                                <strong>
                                    <?php echo date_i18n( 'g:i a', $externals['endTime'] ); ?>
                                </strong>
                            </div>
                        </div>
                        <div class="wideCol">
                            <div class="question">
                                <label for="numAtend">
                                    <?php _e( 'Number of attendees', 'book-a-room' ); ?> *</label>
                            </div>
                            <div class="formInput">
                                <input<?php if( !empty( $errorArr[ 'classes'][ 'numAttend'] ) ) echo ' class="error"'; ?> name="numAttend" type="number" id="numAttend" value="<?php echo $externals['numAttend']; ?>" size="3" maxlength="3"/>
                            </div>
                        </div>
                        <div class="wideCol">
                            <div class="question">
                                <label for="desc">
                                    <?php _e( 'Purpose of meeting', 'book-a-room' ); ?> *</label>
                            </div>
                            <div class="formInput">
                                <textarea<?php if( !empty( $errorArr[ 'classes'][ 'desc'] ) ) echo ' class="error"'; ?> name="desc" rows="3" id="desc"><?php echo htmlspecialchars_decode( $externals['desc'] ); ?></textarea>
                            </div>
                        </div>
                        <div class="wideCol">
                            <div class="question">&nbsp;&nbsp;</div>
                            <div class="formInput">
                                <input<?php if( !empty( $errorArr[ 'classes'][ 'contactName'] ) ) echo ' class="error"'; ?> name="contactName" type="hidden" id="contactName" value="<?php echo $user->data->display_name; ?>" size="32" maxlength="64"/>
                                <input name="startTime" type="hidden" id="startTime" value="<?php echo  $externals['startTime']; ?>"/>
                                <input name="eventName" type="hidden" id="eventName" value="<?php echo $user->data->display_name ?>" size="64" maxlength="255"/>
                                <input name="contactEmail" type="hidden" id="contactEmail" value="<?php echo $user->data->user_email ?>" size="64" maxlength="255"/>
                                <input name="endTime" type="hidden" id="endTime" value="<?php echo  $externals['endTime']; ?>"/>
                                <input name="roomID" type="hidden" id="roomID" value="<?php echo $roomContID; ?>"/>
                                <input name="action" type="hidden" id="action" value="<?php echo $externals['action']; ?>"/>
                                <input type="submit" name="button" id="button" value="<?php _e( 'Submit', 'book-a-room' ); ?>"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
    /* check if nonprofit is selected */
    /* jQuery( "#nonProfitYes" ).click( function () {
        jQuery( "#socialEvent" ).hide();
        jQuery( "#libraryCard" ).hide();
        jQuery( "#cityDrop" ).hide();
        jQuery( "#cityFill" ).show();
        jQuery( "#fileUpload" ).show();
        refreshColors();
    } );
    jQuery( "#nonProfitNo" ).click( function () {
        jQuery( "#socialEvent" ).show();
        jQuery( "#fileUpload" ).hide();
        if ( jQuery( "input[name=social]:checked" ).val() == "TRUE" ) {
            jQuery( "#libraryCard" ).show();
        }
        refreshColors();
    } );

    jQuery( "#socialYes" ).click( function () {
        jQuery( "#cityDrop" ).show();
        jQuery( "#cityFill" ).hide();
        jQuery( "#libraryCard" ).show();
        refreshColors();
    } );
    jQuery( "#socialNo" ).click( function () {
        jQuery( "#cityDrop" ).hide();
        jQuery( "#cityFill" ).show();
        jQuery( "#libraryCard" ).hide();
        refreshColors();
    } ); */
    jQuery( document ).ready( function () {
       /*  if ( jQuery( "input[name=nonProfit]:checked" ).val() == "" ) {
            jQuery( "#socialEvent" ).show();
            jQuery( "#fileUpload" ).hide();
        } else {
            jQuery( "#socialEvent" ).hide();
            jQuery( "#libraryCard" ).hide();
            jQuery( "#fileUpload" ).show();
        }
        <?php 
		if( false == $branchList[$roomContList['id'][$roomContID]['branchID']]['branch_isSocial'] ) {
			
			?>
        jQuery( "#cityDrop" ).hide();
        jQuery( "#cityFill" ).show();
        jQuery( "#libraryCard" ).hide();
        <?php
		} else {
		?>
        console.log( jQuery( "input[name=isSocial]:checked" ).val() );
        if ( jQuery( "input[name=isSocial]:checked" ).val() == "" ) {
            jQuery( "#cityDrop" ).hide();
            jQuery( "#cityFill" ).show();
            jQuery( "#libraryCard" ).hide();

        } else {
            jQuery( "#cityDrop" ).show();
            jQuery( "#cityFill" ).hide();
            jQuery( "#libraryCard" ).show();
        }
        <?php
		}
		?> */
        refreshColors();

    } );

    function refreshColors() {
        jQuery( '#formRow div.question:visible:even, #formRow div.formInput:visible:even' ).css( 'background', '#FFF' );
        jQuery( '#formRow div.question:visible:odd, #formRow div.formInput:visible:odd' ).css( 'background', '#EEE' );
    }
</script> 