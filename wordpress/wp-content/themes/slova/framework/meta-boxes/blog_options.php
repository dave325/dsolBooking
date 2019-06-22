<div id="tb-blog-metabox" class='tb_metabox' style="display: none;">
	<div id="tb-tab-blog" class='categorydiv'>
		<ul class='category-tabs'>
		   <li class='tb-tab'><a href="#tabs-general"><i class="dashicons dashicons-admin-settings"></i> <?php echo _e('GENERAL','slova');?></a></li>
		</ul>
		<div class='tb-tabs-panel'>
			<div id="tabs-general">
				<p class="tb_title_bar tb-title-mb"><i class="dashicons dashicons-menu"></i><?php echo _e('Title Bar Setting','slova'); ?></p>
				<?php
					$this->text('title_bar_icon',
							'Icon',
							'',
							__('Enter icon of title bar in this page.','slova')
					);
					$this->upload('title_bar_bg',
							'Background',
							'',
							__('Select image backgroud of title bar in this page.','slova')
					);
				?>
			</div>
		</div>
	</div>
</div>