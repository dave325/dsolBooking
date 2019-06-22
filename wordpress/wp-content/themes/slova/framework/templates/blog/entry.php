<?php
global $tb_options;
$tb_blog_show_post_image = (int) isset($tb_options['tb_blog_show_post_image']) ? $tb_options['tb_blog_show_post_image'] : 1;
$tb_blog_show_post_title = (int) isset($tb_options['tb_blog_show_post_title']) ? $tb_options['tb_blog_show_post_title'] : 1;
$tb_blog_show_post_meta = (int) isset($tb_options['tb_blog_show_post_meta']) ? $tb_options['tb_blog_show_post_meta'] : 1;
$tb_blog_show_post_excerpt = (int) isset($tb_options['tb_blog_show_post_excerpt']) ? $tb_options['tb_blog_show_post_excerpt'] : 1;
$tb_blog_post_readmore_text = (int) isset($tb_options['tb_blog_post_readmore_text']) ? $tb_options['tb_blog_post_readmore_text'] : 1;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="ro-blog-item">
		<div class="ro-thumb">
			<?php if ( has_post_thumbnail() && $tb_blog_show_post_image ) { ?>
				<?php the_post_thumbnail('full'); ?>
			<?php } ?>
		</div>
		<div class="ro-content">
			<?php if ( $tb_blog_show_post_title ) { ?>
				<h4 class="ro-title ro-text-ellipsis"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h4>
			<?php } ?>
			<?php if ( $tb_blog_show_post_excerpt ) { ?> 
				<div class="ro-excerpt"><?php the_excerpt(); ?></div>
			<?php } ?>
			<?php if ( $tb_blog_show_post_meta ) { ?>
				<div class="ro-meta">
					<div class="ro-author"><div class="ro-meta-btn"><i class="fa fa-pencil"></i><span><?php echo get_the_author(); ?></span></div></div>
					<div class="ro-comment"><div class="ro-meta-btn"><i class="fa fa-comments-o"></i><span><?php comments_number( '0 Comment', '1 Comment', '% Comments' ); ?></span></div></div>
					<div class="ro-publish"><div class="ro-meta-btn"><i class="fa fa-calendar"></i><span><?php echo get_the_date('M d, Y'); ?></span></div></div>
					<?php if ( $tb_blog_post_readmore_text ) { ?>
						<a href="<?php the_permalink(); ?>" class="ro-readmore pull-right"><span><?php echo $tb_blog_post_readmore_text; ?></span></a>
					<?php } ?>
				</div>
			<?php } ?>
		</div>
	</div>
</article>