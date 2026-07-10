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
      <h1 class="wp-heading-inline">Настройки времени чтения и количества слов</h1>
      <p>Здесь можно настроить, как и где выводится блок со временем чтения и количеством слов.</p>

      <form id="rtwcbfp-settings-form">
         <?php wp_nonce_field( 'rtwcbfp_settings_nonce', 'rtwcbfp_settings_nonce_field' ); ?>

         <table class="form-table" role="presentation">
            <tbody>
               <tr>
                  <th scope="row">
                     <label for="rtwcbfp_show_word_count">Показывать количество слов</label>
                  </th>
                  <td>
                     <input type="checkbox" name="rtwcbfp_show_word_count" id="rtwcbfp_show_word_count" value="yes" <?php checked( $show_word_count, 'yes' ); ?> />
                     <label for="rtwcbfp_show_word_count">Да</label>
                  </td>
               </tr>

               <tr>
                  <th scope="row">
                     <label for="rtwcbfp_show_with_title">Показывать после заголовка</label>
                  </th>
                  <td>
                     <input type="checkbox" name="rtwcbfp_show_with_title" id="rtwcbfp_show_with_title" value="yes" <?php checked( $show_with_title, 'yes' ); ?> />
                     <label for="rtwcbfp_show_with_title">Да</label>
                  </td>
               </tr>

               <tr>
                  <th scope="row">
                     <label for="rtwcbfp_show_with_content">Показывать перед контентом</label>
                  </th>
                  <td>
                     <input type="checkbox" name="rtwcbfp_show_with_content" id="rtwcbfp_show_with_content" value="yes" <?php checked( $show_with_content, 'yes' ); ?> />
                     <label for="rtwcbfp_show_with_content">Да</label>
                     <p class="description">На странице самого поста блок выводится перед основным текстом.</p>
                  </td>
               </tr>

               <tr>
                  <th scope="row">
                     <label for="rtwcbfp_show_on_listing">Показывать на главной и в архивах</label>
                  </th>
                  <td>
                     <input type="checkbox" name="rtwcbfp_show_on_listing" id="rtwcbfp_show_on_listing" value="yes" <?php checked( $show_on_listing, 'yes' ); ?> />
                     <label for="rtwcbfp_show_on_listing">Да</label>
                     <p class="description">На страницах со списком постов блок выводится перед отрывком (the_excerpt).</p>
                  </td>
               </tr>

               <tr>
                  <th scope="row">
                     <label for="rtwcbfp_show_in_related">Показывать в связанных постах</label>
                  </th>
                  <td>
                     <input type="checkbox" name="rtwcbfp_show_in_related" id="rtwcbfp_show_in_related" value="yes" <?php checked( $show_in_related, 'yes' ); ?> />
                     <label for="rtwcbfp_show_in_related">Да</label>
                     <p class="description">Если включено, блок выводится также рядом с ссылками на другие посты (виджет «Похожие записи» и т.&nbsp;п.). По&nbsp;умолчанию выключено: блок показывается только на самой странице поста.</p>
                  </td>
               </tr>
            </tbody>
         </table>

         <?php submit_button( 'Сохранить изменения', 'primary', 'submit', true, array( 'id' => 'rtwcbfp-settings-submit' ) ); ?>
      </form>

      <p>
         <strong>Примечание:</strong>
         также можно вставить блок вручную через шорткод <code>[reading_time]</code>.
      </p>
      <div id="rtwcbfp-settings-message"></div>
   </div>
</div>
