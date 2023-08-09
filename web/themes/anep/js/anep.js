/**
 * @file
 * anep behaviors.
 */
(function (Drupal) {

  'use strict';

  Drupal.behaviors.anep = {
    attach(context, settings) {

      $("a.has_subitems").each(function() {
        $(this).append('<i class="bi bi-plus"></i>');
      });


    }
  };

}(Drupal));
