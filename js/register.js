import { names, stateCountries } from "../data/names.js";
import {
	verifyEmail,
	verifyName,
	checkEmptyFields,
	verifyDOB,
} from "./imports/verifications.js";
// Fetching country names
let data = names;
// Coverting data to lowercase to match inputs when user doesn't click on suggestions
let lowerStateCountries = stateCountries.map((e) => e.toLocaleLowerCase());
let lowerData = data.map((e) => e.toLowerCase());

clearforUs_inputs();
/*

2. Terms and conditions checkbox toggles submit button's state
3. Processing Form
  I. Fetch form values
  II . Validate fields
  III . Submit form
  NB : This page doesn't use AJAx but rather submits form to the form action
*/
// 1

// 2
// Checkbox to enable register button
(function () {
	let checkbox = document.querySelector("form input[id='terms']");
	let submitBtn = document.querySelector("form input[type='submit']");
	checkbox.addEventListener("change", () => {
		if (checkbox.checked) {
			submitBtn.style.opacity = "1";
			submitBtn.removeAttribute("disabled");
		} else {
			submitBtn.style.opacity = "0.5";
			submitBtn.setAttribute("disabled", "");
		}
	});
})();

// 3
let register_form = document.querySelector("form");
register_form.addEventListener("submit", (e) => {
	e.preventDefault();
	//   2. I
	let formData = new FormData(register_form);
	let form = document.forms.register;

	let firstname = formData.get("firstname");
	let lastname = formData.get("lastname");
	let othernames = formData.get("othernames");
	let dob = formData.get("dob");
	let country = formData.get("country");
	let email = formData.get("email");
	let cemail = formData.get("cemail");
	let password = formData.get("password");
	let address = formData.get("address");
	let city = formData.get("city");
	let state = formData.get("state");
	let confirmPassword = formData.get("confirmPassword");
	let terms = formData.get("terms");
	dob = dob.replace(/\s/gi, "");

	let obj = {
		firstname,
		lastname,
		email,
		cemail,
		password,
		country,
		confirmPassword,
		dob,
	};
	let err = form.querySelector(".allDetailsErr");

	function displayError(text) {
		let content = err.querySelector(".container p");
		content.textContent = text;
		err.style.display = "flex";
	}

	// 2. II
	if (!checkEmptyFields(obj)) {
		displayError("Please fill in all required fields ");
		return;
	}
	if (!verifyName(firstname)) {
		displayError("Please enter a valid first name ");
		return;
	}
	if (!verifyName(lastname)) {
		displayError("Please enter a valid last name ");
		return;
	}
	if (othernames !== "" && !verifyName(othernames)) {
		displayError("Please enter valid othernames(s) ");
		return;
	}
	if (verifyDOB(dob) !== true) {
		displayError(verifyDOB(dob));
		return;
	}
	if (!verifyEmail(email)) {
		displayError("Please enter a valid email address");
		return;
	}
	if (cemail.toLowerCase() !== email.toLowerCase()) {
		displayError("Email addresses do not match");
		return;
	}
	if (!lowerData.includes(country.toLowerCase())) {
		displayError("Please enter a valid country name");
		return;
	}
	if (password.length < 8) {
		displayError("Password is too short: Must be at least 8 characters");
		return;
	}

	if (password !== confirmPassword) {
		displayError("Passwords do not match");
		return;
	}

	if (
		lowerStateCountries.includes(country.toLowerCase()) &&
		(state == "" || city == "" || address == "")
	) {
		displayError("Please fill in all required credentials");
		return;
	}

	// 2. III
	form.submit();
});

// Country suggestions
let country_select = document.querySelector("select#country");
let forUs_div = document.querySelector(".forUs");

// Creating the country options to be displayed by the select field
var createCountry = (name) => {
	let parent = document.querySelector("#country");
	let option = document.createElement("option");
	// option.classList.add("suggestion__item");
	option.setAttribute("value", name);
	option.textContent = name;
	parent.appendChild(option);
};

(function () {
	data.forEach((e) => {
		createCountry(e);
	});
})();

var clearCountries = () => {
	// Clearing all child elements in the suggestions before appending
	let parent = document.querySelector(".suggestions ul");
	let children = parent.querySelectorAll("li");
	if (children.length >= 1) {
		children.forEach((e) => {
			parent.removeChild(e);
		});
	}
};

function clearforUs_inputs() {
	let forUs_div = document.querySelector(".forUs");
	let inputs = forUs_div.querySelectorAll("input");
	inputs.forEach((e) => {
		e.value = "";
	});
}

// // States , city and address display for US residents
country_select.addEventListener("change", () => {
	let value = country_select.value.toLowerCase();
	if (lowerStateCountries.includes(value)) {
		forUs_div.style.display = "grid";
	} else {
		forUs_div.style.display = "none";
		clearforUs_inputs();
	}
});

// View password as text
let eye = document.querySelectorAll(".eye");
eye.forEach((e, index) => {
	e.addEventListener("click", () => {
		let inputArea = document.querySelectorAll(".passwords");
		let icon = e.querySelector("i");
		if (inputArea[index].type === "password") {
			inputArea[index].type = "text";
			icon.classList.remove("fa-eye");
			icon.classList.add("fa-eye-slash");
		} else {
			inputArea[index].type = "password";
			icon.classList.remove("fa-eye-slash");
			icon.classList.add("fa-eye");
		}
	});
});

// Closing the err field
(function () {
	let errorFields = document.querySelectorAll(".err");
	let closeBtns = document.querySelectorAll(".err .container button");
	closeBtns.forEach((closeBtn, index) => {
		closeBtn.addEventListener("click", (e) => {
			e.preventDefault();
			errorFields[index].style.display = "none";
		});
	});
})();
