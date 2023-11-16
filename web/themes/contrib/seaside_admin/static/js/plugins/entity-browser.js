(function ($, Drupal) {
  Drupal.behaviors.fta_entitybrowser = {
    attach: function (context, settings) {
      var el = $('html.entity-browser .entity-browser-form');
      if (el.length) {
        _.each(el.find('.view-content .views-row'), function (row) {
          $(row).unbind('click').bind('click', function () {
            var checkbox = $(this).find('.form-checkbox');
            checkbox.prop('checked', !checkbox.prop('checked'));
            $(this).toggleClass('active');
          });
        });
      }
    }
  };
})(jQuery, Drupal);
