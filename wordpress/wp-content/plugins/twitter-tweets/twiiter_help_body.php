<div class="wrap" id="weblizar_wrap">
	<div id="content_wrap">			
		<div class="weblizar-header">
			<h2><span class="dashicons dashicons-twitter" style="width: auto;"> Weblizar Twitter Tweets</span></h2>
			<br>
			<div class="weblizar-submenu-links" id="weblizar-submenu-links">
				<ul>
					<li class=""><div class="dashicons dashicons-format-chat"></div> <a href="https://wordpress.org/plugins/twitter-tweets/" target="_blank" title="Support Forum">Support Forum</a></li>
					<li class=""><div class="dashicons dashicons-welcome-write-blog"></div> <a href="<?php echo WEBLIZAR_TWITTER_PLUGIN_URL.'readme.txt'; ?>" target="_blank" title="Theme Changelog">Plugin Change Log</a></li>      
				</ul>
			</div>			
		</div>			
	</div>

	<div id="content">
		<div id="options_tabs" class="ui-tabs">
			<ul class="options_tabs ui-tabs-nav" role="tablist" id="nav">					
				<li class="active">
					<a id="general">
						<div class="dashicons dashicons-admin-generic"></div><?php _e('Twitter Widget',twitter_tweets);?>
					</a>
				</li>
				<li>
					<a id="apikey">
						<div class="dashicons dashicons-admin-generic"></div><?php _e('Twitter Tweets',twitter_tweets);?>
					</a>
				</li>				
				<li>
					<a id="needhelp">
						<div class="dashicons dashicons-editor-help"></div><?php _e('Need Help', twitter_tweets);?>
					</a>
				</li>
				<li>
					<a id="ourproduct">
						<div class="dashicons dashicons-plus"></div><?php _e('Upgrade To Pro', twitter_tweets);?>
					</a>
				</li>
				<li>
					<a id="recommendation">
						<div class="dashicons  dashicons-admin-plugins"></div><?php _e('Recommendation', twitter_tweets);?>
					</a>
				</li>				
			</ul>	

			<?php include('banner.php'); ?>
				
			<?php require_once('twiiter_help.php'); ?>
		</div>		
	</div>
</div>