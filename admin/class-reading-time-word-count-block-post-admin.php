<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package    Reading_Time_Word_Count_Block_Post
 * @subpackage Reading_Time_Word_Count_Block_Post/admin
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Reading_Time_Word_Count_Block_Post
 * @subpackage Reading_Time_Word_Count_Block_Post/admin
 */
class Reading_Time_Word_Count_Block_Post_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of this plugin.
     * @param string $version The version of this plugin.
     * @since    1.0.0
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Reading_Time_Word_Count_Block_Post_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Reading_Time_Word_Count_Block_Post_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/reading-time-word-count-block-post-admin.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Reading_Time_Word_Count_Block_Post_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Reading_Time_Word_Count_Block_Post_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/reading-time-word-count-block-post-admin.js', array('jquery'), $this->version, false);

        wp_localize_script(
            $this->plugin_name,
            'rtwcbfp_i18n',
            array(
                'confirmSave' => __('Save settings?', 'reading-time-word-count-block-for-post'),
            )
        );

    }


    public function add_admin_menu()
    {
        add_menu_page(
            __('Reading Time and Word Count', 'reading-time-word-count-block-for-post'), // Page title
            __('Reading Time', 'reading-time-word-count-block-for-post'),                 // Menu title
            'manage_options',                   // Capability
            'rtwcbfp-settings',                 // Menu slug - using unique prefix
            array($this, 'display_admin_page'), // Callback function
            'dashicons-clock',                  // Icon
            20                                  // Position
        );
    }

    public function display_admin_page()
    {
        require plugin_dir_path(__FILE__) . 'partials/reading-time-word-count-block-post-admin-display.php';
    }

    /**
     * Add a "Settings" link to the plugin row on the Plugins screen.
     *
     * The settings page is registered via add_menu_page() as a top-level
     * menu, so the link points at admin.php?page=rtwcbfp-settings.
     *
     * @param array $links Existing action links for the plugin row.
     * @return array Action links with the "Settings" link prepended.
     * @since 1.0.0
     */
    public function add_plugin_action_links($links)
    {
        $settings_link = sprintf(
            '<a href="%s">%s</a>',
            esc_url(admin_url('admin.php?page=rtwcbfp-settings')),
            esc_html__('Settings', 'reading-time-word-count-block-for-post')
        );

        array_unshift($links, $settings_link);

        return $links;
    }


    public function register_ajax_hooks()
    {
        add_action('wp_ajax_rtwcbfp_save_settings', array($this, 'save_settings'));
    }

    public function save_settings() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => __( 'You do not have permission to perform this action.', 'reading-time-word-count-block-for-post' ) ) );
        }

        if (
            ! isset( $_POST['nonce'] ) ||
            ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'rtwcbfp_settings_nonce' )
        ) {
            wp_send_json_error( array( 'message' => __( 'Invalid security token.', 'reading-time-word-count-block-for-post' ) ) );
        }

        $word_count   = isset( $_POST['rtwcbfp_show_word_count'] )   && $_POST['rtwcbfp_show_word_count']   === 'yes' ? 'yes' : 'no';
        $with_title   = isset( $_POST['rtwcbfp_show_with_title'] )   && $_POST['rtwcbfp_show_with_title']   === 'yes' ? 'yes' : 'no';
        $with_content = isset( $_POST['rtwcbfp_show_with_content'] ) && $_POST['rtwcbfp_show_with_content'] === 'yes' ? 'yes' : 'no';
        $on_listing   = isset( $_POST['rtwcbfp_show_on_listing'] )   && $_POST['rtwcbfp_show_on_listing']   === 'yes' ? 'yes' : 'no';
        $in_related   = isset( $_POST['rtwcbfp_show_in_related'] )   && $_POST['rtwcbfp_show_in_related']   === 'yes' ? 'yes' : 'no';

        $updated_word_count   = update_option( 'rtwcbfp_show_word_count',   $word_count );
        $updated_with_title   = update_option( 'rtwcbfp_show_with_title',   $with_title );
        $updated_with_content = update_option( 'rtwcbfp_show_with_content', $with_content );
        $updated_on_listing   = update_option( 'rtwcbfp_show_on_listing',   $on_listing );
        $updated_in_related   = update_option( 'rtwcbfp_show_in_related',   $in_related );

        if ( $updated_word_count || $updated_with_title || $updated_with_content || $updated_on_listing || $updated_in_related ) {
            wp_send_json_success( array( 'message' => __( 'Settings saved successfully.', 'reading-time-word-count-block-for-post' ) ) );
        } else {
            wp_send_json_error( array( 'message' => __( 'No changes were made to the settings.', 'reading-time-word-count-block-for-post' ) ) );
        }
    }


}