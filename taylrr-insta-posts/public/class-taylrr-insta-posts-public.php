<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://taylrr.co.uk/
 * @since      1.0.0
 *
 * @package    Taylrr_Insta_Posts
 * @subpackage Taylrr_Insta_Posts/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Taylrr_Insta_Posts
 * @subpackage Taylrr_Insta_Posts/public
 * @author     Alex Taylor <alex@taylrr.co.uk>
 */
class Taylrr_Insta_Posts_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Fetch array of users photos, from cache if we have it, scrape if not
	 *
	 * @since    1.0.0
	 */
	public function get_insta_posts($user){
		$settings = get_option( $this->plugin_name . '-settings' );
		if(!$user) {
			$user = $settings['username'];
			if(!$user) return false;
		}
		$cache_time = (isset($settings['cache_time']) && intval($settings['cache_time'])>=0 ? intval($settings['cache_time']) : 360)*60;
		$filepath = plugin_dir_path( __DIR__ ).'cache/'.$user;
		if(file_exists($filepath) && filemtime($filepath) > time()-$cache_time && file_get_contents($filepath)!='[]'){
			return json_decode(file_get_contents($filepath));
		}

		$args = array(
			'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36',
		    'headers' => "Accept: application/xml,application/xhtml+xml,text/html;q=0.9, text/plain;q=0.8,image/png,*/*;q=0.5\r\n"
		);
		$response = wp_remote_get('http://instagram.com/'.$user.'/', $args);
		$html = $response['body'];
		$code=wp_remote_retrieve_response_code($response);
		if($code < 400){
			try {
			    $jsonPos = stripos($html,'window._sharedData = ')+21;
				$jsonText = substr($html,$jsonPos,stripos($html,'};</script>',$jsonPos)-($jsonPos-1));
				$data = json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $jsonText) );
			} catch(Exception $e){
				$data = false;
			}
			if(is_object($data)){
				$images = array();
				foreach ($data->entry_data->ProfilePage[0]->graphql->user->edge_owner_to_timeline_media->edges as $i => $obj) {
					$image = (object)array(
						'caption' => $obj->node->edge_media_to_caption->edges[0]->node->text,
						'code' => $obj->node->shortcode,
						'thumbnail_src' => $obj->node->thumbnail_src
					);
					if(!empty($image->thumbnail_src))
						array_push($images, $image);
				}
				if(count($images)){
					file_put_contents($filepath, json_encode($images));
					return $images;
				}
			}
		}
		if(file_exists($filepath))
			return json_decode(file_get_contents($filepath));
		else
			return false;
	}

	/**
	 * The main functionality of the plugin, the shortcode function.
	 *
	 * @since    1.0.0
	 */
	public function insta_posts_shortcode($args){
		$images = $this->get_insta_posts(isset($args['user']) ? $args['user'] : null);

		if(!is_array($images) || !count($images)){
			echo '<p><em style="color:lightgray">Instrgram images could not be fetched at the moment.</em></p>';
			return false;
		}

		$defaults = array(
    		'num' => 6,
    		'row' => 2,
    		'padding' => 5
		);
		foreach ($defaults as $key => $value) {
			if(!isset($args[$key]))
				$args[$key] = $value;
		}
?>
		<div class="insta-posts">
<?php
		$i = 0;
		for($row=0,$numrows=ceil($args['num']/$args['row']); $row < $numrows; $row++) {
?>
			<div class="insta-posts-row"<?php if($args['padding']) echo ' style="margin-bottom:'.$args['padding'].'px"' ?>>
<?php
			for ($col=0; $col < $args['row'] && $i < $args['num'] && $i < count($images); $col++, $i++) {
?>
				<div class="insta-posts-image-wrap" style="
					width:<?php echo floor(10000/$args['row'])/100; ?>%;
					padding: 0 <?php echo $args['padding']/2; ?>px;
				">
					<a class="insta-posts-image" style="background-image:url(<?php echo $images[$i]->thumbnail_src; ?>);" href="http://instagram.com/p/<?php echo $images[$i]->code; ?>/" title="<?php $caption_parts = array_values(array_filter(array_map('trim',preg_split("/[\n\\.!]+/", $images[$i]->caption)))); echo strcasecmp($caption_parts[0], "new BLOG POST")==0?$caption_parts[1]:$caption_parts[0]; ?>" target="_blank"></a>
				</div>
<?php
			}
?>
			</div>
<?php
		}
?>
		</div> <?php
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Taylrr_Insta_Posts_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Taylrr_Insta_Posts_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/taylrr-insta-posts-public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Taylrr_Insta_Posts_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Taylrr_Insta_Posts_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/taylrr-insta-posts-public.js', array( 'jquery' ), $this->version, false );
	}

}
