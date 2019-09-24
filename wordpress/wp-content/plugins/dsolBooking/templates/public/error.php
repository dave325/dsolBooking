  <div id="topRow">
    <div class="col">
      <div class="instructionsSmooth"><span class="header"><?php _e( 'Step 5.', 'book-a-room' ); ?></span>
        <p><em><?php _e( 'Complete the registration form.', 'book-a-room' ); ?></em></p>
        <p><em><strong><?php _e( 'Items marked with an asterisk* are required fields.', 'book-a-room' ); ?></strong></em></p>
       <div id="errorArea">
          <p><?php _e( 'You have the following error(s):', 'book-a-room' ); ?></p>
          <ul>
           <?php echo $errorMSG; ?>
          </ul>
        </div>
      </div>
      <div class="options">
        <div id="formRow">
          <div class="wideCol" style="background-color:#FFF;"><a href="?action=reserve&amp;roomID=<?php echo $externals['roomID']; ?>&amp;timestamp=<?php echo $externals['startTime']; ?>&session=true">
            <?php _e( 'Click here to go back to the hours page and reselect the hours you would like to reserve.', 'book-a-room' ); ?>
        </a></div>
        </div>
      </div>
    </div>
  </div>

