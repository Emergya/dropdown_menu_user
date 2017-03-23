(function ($, Drupal) {
  Drupal.behaviors.dropdownMenu = {
    attach: function (context, settings) {
      // Handle user links when we are logged in.
      $('.block-body', context).once('dropdownMenu').each(function() {
        $('.block-body').hide();
        $('.logged-in .block-header .user-login .user-settings div, .not-logged-in .block-header .user-login', context).click(function() {
          // Set active class.
          $(this).toggleClass('active');
          // Toggle the links.
          $('.block-body').toggle();
          return false;
        });
      });
    }
  };
})(jQuery, Drupal);
