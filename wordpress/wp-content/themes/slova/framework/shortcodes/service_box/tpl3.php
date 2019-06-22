<a href="<?php echo esc_url($ex_link); ?>">
	<div class="ro-service">
		<?php
			echo wp_get_attachment_image( $img, 'full' );
			if($title) echo '<div class="ro-title">'.esc_html($title).'</div>';
			if($content) echo '<div class="ro-content">'.$content.'</div>';
		?>
	</div>
</a>