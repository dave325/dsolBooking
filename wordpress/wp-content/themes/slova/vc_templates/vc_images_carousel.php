<?php
/**
 * Shortcode attributes
 * @var $atts
 * @var $title
 * @var $onclick
 * @var $custom_links
 * @var $custom_links_target
 * @var $img_size
 * @var $images
 * @var $el_class
 * @var $mode
 * @var $slides_per_view
 * @var $wrap
 * @var $autoplay
 * @var $hide_pagination_control
 * @var $hide_prev_next_buttons
 * @var $speed
 * @var $partial_view
 * Shortcode class
 * @var $this WPBakeryShortCode_VC_images_carousel
 */
$output = '';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$gal_images = '';
$link_start = '';
$link_end = '';
$el_start = '';
$el_end = '';
$slides_wrap_start = '';
$slides_wrap_end = '';
$pretty_rand = $onclick == 'link_image' ? ' rel="prettyPhoto[rel-' . get_the_ID() . '-' . rand() . ']"' : '';

wp_enqueue_script( 'vc_carousel_js' );
wp_enqueue_style( 'vc_carousel_css' );
if ( 'link_image' === $onclick ) {
	wp_enqueue_script( 'prettyphoto' );
	wp_enqueue_style( 'prettyphoto' );
}

$el_class = $this->getExtraClass( $el_class );

if ( '' === $images ) {
	$images = '-1,-2,-3';
}

if ( 'custom_link' === $onclick ) {
	$custom_links = explode( ',', $custom_links );
}

$images = explode( ',', $images );
$i = - 1;
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_images_carousel wpb_content_element' . $el_class . ' vc_clearfix', $this->settings['base'], $atts );
$carousel_id = 'vc_images-carousel-' . WPBakeryShortCode_VC_images_carousel::getCarouselIndex();
$slider_width = $this->getSliderWidth( $img_size );
?>
<div
	class="<?php echo esc_attr( apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $css_class, $this->settings['base'], $atts ) ); ?>">
	<div class="wpb_wrapper">
<?php echo wpb_widget_title( array( 'title' => $title, 'extraclass' => 'wpb_gallery_heading' ) ) ?>
	<div id="<?php echo $carousel_id ?>" data-ride="vc_carousel"
	     data-wrap="<?php echo 'yes' === $wrap ? 'true' : 'false' ?>" style="width: <?php echo $slider_width ?>;"
	     data-interval="<?php echo 'yes' === $autoplay ? $speed : 0 ?>" data-auto-height="yes"
	     data-mode="<?php echo $mode ?>" data-partial="<?php echo $partial_view === 'yes' ? 'true' : 'false' ?>"
	     data-per-view="<?php echo $slides_per_view ?>"
	     data-hide-on-end="<?php echo 'yes' === $autoplay ? 'false' : 'true' ?>" class="vc_slide vc_images_carousel">
		<?php if ( 'yes' !== $hide_pagination_control ): ?>
			<!-- Indicators -->
			<ol class="vc_carousel-indicators">
				<?php for ( $z = 0; $z < count( $images ); $z ++ ): ?>
					<li data-target="#<?php echo $carousel_id ?>" data-slide-to="<?php echo $z ?>"></li>
				<?php endfor; ?>
			</ol>
		<?php endif; ?>
		<!-- Wrapper for slides -->
		<div class="vc_carousel-inner">
			<div class="vc_carousel-slideline">
				<div class="vc_carousel-slideline-inner">
					<?php foreach ( $images as $attach_id ): ?>
						<?php
						$i ++;
						if ( $attach_id > 0 ) {
							$post_thumbnail = wpb_getImageBySize( array(
								'attach_id' => $attach_id,
								'thumb_size' => $img_size
							) );
						} else {
							$post_thumbnail = array();
							$post_thumbnail['thumbnail'] = '<img src="' . vc_asset_url( 'vc/no_image.png' ) . '" />';
							$post_thumbnail['p_img_large'][0] = vc_asset_url( 'vc/no_image.png' );
						}
						$thumbnail = $post_thumbnail['thumbnail'];
						
						$attachment = get_post( $attach_id );
						?>
						<div class="vc_item">
							<div class="vc_inner">
								<?php if ( 'link_image' === $onclick ): ?>
									<?php $p_img_large = $post_thumbnail['p_img_large']; ?>
									<a class="prettyphoto"
									   href="<?php echo $p_img_large[0] ?>" <?php echo $pretty_rand; ?>>
										<?php echo $thumbnail ?>
									</a>
								<?php elseif ( 'custom_link' === $onclick && isset( $custom_links[ $i ] ) && '' !== $custom_links[ $i ] ): ?>
									<a
										href="<?php echo $custom_links[ $i ] ?>"<?php echo( ! empty( $custom_links_target ) ? ' target="' . $custom_links_target . '"' : '' ) ?>>
										<?php echo $thumbnail ?>
									</a>
								<?php else: ?>
									<?php echo $thumbnail ?>
								<?php endif; ?>
								<div class="ro-description"><?php echo $attachment->post_content; ?></div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
		<?php if ( 'yes' !== $hide_prev_next_buttons ): ?>
			<!-- Controls -->
			<a class="vc_left vc_carousel-control" href="#<?php echo $carousel_id ?>" data-slide="prev">
				<span class="icon-prev"></span>
			</a>
			<a class="vc_right vc_carousel-control" href="#<?php echo $carousel_id ?>" data-slide="next">
				<span class="icon-next"></span>
			</a>
		<?php endif; ?>
	</div>
	</div><?php echo $this->endBlockComment( '.wpb_wrapper' ) ?>
	</div><?php echo $this->endBlockComment( $this->getShortcode() ) ?>