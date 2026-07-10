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

// Retrieve current settings
$show_word_count   = get_option( 'rtwcbfp_show_word_count', 'yes' );
$show_with_title   = get_option( 'rtwcbfp_show_with_title', 'yes' );
$show_with_content = get_option( 'rtwcbfp_show_with_content', 'yes' );
$show_on_listing   = get_option( 'rtwcbfp_show_on_listing', 'yes' );
$show_in_related   = get_option( 'rtwcbfp_show_in_related', 'no' );

?>

<div id="wpbody" role="main">
   <div id="wpbody-content">
      <h1 class="wp-heading-inline"><?php esc_html_e( 'Reading Time and Word Count Settings', 'reading-time-word-count-block-for-post' ); ?></h1>
      <p><?php esc_html_e( 'Here you can configure how and where the reading time and word count block is shown.', 'reading-time-word-count-block-for-post' ); ?></p>

      <form id="rtwcbfp-settings-form">
         <?php wp_nonce_field( 'rtwcbfp_settings_nonce', 'rtwcbfp_settings_nonce_field' ); ?>

         <table class="form-table" role="presentation">
            <tbody>
               <tr>
                  <th scope="row">
                     <label for="rtwcbfp_show_word_count"><?php esc_html_e( 'Show word count', 'reading-time-word-count-block-for-post' ); ?></label>
                  </th>
                  <td>
                     <input type="checkbox" name="rtwcbfp_show_word_count" id="rtwcbfp_show_word_count" value="yes" <?php checked( $show_word_count, 'yes' ); ?> />
                     <label for="rtwcbfp_show_word_count"><?php esc_html_e( 'Yes', 'reading-time-word-count-block-for-post' ); ?></label>
                  </td>
               </tr>

               <tr>
                  <th scope="row">
                     <label for="rtwcbfp_show_with_title"><?php esc_html_e( 'Show after the title', 'reading-time-word-count-block-for-post' ); ?></label>
                  </th>
                  <td>
                     <input type="checkbox" name="rtwcbfp_show_with_title" id="rtwcbfp_show_with_title" value="yes" <?php checked( $show_with_title, 'yes' ); ?> />
                     <label for="rtwcbfp_show_with_title"><?php esc_html_e( 'Yes', 'reading-time-word-count-block-for-post' ); ?></label>
                  </td>
               </tr>

               <tr>
                  <th scope="row">
                     <label for="rtwcbfp_show_with_content"><?php esc_html_e( 'Show before the content', 'reading-time-word-count-block-for-post' ); ?></label>
                  </th>
                  <td>
                     <input type="checkbox" name="rtwcbfp_show_with_content" id="rtwcbfp_show_with_content" value="yes" <?php checked( $show_with_content, 'yes' ); ?> />
                     <label for="rtwcbfp_show_with_content"><?php esc_html_e( 'Yes', 'reading-time-word-count-block-for-post' ); ?></label>
                     <p class="description"><?php esc_html_e( 'On a single post page, the block is shown before the main text.', 'reading-time-word-count-block-for-post' ); ?></p>
                  </td>
               </tr>

               <tr>
                  <th scope="row">
                     <label for="rtwcbfp_show_on_listing"><?php esc_html_e( 'Show on the home page and archives', 'reading-time-word-count-block-for-post' ); ?></label>
                  </th>
                  <td>
                     <input type="checkbox" name="rtwcbfp_show_on_listing" id="rtwcbfp_show_on_listing" value="yes" <?php checked( $show_on_listing, 'yes' ); ?> />
                     <label for="rtwcbfp_show_on_listing"><?php esc_html_e( 'Yes', 'reading-time-word-count-block-for-post' ); ?></label>
                     <p class="description"><?php esc_html_e( 'On listing pages, the block is shown before the excerpt (the_excerpt).', 'reading-time-word-count-block-for-post' ); ?></p>
                  </td>
               </tr>

               <tr>
                  <th scope="row">
                     <label for="rtwcbfp_show_in_related"><?php esc_html_e( 'Show in related posts', 'reading-time-word-count-block-for-post' ); ?></label>
                  </th>
                  <td>
                     <input type="checkbox" name="rtwcbfp_show_in_related" id="rtwcbfp_show_in_related" value="yes" <?php checked( $show_in_related, 'yes' ); ?> />
                     <label for="rtwcbfp_show_in_related"><?php esc_html_e( 'Yes', 'reading-time-word-count-block-for-post' ); ?></label>
                     <p class="description"><?php esc_html_e( 'When enabled, the block is also shown next to links to other posts (the “Related posts” widget, etc.). Disabled by default: the block is shown only on the post page itself.', 'reading-time-word-count-block-for-post' ); ?></p>
                  </td>
               </tr>
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
</div>
