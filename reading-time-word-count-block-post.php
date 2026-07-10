<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @since             1.0.0
 * @package           Reading_Time_Word_Count_Block_Post
 *
 * @wordpress-plugin
 * Plugin Name:       100 слов
 * Plugin URI:        https://github.com/vobudanova/word-count
 * Description:       Отвечаю за слова
 * Version:           7
 * Author:            Гуф
 * Author URI:        https://github.com/vobudanova
 * License:           WTFPL
 * License URI:       http://www.wtfpl.net/
 * Text Domain:       reading-time-word-count-block-for-post
 * Domain Path:       /languages
 * Requires at least: 5.0
 * Tested up to:      7.0.1
 * Requires PHP:      7.2
 * Update URI:        false
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'RTWCBFP_PLUGIN_VERSION', '7' );

/**
 * The plugin basename (e.g. "my-plugin/my-plugin.php").
 * Computed from the main file so the "plugin_action_links_{basename}"
 * filter targets the correct row on the Plugins screen.
 */
define( 'RTWCBFP_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-reading-time-word-count-block-post-activator.php
 */
function rtwcbfp_activate_plugin() {
	require_once plugin_dir_path(__FILE__) . 'includes/class-reading-time-word-count-block-post-activator.php';
	Reading_Time_Word_Count_Block_Post_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-reading-time-word-count-block-post-deactivator.php
 */
function rtwcbfp_deactivate_plugin() {
	require_once plugin_dir_path(__FILE__) . 'includes/class-reading-time-word-count-block-post-deactivator.php';
	Reading_Time_Word_Count_Block_Post_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'rtwcbfp_activate_plugin' );
register_deactivation_hook( __FILE__, 'rtwcbfp_deactivate_plugin' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-reading-time-word-count-block-post.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function rtwcbfp_run_plugin() {

	$plugin = new Reading_Time_Word_Count_Block_Post();
	$plugin->run();

}
rtwcbfp_run_plugin();
