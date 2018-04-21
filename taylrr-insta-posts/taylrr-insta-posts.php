<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://taylrr.co.uk/
 * @since             1.0.0
 * @package           Taylrr_Insta_Posts
 *
 * @wordpress-plugin
 * Plugin Name:       Insta Posts by Taylrr
 * Plugin URI:        http://taylrr.co.uk/projects/insta-posts/
 * Description:       Display your latest Instagram images (public account) on your Wordpress website.
 * Version:           1.0.0
 * Author:            Alex Taylor
 * Author URI:        http://taylrr.co.uk/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       taylrr-insta-posts
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-taylrr-insta-posts-activator.php
 */
function activate_taylrr_insta_posts() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-taylrr-insta-posts-activator.php';
	Taylrr_Insta_Posts_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-taylrr-insta-posts-deactivator.php
 */
function deactivate_taylrr_insta_posts() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-taylrr-insta-posts-deactivator.php';
	Taylrr_Insta_Posts_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_taylrr_insta_posts' );
register_deactivation_hook( __FILE__, 'deactivate_taylrr_insta_posts' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-taylrr-insta-posts.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_taylrr_insta_posts() {

	$plugin = new Taylrr_Insta_Posts();
	$plugin->run();

}
run_taylrr_insta_posts();
