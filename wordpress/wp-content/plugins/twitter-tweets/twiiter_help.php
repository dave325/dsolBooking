<style>
label {
	margin-right:10px;
}
#fb-msg,
#wl_twitter_preview {
	border: 1px #888888 solid; background-color: #FFFAF0; padding: 10px; font-size: inherit; font-weight: bold; font-family: inherit; font-style: inherit; text-decoration: inherit;
}
.btn-group-lg>.btn, .btn-lg {
    padding: .2rem 1rem !important;
    line-height: 1.5;
    border-radius: .3rem;
 }
 .twt_help{
	    border-radius: 5px;
	    background-color: #994681;
	    padding: 10px;
	    color: #fff;
	    font-weight: 600;
	}
	.twt_save_btn {
    padding-left: 25px;
    background: #994681;
    font-weight: 600;
    color: #fff;
    border-radius: 5px;
    padding-right: 25px;
 }
 .well{
 	    min-height: 20px;
    padding: 19px;
    margin-bottom: 20px;
    background-color: #f5f5f5;
    border: 1px solid #e3e3e3;
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
    box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
 }   
</style>
<script type="text/javascript">
function SaveSettings() {
	var FbAppId 	 = jQuery("#twitter-page-id-fetch").val();
    var User_name_3  = jQuery("#twitter-page-user-name").val();	
	var show_theme   = jQuery("#show-theme-background").val();
	var Height 		 = jQuery("#twitter-page-url-Height").val();
	var link_color   = jQuery("#twitter-page-lnk-Color").val();
	var replieses 	 = jQuery("#exclude_replies_23").val();
	var photos_acces = jQuery("#photo_1234").val();
	var tw_language  = jQuery("#tw_language").val();
	if(!FbAppId) {
		jQuery("#twitter-page-id-fetch").focus();
		return false;
	}
	jQuery("#fb-save-settings").hide();
	jQuery("#fb-img").show();
	jQuery.ajax({
		url: location.href,
		type: "POST",
		data: jQuery("form#fb-form").serialize(),
		dataType: "html",
		//Do not cache the page
		cache: false,
		//success
		success: function (html) {
			jQuery("#fb-img").hide();
			jQuery("#fb-msg").show();
			setTimeout(function() {location.reload(true);}, 2000);
		}
	});
}

function SaveApiSettings() {
 var wl_twitter_consumer_key 	= jQuery("#wl_twitter_consumer_key").val();
 var wl_twitter_consumer_secret = jQuery("#wl_twitter_consumer_secret").val();;
 var wl_twitter_access_token 	= jQuery("#wl_twitter_access_token").val();;
 var wl_twitter_token_secret 	= jQuery("#wl_twitter_token_secret").val();;
 if( ! wl_twitter_consumer_key ) {
	jQuery("#wl_twitter_consumer_key").focus();
	return false;
 }

 if( ! wl_twitter_consumer_secret ) {
	jQuery("#wl_twitter_consumer_secret").focus();
	return false;
 }

 if( ! wl_twitter_access_token ) {
	jQuery("#wl_twitter_access_token").focus();
	return false;
 }

 if( ! wl_twitter_token_secret ) {
	jQuery("#wl_twitter_token_secret").focus();
	return false;
 }
jQuery("#fb-api-save-settings").hide();
jQuery("#twitter-img").show();
 jQuery.ajax({
		url: location.href,
		type: "POST",
		data: jQuery("form#api-form").serialize(),
		dataType: "html",
		//Do not cache the page
		cache: false,
		//success 
		success: function (html) {
			jQuery("#twitter-img").hide();
			jQuery("#wl_twitter_preview").show();
			setTimeout(function() {location.reload(true);}, 2000);			
		}
	});
}
</script>
<?php
wp_enqueue_style('op-bootstrap-css', WEBLIZAR_TWITTER_PLUGIN_URL. 'css/font-awesome-latest/css/fontawesome-all.min.css');
wp_enqueue_style('font-awesome-latest-css', WEBLIZAR_TWITTER_PLUGIN_URL. 'css/bootstrap.min-product.css');
if(isset($_REQUEST['twitter-page-user_name'])) {

 $TwitterUserName  = sanitize_text_field( $_REQUEST['twitter-page-user_name'] );
 $Theme 		   = sanitize_text_field( $_REQUEST['show-theme-background'] );
 $Height 		   = sanitize_text_field( $_REQUEST['twitter-page-url-Height'] );
 $TwitterWidgetId  = sanitize_text_field( $_REQUEST['twitter-page-id-fetch'] );
 $LinkColor 	   = sanitize_text_field( $_REQUEST['twitter-page-lnk-Color'] );
 $ExcludeReplies   = sanitize_option ( 'ExcludeReplies', $_REQUEST['exclude_replies_23'] );
 $AutoExpandPhotos = sanitize_option ( 'AutoExpandPhotos', $_REQUEST['photo_1234'] );
 $tw_language 	   = sanitize_option ( 'Language', $_REQUEST['tw_language'] );


	$TwitterSettingsArray = serialize(
	array(
		'TwitterUserName'  => $TwitterUserName,
		'Theme' 	       => $Theme,
		'Height' 		   => $Height,
		'TwitterWidgetId'  => $TwitterWidgetId,
		'LinkColor' 	   => $LinkColor,
		'ExcludeReplies'   => $ExcludeReplies,
		'AutoExpandPhotos' => $AutoExpandPhotos,
		'tw_language' 	   => $tw_language,
	));
	update_option("ali_twitter_shortcode", $TwitterSettingsArray);
} 

/* Twitter api key save */

