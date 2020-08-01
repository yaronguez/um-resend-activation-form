jQuery(document).ready(function ($) {
	$("#um-raf-form").submit(function (e) {
		e.preventDefault();
		var form = $(this);
		$("#um-raf-error, #um-raf-success").hide();
		form.find("input, button").prop("disabled", true);

		$.ajax({
			type: "POST",
			url: UM_RAF.ajax_url,
			data: {
				action: "um_raf_submit",
				email: $("#um-raf-email").val(),
				nonce: UM_RAF.nonce,
				recaptcha_input:
					typeof grecaptcha != "undefined" && grecaptcha.getResponse()
						? grecaptcha.getResponse()
						: "",
			},
			dataType: "json",
			success: function (data) {
				if (data.success && data.message) {
					$("#um-raf-success").text(data.message).fadeIn();
					$("#um-raf-email").val("");
				} else if (data.message) {
					$("#um-raf-error").text(data.message).fadeIn();
				} else {
					$("#um-raf-error")
						.text("Something went wrong. Please contact support.")
						.fadeIn();
				}
			},
			error: function (data) {
				$("#um-raf-error")
					.text("Something went wrong. Please contact support.")
					.fadeIn();
			},
			complete: function () {
				form.find("input, button").prop("disabled", false);
			},
		});
	});
});
