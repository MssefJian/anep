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

      let nav = document.querySelector(".top");
      window.onscroll = function()
      {
        if(document.documentElement.scrollTop > 400)
        {
          nav.classList.add("scrolled");
        }
        else
        {
          nav.classList.remove("scrolled");
        }
      }

      $('.owl-carousel').owlCarousel({
        loop:false,
        rewind: true,
        margin:10,
        autoplay:2000,
        nav:true,
        responsive:{
          0:{
            items:1
          },
          600:{
            items:2
          },
          800:{
            items:3
          },
          1000:{
            items:4
          }
        }
      });
      $('.owl-carousel-rtl').owlCarousel({
        loop:false,
        rewind: true,
        margin:10,
        autoplay:2000,
        nav:true,
        rtl:true,
        responsive:{
          0:{
            items:1
          },
          600:{
            items:2
          },
          800:{
            items:3
          },
          1000:{
            items:4
          }
        }
      });
    }
  };

}(Drupal));
