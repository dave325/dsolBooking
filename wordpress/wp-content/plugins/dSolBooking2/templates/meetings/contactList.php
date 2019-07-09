<style type="text/css">
	@media print {
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
		#wpcontent {
			position: absolute;
			top: 0px;
			left: -50px;
			width: 100%;
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
	
	.contactBody {
		width: 600px;
		float: left;
		clear: both;
		padding-bottom: 20px;
	}
	
	.contactBody .contactContainer {
		clear: left;
		float: left;
		width: 100%;
		border: thin solid #666;
		padding: 10px;
		border-radius: 15px;
		box-shadow: 3px 3px 15px #BBB;
		background-color: #FFF;
	}
	
	.contactBody .contactContainer .contactName {
		font-size: 1.1em;
		font-weight: bold;
		clear: left;
		float: left;
		width: 100%;
		padding-bottom: 5px;
	}
	
	.contactBody .contactContainer .contactPhone {
		float: left;
		padding-left: 5%;
		width: 45%;
		padding-bottom: 5px;
	}
	
	.contactBody .contactContainer .contactEmail {
		float: left;
		clear: none;
		width: 50%;
		padding-bottom: 5px;
	}
	
	.contactBody .contactContainer .contactMeeting {
		float: left;
		width: 75%;
		padding-left: 5%;
		font-style: italic;
		font-weight: bold;
		border-top-width: thin;
		border-top-style: solid;
		border-top-color: #666;
		padding-bottom: 5px;
		padding-top: 8px;
		background-color: #EEE;
		margin-bottom: 5px;
	}
	
	.contactBody .contactContainer .contactStatus {
		float: right;
		width: 20%;
		font-style: italic;
		font-weight: bold;
		border-top-width: thin;
		border-top-style: solid;
		border-top-color: #666;
		padding-bottom: 5px;
		padding-top: 8px;
		background-color: #EEE;
		margin-bottom: 5px;
	}
	
	.contactBody .contactContainer .contactLocation {
		clear: left;
		float: left;
		width: 45%;
		padding-left: 5%;
		padding-bottom: 5px;
	}
	
	.contactBody .contactContainer .contactTime {
		float: left;
		width: 50%;
		padding-bottom: 5px;
	}
</style>
<!-- <link href="/css/dataTable.css" rel="stylesheet" type="text/css" /> -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css"/>
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

<link href="<?php echo plugins_url(); ?>/book-a-room/css/bookaroom_meetings.css" rel="stylesheet" type="text/css"/>
<div id="mainHeader">
	<div class=wrap>
		<div id="icon-options-general" class="icon32"></div>
		<h2>
			<?php _e( 'Book a Room - Contact List', 'book-a-room' ); ?>
		</h2>
	</div>
	<form action="" method="post">
		<p>
			<strong>
				<?php _e( 'Choose a date:', 'book-a-room' ); ?>
			</strong><br>
			<input name="timestamp" type="text" id="date" value="<?php echo date( 'm/d/Y', $timestamp ); ?>" size="30">
			<input type="submit" name="button" id="button" value="<?php _e( 'Submit', 'book-a-room' ); ?>">
		</p>
	</form>
	<div class="contactHeader">
		<?php _e( 'This page is printer friendly! Just hit print to get a print-formatted page.', 'book-a-room' ); ?>
	</div>
</div>
<div class="contactHeader">
	<h2>
		<?php printf( __( 'Contact information for meetings on %s.', 'book-a-room' ), date( 'l, F jS, Y', $timestamp ) ); ?>
	</h2>
</div>
<?php
# none
if ( empty( $pendingList[ 'id' ] ) ) {
	?>
	<div class="noMeeting">
		<?php _e( 'There are no meetings on this date.', 'book-a-room' ); ?>
	</div> 
	<?php
} else {
	foreach ( $pendingList[ 'id' ] as $key => $val ) {
		if ( $val[ 'status' ] == 'denied' ) {
			continue;
		}
		?>
		<div class="contactBody">
			<div class="contactContainer">
				<div class="contactName">
					<?php echo $val[ 'contactName' ]; ?>
				</div>
				<div class="contactPhone">
					<?php echo $val[ 'contactPhonePrimary' ]; ?>
					<?php if( !empty($val[ 'contactPhoneSecondary' ] ) ) { echo '<br>'.$val[ 'contactPhoneSecondary' ]; } ?>
				</div>
				<div class="contactEmail">
					<?php if( !empty( $val[ 'contactEmail' ] ) ) { echo '<a href="mailto:'.$val[ 'contactEmail' ].'">'.$val[ 'contactEmail' ].'</a>'; } ?>
				</div>
				<div class="contactMeeting">
					<?php echo htmlspecialchars_decode( $val[ 'eventName' ] ); ?>
				</div>
				<div class="contactStatus">
					<?php echo $typeArr[ $val[ 'status' ] ]; ?>
				</div>
				<div class="contactLocation"><strong><?php echo $branchList[ $roomContList[ 'id' ][ $val[ 'roomID' ] ][ 'branchID' ] ][ 'branchDesc' ]; ?></strong><br> <?php echo $roomContList[ 'id' ][ $val[ 'roomID' ] ][ 'desc' ]; ?></div>
				<div class="contactTime"> <strong><?php echo date( 'l, F jS, Y', strtotime( $val[ 'startTime' ] ) ); ?></strong><br> <?php echo date( 'g:i a', strtotime( $val[ 'startTime' ] ) ); ?> - <?php echo date( 'g:i a', strtotime( $val[ 'endTime' ] ) ); ?></div>
			</div>
		</div> 
		<?php
	}
}
?>