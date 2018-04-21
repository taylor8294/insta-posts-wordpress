<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://taylrr.co.uk/
 * @since      1.0.0
 *
 * @package    Taylrr_Insta_Posts
 * @subpackage Taylrr_Insta_Posts/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Taylrr_Insta_Posts
 * @subpackage Taylrr_Insta_Posts/admin
 * @author     Alex Taylor <alex@taylrr.co.uk>
 */
class Taylrr_Insta_Posts_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	public function plugin_add_settings_link( $links ) {
	    $settings_link = '<a href="/wp-admin/admin.php?page=taylrr-insta-posts">' . __( 'Settings' ) . '</a>';
	    array_unshift( $links, $settings_link );
	  	return $links;
	}

	/**
	 * Register a custom menu page.
	 */
	public function admin_menu(){
	    add_menu_page( 
	        __( 'Insta Posts', 'taylrr-insta-posts' ), //$page_title
	        __( 'Insta Posts', 'taylrr-insta-posts' ), //$menu_title
	        'manage_options', //$capability
	        $this->plugin_name, //$menu_slug
	        function(){require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/taylrr-insta-posts-admin-display.php';}, //$callback
	        plugins_url( 'taylrr-insta-posts/admin/icon.png' ) //$icon_url
	    ); 
	}

	/**
	 * Register all settings for use in the settings page
	 */
	public function admin_init(){
	    register_setting(
	    	$this->plugin_name.'-settings-group', //$option_group
	    	$this->plugin_name.'-settings', //$option_name
	    	array(
		    	'sanitize_callback' => array($this,'sanitize_settings'),
		    	'default' => array(
		    		'username' => '',
		    		'widget_num' => 9,
		    		'widget_row' => 3,
		    		'footer_enabled' => true,
		    		'footer_num' => 9
		    	),
	    	) //$args
	    );
	    add_settings_section(
		    $this->plugin_name.'-settings-general', //$id
		    null, //$title
		    array($this, 'display_settings_section'), //$callback
		    $this->plugin_name //$page
		);
		add_settings_field(
			$this->plugin_name.'-settings-field-username', //$id
			__( 'Instagram Username', 'taylrr-insta-posts' ), //$title
			array( $this, 'display_settings_field_text' ), //$callback
			$this->plugin_name, //$page
			$this->plugin_name.'-settings-general', //$section
			array(
				'field_name' => 'username',
				'description' => __( 'The public account to grab images from for the website.', 'taylrr-insta-posts' )
			) //$args
		);
		add_settings_field(
			$this->plugin_name.'-settings-field-cache-time', //$id
			__( 'Time until cache expires (minutes)', 'taylrr-insta-posts' ), //$title
			array( $this, 'display_settings_field_text' ), //$callback
			$this->plugin_name, //$page
			$this->plugin_name.'-settings-general', //$section
			array(
				'field_name' => 'cache_time',
				'description' => __( 'The amount of time, in minutes, until the cache is discarded and new images cached. Eg. 60 = 1 hour, 360 = 6 hours, etc.', 'taylrr-insta-posts' ),
				'default' => 360
			) //$args
		);
		/*add_settings_field(
			$this->plugin_name.'-settings-field-widget_num', //$id
			__( 'Number of images in widget', 'taylrr-insta-posts' ), //$title
			array( $this, 'display_settings_field_dropdown' ), //$callback
			$this->plugin_name, //$page
			$this->plugin_name.'-settings-general', //$section
			array(
				'field_name' => 'widget_num',
				'description' => __( 'The total number of images to display in the sidebar widget.', 'taylrr-insta-posts' ),
				'options' => array(3=>3,4=>4,5=>5,6=>6,8=>8,9=>9,10=>10,12=>12,14=>14,15=>15),
				'default' => 9
			) //$args
		);
		add_settings_field(
			$this->plugin_name.'-settings-field-widget_row', //$id
			__( 'Number of images in widget row', 'taylrr-insta-posts' ), //$title
			array( $this, 'display_settings_field_dropdown' ), //$callback
			$this->plugin_name, //$page
			$this->plugin_name.'-settings-general', //$section
			array(
				'field_name' => 'widget_row',
				'description' => __( 'The number of images to display in each row of the sidebar widget.', 'taylrr-insta-posts' ),
				'options' => array(1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9,10=>10,12=>12,14=>14,15=>15),
				'default' => 3
			) //$args
		);
		add_settings_field(
			$this->plugin_name.'-settings-field-footer_enabled', //$id
			__( 'Enable footer images', 'taylrr-insta-posts' ), //$title
			array( $this, 'display_settings_field_checkbox' ), //$callback
			$this->plugin_name, //$page
			$this->plugin_name.'-settings-general', //$section
			array(
				'field_name' => 'footer_enabled',
				'description' => __( 'Whether or not to dispay images above the footer of the website.', 'taylrr-insta-posts' )
			) //$args
		);
		add_settings_field(
			$this->plugin_name.'-settings-field-footer_num', //$id
			__( 'Number of images in footer', 'taylrr-insta-posts' ), //$title
			array( $this, 'display_settings_field_dropdown' ), //$callback
			$this->plugin_name, //$page
			$this->plugin_name.'-settings-general', //$section
			array(
				'field_name' => 'footer_num',
				'description' => __( 'The total number of images to display in the footer.', 'taylrr-insta-posts' ),
				'options' => array(3=>3,4=>4,5=>5,6=>6,8=>8,9=>9,10=>10,12=>12,14=>14,15=>15),
				'default' => 9
			) //$args
		);*/
	}
	public function display_settings_section( $args ) {
		echo '<hr>';
	}
	public function display_settings_field_text( $args ) {
		$field_name = $args['field_name'];
		$description = $args['description'];
		$default = isset($args['default']) ? $args['default'] : '';
		$settings = get_option( $this->plugin_name . '-settings' );
		$setting = $default;
		if ( ! empty( $settings[ $field_name ] ) ) {
			$setting = $settings[ $field_name ];
		} ?>
		<div style="position:relative;width:25em">
			<input type="text" name="<?php echo $this->plugin_name . '-settings[' . $field_name . ']'; ?>" id="<?php echo $this->plugin_name; ?>-settings-field-<?php echo $field_name; ?>" value="<?php echo esc_attr( $setting ); ?>" class="regular-text" />
			<p class="description"><?php echo $args['is_html'] ? $description : esc_html( $description ); ?></p>
		</div>
		<?php
	}
	public function display_settings_field_dropdown( $args ) {
		$field_name = $args['field_name'];
		$description = $args['description'];
		$default = isset($args['default']) ? $args['default'] : '';
		$settings = get_option( $this->plugin_name . '-settings' );
		$setting = $default;
		if ( ! empty( $settings[ $field_name ] ) ) {
			$setting = $settings[ $field_name ];
		}
		$field_opts = $args['options'];
		?>
		<select name="<?php echo $this->plugin_name . '-settings[' . $field_name . ']'; ?>" id="<?php echo $this->plugin_name; ?>-settings-field<?php echo $field_name; ?>" />
		<?php
		foreach ( $field_opts as $field_opt_value => $field_opt_label ) {
			if ( $field_opt_value == $setting ) {
				$selected = 'selected="selected"';
			} else {
				$selected = '';
			}
			?>
				<option <?php echo $selected; ?> value="<?php echo esc_attr( $field_opt_value ); ?>"><?php echo esc_html( $field_opt_label ); ?></option>
			<?php
		}
		?>
		</select>
		<p class="description"><?php echo esc_html( $description ); ?></p>
		<?php
	}

	/**
	 * Sanitize user settings input
	 *
	 * @since    1.0.0
	 */
	public function sanitize_settings($input){
		$username = preg_replace('/[^A-Za-z0-9._,;]/', '', $input['username']);
		$username = str_replace(';', ',', $username);
		$username = explode(',', $username)[0];
	    return array('username'=>$username);
	}

	/**
	 * Register Insta Posts widget
	 */
	function widgets_init() {
		register_widget( 'InstaPostsWidget' );
	}

	function insta_posts_autocomplete() {
		$nonce = $_POST['wpnonce'];
		if (empty($_POST) || empty($_POST['wpnonce']) || empty($_POST['input']) || !wp_verify_nonce($nonce, 'taylrr-insta-posts-autocomplete') )
			die('Unauthorized.');
		$users = array(microtime(true));
		$url = 'https://www.instagram.com/web/search/topsearch/?query=';
		$input = $_POST['input'];
		try {
			$data = json_decode(file_get_contents($url.urlencode($input)));
		} catch(Exception $e){
			$data = false;
		}
		if(!$data) return $users;
		for ($i=0; $i < 5; $i++) {
			if(count($data->users) <= $i) break;
			array_push($users, array(
				'username' => $data->users[$i]->user->username,
				'full_name' => $data->users[$i]->user->full_name,
				'profile_pic_url' => $data->users[$i]->user->profile_pic_url
			));
		}
		wp_send_json( $users );
	}

	/**
	 * Register the stylesheets for the admin area.
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
		if($_GET['page']!=='taylrr-insta-posts')
			return;

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/taylrr-insta-posts-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
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
		if($_GET['page']!=='taylrr-insta-posts')
			return;
		
		wp_enqueue_script( $this->plugin_name.'-admin-script', plugin_dir_url( __FILE__ ) . 'js/taylrr-insta-posts-admin.js', array( 'jquery' ), $this->version, false );
		// in JavaScript, object properties are accessed as ajax_object.ajax_url
		wp_localize_script( $this->plugin_name.'-admin-script', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'ajax_nonce' => wp_create_nonce('taylrr-insta-posts-autocomplete') ) );
	}

}
/**
 * Define InstaPostsWidget class
 */
class InstaPostsWidget extends WP_Widget {
	function __construct() {
		// Instantiate the parent object
		parent::__construct(
			'taylrr-insta-posts-widget', //$id_base
			'Insta Posts Widget', //$name,
			array(                  // options
        		'description' => 'Display the latest Instagram images from any public account'
    		)
		);
	}

	function widget( $args, $instance ) {
		echo $args['before_widget'];
   		echo $instance['title'] ? $args['before_title'] . $instance['title'] .  $args['after_title'] : '';
   		$atts = shortcode_atts(
			array(
				'num' => '6',
				'row' => '2',
				'padding' => 5
			), $instance, 'insta_posts' );
   		$atts_string = '';
   		foreach ($atts as $key => $value) {
   			$atts_string .= $key.'='.$value.' ';
   		}
   		// print some HTML for the widget to display here
   		do_shortcode('[insta_posts '.$atts_string.']');
   		echo $args['after_widget'];
	}

	function update( $new_instance, $old_instance ) {
		// Save widget options
		return $new_instance;
		/*$instance = array();
		$instance[ 'title' ] = isset($new_instance[ 'title' ]) ? $new_instance[ 'title' ] : $old_instance['title'];
		$instance[ 'num' ] = isset($new_instance[ 'num' ]) ? $new_instance[ 'num' ] : $old_instance['num'];
		$instance[ 'row' ] = isset($new_instance[ 'row' ]) ? $new_instance[ 'row' ] : $old_instance['row'];
		$instance[ 'padding' ] = isset($new_instance[ 'padding' ]) ? $new_instance[ 'padding' ] : $old_instance['padding'];;
		return $instance;*/
	}

	function form( $instance ) {
		// Output admin widget options form
		$defaults = array(
    		'title' => 'Instagram',
    		'num' => 6,
    		'row' => 2,
    		'padding' => 5
		);
		$args = $defaults;
		foreach ($args as $opt => $val){
			if(isset($instance[$opt]))
				$args[$opt] = $instance[$opt];
		}
	    // markup for form ?>
	    <p>
	        <label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
	    	<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $args['title'] ); ?>">
	    </p>
	    <p>
	        <label for="<?php echo $this->get_field_id( 'num' ); ?>">Number of images:</label>
	        <select name="<?php echo $this->get_field_name( 'num' ); ?>" id="<?php echo $this->get_field_id( 'num' ); ?>">
			<?php
			$opts = array(3,4,5,6,8,9,10,12,14,15);
			foreach ( $opts as $opt ) {
				if ( intval($args['num']) === $opt ) {
					$selected = 'selected="selected"';
				} else {
					$selected = '';
				}
				?>
					<option <?php echo $selected; ?> value="<?php echo $opt; ?>"><?php echo $opt; ?></option>
				<?php
			}
			?>
			</select>
	    </p>
	    <p>
	        <label for="<?php echo $this->get_field_id( 'row' ); ?>">Number in a row:</label>
	        <select name="<?php echo $this->get_field_name( 'row' ); ?>" id="<?php echo $this->get_field_id( 'row' ); ?>">
			<?php
			$opts = array(1,2,3,4,5,6,7,8,9,10,12,14,15);
			foreach ( $opts as $opt ) {
				if ( intval($args['row']) === $opt ) {
					$selected = 'selected="selected"';
				} else {
					$selected = '';
				}
				?>
					<option <?php echo $selected; ?> value="<?php echo $opt; ?>"><?php echo $opt; ?></option>
				<?php
			}
			?>
			</select>
	    </p>
	    <p>
	        <label for="<?php echo $this->get_field_id( 'padding' ); ?>">Padding:</label>
	    	<input type="text" id="<?php echo $this->get_field_id( 'padding' ); ?>" name="<?php echo $this->get_field_name( 'padding' ); ?>" value="<?php echo esc_attr( $args['padding'] ); ?>">
	    </p>
		             
		<?php
	}
}