if ( isset( $_REQUEST['wl_twitter_consumer_key'] ) && isset( $_REQUEST['twitter_api_nonce'] ) && wp_verify_nonce( $_POST['twitter_api_nonce'], 'twitter_api_nonce'  ) ) {
	$wl_twitter_consumer_key 	= sanitize_text_field( $_REQUEST['wl_twitter_consumer_key'] );
	$wl_twitter_consumer_secret = sanitize_text_field( $_REQUEST['wl_twitter_consumer_secret'] );
	$wl_twitter_access_token 	= sanitize_text_field( $_REQUEST['wl_twitter_access_token'] );
	$wl_twitter_token_secret 	= sanitize_text_field( $_REQUEST['wl_twitter_token_secret'] );

	$wl_twitter_tweets = ( isset( $_REQUEST['wl_twitter_tweets'] ) ) ? sanitize_text_field( $_REQUEST['wl_twitter_tweets'] ) : '4';

	$wl_twitter_layout = ( isset( $_REQUEST['wl_twitter_layout'] ) ) ? sanitize_text_field( $_REQUEST['wl_twitter_layout'] ) : '3';

	$twitter_api_settings = array(
		'wl_twitter_consumer_key' 	 => $wl_twitter_consumer_key,
		'wl_twitter_consumer_secret' => $wl_twitter_consumer_secret,
		'wl_twitter_access_token' 	 => $wl_twitter_access_token,
		'wl_twitter_token_secret' 	 => $wl_twitter_token_secret,
		'wl_twitter_tweets'			 => $wl_twitter_tweets,
		'wl_twitter_layout'			 => $wl_twitter_layout
	);

	update_option( 'wl_twitter_api_settings', $twitter_api_settings );
}
?>
<div class="block ui-tabs-panel active" id="option-general">		
	<div class="row">
		<div class="col-md-6">
			<h2 class="well"><?php _e( 'Twitter Shortcode Settings', "WEBLIZAR_TWITTER_TEXT_DOMAIN"); ?></h2>
			<hr>
			<form name='fb-form' id='fb-form'>
				<?php
					$twitterSettings =  unserialize(get_option("ali_twitter_shortcode"));
					$TwitterUserName = "weblizar";
					if( isset($twitterSettings[ 'TwitterUserName' ] ) )  {
						$TwitterUserName = $twitterSettings[ 'TwitterUserName' ];
					}
					$TwitterWidgetId = "123";
					if ( isset($twitterSettings[ 'TwitterWidgetId' ] ) ) {
						$TwitterWidgetId = $twitterSettings[ 'TwitterWidgetId' ];
					}
					$Theme = "light";
					if (isset( $twitterSettings[ 'Theme' ] ) ) {
						$Theme = $twitterSettings[ 'Theme' ];
					}
					$Height = "450";
					if ( isset($twitterSettings[ 'Height' ] ) ) {
						$Height = $twitterSettings[ 'Height' ];
					}
					$Width = "";
					if ( isset($twitterSettings[ 'Width' ] ) ) {
					$Width = $twitterSettings[ 'Width' ];
					}
					$LinkColor = "#CC0000";
					if ( isset( $twitterSettings[ 'LinkColor' ] ) ) {
						$LinkColor = $twitterSettings[ 'LinkColor' ];
					}
					$ExcludeReplies = "yes";
					if ( isset( $twitterSettings[ 'ExcludeReplies' ] ) )  {
						$ExcludeReplies = $twitterSettings['ExcludeReplies' ];
					}
					$AutoExpandPhotos = "yes";
					if ( isset( $twitterSettings[ 'AutoExpandPhotos' ] ) ) {
						$AutoExpandPhotos = $twitterSettings[ 'AutoExpandPhotos' ];
					}
					$tw_language = "";
					if ( isset( $twitterSettings[ 'tw_language' ] ) ) {
						$tw_language = $twitterSettings[ 'tw_language' ];
					}
				?>
				<p>
					<label><?php _e( 'Twitter Account Username',"WEBLIZAR_TWITTER_TEXT_DOMAIN"); ?></label>
					<input class="widefat" id="twitter-page-user-name" name="twitter-page-user_name" type="text" value="<?php echo esc_attr($TwitterUserName); ?>" placeholder="<?php _e( 'Enter Your Twitter Account Username',"WEBLIZAR_TWITTER_TEXT_DOMAIN"); ?>">
				</p>
				<br>
				<p>
					<input class="widefat" id="twitter-page-id-fetch" name="twitter-page-id-fetch" type="hidden" value="<?php echo esc_attr( $TwitterWidgetId); ?>" placeholder="<?php _e( 'Enter Your Twitter Widget ID',"WEBLIZAR_TWITTER_TEXT_DOMAIN"); ?>">
				</p>
				<p>
					<label><?php _e( 'Theme',"WEBLIZAR_TWITTER_TEXT_DOMAIN"); ?></label>
					<select id="show-theme-background" name="show-theme-background">
						<option value="light" <?php if($Theme == "light") echo "selected=selected" ?>>Light</option>
						<option value="dark" <?php if($Theme == "dark") echo "selected=selected" ?>>Dark</option>
					</select>
				</p>
				<br>
				
				<p>
					<label><?php _e( 'Height',"WEBLIZAR_TWITTER_TEXT_DOMAIN"); ?></label>
					<input class="widefat wltt-slider" id="twitter-page-url-Height" name="twitter-page-url-Height" type="range" value="<?php echo esc_attr($Height ); ?>" min="0" max="1500" data-rangeSlider>
				</p>
				<p><b>Set your desire height px</b>: <span id="twitter-range-val"></span></p>
				<br>		
				
				<p>
					<label><?php _e( 'URL Link Color:',"WEBLIZAR_TWITTER_TEXT_DOMAIN"); ?></label>
					<input class="widefat wltt-color-field" id="twitter-page-lnk-Color" name="twitter-page-lnk-Color" type="text" value="<?php echo esc_attr( $LinkColor ); ?>" data-default-color="#effeff" >

					Find More Color Codes <a href="http://html-color-codes.info/" target="_blank">HERE</a>
				</p>
				<br>
				
				<p>
					<label><?php _e( 'Exclude Replies on Tweets',"WEBLIZAR_TWITTER_TEXT_DOMAIN"); ?></label>
					<select id="exclude_replies_23" name="exclude_replies_23">
						<option value="yes" <?php if($ExcludeReplies == "yes") echo "selected=selected" ?>>Yes</option>
						<option value="no" <?php if($ExcludeReplies == "no") echo "selected=selected" ?>>No</option>
					</select>
				</p>
				<br>
				<p>
					<label><?php _e( 'Auto Expand Photos in Tweets',"WEBLIZAR_TWITTER_TEXT_DOMAIN"); ?></label>
					<select id="photo_1234" name="photo_1234">
						<option value="yes" <?php if($AutoExpandPhotos == "yes") echo "selected=selected" ?>>Yes</option>
						<option value="no" <?php if($AutoExpandPhotos == "no") echo "selected=selected" ?>>No</option>
					</select>
				</p>
				<br>
				
				<p>
					<label><?php _e( 'Select Language',"WEBLIZAR_TWITTER_TEXT_DOMAIN"); ?></label>
					<select id="tw_language" name="tw_language">
						<option value=""<?php if($tw_language == "") echo "selected=selected" ?>>Automatic</option>
						<option value="en"<?php if($tw_language == "en") echo "selected=selected" ?>>English (default)</option>
						<option value="ar"<?php if($tw_language == "ar") echo "selected=selected" ?>>Arabic</option>
						<option value="bn"<?php if($tw_language == "bn") echo "selected=selected" ?>>Bengali</option>
						<option value="cs"<?php if($tw_language == "cs") echo "selected=selected" ?>>Czech</option>
						<option value="da"<?php if($tw_language == "da") echo "selected=selected" ?>>Danish</option>
						<option value="de"<?php if($tw_language == "de") echo "selected=selected" ?>>German</option>
						<option value="el"<?php if($tw_language == "el") echo "selected=selected" ?>>Greek</option>
						<option value="es"<?php if($tw_language == "es") echo "selected=selected" ?>>Spanish</option>
						<option value="fa"<?php if($tw_language == "fa") echo "selected=selected" ?>>Persian</option>
						<option value="fi"<?php if($tw_language == "fi") echo "selected=selected" ?>>Finnish</option>
						<option value="fil"<?php if($tw_language == "fil") echo "selected=selected" ?>>Filipino</option>
						<option value="fr"<?php if($tw_language == "fr") echo "selected=selected" ?>>French</option>
						<option value="he"<?php if($tw_language == "he") echo "selected=selected" ?>>Hebrew</option>
						<option value="hi"<?php if($tw_language == "hi") echo "selected=selected" ?>>Hindi</option>
						<option value="hu"<?php if($tw_language == "hu") echo "selected=selected" ?>>Hungarian</option>
						<option value="id"<?php if($tw_language == "id") echo "selected=selected" ?>>Indonesian</option>
						<option value="it"<?php if($tw_language == "it") echo "selected=selected" ?>>Italian</option>
						<option value="ja"<?php if($tw_language == "ja") echo "selected=selected" ?>>Japanese</option>
						<option value="ko"<?php if($tw_language == "ko") echo "selected=selected" ?>>Korean</option>
						<option value="msa"<?php if($tw_language == "msa") echo "selected=selected" ?>>Malay</option>
						<option value="nl"<?php if($tw_language == "nl") echo "selected=selected" ?>>Dutch</option>
						<option value="no"<?php if($tw_language == "no") echo "selected=selected" ?>>Norwegian</option>
						<option value="pl"<?php if($tw_language == "pl") echo "selected=selected" ?>>Polish</option>
						<option value="pt"<?php if($tw_language == "pt") echo "selected=selected" ?>>Portuguese</option>
						<option value="ro"<?php if($tw_language == "ro") echo "selected=selected" ?>>Romanian</option>
						<option value="ru"<?php if($tw_language == "ru") echo "selected=selected" ?>>Russian</option>
						<option value="sv"<?php if($tw_language == "sv") echo "selected=selected" ?>>Swedish</option>
						<option value="th"<?php if($tw_language == "th") echo "selected=selected" ?>>Thai</option>
						<option value="tr"<?php if($tw_language == "tr") echo "selected=selected" ?>>Turkish</option>
						<option value="uk<?php if($tw_language == "uk") echo "selected=selected" ?>">Ukrainian</option>
						<option value="ur"<?php if($tw_language == "ur") echo "selected=selected" ?>>Urdu</option>
						<option value="vi"<?php if($tw_language == "vi") echo "selected=selected" ?>>Vietnamese</option>
						<option value="zh-cn"<?php if($tw_language == "zh-cn") echo "selected=selected" ?>>Chinese (Simplified)</option>
						<option value="zh-tw"<?php if($tw_language == "zh-tw") echo "selected=selected" ?>>Chinese (Traditional)</option>
					</select>
				</p>
				<br>
				
				<input onclick="return SaveSettings();" type="button" class="twt_save_btn" id="fb-save-settings" name="fb-save-settings" value="SAVE">
			
				<div id="fb-img" style="display: none;">
					<img src="<?php echo WEBLIZAR_TWITTER_PLUGIN_URL.'images/loading.gif'; ?>" />
				</div>
				<div id="fb-msg" style="display: none;" class"alert">
					<?php _e( 'Settings successfully saved. Reloading page for generating preview right side of setting.', "WEBLIZAR_TWITTER_TEXT_DOMAIN" ); ?> 
				</div>		
			</form>
			
		</div>
		<!-- Preview Part-->
		<div class="col-md-6">
			<?php if($TwitterWidgetId) { ?>
			<h2 class="well">Twitter Shortcode <?php _e( 'Preview', "WEBLIZAR_TWITTER_TEXT_DOMAIN"); ?></h2>
			<hr>
			<p>
		<a class="twitter-timeline" data-dnt="true" href="https://twitter.com/<?php echo esc_attr($TwitterUserName); ?>" 
		min-width="<?php echo esc_attr($Width); ?>" 
		height="<?php echo esc_attr($Height); ?>" 
		data-theme="<?php echo esc_attr($Theme); ?>" 
		data-lang="<?php echo esc_attr($tw_language); ?>"
		data-link-color="<?php echo esc_attr($LinkColor); ?>"></a>
		<div class="twt_help">
			<?php _e('Please copy the twitter shortcode', twitter_tweets );?>  <span style="color:#000;"> <b>[TWTR]</b> </span> <?php _e('and paste it to on the Page/Post', twitter_tweets );?></span>
		</div>

				<script>
				!function(d,s,id) {
					var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}
				} (document,"script","twitter-wjs");
				</script>
			</p>
			<?php }?>
		</div>
    </div>
