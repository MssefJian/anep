(function ( $, Drupal, window, document, undefined ) {

  Drupal.behaviors.expandParagraphs = {
    attach: function (context, settings) {

      function expandParagraphs() {
        if ($('.field--widget-entity-reference-paragraphs').hasClass('expanded') !== false) {
          $('.field--type-entity-reference-revisions.field--widget-entity-reference-paragraphs').removeClass('expanded');
          $('.expand-button').html('<img src="/' + settings.currentThemePath + '/static/images/expand.svg" width="20" height="20" />');
          $('body').css('overflow', 'auto');
        } else {
          $('.field--type-entity-reference-revisions.field--widget-entity-reference-paragraphs').first().addClass('expanded');
          $('.expand-button').html('<img src="/' + settings.currentThemePath + '/static/images/shrink.svg" width="20" height="20" />');
          $('.field--type-entity-reference-revisions.field--widget-entity-reference-paragraphs').first().css('overflow', 'auto');
          $('body').css('overflow', 'hidden');
        }
      }

      if ($('.field--widget-entity-reference-paragraphs').hasClass('expanded') === false && $('.expand-button')[0] === undefined ) {
        $('.field--type-entity-reference-revisions.field--widget-entity-reference-paragraphs div > .tabledrag-toggle-weight-wrapper').first().prepend(
           '<span class="expand-button button">' +
           '<img src="/' + settings.currentThemePath + '/static/images/expand.svg" width="20" height="20" />' +
           '</span>'
        );
      }

      // Remove unnessecary tabledrag show/hide row weights
      var counter = 0;
      $('.tabledrag-toggle-weight-wrapper').each(function () {
        counter++;
        if(counter > 1) {
          $(this).remove();
        }
      });

      if( $('.field-multiple-drag').is(":visible") === true) {
        $('tr.draggable tr tr td:nth-child(2)').addClass('card');
      }

      $('.dropbutton-wrapper .field-add-more-submit').addClass('multiple');
      $('.field-add-more-submit:not(.multiple)').parent().addClass('center');

      $('div > input[data-drupal-selector*="remove-button"]:not([data-drupal-selector*="confirm-remove-button"])').val('-').addClass('button--danger').addClass('button--small');
      $('body', context).on('click', '.expand-button', function() {
        expandParagraphs();
      });

    }
  };
})(jQuery, Drupal, this, this.document);


