<?php
if ( ! defined( 'ABSPATH' ) ) exit;
$twt_imgpath = WEBLIZAR_TWITTER_PLUGIN_URL."images/twt.jpg";
?>
<div class="wb_plugin_feature notice  is-dismissible">
	<div class="wb_plugin_feature_banner default_pattern pattern_ ">
		<div class="wb-col-md-6 wb-col-sm-12">
			<img class="wp-img-responsive" src="<?php echo $twt_imgpath; ?>" alt="img">
		</div>
		<div class="wb-col-md-6 wb-col-sm-12 wb_banner_featurs-list">
			<span><h2><?php _e('Twitter Tweets Pro Features', twitter_tweets); ?> </h2></span>
			<ul>
				<li> <?php _e('Multiple Twitter feeds with no limitations', twitter_tweets); ?> </li>
				<li> <?php _e('Feeds of multiple Users, hashtags or search terms', twitter_tweets); ?> </li>
				<li> <?php _e('Filter Tweets by hashtag or words', twitter_tweets); ?> </li>
				<li> <?php _e('Display Tweets in Slider', twitter_tweets); ?> </li>
				<li> <?php _e('Configurable number of tweets to display', twitter_tweets); ?> </li>
				<li> <?php _e('Load more Tweets', twitter_tweets); ?> </li>					
				<li> <?php _e('Style Settings'); ?></li>
				<li> <?php _e('Google Fonts', twitter_tweets); ?> </li>
				<li> <?php _e('Tweet Actions (reply, retweet, like)', twitter_tweets); ?> </li>
				<li> <?php _e('Share Tweets on Social Media', twitter_tweets); ?> </li>
				<li> <?php _e('Update Twitter Status', twitter_tweets); ?> </li>
				<li> <?php _e('Twitter Tweets Widget', twitter_tweets); ?> </li>
			</ul>
		<div class="wp_btn-grup">
			<a class="wb_button-primary"  href="http://demo.weblizar.com/twitter-tweets-pro/" target="_blank"><?php _e('View Demo', twitter_tweets); ?> </a>
			<a class="wb_button-primary" href="https://weblizar.com/plugins/twitter-tweets-pro/" target="_blank"><?php _e('Buy Now', twitter_tweets); ?>  $19</a>
		</div>
		<div class="plugin_vrsion"> <span> <b> 1.0 </b> <?php _e('Version', twitter_tweets); ?>   </span> </div>
		<span class="twt_offer">First 100 Users Get 50% OFF Using "50%OFF" Coupon Code</span>
		</div>
	</div>
</div>
<style type="text/css">
	.wb-col-md-12{
	width:100%;
}
.wb_plugin_feature{
	color:#fff;
}
.wb-text-center{
	text-align:center;
}

.wp-img-responsive{
	max-width:100%;
}

.wb_plugin_feature_banner.default_pattern {
   box-shadow: 0px 2px 20px #818181;
    margin: 40px 0px;
    background-color: #994681;
    float: left;
    display: block;
    clear: right;
    width: 98%;
    position: relative;
}
.wb_banner_featurs-list ul {
    list-style: decimal;
	color:#fff;
    display: inline-block;
}

.wb_button-primary {
    padding: 15px 20px;
    color: #fff;
    text-decoration: none;
    margin: 5px;
    border-radius: 4px;
    box-shadow: 2px 2px 5px #1111113d;
    background-color: #3d9b3d;
}
.wp_btn-grup .wb_button-primary {
    width: 100%;
}
.plugin_vrsion {
    position: absolute;
    background: #55505a;
    border-radius: 0px 0px 0px 52px;
    padding: 15px 30px;
    right: 0px;
    /* border: 1px solid; */
    font-size: 18px;
    top: 0;
    box-shadow: -6px 5px 7px hsla(187, 1%, 15%, 0.3);
}
.wb_banner_featurs-list ul li {
    margin: 7px 20px;
    font-size: 14px;

}
.wb_banner_featurs-list h2 {
    border-bottom:2px solid #fff;
}
.wp_btn-grup {
    display: flex;
	text-align:center;
}
/*--media-responsive csss--*/
@media (min-width: 901px){
.wb_banner_featurs-list ul li {
    float: left;
    width: 42%
}

.wb-col-md-6{
	float:left;
	width:50%;
}
.wp_btn-grup {
    margin: 0 auto;
    width: 60%;
}
}

@media (max-width: 900px){
.wb-col-sm-12{
	width:100%;
}
.wb_plugin_feature_banner.default_pattern {
    background: linear-gradient(0deg, #994681 57%, rgba(4, 4, 4, 0.74) 39%), url(./img/bg.jpg);
}
.wb_plugin_feature_banner{
	float:none;
}
.wb-col-sm-6{
	float:left;
	width:50%;
}
}

.wb_plugin_feature_banner.pattern_2 {
    background: linear-gradient(17deg, #663399 -16%, #ee3e3f 70%, #440e0e 93%), url(./img/bg.jpg);
}

.wb_plugin_feature_banner.pattern_3 {
    background: linear-gradient(17deg, #6f3f9e -16%, #d63131de 93%), url(./img/bg-3.jpg);
    background-repeat: repeat-x;
}
a.wb_button-primary:hover,
	a.wb_button-primary:focus {
    color: #f1f1f1;
    text-decoration: none;
}
.twt_offer{
	padding-top: 10px;
    display: block;
    margin-left: 0px;
    font-size: 24px;
    margin-top: 10px;
    margin-bottom: 10px;
}
</style>
