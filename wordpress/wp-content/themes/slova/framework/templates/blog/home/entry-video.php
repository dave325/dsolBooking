<?php
global $tb_options;
$tb_blog_show_post_image = (int) isset($tb_options['tb_blog_show_post_image']) ? $tb_options['tb_blog_show_post_image'] : 1;
$tb_blog_show_post_title = (int) isset($tb_options['tb_blog_show_post_title']) ? $tb_options['tb_blog_show_post_title'] : 1;
$tb_blog_show_post_meta = (int) isset($tb_options['tb_blog_show_post_meta']) ? $tb_options['tb_blog_show_post_meta'] : 1;
$tb_blog_show_post_excerpt = (int) isset($tb_options['tb_blog_show_post_excerpt']) ? $tb_options['tb_blog_show_post_excerpt'] : 1;
$tb_blog_post_readmore_text = (int) isset($tb_options['tb_blog_post_readmore_text']) ? $tb_options['tb_blog_post_readmore_text'] : 1;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="ro-blog-sub-article">
		<?php if ( has_post_thumbnail() && $tb_blog_show_post_image ) { ?>
			<?php the_post_thumbnail('full'); ?>
		<?php } ?>
        <div class="wp-post-media">
            <?php
			if(!is_home()){
				$video_source = get_post_meta(get_the_ID(), 'tb_post_video_source', true);
				if(empty($video_source)) $video_source = 'post';
				$video_height = get_post_meta(get_the_ID(), 'tb_post_video_height', true);
				switch ($video_source) {
					case 'post':
						$shortcode = ro_theme_get_shortcode_from_content('wpvideo');
						if(!$shortcode){
							the_content();
						}
						if($shortcode):
							echo do_shortcode('[wpvideo tFnqC9XQ w=680]');
						endif;
						break;
					case 'youtube':
						$video_youtube = get_post_meta(get_the_ID(), 'tb_post_video_youtube', true);
						if($video_youtube){
							echo do_shortcode('[tb-video height="'.$video_height.'"]'.$video_youtube.'[/tb-video]');
						}
						break;
					case 'vimeo':
						$video_vimeo = get_post_meta(get_the_ID(), 'tb_post_video_vimeo', true);
						if($video_vimeo){
							echo do_shortcode('[tb-video height="'.$video_height.'"]'.$video_vimeo.'[/tb-video]');
						}
						break;
					case 'media':
						$video_type = get_post_meta(get_the_ID(), 'tb_post_video_type', true);
						$preview_image = get_post_meta(get_the_ID(), 'tb_post_preview_image', true);
						$video_file = get_post_meta(get_the_ID(), 'tb_post_video_url', true);
						if($video_file){
							echo do_shortcode('[video height="'.$video_height.'" '.$video_type.'="'.$video_file.'" poster="'.$preview_image.'"][/video]');
						}
						break;
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
