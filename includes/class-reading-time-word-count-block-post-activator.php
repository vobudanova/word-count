<?php

/**
 * Fired during plugin activation
 *
 * @since      1.0.0
 *
 * @package    Reading_Time_Word_Count_Block_Post
 * @subpackage Reading_Time_Word_Count_Block_Post/includes
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Reading_Time_Word_Count_Block_Post
 * @subpackage Reading_Time_Word_Count_Block_Post/includes
 */
class Reading_Time_Word_Count_Block_Post_Activator
{

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function activate()
    {
        // Seed the default display matrix on first activation only, so an
        // existing site's saved settings are never overwritten.
        if ( false === get_option( 'rtwcbfp_display' ) ) {
            add_option(
                'rtwcbfp_display',
                array(
                    'post_title' => 'none',
                    'post_body'  => 'both',
                    'page_body'  => 'none',
                    'excerpt'    => 'time',
                    'related'    => 'none',
                )
            );
        }
    }

}