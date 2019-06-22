<div class="ro-thumb">
	<?php if( has_post_thumbnail() ) the_post_thumbnail('thumbnail'); ?>
	<a href="#" class="ro-zoom"><span><i class="fa fa-search"></i></span></a>
</div>
<h6 class="ro-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h6>
<div class="ro-publish"><?php echo get_the_date(); ?></div>
<a class="ro-readmore ro-btn-small" href="<?php the_permalink(); ?>"><?php esc_html_e('Read more', 'slova'); ?></a>