<?php
	if ( ! isset( $content_width ) ) $content_width = 900;
	if ( is_singular() ) wp_enqueue_script( "comment-reply" );
	if ( ! function_exists( 'ro_theme_setup' ) ) {
		function ro_theme_setup() {
			global $tb_options;
			load_theme_textdomain( 'slova', get_template_directory() . '/languages' );
			// Add Custom Header.
			add_theme_support('custom-header');
			// Add RSS feed links to <head> for posts and comments.
			add_theme_support( 'automatic-feed-links' );
			// Enable support for Post Thumbnails, and declare two sizes.
			add_theme_support( 'post-thumbnails' );
			//Enable support for Title Tag
			add_theme_support( "title-tag" );
			// This theme uses wp_nav_menu() in two locations.
			register_nav_menus( array(
			'main_navigation'   => __( 'Main Navigation','slova' ),
			) );
			/*
				* Switch default core markup for search form, comment form, and comments
				* to output valid HTML5.
			*/
			add_theme_support( 'html5', array(
			'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
			) );
			/*
				* Enable support for Post Formats.
				* See http://codex.wordpress.org/Post_Formats
			*/
			add_theme_support( 'post-formats', array(
			'video', 'audio', 'quote', 'link', 'gallery',
			) );
			// This theme allows users to set a custom background.
			add_theme_support( 'custom-background', apply_filters( 'ro_theme_custom_background_args', array(
			'default-color' => 'f5f5f5',
			) ) );
			// Add support for featured content.
			add_theme_support( 'featured-content', array(
			'featured_content_filter' => 'ro_theme_get_featured_posts',
			'max_posts' => 6,
			) );
			// This theme uses its own gallery styles.
			add_filter( 'use_default_gallery_style', '__return_false' );
			// Register a new image size
			add_image_size( 'slova-blog-special-large', 560, 280, true );
			add_image_size( 'slova-blog-special-small', 270, 270, true );
		}
	}
	add_action( 'after_setup_theme', 'ro_theme_setup' );
	/* Favicon */
	if (!function_exists('ro_theme_favicon')) {
		function ro_theme_favicon() {
			global $tb_options;
			$icon = $tb_options['tb_favicon_image']['url'] ? $tb_options['tb_favicon_image']['url']: URI_PATH.'/favicon.ico';
			echo '<link rel="shortcut icon" href="' . esc_url($icon) . '"/>';
		}
	}
	add_action('wp_head', 'ro_theme_favicon');
	/* Favicon */
	if (!function_exists('ro_theme_logo')) {
		function ro_theme_logo() {
			global $tb_options;
			$logo = $tb_options['tb_logo_image']['url'] ? $tb_options['tb_logo_image']['url'] : URI_PATH.'/assets/images/platonic-brand.png';
			echo '<img src="'.esc_url($logo).'" alt="Logo">';
		}
	}
	/* Custom Site Title */
	if (!function_exists('ro_theme_wp_title')) {
		function ro_theme_wp_title( $title, $sep ) {
			global $paged, $page;
			if ( is_feed() ) {
				return $title;
			}
			// Add the site name.
			$title .= get_bloginfo( 'name', 'display' );
			// Add the site description for the home/front page.
			$site_description = get_bloginfo( 'description', 'display' );
			if ( $site_description && ( is_home() || is_front_page() ) ) {
				$title = "$title $sep $site_description";
			}
			// Add a page number if necessary.
			if ( $paged >= 2 || $page >= 2 ) {
				$title = "$title $sep " . sprintf( __( 'Page %s', 'slova' ), max( $paged, $page ) );
			}
			return $title;
		}
	}
	add_filter( 'wp_title', 'ro_theme_wp_title', 10, 2 );
	/* Page title */
	if (!function_exists('ro_theme_page_title')) {
		function ro_theme_page_title() { 
			ob_start();
			if( is_home() ){
				_e('Home', 'slova');
				}elseif(is_search()){
				_e('Search', 'slova');
				}elseif (!is_archive()) {
				the_title();
				} else { 
				if (is_category()){
					single_cat_title();
					}elseif(get_post_type() == 'recipe' || get_post_type() == 'portfolio' || get_post_type() == 'produce' || get_post_type() == 'team' || get_post_type() == 'testimonial' || get_post_type() == 'myclients' || get_post_type() == 'product'){
					single_term_title();
					}elseif (is_tag()){
					single_tag_title();
					}elseif (is_author()){
					printf(__('Author: %s', 'slova'), '<span class="vcard">' . get_the_author() . '</span>');
					}elseif (is_day()){
					printf(__('Day: %s', 'slova'), '<span>' . get_the_date() . '</span>');
					}elseif (is_month()){
					printf(__('Month: %s', 'slova'), '<span>' . get_the_date() . '</span>');
					}elseif (is_year()){
					printf(__('Year: %s', 'slova'), '<span>' . get_the_date() . '</span>');
					}elseif (is_tax('post_format', 'post-format-aside')){
					_e('Asides', 'slova');
					}elseif (is_tax('post_format', 'post-format-gallery')){
					_e('Galleries', 'slova');
					}elseif (is_tax('post_format', 'post-format-image')){
					_e('Images', 'slova');
					}elseif (is_tax('post_format', 'post-format-video')){
					_e('Videos', 'slova');
					}elseif (is_tax('post_format', 'post-format-quote')){
					_e('Quotes', 'slova');
					}elseif (is_tax('post_format', 'post-format-link')){
					_e('Links', 'slova');
					}elseif (is_tax('post_format', 'post-format-status')){
					_e('Statuses', 'slova');
					}elseif (is_tax('post_format', 'post-format-audio')){
					_e('Audios', 'slova');
					}elseif (is_tax('post_format', 'post-format-chat')){
					_e('Chats', 'slova');
					}else{
					_e('Archives', 'slova');
				}
			}
			return ob_get_clean();
		}
	}
	/* Page breadcrumb */
	if (!function_exists('ro_theme_page_breadcrumb')) {
		function ro_theme_page_breadcrumb($delimiter='<i class="fa fa-angle-right"></i>') {
			ob_start();
			$home = __('Home', 'slova');
			global $post;
			$homeLink = home_url();
			if( is_home() ){
				_e('Home', 'slova');
				}else{
				echo '<a href="' . $homeLink . '">' . $home . '</a> ' . $delimiter . ' ';
			}
			if ( is_category() ) {
				$thisCat = get_category(get_query_var('cat'), false);
				if ($thisCat->parent != 0) echo get_category_parents($thisCat->parent, TRUE, ' ' . $delimiter . ' ');
				echo '<span class="current">' . __('Archive by category: ', 'slova') . single_cat_title('', false) . '</span>';
				} elseif ( is_search() ) {
				echo '<span class="current">' . __('Search results for: ', 'slova') . get_search_query() . '</span>';
				} elseif ( is_day() ) {
				echo '<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F').' '. get_the_time('Y') . '</a> ' . $delimiter . ' ';
				echo '<span class="current">' . get_the_time('d') . '</span>';
				} elseif ( is_month() ) {
				echo '<span class="current">' . get_the_time('F'). ' '. get_the_time('Y') . '</span>';
				} elseif ( is_single() && !is_attachment() ) {
				if ( get_post_type() != 'post' ) {
					if(get_post_type() == 'portfolio'){
						$terms = get_the_terms(get_the_ID(), 'portfolio_category', '' , '' );
						if($terms) {
							the_terms(get_the_ID(), 'portfolio_category', '' , ', ' );
							echo ' ' . $delimiter . ' ' . '<span class="current">' . get_the_title() . '</span>';
							}else{
							echo '<span class="current">' . get_the_title() . '</span>';
						}
						}elseif(get_post_type() == 'recipe'){
						$terms = get_the_terms(get_the_ID(), 'recipe_category', '' , '' );
						if($terms) {
							the_terms(get_the_ID(), 'recipe_category', '' , ', ' );
							echo ' ' . $delimiter . ' ' . '<span class="current">' . get_the_title() . '</span>';
							}else{
							echo '<span class="current">' . get_the_title() . '</span>';
						}
						}elseif(get_post_type() == 'produce'){
						$terms = get_the_terms(get_the_ID(), 'produce_category', '' , '' );
						if($terms) {
							the_terms(get_the_ID(), 'produce_category', '' , ', ' );
							echo ' ' . $delimiter . ' ' . '<span class="current">' . get_the_title() . '</span>';
							}else{
							echo '<span class="current">' . get_the_title() . '</span>';
						}
						}elseif(get_post_type() == 'team'){
						$terms = get_the_terms(get_the_ID(), 'team_category', '' , '' );
						if($terms) {
							the_terms(get_the_ID(), 'team_category', '' , ', ' );
							echo ' ' . $delimiter . ' ' . '<span class="current">' . get_the_title() . '</span>';
							}else{
							echo '<span class="current">' . get_the_title() . '</span>';
						}
						}elseif(get_post_type() == 'testimonial'){
						$terms = get_the_terms(get_the_ID(), 'testimonial_category', '' , '' );
						if($terms) {
							the_terms(get_the_ID(), 'testimonial_category', '' , ', ' );
							echo ' ' . $delimiter . ' ' . '<span class="current">' . get_the_title() . '</span>';
							}else{
							echo '<span class="current">' . get_the_title() . '</span>';
						}
						}elseif(get_post_type() == 'myclients'){
						$terms = get_the_terms(get_the_ID(), 'clientscategory', '' , '' );
						if($terms) {
							the_terms(get_the_ID(), 'clientscategory', '' , ', ' );
							echo ' ' . $delimiter . ' ' . '<span class="current">' . get_the_title() . '</span>';
							}else{
							echo '<span class="current">' . get_the_title() . '</span>';
						}
						}elseif(get_post_type() == 'product'){
						$terms = get_the_terms(get_the_ID(), 'product_cat', '' , '' );
						if($terms) {
							the_terms(get_the_ID(), 'product_cat', '' , ', ' );
							echo ' ' . $delimiter . ' ' . '<span class="current">' . get_the_title() . '</span>';
							}else{
							echo '<span class="current">' . get_the_title() . '</span>';
						}
						}else{
						$post_type = get_post_type_object(get_post_type());
						$slug = $post_type->rewrite;
						echo '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a>';
						echo ' ' . $delimiter . ' ' . '<span class="current">' . get_the_title() . '</span>';
					}
					} else {
					$cat = get_the_category(); $cat = $cat[0];
					$cats = get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
					echo ''.$cats;
					echo '<span class="current">' . get_the_title() . '</span>';
				}
				} elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
				$post_type = get_post_type_object(get_post_type());
				if($post_type) echo '<span class="current">' . $post_type->labels->singular_name . '</span>';
				} elseif ( is_attachment() ) {
				echo '<span class="current">' . get_the_title() . '</span>';
				} elseif ( is_page() && !$post->post_parent ) {
				echo '<span class="current">' . get_the_title() . '</span>';
				} elseif ( is_page() && $post->post_parent ) {
				$parent_id  = $post->post_parent;
				$breadcrumbs = array();
				while ($parent_id) {
					$page = get_page($parent_id);
					$breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
					$parent_id = $page->post_parent;
				}
				$breadcrumbs = array_reverse($breadcrumbs);
				for ($i = 0; $i < count($breadcrumbs); $i++) {
					echo ''.$breadcrumbs[$i];
					if ($i != count($breadcrumbs) - 1)
					echo ' ' . $delimiter . ' ';
				}
				echo ' ' . $delimiter . ' ' . '<span class="current">' . get_the_title() . '</span>';
				} elseif ( is_tag() ) {
				echo '<span class="current">' . __('Posts tagged: ', 'slova') . single_tag_title('', false) . '</span>';
				} elseif ( is_author() ) {
				global $author;
				$userdata = get_userdata($author);
				echo '<span class="current">' . __('Articles posted by ', 'slova') . $userdata->display_name . '</span>';
				} elseif ( is_404() ) {
				echo '<span class="current">' . __('Error 404', 'slova') . '</span>';
			}
			if ( get_query_var('paged') ) {
				if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
				echo ' '.$delimiter.' '.__('Page', 'slova') . ' ' . get_query_var('paged');
				if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
			}
			return ob_get_clean();
		}
	}
	/* Custom excerpt */
	function ro_custom_excerpt($limit, $more) {
		$excerpt = explode(' ', get_the_excerpt(), $limit);
		if (count($excerpt) >= $limit) {
			array_pop($excerpt);
			$excerpt = implode(" ", $excerpt) . $more;
			} else {
			$excerpt = implode(" ", $excerpt);
		}
		$excerpt = preg_replace('`\[[^\]]*\]`', '', $excerpt);
		return $excerpt;
	}
	/* Display navigation to next/previous set of posts */
	if ( ! function_exists( 'ro_theme_paging_nav' ) ) {
		function ro_theme_paging_nav() {
			if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
				return;
			}
			$paged        = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
			$pagenum_link = html_entity_decode( get_pagenum_link() );
			$query_args   = array();
			$url_parts    = explode( '?', $pagenum_link );
			if ( isset( $url_parts[1] ) ) {
				wp_parse_str( $url_parts[1], $query_args );
			}
			$pagenum_link = remove_query_arg( array_keys( $query_args ), $pagenum_link );
			$pagenum_link = trailingslashit( $pagenum_link ) . '%_%';
			$format  = $GLOBALS['wp_rewrite']->using_index_permalinks() && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
			$format .= $GLOBALS['wp_rewrite']->using_permalinks() ? user_trailingslashit( 'page/%#%', 'paged' ) : '?paged=%#%';
			// Set up paginated links.
			$links = paginate_links( array(
			'base'     => $pagenum_link,
			'format'   => $format,
			'total'    => $GLOBALS['wp_query']->max_num_pages,
			'current'  => $paged,
			'mid_size' => 1,
			'add_args' => array_map( 'urlencode', $query_args ),
			'prev_text' => __( '<i class="fa fa-angle-left"></i>', 'slova' ),
			'next_text' => __( '<i class="fa fa-angle-right"></i>', 'slova' ),
			) );
			if ( $links ) {
			?>
			<nav class="ro-pagination ro-uppercase text-center" role="navigation">
				<?php echo ''.$links; ?>
			</nav>
			<?php
			}
		}
	}
	/* Display navigation to next/previous post */
	if ( ! function_exists( 'ro_theme_post_nav' ) ) {
		function ro_theme_post_nav() {
			$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
			$next     = get_adjacent_post( false, '', false );
			if ( ! $next && ! $previous ) {
				return;
			}
		?>
		<nav class="ro-post-nav clearfix">
			<?php
				previous_post_link( '%link', '<i class="fa fa-angle-double-left"></i>'.get_the_title() );
				next_post_link( 	'%link', get_the_title().'<i class="fa fa-angle-double-right"></i>' );
			?>
		</nav>
		<?php
		}
	}
	/* Display navigation to next/previous post postfolio */
	if ( ! function_exists( 'ro_theme_post_portfolio_nav' ) ) {
		function ro_theme_post_portfolio_nav() {
			$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
			$next     = get_adjacent_post( false, '', false );
			if ( ! $next && ! $previous ) {
				return;
			}
		?>
		<nav class="ro-post-nav clearfix">
			<?php
				previous_post_link( '%link', __( '<i class="fa fa-angle-left"></i>', 'slova' ) );
				next_post_link(     '%link',     __('<i class="fa fa-angle-right"></i>', 'slova' ) );
			?>
		</nav>
		<?php
		}
	}
	/* Title Bar */
	if ( ! function_exists( 'ro_theme_title_bar' ) ) {
		function ro_theme_title_bar($tb_show_page_title, $tb_show_page_breadcrumb) {
			global $tb_options;
			if($tb_show_page_title || $tb_show_page_breadcrumb) { 
			?>
			<div class="ro-section-title-bar text-center">
				<div class="container">
					<div class="row">
						<div class="col-md-12">
							<?php
								$title_bar_icon = get_post_meta(get_the_ID(), 'tb_title_bar_icon', true);
								if(!$title_bar_icon) $title_bar_icon = "fa fa-pencil";
								if($title_bar_icon) echo '<div class="ro-icon-style1"><i class="'.esc_attr($title_bar_icon).'"></i></div>';
							?>
							<?php if($tb_show_page_title) echo '<h2>'.ro_theme_page_title().'</h2>'; ?>
							<?php if($tb_show_page_breadcrumb) echo '<div class="ro-path">'.ro_theme_page_breadcrumb($delimiter='<i class="fa fa-angle-right"></i>').'</div>'; ?>
						</div>
					</div>
				</div>
			</div>
			<?php 
			}
		}
	}
	/* This code filters the Categories archive widget to include the post count inside the link */
	add_filter('wp_list_categories', 'ro_theme_cat_count_span');
	function ro_theme_cat_count_span($links) {
		$links = str_replace('</a> (', ' <span>', $links);
		$links = str_replace('(', '', $links);
		$links = str_replace(')', '</span></a>', $links);
		return $links;
	}
	/* This code filters the Archive widget to include the post count inside the link */
	add_filter('get_archives_link', 'ro_theme_archive_count_span');
	function ro_theme_archive_count_span($links) {
		$links = str_replace('(', '<span class="count">', $links);
		$links = str_replace(')', '</span></a>', $links);
		return $links;
	}
	add_filter ( 'wp_tag_cloud', 'ro_theme_tag_cloud_count' );
	function ro_theme_tag_cloud_count( $return ) {
		$tags = explode('</a>', $return);
		foreach( $tags as $tag ) {
			$tagn[] = '<span>'.$tag.'</a>';
		}
		$return = implode('</span>', $tagn);
		return $return;
	}			