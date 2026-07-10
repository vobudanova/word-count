<?php
/**
 * Public-facing display template for the plugin
 *
 * This file outputs the reading time and word count block.
 * It follows WordPress plugin development best practices.
 *
 * @package    Reading_Time_Word_Count_Block_Post
 * @subpackage Reading_Time_Word_Count_Block_Post/public/partials
 * @since      1.0.0
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Never render in the admin. Front-end gating (which context, what to show) is
// handled by the caller; this template only renders what it is asked to.
if ( is_admin() ) {
    return;
}

// Prefer the post passed in by the caller (avoids cross-contamination when the
// `the_title` filter fires for related-post links). Fall back to the global post.
if ( ! empty( $rtwcbfp_post ) && $rtwcbfp_post instanceof WP_Post ) {
    $post = $rtwcbfp_post;
} else {
    global $post;
}

if ( ! $post ) {
    return;
}

// What to render: 'time', 'words' or 'both'. Set by get_reading_time_html().
$rtwcbfp_mode = isset( $rtwcbfp_mode ) && in_array( $rtwcbfp_mode, array( 'time', 'words', 'both' ), true ) ? $rtwcbfp_mode : 'both';
$show_time    = in_array( $rtwcbfp_mode, array( 'time', 'both' ), true );
$show_words   = in_array( $rtwcbfp_mode, array( 'words', 'both' ), true );

// Calculate word count and reading time.
// Strip layers a reader never sees, so they don't pad the count:
//   1. HTML comments — Gutenberg block delimiters like `<!-- wp:paragraph -->`
//      and their JSON attributes survive wp_strip_all_tags() otherwise.
//   2. Shortcodes — `[gallery ids="1,2,3"]` would otherwise count as words.
//   3. HTML tags themselves.
//   4. HTML entities — `&nbsp;` stays as literal text after wp_strip_all_tags(),
//      and `\p{L}+` then matches the `nbsp` part as a fake word, roughly
//      doubling the count on entity-heavy content (common when pasted from Word).
// Unicode-aware: counts words in any script (Cyrillic, Latin, CJK, etc.), not just a-z.
// Hyphenated words like «темно-зеленом» are counted as one, matching MS Word.
$content      = preg_replace( '/<!--.*?-->/s', '', (string) $post->post_content );
$content      = strip_shortcodes( $content );
$content      = wp_strip_all_tags( $content );
$content      = html_entity_decode( $content, ENT_QUOTES | ENT_HTML5, 'UTF-8' );
$word_count   = preg_match_all( '/[\p{L}\p{N}]+(?:-[\p{L}\p{N}]+)*/u', $content );
$reading_time = max( 1, (int) ceil( $word_count / 200 ) ); // Average reading speed: 200 WPM

// Numbers and word forms are localized via gettext:
//   - number_format_i18n() applies the active locale's thousands separator
//     (comma for en_US, space for ru_RU).
//   - _n() picks the correct plural form per locale — 2 forms for English,
//     3 for Russian (defined by the Plural-Forms header of the .po file).
?>
<!-- Reading Time & Word Count Display Block -->
<div class="count-word-wrapper" style="margin-bottom: 10px; font-size: 0.9em; color: #555;">
    <?php if ( $show_time ) : ?>
        <p><b><?php
            /* translators: %s: reading time in minutes. */
            echo esc_html( sprintf( __( '%s min read', 'reading-time-word-count-block-for-post' ), number_format_i18n( $reading_time ) ) );
        ?></b></p>
    <?php endif; ?>

    <?php if ( $show_words ) : ?>
        <p><?php
            printf(
                /* translators: %s: number of words. */
                esc_html( _n( '%s word', '%s words', $word_count, 'reading-time-word-count-block-for-post' ) ),
                esc_html( number_format_i18n( $word_count ) )
            );
        ?></p>
    <?php endif; ?>
</div>