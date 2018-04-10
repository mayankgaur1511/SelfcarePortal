var checks = 0;

function getStatus(orderId) {
	checks = checks + 1;
	console.log(checks);
	$.get("/OrderOrchestration/getOrderStatus/" + orderId, function (result) {
			if (result.status == "COMPLETED") {
				clearInterval(intervalId);
				$("#processingModal").modal("hide");
				$("#requestCompletedModal").modal("show");
				checks = 0;
			}

			if (result.status == "ERROR" || checks > 20) {
				console.log("Timeout");
				$("#processingModal").modal("hide");
				$("#error_modal").modal("show");
				clearInterval(intervalId);
			}
		})
		.fail(function (result) {
			if (checks > 20) {
				$("#processingModal").modal("hide");
				$("#error_modal").modal("show");
				clearInterval(intervalId);
			}
	});
}

$("#duration").on("change, keyup", function () {
    if (Math.floor($(this).val()) != $(this).val() || $.isNumeric($(this).val()) != true) {
        $(this).addClass("is-invalid");
    } else {
        $(this).removeClass("is-invalid");
    }
});

$("#startDate").on("change, keyup", function () {
    // Check if Start Date is valid
    var expression = /(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2})/;
    if (!expression.test($("#startDate").val())) {
        $(this).addClass("is-invalid");
    } else {
        $(this).removeClass("is-invalid");
    }
});

$(function () {
    $(".form_datetime").datetimepicker({
        format: 'yyyy-mm-dd hh:ii'
    });
});