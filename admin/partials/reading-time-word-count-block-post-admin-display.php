<?php

/**
 * Provide an admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @since      1.0.0
 *
 * @package    Reading_Time_Word_Count_Block_Post
 * @subpackage Reading_Time_Word_Count_Block_Post/admin/partials
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Current global display matrix (placement => mode), filled with defaults.
$rtwcbfp_settings = Reading_Time_Word_Count_Block_Post::get_display_settings();

// The five placements, in display order, with a user-facing label + description.
$rtwcbfp_rows = array(
   'post_title' => array(
      'label' => __( 'After the post title', 'reading-time-word-count-block-for-post' ),
      'desc'  => __( 'On a single post page, right after its title.', 'reading-time-word-count-block-for-post' ),
   ),
   'post_body'  => array(
      'label' => __( 'In the post body', 'reading-time-word-count-block-for-post' ),
      'desc'  => __( 'On a single post page, before the main text.', 'reading-time-word-count-block-for-post' ),
   ),
   'page_body'  => array(
      'label' => __( 'In the page body', 'reading-time-word-count-block-for-post' ),
      'desc'  => __( 'On a single page, before the main text.', 'reading-time-word-count-block-for-post' ),
   ),
   'excerpt'    => array(
      'label' => __( 'In listings and archives', 'reading-time-word-count-block-for-post' ),
      'desc'  => __( 'On the home page, archives and search, before each post excerpt.', 'reading-time-word-count-block-for-post' ),
   ),
   'related'    => array(
      'label' => __( 'In related posts', 'reading-time-word-count-block-for-post' ),
      'desc'  => __( 'Next to post titles rendered inside secondary loops (e.g. a “Related posts” widget).', 'reading-time-word-count-block-for-post' ),
   ),
);

// The four display modes offered for every placement.
$rtwcbfp_modes = array(
   'none'  => __( 'Do not show', 'reading-time-word-count-block-for-post' ),
   'time'  => __( 'Reading time only', 'reading-time-word-count-block-for-post' ),
   'words' => __( 'Word count only', 'reading-time-word-count-block-for-post' ),
   'both'  => __( 'Reading time + word count', 'reading-time-word-count-block-for-post' ),
);

?>

<div class="wrap">
   <h1 class="wp-heading-inline"><?php esc_html_e( 'Reading Time and Word Count Settings', 'reading-time-word-count-block-for-post' ); ?></h1>
   <p><?php esc_html_e( 'Choose what to show in each location. Individual posts and pages can override this in the editor.', 'reading-time-word-count-block-for-post' ); ?></p>

   <form id="rtwcbfp-settings-form">
      <?php wp_nonce_field( 'rtwcbfp_settings_nonce', 'rtwcbfp_settings_nonce_field' ); ?>

      <table class="form-table" role="presentation">
         <tbody>
            <?php foreach ( $rtwcbfp_rows as $rtwcbfp_key => $rtwcbfp_row ) : ?>
               <?php $rtwcbfp_current = isset( $rtwcbfp_settings[ $rtwcbfp_key ] ) ? $rtwcbfp_settings[ $rtwcbfp_key ] : 'none'; ?>
               <tr>
                  <th scope="row">
                     <label for="rtwcbfp_display_<?php echo esc_attr( $rtwcbfp_key ); ?>"><?php echo esc_html( $rtwcbfp_row['label'] ); ?></label>
                  </th>
                  <td>
                     <select name="rtwcbfp_display[<?php echo esc_attr( $rtwcbfp_key ); ?>]" id="rtwcbfp_display_<?php echo esc_attr( $rtwcbfp_key ); ?>">
                        <?php foreach ( $rtwcbfp_modes as $rtwcbfp_mode_key => $rtwcbfp_mode_label ) : ?>
                           <option value="<?php echo esc_attr( $rtwcbfp_mode_key ); ?>" <?php selected( $rtwcbfp_current, $rtwcbfp_mode_key ); ?>><?php echo esc_html( $rtwcbfp_mode_label ); ?></option>
                        <?php endforeach; ?>
                     </select>
                     <p class="description"><?php echo esc_html( $rtwcbfp_row['desc'] ); ?></p>
                  </td>
               </tr>
            <?php endforeach; ?>
         </tbody>
      </table>

      <?php submit_button( __( 'Save Changes', 'reading-time-word-count-block-for-post' ), 'primary', 'submit', true, array( 'id' => 'rtwcbfp-settings-submit' ) ); ?>
   </form>

   <p>
      <strong><?php esc_html_e( 'Note:', 'reading-time-word-count-block-for-post' ); ?></strong>
      <?php
      echo wp_kses(
         sprintf(
            /* translators: %s: the [reading_time] shortcode wrapped in a <code> tag. */
            __( 'you can also insert the block manually via the %s shortcode.', 'reading-time-word-count-block-for-post' ),
            '<code>[reading_time]</code>'
         ),
         array( 'code' => array() )
      );
      ?>
   </p>
   <div id="rtwcbfp-settings-message"></div>
</div>
