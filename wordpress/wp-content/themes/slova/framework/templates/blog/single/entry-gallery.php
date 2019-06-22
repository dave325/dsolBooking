<?php
global $tb_options;
$tb_post_show_post_image = (int) isset($tb_options['tb_post_show_post_image']) ? $tb_options['tb_post_show_post_image'] : 1;
$tb_post_show_post_title = (int) isset($tb_options['tb_post_show_post_title']) ? $tb_options['tb_post_show_post_title'] : 1;
$tb_post_show_post_meta = (int) isset($tb_options['tb_post_show_post_meta']) ? $tb_options['tb_post_show_post_meta'] : 1;
$tb_post_show_post_desc = (int) isset($tb_options['tb_post_show_post_desc']) ? $tb_options['tb_post_show_post_desc'] : 1;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="ro-blog-sub-article">
		<div class="ro-header">
			<div class="publish"><?php echo '<span>'.get_the_date('d').'</span><br>'.get_the_date('M'); ?></div>
			<?php if ( $tb_post_show_post_title ) { ?>
				<h4 class="ro-title"><?php the_title(); ?></h4>
			<?php } ?>
			<?php if ( $tb_post_show_post_meta ) { ?>
			<div class="ro-blog-meta">
				<?php if ( is_sticky() ) { ?>
					<span class="publish"><?php _e('<i class="fa fa-thumb-tack"></i> Sticky', 'slova'); ?></span>
				<?php } ?>
				<span class="author"><i class="fa fa-pencil"></i> <?php echo get_the_author(); ?></span>
				<span class="comment"><i class="fa fa-comments-o"></i> <?php comments_number( '0 comment', '1 comment', '% comments' ); ?></span>
			</div>
		<?php } ?>
		</div>
	
		<?php if ( has_post_thumbnail() && $tb_post_show_post_image ) { ?>
			<div class="ro-thumb"><?php the_post_thumbnail('full'); ?></div>
		<?php } ?>
		
		
		<?php if ( $tb_post_show_post_desc ) { ?> 
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