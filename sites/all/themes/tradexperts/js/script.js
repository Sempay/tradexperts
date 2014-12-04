/**
 * @file
 * A JavaScript file for the theme.
 *
 * In order for this JavaScript to be loaded on pages, see the instructions in
 * the README.txt next to this file.
 */

// JavaScript should be made compatible with libraries other than jQuery by
// wrapping it with an "anonymous closure". See:
// - https://drupal.org/node/1446420
// - http://www.adequatelygood.com/2010/3/JavaScript-Module-Pattern-In-Depth
(function ($, Drupal, window, document, undefined) {


  // To understand behaviors, see https://drupal.org/node/756722#behaviors
  Drupal.behaviors.tradexpertsLoginForm = {
    attach: function(context, settings) {
    	$('#user-login-form').once('tradexpertsLoginForm', function () {
    		$(this).find('.form-text').each(function () {
    			$(this).bind('focus', function () {
  	  			$(this).prev('label').hide();
  	  		}).bind('blur', function () {
  	  			if (!$(this).val()) {
  	  				$(this).prev('label').show();
  	  			}
  	  		});
    		});
    	});
    }
  };

  Drupal.behaviors.tradexpertsTopButton = {
    attach: function() {
      $('body').once('tradexpertsTopButton', function () {
        var $btn = $('<div/>', {
          class: 'top-button'
        }).html(Drupal.t('Up'));
        $btn.bind('click', function () {
          $('html, body').animate({scrollTop: 0}, 500, 'swing');
        });
        $(this).append($btn);
        $(window).bind('scroll', function () {
          if ($(window).scrollTop() > 0) {
            $btn.fadeIn();
          }
          else {
            $btn.fadeOut();
          }
        });
      });
    }
  };

})(jQuery, Drupal, this, this.document);
