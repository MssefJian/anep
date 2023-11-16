(function ( $, Drupal, window, document, undefined ) {

  Drupal.behaviors.userLoginHint = {
    attach: function (context, settings) {
      // Cookies
      function createCookie(name, value, days) {
        var expires = "";
        if (days) {
          var date = new Date();
          date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
          expires = "; expires=" + date.toGMTString();
        }

        document.cookie = name + "=" + value + expires + "; path=/";
      }

      function readCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
          var c = ca[i];
          while (c.charAt(0) == ' ') c = c.substring(1, c.length);
          if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
      }

      function eraseCookie(name) {
        createCookie(name, "", -1);
      }

      if(readCookie("Drupal.visitor.id") !== null && readCookie("Drupal.visitor.id") !== 0) {
        $('.user-login-form').prepend(settings.imageMarkup);
        $('#edit-name').val(settings.username);
        $('#edit-name').hide();
      }

      $('.hide-hint').click(function () {
          $('.hint').fadeOut();
          $('#edit-name').val('');
          $('#edit-name').fadeIn();
          eraseCookie("Drupal.visitor.id");
        }
      );
    }
  };
})(jQuery, Drupal, this, this.document);
