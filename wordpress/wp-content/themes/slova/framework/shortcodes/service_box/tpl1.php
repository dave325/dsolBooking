<a href="<?php echo esc_url($ex_link); ?>">
<div class="ro-service">
	<div class="ro-service-inner">
		<?php 
			if($icon) echo '<div class="ro-icon"><i class="'.esc_attr($icon).'"></i></div>';
			if($title) echo '<h5 class="ro-title">'.esc_html($title).'</h5>';
			if($content) echo '<div class="ro-content">'.$content.'</div>';
		?>
	</div>
</div>
</a>