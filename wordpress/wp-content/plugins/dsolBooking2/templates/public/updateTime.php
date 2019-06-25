<?php
# Selected day
$thisTimeStamp = mktime(0, 0, 0, $timestampInfo['mon'], $curDay, $timestampInfo['year']);
echo json_encode($thisTimeStamp);
?>