<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
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
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Reading_Time_Word_Count_Block_Post
 * @subpackage Reading_Time_Word_Count_Block_Post/includes
 */
class Reading_Time_Word_Count_Block_Post
{

    /**
     * Option key storing the global display matrix (placement => mode).
     */
    const OPTION_DISPLAY = 'rtwcbfp_display';

    /**
     * Post-meta key storing a per-post display override.
     */
    const META_DISPLAY = '_rtwcbfp_display';

    /**
     * Default global display matrix.
     *
     * Placements: post_title, post_body, page_body, excerpt, related.
     * Modes:      none | time | words | both.
     *
     * @return array
     */
    public static function default_display_settings()
    {
        return array(
            'post_title' => 'none',
            'post_body'  => 'both',
            'page_body'  => 'none',
            'excerpt'    => 'time',
            'related'    => 'none',
        );
    }

    /**
     * The list of valid display modes.
     *
     * @return string[]
     */
    public static function valid_modes()
    {
        return array( 'none', 'time', 'words', 'both' );
    }

    /**
     * The saved global display matrix, filled in with defaults for missing keys.
     *
     * @return array
     */
    public static function get_display_settings()
    {
        $saved = get_option( self::OPTION_DISPLAY, array() );
        if ( ! is_array( $saved ) ) {
            $saved = array();
        }
        return wp_parse_args( $saved, self::default_display_settings() );
    }

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Reading_Time_Word_Count_Block_Post_Loader $loader Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $plugin_name The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $version The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct()
    {
        if (defined('RTWCBFP_PLUGIN_VERSION')) {
            $this->version = RTWCBFP_PLUGIN_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'reading-time-word-count-block-post';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();

    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Reading_Time_Word_Count_Block_Post_Loader. Orchestrates the hooks of the plugin.
     * - Reading_Time_Word_Count_Block_Post_i18n. Defines internationalization functionality.
     * - Reading_Time_Word_Count_Block_Post_Admin. Defines all hooks for the admin area.
     * - Reading_Time_Word_Count_Block_Post_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies()
    {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-reading-time-word-count-block-post-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-reading-time-word-count-block-post-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-reading-time-word-count-block-post-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-reading-time-word-count-block-post-public.php';

        $this->loader = new Reading_Time_Word_Count_Block_Post_Loader();

    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Reading_Time_Word_Count_Block_Post_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale()
    {

        $plugin_i18n = new Reading_Time_Word_Count_Block_Post_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');

    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks()
    {

        $plugin_admin = new Reading_Time_Word_Count_Block_Post_Admin($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
        $this->loader->add_action('admin_menu', $plugin_admin, 'add_admin_menu');
        $this->loader->add_action('wp_ajax_rtwcbfp_save_settings', $plugin_admin, 'save_settings');
        $this->loader->add_action('add_meta_boxes', $plugin_admin, 'add_meta_box');
        $this->loader->add_action('save_post', $plugin_admin, 'save_meta_box');
        $this->loader->add_filter('plugin_action_links_' . RTWCBFP_PLUGIN_BASENAME, $plugin_admin, 'add_plugin_action_links');
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks()
    {

        $plugin_public = new Reading_Time_Word_Count_Block_Post_Public($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');

        // Register the shortcode properly
        $this->loader->add_action('init', $plugin_public, 'register_shortcodes');

        // Filters are always attached; each callback decides at render time what
        // (if anything) to output, based on the global display matrix and any
        // per-post override.
        $this->loader->add_filter('the_title', $plugin_public, 'add_reading_time_to_title', 10, 2);
        $this->loader->add_filter('the_content', $plugin_public, 'add_reading_time_to_content');
        $this->loader->add_filter('the_excerpt', $plugin_public, 'add_reading_time_to_excerpt');
    }


    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @return    string    The name of the plugin.
     * @since     1.0.0
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @return    Reading_Time_Word_Count_Block_Post_Loader    Orchestrates the hooks of the plugin.
     * @since     1.0.0
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @return    string    The version number of the plugin.
     * @since     1.0.0
     */
    public function get_version()
    {
        return $this->version;
    }

}