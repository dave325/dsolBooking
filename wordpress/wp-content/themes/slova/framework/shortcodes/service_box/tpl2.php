<a href="<?php echo esc_url($ex_link); ?>">
	<div class="ro-service">
		<div class="ro-overlay"></div>
		<?php echo wp_get_attachment_image( $img, 'full' ); ?>
		<div class="ro-service-inner">
			<?php
				if($icon) echo '<i class="'.esc_attr($icon).'"></i>';
				if($title) echo '<h5>'.esc_html($title).'</h5>';
			?>
		</div>
	</div>
</a>