</div>

<!-- API Key -->
<?php
	include_once('load-tweets.php');
?>
<div class="block ui-tabs-panel deactive" id="option-apikey">
	<div class="row">
		<div class="col-md-6">
			<div class="row">
				<div class="col-md-12">
					<h2 class="well"><?php _e( 'Twitter API Setting', "WEBLIZAR_TWITTER_TEXT_DOMAIN"); ?></h2>
				</div>
				<div class="col-md-12">
					<form name='api-form' id='api-form'>
						<br>
						<p>
							<label><?php _e( 'Consumer Key',"WEBLIZAR_TWITTER_TEXT_DOMAIN"); ?>&nbsp;*</label>					
							<input class="widefat" id="wl_twitter_consumer_key" name="wl_twitter_consumer_key" type="text" value="<?php if( isset( $wl_twitter_consumer_key ) ) {echo esc_attr($wl_twitter_consumer_key);} ?>" placeholder="<?php _e( 'Enter Your Twitter Consumer Key',"WEBLIZAR_TWITTER_TEXT_DOMAIN"); ?>">
						</p>
						<span class="helplink"><?php _e("Visit this link to ", "WEBLIZAR_TWITTER_TEXT_DOMAIN"); ?><a href="https://weblizar.com/blog/generate-twitter-api-key/" target="_bank"><?php _e("Generate Twitter API key", "WEBLIZAR_TWITTER_TEXT_DOMAIN"); ?></a></span>
						<br>

						<br>
						<p>
							<label><?php _e( 'Consumer Secret',"WEBLIZAR_TWITTER_TEXT_DOMAIN"); ?>&nbsp;*</label>					
							<input class="widefat" id="wl_twitter_consumer_secret" name="wl_twitter_consumer_secret" type="text" value="<?php if(isset($wl_twitter_consumer_secret)) {echo esc_attr($wl_twitter_consumer_secret);} ?>" placeholder="<?php _e( 'Enter Your Twitter Consumer Secret',"WEBLIZAR_TWITTER_TEXT_DOMAIN"); ?>">
						</p>
						<br>

						<br>
						<p>
							<label><?php _e( 'Access Token',"WEBLIZAR_TWITTER_TEXT_DOMAIN"); ?>&nbsp;*</label>					
							<input class="widefat" id="wl_twitter_access_token" name="wl_twitter_access_token" type="text" value="<?php if( isset( $wl_twitter_access_token ) ) {echo esc_attr($wl_twitter_access_token);} ?>" placeholder="<?php _e( 'Enter Your Twitter Access Token',"WEBLIZAR_TWITTER_TEXT_DOMAIN"); ?>">
						</p>
						<br>

						<br>
						<p>
							<label><?php _e( 'Access Token Secret',"WEBLIZAR_TWITTER_TEXT_DOMAIN"); ?>&nbsp;*</label>					
							<input class="widefat" id="wl_twitter_token_secret" name="wl_twitter_token_secret" type="text" value="<?php if( isset( $wl_twitter_token_secret ) ) {echo esc_attr($wl_twitter_token_secret);} ?>" placeholder="<?php _e( 'Enter Your Twitter Token Secret',"WEBLIZAR_TWITTER_TEXT_DOMAIN"); ?>">
						</p>

						<br>
						<p>
							<label><?php _e( 'No. Of tweets Show',"WEBLIZAR_TWITTER_TEXT_DOMAIN"); ?></label>
							<input class="widefat wltt-slider" id="wl_twitter_tweets" name="wl_twitter_tweets" type="range" value="<?php  if( isset( $wl_twitter_tweets ) ) {echo esc_attr($wl_twitter_tweets);} ?>" min="1" max="14" data-rangeSlider>
						</p>
							<p>
								<b>Set no of tweets you want to show</b>: <span id="wl_twitter_range_show"></span>
							</p>
							<br>
						<p>
							<label><?php _e( 'Layout',"WEBLIZAR_TWITTER_TEXT_DOMAIN"); ?></label>
							<select class="widefat" name="wl_twitter_layout" id="wl_twitter_layout">
								<option value=""><?php _e( 'Select',"WEBLIZAR_TWITTER_TEXT_DOMAIN"); ?></option>
								<option value="12">1</option>
								<option value="6">2</option>
								<option value="4">3</option>													
								<option value="3">4</option>													
							</select>
						</p>
						<script type="text/javascript">
                            var abc = '<?php echo "$wl_twitter_layout"; ?>';
                            jQuery('#wl_twitter_layout').find('option[value="' + abc + '"]').attr('selected', 'selected')
                        </script>
						<br>
						<?php 
							wp_nonce_field( 'twitter_api_nonce', 'twitter_api_nonce' );
						?>
						<input onclick="return SaveApiSettings();" type="button" class="twt_save_btn" id="fb-api-save-settings" name="fb-api-save-settings" value="SAVE">
						<br><br><br>
						<div class="twt_help">
							<?php _e('Please copy the twitter shortcode', twitter_tweets );?>  <span style="color:#000;"> <b>[WL_TWITTER]</b> </span> <?php _e('and paste it to on the Page/Post', twitter_tweets );?></span>
						</div>							
						<div id="twitter-img" style="display: none;">
							<img src="<?php echo WEBLIZAR_TWITTER_PLUGIN_URL.'images/loading.gif'; ?>" />
						</div>
						<div id="wl_twitter_preview" style="display: none;" class"alert">
							<?php _e( 'Settings successfully saved. Reloading page for generating preview right side of setting.', "WEBLIZAR_TWITTER_TEXT_DOMAIN" ); ?> 
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="wl_twt_free">
				<div class="container-fluid">
				    <div class="row">	    	
				    	<div class="col-md-<?php if( isset( $wl_twitter_layout ) ){echo $wl_twitter_layout;} ?>">
				    		<?php
				    		if ( isset( $statuses ) && is_array( $statuses ) ) {
							foreach ( $statuses as $status ) {								
							/* user info */
							if( isset( $status->user ) ) {
							 $user = $status->user;
							}
							if( isset( $user->name ) ) {
							 $name = $user->name;
							}
							if( isset( $user->screen_name ) ) {
							$screen_name = $user->screen_name;
							}
							if( isset( $user->location ) ) {
							$location = $user->location;
							}
							if( isset( $user->description ) ) {
							$description = $user->description;
							}
							if( isset( $user->url ) ) {
							$url = $user->url;							
							}
							if( isset( $status->id_str ) ) {
							$id_str = $status->id_str; /* use it to make link of post */							
							}
							if( isset( $status->created_at ) ) {
							$created_at = $status->created_at; /* time when tweet was created */							
							}
							/* profile_image_url */
							if( isset( $user->profile_image_url ) ) {
							$profile_image_url = $user->profile_image_url;							
							}
							if( isset( $user->profile_image_url_https ) ) {
							$profile_image_url_https = $user->profile_image_url_https;							
							}
					    ?>					  
				    		<div class="wl_tweet_box">
				                <p class="wl_tweet">
				                    <img class="align-self-start mr-3" src="<?php if( isset( $user->profile_image_url_https ) ){echo $profile_image_url_https;} ?>"
				                         alt="">
				                    <a href="https://twitter.com/<?php if( isset( $user->screen_name ) ){echo $screen_name;} ?>">
										<?php if( isset( $user->screen_name ) ) {echo "@" . $screen_name;} ?>
				                    </a>
				                </p>
								<?php
								// $entities = $status->entities;				
									if ( isset( $status->extended_entities ) ) {
										$extended_entities_array = $status->extended_entities->media;
										$extended_entities       = $extended_entities_array[0];
										$display_url             = $extended_entities->display_url;
										$media_expanded_url      = $extended_entities->expanded_url;
										$media_type              = $extended_entities->type;
										$media_url               = $extended_entities->media_url;
										if ( $media_type == "photo" ) {
											?>
				                            <img src="<?php echo $media_url; ?>" class="img-fluid"/>
											<?php
										} elseif ( $media_type == "video" ) {
											$video_info   = $extended_entities->video_info->variants[2];
											$content_type = $video_info->content_type;
											$url          = $video_info->url;
											$new_url      = str_replace( "?tag=8", "", $url );
											
											if ( isset( $enable_extended_entitie ) && $enable_extended_entitie == "enable" ) {
												?>
				                                <a href="#" data-toggle="modal" data-target="#myModal">
				                                    <img src="<?php echo $media_url; ?>" class="img-fluid"/>
				                                </a>
												<?php
											} else {
												?>
				                                <a href="#">
				                                    <img src="<?php echo $media_url; ?>" class="img-fluid"/>
				                                </a>
												<?php
											}
										}
									} /* extended enntities */
				                    elseif ( ! empty( $entities->media ) && is_array( $entities->media ) ) {
										$media = $entities->media;
										foreach ( $media as $media_key => $media_value ) {
											$media_url          = $media_value->media_url;
											$media_url_https    = $media_value->media_url_https;
											$media_detail_url   = $media_value->url;
											$media_display_url  = $media_value->display_url;
											$media_expanded_url = $media_value->expanded_url;
											$media_type         = $media_value->type;
											$media_sizes        = $media_value->sizes;
											?>
				                            <a href="<?php echo $media_expanded_url; ?>">
				                                <img src="<?php echo $media_url_https; ?>" class="img-fluid"/>
				                            </a>
											<?php
										}
									}				
								?>
				                <p class="wl_tweet_desc">
									<?php
									if( isset( $status->text ) ) {								
										echo makeLinks( $status->text );
									}
									?>
				                </p>
				                <p class="wl_tweet_action_buttons">
				                    <a href="https://twitter.com/intent/retweet?tweet_id=<?php echo $id_str; ?>&related=<?php echo $screen_name; ?> retweet"
				                       target="_blank"
				                       onclick="window.open('https://twitter.com/intent/retweet?tweet_id=<?php echo $id_str; ?>&related=<?php echo $screen_name; ?> retweet', 'newwindow', 'width=600,height=450'); return false;">
				                       <?php
				                       if ( isset( $status->retweet_count ) ) {
				                       		_e( 'Retweet', twitter_tweets );
											echo " ($status->retweet_count)";
				                       }
				                       ?>
				                    </a>

				                    <a href="https://twitter.com/intent/like?tweet_id=<?php echo $id_str; ?>&related=<?php echo $screen_name; ?>"
				                       target="_blank"
				                       onclick="window.open('https://twitter.com/intent/like?tweet_id=<?php echo $id_str; ?>&related=<?php echo $screen_name; ?> retweet', 'newwindow', 'width=600,height=450'); return false;">
				                       <?php
				                       if ( isset( $status->favorite_count ) ) {
			                       		    _e( 'Like', twitter_tweets );
											echo " ($status->favorite_count)";
				                       }
				                      ?>
				                    </a>

				                    <a href="https://twitter.com/intent/tweet?in_reply_to=<?php echo $id_str; ?>&related=<?php echo $screen_name; ?>"
				                       target="_blank"
				                       onclick="window.open('https://twitter.com/intent/tweet?in_reply_to=<?php echo $id_str; ?>&related=<?php echo $screen_name; ?> retweet', 'newwindow', 'width=600,height=450'); return false;"><?php _e( 'Reply', twitter_tweets ); ?>
				                    </a>
				                </p>                
				                <span class="wl-wtp-date-font-size"><?php if( isset( $status->created_at ) ) {echo tweet_time_calculate( $created_at );} ?>
				                    &nbsp;<?php if( isset( $status->created_at ) ) {_e( 'ago', twitter_tweets );} ?></span>
				            </div> <!-- Tweet box -->
				        <?php } 
				        } ?>
				    	</div>
				    </div>
				</div>
			</div>					
		</div>
	</div>	
