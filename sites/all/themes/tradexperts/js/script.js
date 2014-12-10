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

  Drupal.behaviors.tradexpertColorbox = {
    attach: function() {

      var $a;
        $('img.colorbox').once('tradexpertColorbox', function () {
          if ($(this).parents('a').length) {
           $(this).parents('a').colorbox({width: '95%', height: '95%'});
             $a = $('<a/>', {
              href: $(this).attr('src')
            });
          }
          else {
            $a = $('<a/>', {
              href: $(this).attr('src')
            });
            $a.colorbox({width: '95%', height: '95%'});
            $(this).wrap($a);
          }
       });
    }
  };

  Drupal.behaviors.tradexpertSiteHeart = {
    attach: function() {
      $('body').once('tradexpertSiteHeart', function () {
        var widget_id = Drupal.settings.tradexpertsWidgetId;
        _shcp =[{widget_id : widget_id}];
        var lang =(navigator.language || navigator.systemLanguage
        || navigator.userLanguage ||"en")
        .substr(0,2).toLowerCase();
        var url ="widget.siteheart.com/widget/sh/"+ widget_id +"/"+ lang +"/widget.js";
        var hcc = document.createElement("script");
        hcc.type ="text/javascript";
        hcc.async =true;
        hcc.src =("https:"== document.location.protocol ?"https":"http")
        +"://"+ url;
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(hcc, s.nextSibling);
     }
   );
  }
};
})(jQuery, Drupal, this, this.document);
