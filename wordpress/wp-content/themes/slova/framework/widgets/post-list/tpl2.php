<div class="ro-thumb">
	<?php if( has_post_thumbnail() ) the_post_thumbnail('thumbnail'); ?>
	<a href="<?php the_permalink(); ?>" class="ro-zoom"><span><i class="fa fa-share"></i></span></a>
</div>
<h6 class="ro-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h6>
<div class="ro-publish"><?php echo get_the_date(); ?></div>