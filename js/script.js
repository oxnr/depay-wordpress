;(function($) {
	$(".depay-widget").each(function() {
		var container = $(this);
		var mButton   = container.find("button");
		var amount    = container.data("amount").toString().trim();
		var token     = container.data("token").toString().trim();
		var receiver  = container.data("receiver").toString().trim();

		mButton.on("click", function() {
			//container.append('<script>DePayWidgets.Payment({amount: \'' + amount + '\', token: \'' + token + '\', receiver: \'' + receiver + '\'});</script>');
			DePayWidgets.Payment({
				amount:	amount,
				token: token,
				receiver: receiver
			});
		});
	});
})(jQuery);