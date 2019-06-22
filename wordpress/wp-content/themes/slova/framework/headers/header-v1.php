<?php
	global $tb_options;
	$cl_stick = $tb_options['tb_stick_header'] ? 'ro-header-stick': '';
?>
<!-- Start Header -->
<header>
	<div class="ro-header-v1 <?php echo esc_attr($cl_stick); ?>">
		<div class="row">
			<div class="container">
				<div class="col-md-3 ro-logo">
					<a href="<?php echo esc_url(home_url()); ?>">
						<?php ro_theme_logo(); ?>
					</a>
					<div id="ro-hamburger" class="ro-hamburger visible-xs visible-sm"><i class="icon icon-menu"></i></div>
				</div>
				<div class="col-md-9">
					<?php
						$manage_location = $tb_options['tb_manage_location'];
						$arr = array(
						'theme_location' => $manage_location,
						'menu_id' => 'nav',
						'menu' => '',
						'container_class' => 'ro-menu-list hidden-xs hidden-sm',
						'menu_class'      => 'text-right ro-menu-sidebar-active',
						'echo'            => true,
						'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
						'depth'           => 0,
						);
						if ($manage_location) {
							wp_nav_menu( $arr );
						} else { ?>
						<div class="menu-list-default">
							<?php wp_page_menu();?>
						</div>    
					<?php } ?>
					<div class="ro-menu-sidebar hidden-xs">
						<?php
							echo '<a id="ro-search-form" href="javascript:void(0)"><i class="fa fa-search"></i></a>';
							if (is_active_sidebar("tbtheme-menu-right-sidebar")) dynamic_sidebar("tbtheme-menu-right-sidebar"); 
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</header>
<!-- End Header -->					