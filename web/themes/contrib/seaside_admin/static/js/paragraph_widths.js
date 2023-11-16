(function ( $, Drupal, window, document, undefined ) {

  Drupal.behaviors.paragraphWidths = {
    attach: function (context, settings) {

      function paragraphWidths() {
        $('table[id*="field-column-values"] tr').each(function (i) {
          value = $(this).find('select[data-drupal-selector*="subform-field-width"] option:selected').text();

          $(this).css('width', 'calc('+ value +' * 100%)');
          if (value === "Full") {
            $(this).css('width', 'calc(1 * 100%)');
          }

        });
      }

      $('tr tr.draggable select[data-drupal-selector*="subform-field-width"]').change(function() {
        paragraphWidths();
      });

      paragraphWidths();
    }
  };
})(jQuery, Drupal, this, this.document);


