<?php

/* Post gallery */
if (!function_exists('ro_theme_grab_ids_from_gallery')) {

    function ro_theme_grab_ids_from_gallery() {
        global $post;
        $gallery = ro_theme_get_shortcode_from_content('gallery');
        $object = new stdClass();
        $object->columns = '3';
        $object->link = 'post';
        $object->ids = array();
        if ($gallery) {
            $object = ro_theme_extra_shortcode('gallery', $gallery, $object);
        }
        return $object;
    }

}
/* Extra shortcode */
if (!function_exists('ro_theme_extra_shortcode')) {
    function ro_theme_extra_shortcode($name, $shortcode, $object) {
        if ($shortcode && is_object($object)) {
            $attrs = str_replace(array('[', ']', '"', $name), null, $shortcode);
            $attrs = explode(' ', $attrs);
            if (is_array($attrs)) {
                foreach ($attrs as $attr) {
                    $_attr = explode('=', $attr);
                    if (count($_attr) == 2) {
                        if ($_attr[0] == 'ids') {
                            $object->$_attr[0] = explode(',', $_attr[1]);
                        } else {
                            $object->$_attr[0] = $_attr[1];
                        }
                    }
                }
            }
        }
        return $object;
    }
}
/* Get Shortcode Content */
if (!function_exists('ro_theme_get_shortcode_from_content')) {

    function ro_theme_get_shortcode_from_content($param) {
        global $post;
        $pattern = get_shortcode_regex();
        $content = $post->post_content;
        if (preg_match_all('/' . $pattern . '/s', $content, $matches) && array_key_exists(2, $matches) && in_array($param, $matches[2])) {
            $key = array_search($param, $matches[2]);
            return $matches[0][$key];
        }
    }

}
/* Remove Shortcode */
if (!function_exists('ro_theme_remove_shortcode_gallery')) {
	function ro_theme_remove_shortcode_gallery() {
		return null;
	}
}

/*Author*/
if ( ! function_exists( 'ro_theme_author_render' ) ) {
	function ro_theme_author_render() {
		ob_start();
		?>
		<?php if ( is_sticky() && is_home() && ! is_paged() ) { ?>
			<span class="featured-post"> <?php _e( 'Sticky', 'slova' ); ?></span>
		<?php } ?>
		<div class="ro-about-author clearfix">
			<div class="ro-author-avatar"><div class="ro-avatar-inner"><?php echo get_avatar( get_the_author_meta( 'ID' ), 170 ); ?></div></div>
			<div class="ro-author-info">
				<?php the_author_meta('description'); ?>
				<div class="ro-name"><span><?php the_author(); ?></span><span><?php _e('Creative art', 'slova'); ?></span></div>
				<ul class="ro-social">
					<li><a href="#"><i class="fa fa-facebook"></i></a></li>
					<li><a href="#"><i class="fa fa-twitter"></i></a></li>
					<li><a href="#"><i class="fa fa-tumblr"></i></a></li>
					<li><a href="#"><i class="fa fa-youtube"></i></a></li>
					<li><a href="#"><i class="fa fa-rss"></i></a></li>
				</ul>
			</div>
		</div>
		<?php
		return  ob_get_clean();
	} 
}
/*Custom comment list*/
function ro_theme_custom_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);

	if ( 'div' == $args['style'] ) {
		$tag = 'div';
		$add_below = 'comment';
	} else {
		$tag = 'li';
		$add_below = 'div-comment';
	}
?>
	<<?php echo esc_html( $tag ); ?> <?php comment_class( empty( $args['has_children'] ) ? 'ro-comment-item' : 'ro-comment-item parent' ) ?> id="comment-<?php comment_ID() ?>">
	<div class="ro-avatar">
		<?php if ( $args['avatar_size'] != 0 ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
	</div>
	<div class="ro-comment">
		<div class="ro-comment-inner">
			<div class="ro-name">
				<?php comment_author( get_comment_ID() ); ?>
			</div>
			<?php if ( $comment->comment_approved == '0' ) : ?>
				<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'slova' ); ?></em>
				<br />
			<?php endif; ?>
			<span class="ro-time">
				<a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>">
				<?php echo get_comment_date().' '.get_comment_time(); ?>
				</a>
				<?php edit_comment_link( __( '(Edit)', 'slova' ), '  ', '' ); ?>
			</span>
			<?php comment_reply_link( array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
		</div>
		<div class="ro-content"><?php comment_text(); ?></div>
	</div>
<?php
}

/* Social share */
if ( ! function_exists('ro_theme_social_share_post_render') ) {
	function ro_theme_social_share_post_render() {
		global $post;
		$post_title = $post->post_title;
		$permalink = get_permalink($post->ID);
		$title = get_the_title();
		$output = '';
		$output .= '<div class="ro-social-buttons">
			<a href="http://twitter.com/share?text='.$title.'&url='.$permalink.'"
				onclick="window.open(this.href, \'twitter-share\', \'width=550,height=235\');return false;">
				<i class="icon icon-twitter"></i>
			</a>             
			<a href="https://www.facebook.com/sharer/sharer.php?u='.$permalink.'"
				 onclick="window.open(this.href, \'facebook-share\',\'width=580,height=296\');return false;">
				<i class="icon icon-facebook"></i>
			</a>         
			<a href="https://plus.google.com/share?url='.$permalink.'"
			   onclick="window.open(this.href, \'google-plus-share\', \'width=490,height=530\');return false;">
				<i class="icon icon-gplus"></i>
			</a>
		</div>';
		return $output;
	}
}
