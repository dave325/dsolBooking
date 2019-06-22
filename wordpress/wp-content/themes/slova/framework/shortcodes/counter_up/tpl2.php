<div class="ro-counter">
	<?php
		if($icon) echo '<i class="'.esc_attr($icon).'"></i>';
		if($number) echo '<span class="ro-number">'.$number.'</span>';
		if($title) echo '<h4 class="ro-title">'.$title.'</h4>';
		if($content) echo '<div class="ro-content">'.$content.'</div>';
	?>
</div>