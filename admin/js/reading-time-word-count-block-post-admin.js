(function ($) {
   "use strict";

   /**
    * All of the code for your admin-facing JavaScript source
    * should reside in this file.
    *
    * Note: It has been assumed you will write jQuery code here, so the
    * $ function reference has been prepared for usage within the scope
    * of this function.
    *
    * This enables you to define handlers, for when the DOM is ready:
    *
    * $(function() {
    *
    * });
    *
    * When the window is loaded:
    *
    * $( window ).load(function() {
    *
    * });
    *
    * ...and/or other possibilities.
    *
    * Ideally, it is not considered best practise to attach more than a
    * single DOM-ready or window-load handler for a particular page.
    * Although scripts in the WordPress core, Plugins and Themes may be
    * practising this, we should strive to set a better example in our own work.
    */
   jQuery(document).ready(function ($) {
      $("#rtwcbfp-settings-submit").on("click", function (e) {
         e.preventDefault();

         if (!confirm("Сохранить настройки?")) {
            return; // Cancelled by user
         }

         // Collect every checkbox in the form by its `name`, so adding a new
         // option to the HTML no longer requires touching this JS file.
         const data = {
            action: "rtwcbfp_save_settings",
            nonce: $("#rtwcbfp_settings_nonce_field").val(),
         };
         $("#rtwcbfp-settings-form input[type=checkbox][name]").each(function () {
            data[this.name] = this.checked ? "yes" : "no";
         });

         $.post(ajaxurl, data, function (response) {
            const messageBox = $("#rtwcbfp-settings-message");
            let messageHtml = "";

            if (response.success) {
               messageHtml = '<div class="notice notice-success"><p>' + response.data.message + "</p></div>";
            } else {
               messageHtml = '<div class="notice notice-error"><p>' + response.data.message + "</p></div>";
            }

            messageBox
                .stop(true, true)
                .hide()
                .html(messageHtml)
                .fadeIn(500); // show with fade in

            // Auto hide after 4 seconds
            setTimeout(function () {
               messageBox.fadeOut(700, function () {
                  $(this).html("").show(); // reset for next use
               });
            }, 2000);
         });
      });
   });


})(jQuery);