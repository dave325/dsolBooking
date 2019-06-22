<?php
global $tb_options;
$tb_blog_show_post_image = (int) isset($tb_options['tb_blog_show_post_image']) ? $tb_options['tb_blog_show_post_image'] : 1;
$tb_blog_show_post_title = (int) isset($tb_options['tb_blog_show_post_title']) ? $tb_options['tb_blog_show_post_title'] : 1;
$tb_blog_show_post_meta = (int) isset($tb_options['tb_blog_show_post_meta']) ? $tb_options['tb_blog_show_post_meta'] : 1;
$tb_blog_show_post_excerpt = (int) isset($tb_options['tb_blog_show_post_excerpt']) ? $tb_options['tb_blog_show_post_excerpt'] : 1;
$tb_blog_post_readmore_text = (int) isset($tb_options['tb_blog_post_readmore_text']) ? $tb_options['tb_blog_post_readmore_text'] : 1;
$audio_type = get_post_meta(get_the_ID(), 'tb_post_audio_type', true);
$audio_url = get_post_meta(get_the_ID(), 'tb_post_audio_url', true);
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="ro-blog-sub-article">
		<div class="wp-post-media">
			<?php
			if(!is_home()){
				if ($audio_type == 'post'){
					$shortcode = ro_theme_get_shortcode_from_content('audio');
					if($shortcode) echo do_shortcode($shortcode);
				} elseif ($audio_type == 'ogg' || $audio_type == 'mp3' || $audio_type == 'wav'){
					if($audio_url) echo do_shortcode('[audio '.$audio_type.'="'.$audio_url.'"][/audio]');
				}
			}
			?>
		</div>
		<?php if ( $tb_blog_show_post_title ) { ?>
			<h4 class="ro-uppercase"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h4>
		<?php } ?>
		<?php if ( $tb_blog_show_post_meta ) { ?>
			<div class="ro-blog-meta">
				<?php if ( is_sticky() ) { ?>
					<span class="publish"><?php _e('<i class="fa fa-thumb-tack"></i> Sticky', 'slova'); ?></span>
				<?php } ?>
				<span class="publish"><?php _e('<i class="fa fa-clock-o"></i> ', 'slova'); echo get_the_date(); ?></span>
				<span class="author"><?php _e('<i class="fa fa-user"></i> ', 'slova'); echo get_the_author(); ?></span>
				<span class="categories"><?php the_terms(get_the_ID(), 'category', __('<i class="fa fa-folder-open"></i> ', 'slova') , ', ' ); ?></span>
				<span class="tags"><?php the_tags( __('<i class="fa fa-tags"></i> ', 'slova'), ', ', '' ); ?> </span>
			</div>
		<?php } ?>
		<?php if ( $tb_blog_show_post_excerpt ) { ?> 
			<div class="ro-sub-content clearfix">
				<?php
					the_content();
					wp_link_pages(array(
						'before' => '<div class="page-links">' . __('Pages:', 'slova'),
						'after' => '</div>',
					));
				?>
			</div>
		<?php } ?>
	</div>
</article>