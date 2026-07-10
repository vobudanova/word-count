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

// Prevent output on admin pages or secondary queries.
if ( is_admin() || ! is_main_query() ) {
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

// Optional setting: whether to show word count (default: yes)
$show_word_count = get_option( 'rtwcbfp_show_word_count', 'yes' );

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

// Russian pluralization: pick one of three forms based on grammatical number.
if ( ! function_exists( 'rtwcbfp_plural_ru' ) ) {
    function rtwcbfp_plural_ru( $n, $one, $few, $many ) {
        $n    = abs( (int) $n );
        $n100 = $n % 100;
        $n10  = $n % 10;
        if ( $n100 >= 11 && $n100 <= 14 ) {
            return $many;
        }
        if ( 1 === $n10 ) {
            return $one;
        }
        if ( $n10 >= 2 && $n10 <= 4 ) {
            return $few;
        }
        return $many;
    }
}

// Russian number formatting: thousands separated by U+202F (NARROW NO-BREAK SPACE).
if ( ! function_exists( 'rtwcbfp_format_int_ru' ) ) {
    function rtwcbfp_format_int_ru( $n ) {
        return number_format( (int) $n, 0, '', "\u{202F}" );
    }
}

?>
<!-- Reading Time & Word Count Display Block -->
<div class="count-word-wrapper" style="margin-bottom: 10px; font-size: 0.9em; color: #555;">
    <p><b><?php echo esc_html( rtwcbfp_format_int_ru( $reading_time ) ); ?> мин.</b></p>

    <?php if ( 'yes' === $show_word_count ) : ?>
        <p><?php echo esc_html( rtwcbfp_format_int_ru( $word_count ) . ' ' . rtwcbfp_plural_ru( $word_count, 'слово', 'слова', 'слов' ) ); ?></p>
    <?php endif; ?>
</div>