</div>

<!---------------- need help tab------------------------>
<div class="block ui-tabs-panel deactive" id="option-needhelp">		
		<div class="row">
		<div class="col-md-10">
			<div id="heading">
				<h2 class="well">Twitter Tweet Widget & Shortcode Help Section</h2>
			</div>
			<p class="well"><b>Twitter Tweet By Weblizar plugin comes with 2 major feature.</b></p>
			
			<ol>
				<li>Twitter Tweets Widget</li>
				<li>Twitter Tweets Shortcode [TWTR]</li>
				<li>Note: Protected tweets will not view <a href="https://help.twitter.com/en/safety-and-security/public-and-protected-tweets" target="_blank">Help</a></li>
			</ol>
			<br>
			<p class="well"><strong>Twitter Tweets Widget</strong></p>
			
			<ol>                             
				<li>You can use the widget to display your Twitter Tweets in any theme Widget Sections.</li>
				<li>Simple go to your <a href="<?php echo get_site_url(); ?>/wp-admin/widgets.php"><strong>Widgets</strong></a> section and activate available <strong>"Twitter By Weblizar"</strong> widget in any sidebar section, like in left sidebar, right sidebar or footer sidebar.</li>
		    </ol>
			<br>
			<p class="well"><strong>Twitter Tweets Shortcode [TWTR]</strong></p>
			<ol>
				<li><strong>[TWTR]</strong> shortcode give ability to display Twitter Tweets Box in any Page / Post with content.</li>
				<li>To use shortcode, just copy <strong>[TWTR]</strong> shortcode and paste into content editor of any Page / Post.</li>
			</ol>

			<br>
			<p class="well"><strong>Twitter Tweets Shortcode [WL_TWITTER]</strong></p>
			<ol>
				<li><strong>[WL_TWITTER]</strong> shortcode, another shortcode, using API Key to login, give ability to display Twitter Tweets Box in any Page / Post with content.</li>
				<li>To use shortcode, just copy <strong>[WL_TWITTER]</strong> shortcode and paste into content editor of any Page / Post.</li>
			</ol>

			<br>
			<p class="well"><strong>How to generate Twitter API Key</strong></p>
			<p>
				We have created a blog post on this topic. It is very easy to understand. <span class="helptopic"><a href="https://weblizar.com/blog/generate-twitter-api-key/" target="_blank">Click here</a></span> to visit the blog. 
			</p>

			<br>
			<p class="well"><strong>Q. What is Twitter Widget ID?</strong></p>
			
				<p><strong>Ans. Twitter Widget ID</strong> used to authenticate your TWITTER
				Page data & settings. To get your own TWITTER ID please read our very simple and easy <a href="https://weblizar.com/get-twitter-widget-id/" target="_blank"><strong>Tutorial</strong>.</a></p>
				
		</div>
	</div>	
	<div class="row">
		<div class="col-md-10">
			<div id="heading">
					<h2>Rate Us</h2>
			</div>
			<p>
				If you are enjoying using our <b>Weblizar Twitter Widget</b> plugin and find it useful, then please consider writing a positive feedback. Your feedback will help us to encourage and support the plugin's continued development and better user support.
			</p>
				<style>
				.acl-rate-us  span.dashicons{
				width: 30px;
				height: 30px;
				}
				.acl-rate-us  span.dashicons-star-filled:before {
				content: "\f155";
				font-size: 30px;
				}
				.acl-rate-us {
					color : #fff !important;
					padding-top:3px !important;
				}
				
				.acl-rate-us span{
					display:inline-block;
				}
				.twt_star{
					background:#994681;display:inline-block;border-radius: 4px;padding: 4px; margin: 0 auto;
				}
			</style>
			<div class="twt_star">
				<a class="acl-rate-us" style="text-align:center; text-decoration: none;font:normal 30px/l; " href="https://wordpress.org/plugins/twitter-tweets/#reviews" target="_blank" >
					<span class="dashicons dashicons-star-filled"></span>
					<span class="dashicons dashicons-star-filled"></span>
					<span class="dashicons dashicons-star-filled"></span>
					<span class="dashicons dashicons-star-filled"></span>
					<span class="dashicons dashicons-star-filled"></span>
				</a>
			</div>
		</div>
	</div>
