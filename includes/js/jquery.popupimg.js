(function($) {
	$.fn.popupimg = function(options) {

		var settings = $.extend({
			'show': 600,
			'hide': 600
		}, options );

		console.log(settings.speed);

		$(this).on('click', function() {
				var src = $(this).attr('src');
				$('body').append(
					$('<div>')
						.attr('id', 'popup-container')
						.css({
							'width':'100%',
							'height': '100%',
							'position': 'fixed',
							'top': '0',
							'left': '0',
							'text-align': 'center',
							'padding': '10px',
							'background-color': 'rgba(255,255,255,0.7)',
							'z-index': '10001',
							'cursor': 'pointer',
							'box-sizing': 'border-box'
						})
						.click(function() {$('#popup-container')
							.animate({opacity:'hide'}, settings.hide, function () {
									$(this).remove()
								}
							);
						})
						.append(
							$('<img>')
								.attr({'src': src, 'id':'popup-img'})
								.css({
									'max-width': '100%',
									'max-height': '100%',
									'margin': '0 auto',
									'box-shadow': '0 3px 6px rgba(0,0,0,0.7)'
								})
						)
						.animate({opacity:'show'}, settings.show)

				);

				$('#popup-img').on('load', function () {
					var parentHeight = $('#popup-container').height();
					var childHeight = $('#popup-img').height();
					$('#popup-img').css('margin-top', (parentHeight - childHeight) / 2);
				});			
		});
	};
})(jQuery);