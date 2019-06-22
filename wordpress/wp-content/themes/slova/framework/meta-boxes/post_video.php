<div id="tb-blog-loading" class="tb_loading" style="display: block;">
	<div id="followingBallsG">
	<div id="followingBallsG_1" class="followingBallsG">
	</div>
	<div id="followingBallsG_2" class="followingBallsG">
	</div>
	<div id="followingBallsG_3" class="followingBallsG">
	</div>
	<div id="followingBallsG_4" class="followingBallsG">
	</div>
	</div>
</div>
<div class='tb_metabox' style="display: none;">
	<?php
	$this->select('post_video_source',
			'Select Source',
			array(
					'post'		=>'From Post',
					'media' 	=> 'From Media',
					'youtube' 	=> 'Youtube',
					'vimeo' 	=> 'Vimeo'
			),
			'',
			''
	);
	?>
	<div id="tb_video_setting">
	<?php
	$this->select('post_video_type',
			'Video Type',
			array(
					'mp4' 	=> 'MP4',
					'webm' 	=> 'WebM',
					'ogg' 	=> 'Ogg'
			),
			'',
			''
	);
	$this->upload('post_video_url',
			'Video URL',
			__('Please upload the (MP4,WebM,Ogg) video file. You must include both formats.','slova')
	);
	$this->upload('post_preview_image',
			'Preview Image',
			__('Image should be at least 680px wide. Click the "Upload" button to begin uploading your image, followed by "Select File" once you have made your selection. Only applies to self hosted videos.','slova')
	);
	$this->text('post_video_youtube',
			'Youtube',
			'',
			__('Enter in a Youtube (http://youtu.be/ID)','slova')
	);
	$this->text('post_video_vimeo',
			'Vimeo',
			'',
			__('Enter in a Vimeo (http://vimeo.com/ID)','slova')
	);
	$this->text('post_video_height',
			'Video Height',
			'200px',
			'Set Height for Video'
	);
	?>
	<p class="tb_info"><i class="dashicons dashicons-dashboard"></i><a href="<?php echo 'http://www.w3schools.com/html/html5_video.asp';?>"><?php echo _e('Video Formats and Browser Support','slova'); ?></a></p>
	</div>
</div>
