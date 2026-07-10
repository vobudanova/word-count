<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * Removes all data the plugin stores: the global display option and every
 * per-post display override.
 *
 * @since   1.0.0
 * @package Reading_Time_Word_Count_Block_Post
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Clean up the global display matrix and every per-post override.
delete_option( 'rtwcbfp_display' );
delete_post_meta_by_key( '_rtwcbfp_display' );