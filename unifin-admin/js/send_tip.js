import {
	validateAmount,
	displayError,
	clearError,
} from "../../js/imports/verifications.js";

(function () {
	let form = document.querySelector("form#sendTipForm");
	let sendTip = document.querySelector("form input[name='sendTip']");
	let error = form.querySelector("p.err");
	let inputs = form.querySelectorAll("input");
	form.addEventListener("submit", (e) => {
		e.preventDefault();
		let type = form.classList.value;
		let formData = new FormData(form);
		let amount = formData.get("amount");
		let verification = validateAmount(amount);
		if (!verification) {
			displayError(
				error,
				"Please enter a valid tip amount (numbers only , to 2dp)"
			);
			return;
		}
		let data = `type=${type}&amount=${amount}`;
		postToPHP(data);
	});
	clearError(inputs, error);
})();

function postToPHP(data) {
	$.ajax({
		url: "./ajax/send_tip.ajax.php",
		method: "POST",
		data: data,
		success: function (data) {
			// data = JSON.parse(data);
			console.log(data);

			if (data === "success") {
				window.location.href = `../admin`;
			}
			// else if (data == "done") {
			// 	let editStart = document.querySelector("#editStart");
			// 	editStart.style.display = "none";
			// } else if (data.deleted) {
			// 	window.location.href = "../admin";
			// } else {
			// 	displayError("4", "An error occured during creation of toml file");
			// 	return;
			// }
		},
	});
}
