/**
 * @file
 * Behaviors for the search widget in the admin toolbar.
 */
(function ($) {
  Drupal.behaviors.states = {
    attach: function (context, settings) {
      $('.reg1').on('tap', function () {
        $('.reg-content1').show();
      })
      $('.reg2').on('tap', function () {
        $('.reg-content2').show();
      })
      $('.reg3').on('tap', function () {
        $('.reg-content3').show();
      })
      $('.reg4').on('tap', function () {
        $('.reg-content4').show();
      })
      $('.reg5').on('tap', function () {
        $('.reg-content5').show();
      })
      $('.reg6').on('tap', function () {
        $('.reg-content6').show();
      })
      $('.regcontent').on('tap', function () {
        $('.regcontent').hide();
      })
      $('.reg1').on('mouseover', function () {
        $('.reg-content1').show();
        $('.reg1').addClass('hover')
      })
      $('.reg1').on('mouseout', function () {
        $('.reg-content1').hide();
        $('.reg1').removeClass('hover')
      })
      $('.reg2').on('mouseover', function () {
        $('.reg-content2').show();
        $('.reg2').addClass('hover')
      })
      $('.reg2').on('mouseout', function () {
        $('.reg-content2').hide();
        $('.reg2').removeClass('hover')
      })
      $('.reg3').on('mouseover', function () {
        $('.reg-content3').show();
        $('.reg3').addClass('hover')
      })
      $('.reg3').on('mouseout', function () {
        $('.reg-content3').hide();
        $('.reg3').removeClass('hover')
      })
      $('.reg4').on('mouseover', function () {
        $('.reg-content4').show();
        $('.reg4').addClass('hover')
      })
      $('.reg4').on('mouseout', function () {
        $('.reg-content4').hide();
        $('.reg4').removeClass('hover')
      })
      $('.reg5').on('mouseover', function () {
        $('.reg-content5').show();
        $('.reg5').addClass('hover')
      })
      $('.reg5').on('mouseout', function () {
        $('.reg-content5').hide();
        $('.reg5').removeClass('hover')
      })
      $('.reg6').on('mouseover', function () {
        $('.reg-content6').show();
        $('.reg6').addClass('hover')
      })
      $('.reg6').on('mouseout', function () {
        $('.reg-content6').hide();
        $('.reg6').removeClass('hover')
      })
    }
  };
})(jQuery);
