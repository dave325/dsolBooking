<div class='tb_metabox'>
	<?php
	$this->select('post_audio_type',
			'Audio Type',
			array(
					'post' 	=> 'From Post',
					'ogg' 		=> 'OGG',
					'mp3' 		=> 'MP3',
					'wav' 		=> 'WAV'
			),
			'',
			''
	);
	$this->upload('post_audio_url',
			'File URL',
			'',
			__('Please enter in the URL to the (OGG,MP3,WAV) file','slova')
	);
	?>
</div>