</div>

<!-- Recommendation ---->
<!---------------- our product tab------------------------>
<div class="block ui-tabs-panel deactive" id="option-recommendation">
	<!-- Dashboard Settings panel content --- >
<!----------------------------------------> 

<div class="row">
	<div class="panel panel-primary panel-default content-panel">
		<div class="panel-body">
			<table class="form-table2">
				<tr class="radio-span" style="border-bottom:none;">
					<td>
	<?php
	include( ABSPATH . "wp-admin/includes/plugin-install.php" );
	global $tabs, $tab, $paged, $type, $term;
	$tabs = array();
	$tab = "search";
	$per_page = 20;
	$args = array
	(
		"author"=> "weblizar",
		"page" => $paged,
		"per_page" => $per_page,
		"fields" => array( "last_updated" => true, "downloaded" => true, "icons" => true ),
		"locale" => get_locale(),
	);
	$arges = apply_filters( "install_plugins_table_api_args_$tab", $args );
	$api = plugins_api( "query_plugins", $arges );
	$item = $api->plugins;
	if(!function_exists("wp_star_rating"))
	{
		function wp_star_rating( $args = array() )
		{
			$defaults = array(
					'rating' => 0,
					'type' => 'rating',
					'number' => 0,
			);
			$r = wp_parse_args( $args, $defaults );
	
			// Non-english decimal places when the $rating is coming from a string
			$rating = str_replace( ',', '.', $r['rating'] );
	
			// Convert Percentage to star rating, 0..5 in .5 increments
			if ( 'percent' == $r['type'] ) {
				$rating = round( $rating / 10, 0 ) / 2;
			}
	
			// Calculate the number of each type of star needed
			$full_stars = floor( $rating );
			$half_stars = ceil( $rating - $full_stars );
			$empty_stars = 5 - $full_stars - $half_stars;
	
			if ( $r['number'] ) {
				/* translators: 1: The rating, 2: The number of ratings */
				$format = _n( '%1$s rating based on %2$s rating', '%1$s rating based on %2$s ratings', $r['number'] );
				$title = sprintf( $format, number_format_i18n( $rating, 1 ), number_format_i18n( $r['number'] ) );
			} else {
				/* translators: 1: The rating */
				$title = sprintf( __( '%s rating' ), number_format_i18n( $rating, 1 ) );
			}
	
			echo '<div class="star-rating" title="' . esc_attr( $title ) . '">';
			echo '<span class="screen-reader-text">' . $title . '</span>';
			echo str_repeat( '<div class="star star-full"></div>', $full_stars );
			echo str_repeat( '<div class="star star-half"></div>', $half_stars );
			echo str_repeat( '<div class="star star-empty"></div>', $empty_stars);
			echo '</div>';
		}
	}
	?>
	<form id="frmrecommendation" class="layout-form">
		<div id="poststuff" style="width: 99% !important;">
			<div id="post-body" class="metabox-holder">
				<div id="postbox-container-2" class="postbox-container">
					<div id="advanced" class="meta-box-sortables">
						<div id="gallery_bank_get_started" class="postbox" >
							<div class="handlediv" data-target="ux_recommendation" title="Click to toggle" data-toggle="collapse"><br></div>
							<h2 class="hndle"><span>Get More Free WordPress Plugins From Weblizar</span></h3>
							<div class="inside">
								<div id="ux_recommendation" class="gallery_bank_layout">
									
									<div class="separator-doubled"></div>
									<div class="fluid-layout">
										<div class="layout-span12">
											<div class="wp-list-table plugin-install">
												<div id="the-list">
													<?php 
													foreach ((array) $item as $plugin) 
													{
														if (is_object( $plugin))
														{
															$plugin = (array) $plugin;
															
														}
														if (!empty($plugin["icons"]["svg"]))
														{
															$plugin_icon_url = $plugin["icons"]["svg"];
														} 
														elseif (!empty( $plugin["icons"]["2x"])) 
														{
															$plugin_icon_url = $plugin["icons"]["2x"];
														} 
														elseif (!empty( $plugin["icons"]["1x"]))
														{
															$plugin_icon_url = $plugin["icons"]["1x"];
														} 
														else 
														{
															$plugin_icon_url = $plugin["icons"]["default"];
														}
														$plugins_allowedtags = array
														(
															"a" => array( "href" => array(),"title" => array(), "target" => array() ),
															"abbr" => array( "title" => array() ),"acronym" => array( "title" => array() ),
															"code" => array(), "pre" => array(), "em" => array(),"strong" => array(),
															"ul" => array(), "ol" => array(), "li" => array(), "p" => array(), "br" => array()
														);
														$title = wp_kses($plugin["name"], $plugins_allowedtags);
														$description = strip_tags($plugin["short_description"]);
														$author = wp_kses($plugin["author"], $plugins_allowedtags);
														$version = wp_kses($plugin["version"], $plugins_allowedtags);
														$name = strip_tags( $title . " " . $version );
														$details_link   = self_admin_url( "plugin-install.php?tab=plugin-information&amp;plugin=" . $plugin["slug"] .
														"&amp;TB_iframe=true&amp;width=600&amp;height=550" );
														
														/* translators: 1: Plugin name and version. */
														$action_links[] = '<a href="' . esc_url( $details_link ) . '" class="thickbox" aria-label="' . esc_attr( sprintf("More information about %s", $name ) ) . '" data-title="' . esc_attr( $name ) . '">' . __( 'More Details' ) . '</a>';
														$action_links = array();
														if (current_user_can( "install_plugins") || current_user_can("update_plugins"))
														{
															$status = install_plugin_install_status( $plugin );
															switch ($status["status"])
															{
																case "install":
																	if ( $status["url"] )
																	{
																		/* translators: 1: Plugin name and version. */
																		$action_links[] = '<a class="install-now button" href="' . $status['url'] . '" aria-label="' . esc_attr( sprintf("Install %s now", $name ) ) . '">' . __( 'Install Now' ) . '</a>';
																	}
																break;
																case "update_available":
																	if ($status["url"])
																	{
																		/* translators: 1: Plugin name and version */
																		$action_links[] = '<a class="button" href="' . $status['url'] . '" aria-label="' . esc_attr( sprintf( "Update %s now", $name ) ) . '">' . __( 'Update Now' ) . '</a>';
																	}
																break;
																case "latest_installed":
																case "newer_installed":
																	$action_links[] = '<span class="button button-disabled" title="' . esc_attr__( "This plugin is already installed and is up to date" ) . ' ">' . _x( 'Installed', 'plugin' ) . '</span>';
																break;
															}
														}
														?>
														<div class="plugin-div plugin-div-settings">
															<div class="plugin-div-top plugin-div-settings-top">
																<div class="plugin-div-inner-content">
																	<a href="<?php echo esc_url( $details_link ); ?>" class="thickbox plugin-icon plugin-icon-custom">
																		<img class="custom_icon" src="<?php echo esc_attr( $plugin_icon_url ) ?>" />
																	</a>
																	<div class="name column-name">
																		<h4>
																			<a href="<?php echo esc_url( $details_link ); ?>" class="thickbox"><?php echo $title; ?></a>
																		</h4>
																	</div>
																	<div class="desc column-description">
																		<p>
																			<?php echo $description; ?>
																		</p>
																		<p class="authors">
																			<cite>
																				By <?php echo $author;?>
																			</cite>
																		</p>
																	</div>
																</div>
																<div class="action-links">
																	<ul class="plugin-action-buttons-custom">
																		<li>
																			<?php
																				if ($action_links) {
																					echo implode("</li><li>", $action_links);
																				}
																					
																				switch($plugin["slug"]) {
																					case "gallery-bank" :
																						?>
																							<a class="plugin-div-button install-now button" href="http://tech-banker.com/products/wp-gallery-bank/pricing/" target="_blank" >
																								<?php _e("Premium Editions", WEBLIZAR_ACL); ?>
																							</a>
																							<a class="plugin-div-button install-now button" href="http://tech-banker.com/products/wp-gallery-bank/" target="_blank" >
																								<?php _e("Visit Website", WEBLIZAR_ACL); ?>
																							</a>
																						<?php
																					break;
																					case "contact-bank" :
																						?>
																							<a class="plugin-div-button install-now button" href="http://tech-banker.com/products/wp-contact-bank/pricing/" target="_blank" >
																								<?php _e("Premium Editions", WEBLIZAR_ACL); ?>
																							</a>
																							<a class="plugin-div-button install-now button" href="http://tech-banker.com/products/wp-contact-bank/" target="_blank" >
																								<?php _e("Visit Website", WEBLIZAR_ACL); ?>
																							</a>
																						<?php
																					break;
																					case "captcha-bank" :
																						?>
																							<a class="plugin-div-button install-now button" href="http://tech-banker.com/products/wp-captcha-bank/pricing/" target="_blank" >
																								<?php _e("Premium Editions", WEBLIZAR_ACL); ?>
																							</a>
																							<a class="plugin-div-button install-now button" href="http://tech-banker.com/products/wp-captcha-bank/" target="_blank" >
																								<?php _e("Visit Website", WEBLIZAR_ACL); ?>
																							</a>
																						<?php 
																					break;
																					case "wp-clean-up-optimizer" :
																						?>
																							<a class="plugin-div-button install-now button" href="http://tech-banker.com/products/wp-clean-up-optimizer/pricing/" target="_blank" >
																								<?php _e("Premium Editions", WEBLIZAR_ACL); ?>
																							</a>
																							<a class="plugin-div-button install-now button" href="http://tech-banker.com/products/wp-clean-up-optimizer/" target="_blank" >
																								<?php _e("Visit Website", WEBLIZAR_ACL); ?>
																							</a>
																						<?php 
																					break;
																					case "google-maps-bank":
																						?>
																							<a class="plugin-div-button install-now button" href="http://tech-banker.com/products/wp-google-maps-bank/pricing/" target="_blank" >
																								<?php _e("Premium Editions", WEBLIZAR_ACL); ?>
																							</a>
																							<a class="plugin-div-button install-now button" href="http://tech-banker.com/products/wp-google-maps-bank/" target="_blank" >
																								<?php _e("Visit Website", WEBLIZAR_ACL); ?>
																							</a>
																						<?php
																					break;
																					case "wp-backup-bank":
																						?>
																							<a class="plugin-div-button install-now button" href="http://tech-banker.com/products/wp-backup-bank/pricing/" target="_blank" >
																								<?php _e("Premium Editions", WEBLIZAR_ACL); ?>
																							</a>
																							<a class="plugin-div-button install-now button" href="http://tech-banker.com/products/wp-backup-bank/" target="_blank" >
																								<?php _e("Visit Website", WEBLIZAR_ACL); ?>
																							</a>
																						<?php
																					break;
																				}
																			?>
																		</li>
																	</ul>
																</div>
															</div>
															<div class="plugin-card-bottom plugin-card-bottom_settings">
																<div class="vers column-rating">
																	<?php wp_star_rating( array( "rating" => $plugin["rating"], "type" => "percent", "number" => $plugin["num_ratings"] ) ); ?>
																	<span class="num-ratings">
																		(<?php echo number_format_i18n( $plugin["num_ratings"] ); ?>)
																	</span>
																</div>
																<div class="column-updated">
																	<strong><?php _e("Last Updated:"); ?></strong> <span title="<?php echo esc_attr($plugin["last_updated"]); ?>">
																		<?php printf("%s ago", human_time_diff(strtotime($plugin["last_updated"]))); ?>
																	</span>
																</div>
																<div class="column-downloaded">
																	<?php echo sprintf( _n("%s download", "%s downloads", $plugin["downloaded"]), number_format_i18n($plugin["downloaded"])); ?>
																</div>
																<div class="column-compatibility">
																	<?php
																	if ( !empty($plugin["tested"]) && version_compare(substr($GLOBALS["wp_version"], 0, strlen($plugin["tested"])), $plugin["tested"], ">"))
																	{
																		echo '<span class="compatibility-untested">' . __( "<strong>Untested</strong> with your version of WordPress" ) . '</span>';
																	} 
																	elseif (!empty($plugin["requires"]) && version_compare(substr($GLOBALS["wp_version"], 0, strlen($plugin["requires"])), $plugin["requires"], "<")) 
																	{
																		echo '<span class="compatibility-incompatible">' . __("Incompatible with your version of WordPress") . '</span>';
																	} 
																	else
																	{
																		echo '<span class="compatibility-compatible">' . __("Compatible with your version of WordPress") . '</span>';
																	}
																	?>
																</div>
															</div>
														</div>
													<?php
													}
													?>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
				</td>
			</tr>
		</table>
	</div>
</div>
<script>
	var slider = document.getElementById("twitter-page-url-Height");
	var output = document.getElementById("twitter-range-val");
	
	var x = slider.value;
	// var y = x/1000;
    output.innerHTML = x;
	
	slider.oninput = function() {
		var x = slider.value;
		var y = x/1000;
		output.innerHTML = x;
	}

	var slider_tweet_show 		 = document.getElementById("wl_twitter_tweets");
	var output_slider_tweet_show = document.getElementById("wl_twitter_range_show");

	var y = slider_tweet_show.value;
	// var y = x/1000;
    output_slider_tweet_show.innerHTML = y;

    slider_tweet_show.oninput = function() {
		var x = slider_tweet_show.value;
		// var y = x/1000;
		output_slider_tweet_show.innerHTML = x;
	}

	jQuery(document).ready(function($){
	    jQuery('.wltt-color-field').wpColorPicker();
	});
</script>	
   
	
</div>
<!-- /row -->

</div>


<!---------------- our product tab------------------------>
<div class="block ui-tabs-panel deactive" id="option-ourproduct">
	
		<?php require_once('our_product.php'); ?>
	
</div>