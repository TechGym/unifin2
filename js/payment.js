// For when no type is selected- Clearing error message
(function () {
	let err = document.querySelector("form .err");
	if (err) {
		let inputs = document.querySelectorAll("form input");
		inputs.forEach((e) => {
			e.addEventListener("focus", () => {
				err.style.display = "none";
			});
		});
	}
})();

// Proceeding to payment in each type after reading instructions
// Returning to instructions
(function () {
	// Proceeding to payment in each type after reading instructions
	let proceed_to_payment = document.querySelector(".proceed_to_payment");
	let payment_section = document.querySelector("main section#payment");
	let return_to_instructions = document.querySelector(
		"main section#payment button.cancel"
	);
	if (proceed_to_payment) {
		proceed_to_payment.addEventListener("click", () => {
			let answer = confirm(
				"Proceeding means you have read and understood all the instructions about how to make payment with the chosen payment type.\n\nContact support@unifin.cc if you have any questions.."
			);
			if (answer) {
				payment_section.style.animation = "animatePaymentIn 0.5s forwards";
			}
		});
	}
	if (return_to_instructions) {
		return_to_instructions.addEventListener("click", (e) => {
			e.preventDefault();
			payment_section.style.animation = "animatePaymentOut 0.5s forwards";
		});
	}

	// Returning to instructions
})();

// Form validation(s) and posting to PHP
