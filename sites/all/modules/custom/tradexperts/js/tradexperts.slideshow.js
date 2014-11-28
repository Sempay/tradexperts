(function ($) {
	Drupal.behaviors.tradexpertsSlideshowInit = {
		attach: function() {
			$('.tradexperts-slideshow').once('tradexpertsSlideshowInit', function () {
				$(this).mobilyslider({
					content: '.slideshow-content',
					children: 'div.item',
					pauseOnHover: true,
					bullets: true,
					arrows: true,
					arrowsHide: false,
					transition: 'fade',
					animationSpeed: 800
				});
			});
		}
	};
})(jQuery);