<div id="tb_testimonial_metabox" class='tb-testimonial-metabox'>
	<?php
	$this->upload('testimonial_avatar',
			'Avatar',
			'',
			__('Select avatar in this post.','slova')
	);
	$this->text('testimonial_position',
			'Position',
			'',
			__('Enter position in this post.','slova')
	);
	
	?>
</div>
