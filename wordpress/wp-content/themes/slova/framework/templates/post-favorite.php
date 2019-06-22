<?php
class tb_NectarLove {
	public $post_id;
	 function __construct($post_id = null)   {
		$this->post_id = $post_id;
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_ajax_nectar-love', array($this, 'ajax'));
		add_action('wp_ajax_nopriv_nectar-love', array($this, 'ajax'));
	}

	function enqueue_scripts() {
		wp_register_script( 'post-favorite', get_template_directory_uri() . '/assets/js/post-favorite.js', 'jquery', '1.0', TRUE );
		global $post;
		wp_localize_script('post-favorite', 'nectarLove', array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'postID' => $post ? $post->ID : 0,
			'rooturl' => home_url()
		));
		wp_enqueue_script('post-favorite');
	}

	function ajax($post_id) {
		//update
		if( isset($_POST['loves_id']) ) {
			$post_id = str_replace('nectar-love-', '', $_POST['loves_id']);
			echo ''.$this->love_post($post_id, 'update');
		}
		//get
		else {
			$post_id = str_replace('nectar-love-', '', $_POST['loves_id']);
			echo ''.$this->love_post($post_id, 'get');
		}
		exit;
	}

	function love_post($post_id, $action = 'get')
	{
		if(!is_numeric($post_id)) return;

		switch($action) {

			case 'get':
				$love_count = get_post_meta($post_id, '_nectar_love', true);
				if( !$love_count ){
					$love_count = 0;
					add_post_meta($post_id, '_nectar_love', $love_count, true);
				}

				return '<span class="ro-count">'. $love_count .'</span>';
				break;

			case 'update':
				$love_count = get_post_meta($post_id, '_nectar_love', true);
				if( isset($_COOKIE['nectar_love_'. $post_id]) ) return $love_count;

				$love_count++;
				update_post_meta($post_id, '_nectar_love', $love_count);
				setcookie('nectar_love_'. $post_id, $post_id, time()*20, '/');

				return '<span class="ro-count">'. $love_count .'</span>';
				break;

		}
	}

	function add_love() {
		global $post;

		$love_count = $this->love_post($post->ID);
		
		$output = '';
		if($love_count > 1) {
			$output = $love_count.'<span class="ro-unit"> Likes</span>';
		}else {
			$output = $love_count.'<span class="ro-unit"> Like</span>';
		}
		
  		$class = 'ro-nectar-love';
		$icon = 'fa fa-heart-o';
		if( isset($_COOKIE['nectar_love_'. $post->ID]) ){
			$class = 'ro-nectar-love loved';
			$icon = 'fa fa-heart';
		}
		$heart_icon = '<i class="'.$icon.'"></i>';
		return '<div class="ro-nectar-love-wrap"><a href="#" class="'. $class .'" id="nectar-love-'. $post->ID .'"> '. $output . $heart_icon .'</a></div>';
	}

}
global $post_favorite;
$post_favorite = new tb_NectarLove();
function post_favorite() {
	global $post_favorite;
	echo ''.$post_favorite->add_love();
}
?>
