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
        // Save default options
        add_option('rtwcbfp_show_word_count', 'yes');
        add_option('rtwcbfp_show_with_title', 'yes');
        add_option('rtwcbfp_show_with_content', 'yes');

    }

}