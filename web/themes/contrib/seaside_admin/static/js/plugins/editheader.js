(function (Drupal, $) {
  Drupal.fta_editheader = function EditHeader(options) {
    var view = {

      defaults: {},

      init: function (options) {
        $.extend(view.defaults, options);
        view.item = $(options.el);

        view.form = view.item.find('#edit-header');
        if (view.form.length) {
          view.checkboxes = view.item.find('table .form-checkbox');
          view._addEventHandlers();
        }
      },

      _addEventHandlers: function() {
        view.checkboxes.change(function() {
          view._checkForChecked();
        });
      },

      _checkForChecked: function() {
        var checked = false;
        $.each(view.checkboxes, function() {
          if ($(this).prop('checked')) {
            checked = true;
          }
        });

        if (checked) {
          view.form.addClass('edit-header-visible');
          view.item.find('.form-actions').show();
        }
        else {
          view.form.removeClass('edit-header-visible');
          view.item.find('.form-actions').hide();
        }
      }
    };

    view.init(options);

    return view;
  };

  $(function () {
    $('div.view').each(function () {
      new Drupal.fta_editheader({el: this});
    });
  });
}(Drupal, jQuery));
