<?php
/*
Template Name: No Title Bar Template
*/
?>
<?php get_header(); ?>
<?php
global $tb_options;
$tb_show_page_comment = (int) isset($tb_options['tb_page_show_page_comment']) ?  $tb_options['tb_page_show_page_comment']: 1;
?>
	<div class="main-content">
		<?php while ( have_posts() ) : the_post(); ?>

			<?php the_content(); ?>
			
			<?php if($tb_show_page_comment){ ?>
				<div class="tb-container">
					<?php if ( comments_open() || get_comments_number() ) comments_template(); ?>
				</div>
			<?php } ?>
			
		<?php endwhile; // end of the loop. ?>
	</div>
<?php get_footer(); ?>