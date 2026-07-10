<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package    Reading_Time_Word_Count_Block_Post
 * @subpackage Reading_Time_Word_Count_Block_Post/public
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Reading_Time_Word_Count_Block_Post
 * @subpackage Reading_Time_Word_Count_Block_Post/public
 */
class Reading_Time_Word_Count_Block_Post_Public {

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
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url(__FILE__) . 'css/reading-time-word-count-block-post-public.css', array(), $this->version, 'all' );

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
		 * defined in Reading_Time_Word_Count_Block_Post_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Reading_Time_Word_Count_Block_Post_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url(__FILE__) . 'js/reading-time-word-count-block-post-public.js', array( 'jquery' ), $this->version, false );

	}



/**
 * Register the shortcode for reading time.
 */
public function register_shortcodes() {
	add_shortcode( 'reading_time', array( $this, 'rtwcbfp_shortcode_handler' ) );
}



    /**
     * Get the reading time HTML block.
     *
     * @param WP_Post|int|null $for_post Optional. Render for this specific post instead of the global one.
     * @param string           $mode     What to render: 'time', 'words' or 'both'.
     * @return string
     */
    private function get_reading_time_html( $for_post = null, $mode = 'both' ) {
        $rtwcbfp_post = $for_post ? get_post( $for_post ) : null;
        $rtwcbfp_mode = in_array( $mode, array( 'time', 'words', 'both' ), true ) ? $mode : 'both';
        ob_start();
        require plugin_dir_path(__FILE__) . 'partials/reading-time-word-count-block-post-public-display.php';
        return ob_get_clean();
    }

    /**
     * Resolve what to display for a given placement and post.
     *
     * Combines the global display matrix with a per-post override. The override
     * (Variant A) applies only to a post's own single-view placements
     * (post_title, post_body, page_body); listing and related placements always
     * follow the global setting.
     *
     * @param string           $placement One of post_title|post_body|page_body|excerpt|related.
     * @param WP_Post|int|null  $post      The post in question.
     * @return string 'none' | 'time' | 'words' | 'both'
     */
    private function resolve_mode( $placement, $post ) {
        $settings = Reading_Time_Word_Count_Block_Post::get_display_settings();
        $mode     = isset( $settings[ $placement ] ) ? $settings[ $placement ] : 'none';

        $single_view = array( 'post_title', 'post_body', 'page_body' );
        if ( $post && in_array( $placement, $single_view, true ) ) {
            $post_id  = $post instanceof WP_Post ? $post->ID : (int) $post;
            $override = get_post_meta( $post_id, Reading_Time_Word_Count_Block_Post::META_DISPLAY, true );
            if ( in_array( $override, Reading_Time_Word_Count_Block_Post::valid_modes(), true ) ) {
                $mode = $override; // empty string ('inherit') falls through to the global value
            }
        }

        return $mode;
    }

/**
 * Shortcode handler for reading time.
 *
 * @param array $atts Shortcode attributes. Supports show="both|time|words".
 * @return string
 */
public function rtwcbfp_shortcode_handler( $atts ) {
    $atts = shortcode_atts( array( 'show' => 'both' ), $atts, 'reading_time' );
    $mode = in_array( $atts['show'], array( 'time', 'words', 'both' ), true ) ? $atts['show'] : 'both';
    return $this->get_reading_time_html( null, $mode );
}





    /**
     * Add the block before the content of a single post or page.
     *
     * Posts use the `post_body` placement, pages use `page_body`; other post
     * types are left untouched.
     *
     * @param string $content Post content.
     * @return string Modified content.
     */
    public function add_reading_time_to_content( $content ) {
        if ( ! is_singular() || ! in_the_loop() || ! is_main_query() ) {
            return $content;
        }

        $post = get_post();
        if ( ! $post ) {
            return $content;
        }

        switch ( get_post_type( $post ) ) {
            case 'post':
                $placement = 'post_body';
                break;
            case 'page':
                $placement = 'page_body';
                break;
            default:
                return $content;
        }

        $mode = $this->resolve_mode( $placement, $post );
        if ( 'none' === $mode ) {
            return $content;
        }

        return $this->get_reading_time_html( $post, $mode ) . $content;
    }

    /**
     * Add the block before the excerpt on listing pages (home, archives, search).
     *
     * Uses the global `excerpt` placement (per-post overrides do not apply here).
     *
     * @param string $excerpt Post excerpt.
     * @return string Modified excerpt.
     */
    public function add_reading_time_to_excerpt( $excerpt ) {
        if ( is_singular() || ! in_the_loop() || ! is_main_query() ) {
            return $excerpt;
        }

        $post = get_post();
        if ( ! $post ) {
            return $excerpt;
        }

        $mode = $this->resolve_mode( 'excerpt', $post );
        if ( 'none' === $mode ) {
            return $excerpt;
        }

        return $this->get_reading_time_html( $post, $mode ) . $excerpt;
    }

    /**
     * Add the block after a post title.
     *
     * The main post's own title on its singular page uses the `post_title`
     * placement (with per-post override). Post titles rendered inside secondary
     * loops use the global `related` placement.
     *
     * @param string $title Post title.
     * @param int    $id    Post ID.
     * @return string Modified title.
     */
    public function add_reading_time_to_title( $title, $id = null ) {
        if (
            is_admin()
            || ! in_the_loop()
            || empty( $id )
            || get_post_type( $id ) !== 'post'
        ) {
            return $title;
        }

        $post = get_post( $id );
        if ( ! $post || empty( $post->post_content ) ) {
            return $title;
        }

        $is_main_post_title = is_main_query() && is_singular() && (int) $id === get_queried_object_id();
        $placement          = $is_main_post_title ? 'post_title' : 'related';

        $mode = $this->resolve_mode( $placement, $post );
        if ( 'none' === $mode ) {
            return $title;
        }

        return $title . $this->get_reading_time_html( $post, $mode );
    }



}