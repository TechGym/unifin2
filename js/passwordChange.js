// Change password functionality
let formInputs = document.querySelectorAll("#passwordChange input");
let cancelPasswordChangeBtn = document.querySelector(
	"#passwordChange button[class='cancel']"
);

// Preventing cancel button from refereshing the page and reirecting to the profile page
cancelPasswordChangeBtn.addEventListener("click", (e) => {
	e.preventDefault();
	document.location.href = "./profile";
});

// Clear the error messages on input foucs
formInputs.forEach((e) => {
	e.addEventListener("focus", () => {
		let changeErrors = document.querySelectorAll("#passwordChange .err");
		changeErrors.forEach((t) => {
			t.style.display = "none";
		});
	});
});
