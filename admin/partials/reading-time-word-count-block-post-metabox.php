<?php

/**
 * Per-post display override meta box.
 *
 * Rendered by Reading_Time_Word_Count_Block_Post_Admin::render_meta_box(),
 * which provides $post (WP_Post) and $value (the saved override, or '').
 *
 * @package    Reading_Time_Word_Count_Block_Post
 * @subpackage Reading_Time_Word_Count_Block_Post/admin/partials
 * @since      1.0.0
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$rtwcbfp_meta_options = array(
	''      => __( 'Default (use global settings)', 'reading-time-word-count-block-for-post' ),
	'none'  => __( 'Do not show', 'reading-time-word-count-block-for-post' ),
	'time'  => __( 'Reading time only', 'reading-time-word-count-block-for-post' ),
	'words' => __( 'Word count only', 'reading-time-word-count-block-for-post' ),
	'both'  => __( 'Reading time + word count', 'reading-time-word-count-block-for-post' ),
);
?>
<p><?php esc_html_e( 'Reading-time block for this post:', 'reading-time-word-count-block-for-post' ); ?></p>
<select name="rtwcbfp_display_meta" id="rtwcbfp_display_meta" style="width: 100%;">
	<?php foreach ( $rtwcbfp_meta_options as $rtwcbfp_val => $rtwcbfp_label ) : ?>
		<option value="<?php echo esc_attr( $rtwcbfp_val ); ?>" <?php selected( $value, $rtwcbfp_val ); ?>>
			<?php echo esc_html( $rtwcbfp_label ); ?>
		</option>
	<?php endforeach; ?>
</select>
