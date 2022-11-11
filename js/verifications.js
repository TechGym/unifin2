//  <!-- Verifying email address (Registration) -->
function setCountDown(element, color) {
	element.setAttribute("disabled", "");
	let rand = Math.ceil(Math.random() * 4);
	let timeout = localStorage.getItem("countdown") || 60 * rand;
	if (timeout < 120) {
		timeout = 120;
	}
	let resendCount = setInterval(() => {
		let minutes = Math.floor(timeout / 60);
		minutes = formatNumber(minutes);
		let seconds = timeout % 60;
		seconds = formatNumber(seconds);
		element.textContent = minutes + ":" + seconds;
		element.style.backgroundColor = "white";
		if (timeout == 0) {
			clearInterval(resendCount);
			element.style.backgroundColor = color;
			element.textContent = "Resend email";
			element.removeAttribute("disabled");
		}
		localStorage.setItem("countdown", timeout);
		timeout -= 1;
	}, 1000);
}

let resend = document.querySelector(".email .resend");
if (resend) {
	resend.addEventListener("click", () => {
		let rand = Math.ceil(Math.random() * 4);
		postDataToPhp("resendRegistrationCode=true");
		// Setting up countdown
		setCountDown(resend, "#00AF50");
	});
}

function formatNumber(number) {
	if (number < 10) {
		return `0${number}`;
	}
	return number;
}

let del = document.querySelector(".delete");
if (del) {
	del.addEventListener("click", () => {
		postDataToPhp("deleteAccount=true");
	});
}

// <!-- Verifying Phone Number and Email(Editing) -->
let displayResend = document.querySelector(".displayResend");
let verify_action = document.querySelector(".verify_action");
if (displayResend) {
	displayResend.addEventListener("click", () => {
		verify_action.style.display = "block";
		displayResend.style.display = "none";
	});
}

// Resend Code
let resendCode = document.querySelector("#verify_action .resend");
let form = document.querySelector(".verifyDetails form");
if (resendCode) {
	resendCode.addEventListener("click", () => {
		// Telling PHP to set a new referral code
		postDataToPhp(`resendDetailChangeCode=true&type=${form.classList}`);
		// Setting timer
		setCountDown(resendCode, "grey");
	});
}

// Verify inputs and sending data to php
let msgError = document.querySelector(".msgError");
if (form) {
	form.addEventListener("submit", (e) => {
		e.preventDefault();
		let value = form.elements[0].value;
		if (!/^[0-9]{6}$/.test(value)) {
			msgError.innerHTML = "Please enter a valid code";
			msgError.style.display = "block";
		} else {
			// Sending data to PHP
			postDataToPhp(`code=${value}&editType=${form.classList}`);
		}
	});
	form.elements[0].addEventListener("focus", () => {
		msgError.style.display = "none";
	});
}

function postDataToPhp(param) {
	let xhr = new XMLHttpRequest();
	xhr.addEventListener("readystatechange", () => {
		if (xhr.status == 200 && xhr.readyState == 4) {
			if (xhr.responseText == "deletedAccount") {
				window.location.href = "./";
			} else if (xhr.responseText == "verifiedRegister") {
				window.location.href = "./login";
			} else if (xhr.responseText == "updatedDetails") {
				window.location.href = "./profile";
			}
		}
	});
	xhr.open("POST", "ajax/verifications.php");
	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhr.send(`${param}`);
}
