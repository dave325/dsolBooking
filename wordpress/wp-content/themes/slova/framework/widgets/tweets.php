<?php
add_action('widgets_init', 'ro_tweets_load_widgets');

function ro_tweets_load_widgets()
{
    register_widget('RO_Tweets_Widget');
}

class RO_Tweets_Widget extends WP_Widget {

    function RO_Tweets_Widget()
    {
        $widget_ops = array('classname' => 'tweets', 'description' => '');

        $control_ops = array('id_base' => 'ro-tweets-widget');

        parent::__construct('ro-tweets-widget', __('Tweets', 'slova'), $widget_ops, $control_ops);
    }

    function widget($args, $instance)
    {
        extract($args);
        $title = apply_filters('widget_title', $instance['title']);
        $consumer_key = $instance['consumer_key'];
        $consumer_secret = $instance['consumer_secret'];
        $access_token = $instance['access_token'];
        $access_token_secret = $instance['access_token_secret'];
        $count = (int) $instance['count'];

        echo balanceTags($before_widget);

        if($title) {
            echo balanceTags($before_title.$title.$after_title);
        }

        if($consumer_key && $consumer_secret && $access_token && $access_token_secret && $count) {
			
			$connection = new TwitterOAuth($consumer_key, $consumer_secret, $access_token, $access_token_secret);

			$twitter = $connection->get('statuses/user_timeline', array('count' => $count));
			
            if($twitter && is_array($twitter)) {
                ?>
				<div class="ro-tweets-slider flexslider">
					<ul class="slides">
						<?php foreach($twitter as $tweet): ?>
							<li class="ro-tweet">
								<div class="ro-tweet-text">
									<?php echo $tweet->text; ?>
								</div>
								<div class="ro-tweet-meta">
									<?php echo '<span class="ro-name">'.$tweet->user->name.'</span><span> (@'.$tweet->user->screen_name.')</span>'; echo ' '.date("F d, Y", strtotime($tweet->created_at)); ?>
								</div>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
            <?php }
        }

        echo balanceTags($after_widget);
    }

    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;

        $instance['title'] = strip_tags($new_instance['title']);
        $instance['consumer_key'] = $new_instance['consumer_key'];
        $instance['consumer_secret'] = $new_instance['consumer_secret'];
        $instance['access_token'] = $new_instance['access_token'];
        $instance['access_token_secret'] = $new_instance['access_token_secret'];
        $instance['count'] = $new_instance['count'];

        return $instance;
    }

    function form($instance)
    {
        $defaults = array('title' => 'Recent Tweets', 'twitter_id' => '', 'count' => 3, 'consumer_key' => '', 'consumer_secret' => '', 'access_token' => '', 'access_token_secret' => '');
        $instance = wp_parse_args((array) $instance, $defaults); ?>

        <p><a href="<?php echo esc_url("http://dev.twitter.com/apps"); ?>">Find or Create your Twitter App</a></p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>">Title:</label>
            <input class="widefat" type="text" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo esc_attr($instance['title']); ?>" />
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('consumer_key')); ?>">Consumer Key:</label>
            <input class="widefat" type="text" id="<?php echo esc_attr($this->get_field_id('consumer_key')); ?>" name="<?php echo esc_attr($this->get_field_name('consumer_key')); ?>" value="<?php echo esc_attr($instance['consumer_key']); ?>" />
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('consumer_secret')); ?>">Consumer Secret:</label>
            <input class="widefat" type="text" id="<?php echo esc_attr($this->get_field_id('consumer_secret')); ?>" name="<?php echo esc_attr($this->get_field_name('consumer_secret')); ?>" value="<?php echo esc_attr($instance['consumer_secret']); ?>" />
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('access_token')); ?>">Access Token:</label>
            <input class="widefat" type="text" id="<?php echo esc_attr($this->get_field_id('access_token')); ?>" name="<?php echo esc_attr($this->get_field_name('access_token')); ?>" value="<?php echo esc_attr($instance['access_token']); ?>" />
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('access_token_secret')); ?>">Access Token Secret:</label>
            <input class="widefat" type="text" id="<?php echo esc_attr($this->get_field_id('access_token_secret')); ?>" name="<?php echo esc_attr($this->get_field_name('access_token_secret')); ?>" value="<?php echo esc_attr($instance['access_token_secret']); ?>" />
        </p>
		
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('count')); ?>">Number of Tweets:</label>
            <input class="widefat" type="text" id="<?php echo esc_attr($this->get_field_id('count')); ?>" name="<?php echo esc_attr($this->get_field_name('count')); ?>" value="<?php echo esc_attr($instance['count']); ?>" />
        </p>

    <?php
    }
}
?>