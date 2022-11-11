// Cancelling both forms
let cancel = document.querySelector(".cancel");
cancel.addEventListener("click", (e) => {
	e.preventDefault();
	window.location.href = "./login";
});

// Submitting findUser form
let submit = document.querySelector(".submit");
let form = document.querySelector("form");
let inputs = document.querySelectorAll("form input");
let err = document.querySelector("form .err");
submit.addEventListener("click", (e) => {
	e.preventDefault();
	let formData = new FormData(form);

	// Validating findUser Form
	if (form.id == "findUser") {
		let email = formData.get("email");

		// Form input validation
		if (!email) {
			err.style.display = "block";
			err.textContent = "Please enter your email address";
			return;
		}

		if (!/^[a-zA-Z][\w\.-]+[a-zA-Z0-9]@[\w.-]+\.[a-zA-Z0-9]+$/.test(email)) {
			err.style.display = "block";
			err.textContent = "Please enter a valid email address";
			return;
		}

		postToPHP(`find=true&email=${email}`);
	} else if (form.id == "confirmEmail") {
		// Validationg confirmEmail Form
		let code = formData.get("code");

		// Form input validation
		if (!code) {
			err.style.display = "block";
			err.textContent = "Please enter a valid code";
			return;
		}

		if (!/[a-zA-Z0-9]{6}$/.test(code)) {
			err.style.display = "block";
			err.textContent = "Please enter a valid code";
			return;
		}

		postToPHP(`confirm=true&code=${code}`);
	} else {
		// Validationg confirmEmail Form
		let password = formData.get("password");
		let pwdRepeat = formData.get("Cpassword");

		// Form input validation
		if (!password || !pwdRepeat) {
			err.style.display = "block";
			err.textContent = "Please fill in all fields";
			return;
		}
		if (password.length < 8) {
			err.style.display = "block";
			err.textContent = "Password is too short";
			return;
		}

		if (password !== pwdRepeat) {
			err.style.display = "block";
			err.textContent = "Passwords do not match";
			return;
		}

		postToPHP(`reset=true&password=${password}`);
	}
});

inputs.forEach((e) => {
	e.addEventListener("focus", () => {
		err.style.display = "none";
	});
});
function postToPHP(string) {
	let xhr = new XMLHttpRequest();

	xhr.addEventListener("readystatechange", () => {
		if (xhr.readyState == 4 && xhr.status == 200) {
			if (xhr.responseText == "found") {
				window.location.href = "./reset?rel=key";
			} else if (xhr.responseText == "confirmed") {
				window.location.href = "./reset?rel=set";
			} else if (xhr.responseText == "reset") {
				window.location.href = "./login";
			} else {
				err.style.display = "block";
				err.textContent = xhr.responseText;
			}
		}
	});
	xhr.open("POST", "ajax/reset.php");
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send(`${string}`);
}
