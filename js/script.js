;(function($) {
	$(".depay-widget").each(function() {
		var container = $(this);
		var amount    = container.data("amount");
		var token     = container.data("token");
		var receiver  = container.data("receiver");

		container.append('<script>DePayWidgets.Payment({amount: \'' + amount + '\', token: \'' + token + '\', receiver: \'' + receiver + '\'});</script>');
	});
})(jQuery);