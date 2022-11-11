import { names, stateCountries } from "../../data/names.js";
import { codes } from "../../data/codes.js";
import { verifyDOB, checkEmptyFields, verifyName, verifyEmail } from "./verifications.js";

let lowerStateCountries = stateCountries.map((e) => e.toLowerCase());

// Edits
let editsForm = document.querySelector(".editsForm");

export function cancelForm() {
	let cancelForms = document.querySelectorAll(".editsForm form .form_controls button");
	// Refreshes page when a user cancels a form
	cancelForms.forEach((e, index) => {
		e.addEventListener("click", (e) => {
			e.preventDefault();
			window.location.href = "./profile";
		});
	});
}

// Create countries
function createACountry(element, text) {
	let country = document.createElement("div");
	country.classList.add("country");
	country.textContent = text;
	element.appendChild(country);
}

// Create countries using the data from names and the createACountry functionality
export function createCountries() {
	let countriesHolder = editsForm.querySelector(".countriesHolder");
	countriesHolder.innerHTML = "";
	names.forEach((name) => {
		if (name.length > 30) {
			name = name.substring(0, 30) + "...";
		}
		createACountry(countriesHolder, name);
	});
}

export function displayCountrySuggestions() {
	let dropdown = document.querySelector(".editsForm .countries");
	let dropdownInput = document.querySelector(".editsForm .countries input");
	let countriesHolder = document.querySelector(".countriesHolder");
	let icon = document.querySelector(".editsForm .countries .icon i");

	dropdown.addEventListener("click", () => {
		dropdownInput.value = "";
		countriesHolder.style.display = "block";
		// // Arrow up
		icon.classList.add("fa-angle-up");
		icon.classList.remove("fa-angle-down");
	});
}

function registerClick() {
	let countries = document.querySelectorAll(".country");
	let addressForm = document.querySelector(".editsForm .address form");

	let countriesHolder = document.querySelector(".countriesHolder");
	let dropdownInput = document.querySelector(
		".editsForm .countries .countryName input"
	);

	const code_div = document.querySelector(".editsForm .phoneInfo .code");
	let icon = document.querySelector(".editsForm .countries  .icon i");

	countries.forEach((country) => {
		country.addEventListener("click", () => {
			let text = country.innerText;
			let shortText = text;

			if (text.length > 15) {
				shortText = `${text.substring(0, 15)}...`;
			}

			dropdownInput.value = shortText;
			dropdownInput.setAttribute("data-full", text);
			countriesHolder.style.display = "none";

			// Arrow down
			icon.classList.remove("fa-angle-up");
			icon.classList.add("fa-angle-down");

			// setting phone code for phonedit when user clicks on a country suggestion in phone form
			if (code_div) {
				let code = fetchCountryCode(country.innerText);
				const inputField = document.querySelector(".editsForm .phoneInfo input");
				code_div.textContent = code;
				// inputField.value = "";
			}

			// Displaying advanced form if user selects a country with states
			if (addressForm) {
				let laterAdd = addressForm.querySelector(".laterAdd");
				if (lowerStateCountries.includes(text.toLowerCase())) {
					laterAdd.style.display = "block";
				} else {
					laterAdd.style.display = "none";
				}
			}
		});
	});
}

// Registering a selected country in both address and phone forms
export function registerASelectedCountry() {
	let countriesHolder = document.querySelector(".countriesHolder");
	let addressForm = document.querySelector(".editsForm .address form");
	let dropdownInput = document.querySelector(
		".editsForm .countries .countryName input"
	);
	let icon = document.querySelector(".editsForm .countries  .icon i");
	// Register a country based on click
	registerClick();

	// Filtering

	dropdownInput.addEventListener("keyup", (e) => {
		let value = dropdownInput.value;

		let match = names.filter((e) => e.toLowerCase().startsWith(value.toLowerCase()));
		countriesHolder.style.display = "block";
		// Arrow up
		icon.classList.add("fa-angle-up");
		icon.classList.remove("fa-angle-down");

		if (value == "") {
			createCountries();
		}
		if (match.length > 0) {
			countriesHolder.innerHTML = "";
			match.forEach((e) => {
				if (e.length > 30) {
					e = e.substring(0, 30) + "...";
				}
				createACountry(countriesHolder, e);
			});
			countriesHolder.style.height = "auto";
			countriesHolder.style.maxHeight = "400px";
			countriesHolder.style.paddingBottom = "50px";
			dropdownInput.setAttribute("data-full", value);
		} else {
			countriesHolder.style.display = "none";
		}

		// Hide countriesHolder when a match is found
		match = names.filter((e) => e.toLowerCase() == value.toLowerCase());
		if (match.length == 1) {
			countriesHolder.style.display = "none";

			// Arrow up
			icon.classList.add("fa-angle-down");
			icon.classList.remove("fa-angle-up");

			createCountries();

			// Create code when user types a country match
			const code_div = document.querySelector(".editsForm .phoneInfo .code");
			if (code_div) {
				let code = fetchCountryCode(dropdownInput.value);
				code_div.textContent = code;
			}
		}

		registerClick();
		if (addressForm) {
			// Recalled to update items in querySelectorAll
			modifyLaterAdd();
		}
	});
}

// Country code
export function fetchCountryCode(country) {
	const countryCodes = codes.filter((e) => {
		return e.country.toLowerCase() == country.toLowerCase();
	});
	return countryCodes[0]["code"];
}

function displayError(text) {
	let error = document.querySelector(".err");
	error.textContent = text;
	error.style.display = "block";
}

/* Used in only the phone form.
Called 
1. When the person opens the phone form 
2. When a user changes the country
*/
export function setPhoneCode(code) {
	const code_div = document.querySelector(".editsForm .phoneInfo .code");
	const inputField = document.querySelector(".editsForm .phoneInfo input");

	code_div.textContent = code;
}
/*
Used in only the address form.
1. Checks if country has a state and displays the later Add div
*/
export function modifyLaterAdd() {
	let addressForm = document.querySelector(".editsForm .address form");
	let countries = addressForm.querySelectorAll(".countriesHolder .country");
	let laterAdd = addressForm.querySelector(".laterAdd");

	let country = addressForm.querySelector(".countryName input").value;

	//Page load
	if (lowerStateCountries.includes(country.toLowerCase())) {
		laterAdd.style.display = "block";
	} else {
		laterAdd.style.display = "none";
	}
}

export function clearErrors() {
	let inputs = document.querySelectorAll(".editsForm form input");
	inputs.forEach((e) => {
		e.addEventListener("focus", () => {
			let error = document.querySelector(".editsForm form .err");
			error.style.display = "none";
		});
	});
}

// Forms validation and Submission
export function phoneFormValidation() {
	// The Phone Forms
	let phoneForm = document.querySelector(".editsForm .phone form");
	let error = phoneForm.querySelector(".err");
	let countryCode = phoneForm.querySelector(".phoneInfo .code");

	// Validating phone forms
	phoneForm.addEventListener("submit", (e) => {
		e.preventDefault();
		let formData = new FormData(phoneForm);
		let number = formData.get("number");
		let password = formData.get("password");
		// Checking filled elements
		if (!password || !number) {
			error.style.display = "block";
			error.textContent = "Please fill in all credentials ";
			return;
		}
		// // Checking valid phone number
		if (!/^[0-9]{9,10}$/.test(number)) {
			error.style.display = "block";
			error.textContent = "Please enter a valid phone number ";
			return;
		}
		// Sending over data to PHP
		let phone = countryCode.textContent + number;
		let postString = `phoneForm=true&phone=${phone}&password=${password}`;
		postDataToPhp(postString);
	});
}
export function emailFormValidation() {
	// The emailForms
	let emailForm = document.querySelector(".editsForm .email form");
	let emailElements = emailForm.elements;
	let error = emailForm.querySelector(".err");

	// Validating email forms
	emailForm.addEventListener("submit", (e) => {
		e.preventDefault();
		let formData = new FormData(emailForm);
		let email = formData.get("email");
		let password = formData.get("password");

		console.log(`${email} ${password}`);

		// Checking filled elements
		if (!email || !password) {
			error.style.display = "block";
			error.textContent = "Please fill in all credentials ";
			return;
		}
		// Checking valid phone number
		if (!/^[A-Za-z][\w-\.]+[\w]@[\w-\.]+\.[\w]{2,3}$/.test(emailElements[0].value)) {
			error.style.display = "block";
			error.textContent = "Please enter a valid email address ";
			return;
		}

		// Sending over data to PHP
		let postString = `emailForm=true&email=${emailElements[0].value}&password=${emailElements[1].value}`;
		postDataToPhp(postString);
	});
}

export function addressFormValidation() {
	// AddressForm validation and submission
	let addressForm = document.querySelector(".editsForm .address form");
	let addressCountry = addressForm.querySelector(".countries .countryName input");
	let error = addressForm.querySelector(".err");

	// Validating addressForm
	addressForm.addEventListener("submit", (e) => {
		e.preventDefault();
		let formData = new FormData(addressForm);
		let country = addressCountry.getAttribute("data-full");
		// Checking if country exists
		if (!names.includes(country)) {
			error.style.display = "block";
			error.textContent = "Please enter a valid country name";
			return;
		}

		// Validating countries with states
		if (lowerStateCountries.includes(country.toLowerCase())) {
			// Checking valid inputs(states, city , address);
			let state = formData.get("state");
			let city = formData.get("city");
			let address = formData.get("address");
			let zipCode = formData.get("zipCode");
			let password = formData.get("addressPassword");
			if (!state || !city || !address || !zipCode || !password) {
				error.style.display = "block";
				error.textContent = "Please fill in all credentials";
				return;
			}
			function testText(text) {
				return /^[\w\s\.-]+$/.test(text);
			}
			if (
				testText(state) &&
				testText(city) &&
				testText(address) &&
				/^[0-9]{5,10}$/.test(zipCode)
			) {
				let postString = `addressForm=true&country=${country}&password=${password}&state=${state}&city=${city}&address=${address}&zipCode=${zipCode}`;
				postDataToPhp(postString);
			} else {
				error.style.display = "block";
				error.textContent = "Please fill in valid credentials";
				return;
			}
		} else {
			let password = formData.get("addressPassword");
			if (!password) {
				error.style.display = "block";
				error.textContent = "Please fill in all credentials";
			} else {
				// Sending over data to PHP
				let postString = `addressForm=true&country=${country}&password=${password}`;
				postDataToPhp(postString);
			}
		}
	});
}

export function nameFormValidation() {
	// The NameEditfield
	let nameForm = document.querySelector(".editsForm .name form");
	let nameElements = nameForm.elements;
	let error = nameForm.querySelector(".err");

	nameForm.addEventListener("submit", (e) => {
		e.preventDefault();

		let formData = new FormData(nameForm);
		let firstname = formData.get("firstname");
		let lastname = formData.get("lastname");
		let othernames = formData.get("othernames");
		let password = formData.get("namePassword");
		let title = formData.get("title");

		// Checking filled
		if (!firstname || !lastname || !title || !password) {
			error.style.display = "block";
			error.textContent = "Please fill in all credentials";
			return;
		}

		// Validating inputs
		if (
			!verifyName(firstname) ||
			!verifyName(lastname) ||
			!verifyName(lastname) ||
			!/^[A-Za-z.]+$/.test(title)
		) {
			displayError(
				"Please fill in valid name credentials : avoid special characters"
			);
			return;
		}

		if (othernames != "") {
			if (!verifyName(othernames)) {
				displayError("Invalid other name(s) : avoid special characters ");
				return;
			}
		}

		// Sending over data to PHP

		let postString = `nameForm=true&firstname=${firstname}&lastname=${lastname}&othernames=${othernames}&title=${title}&password=${password}`;
		postDataToPhp(postString);
	});
}

export function dobFormValidation() {
	// The NameEditfield
	let dobForm = document.querySelector(".editsForm .dob form");
	let dobElements = dobForm.elements;

	dobForm.addEventListener("submit", (e) => {
		e.preventDefault();

		let formData = new FormData(dobForm);
		let password = formData.get("password");
		let dob = formData.get("dob");
		dob = dob.replace(/\s/gi, "");

		if (!checkEmptyFields({ password, dob })) {
			displayError("Please fill in all required fields");
			return;
		}
		if (verifyDOB(dob) !== true) {
			displayError(verifyDOB(dob));
			return;
		}

		let postString = `dobForm=true&dob=${dob}&password=${password}`;
		postDataToPhp(postString);
	});
}

// Post data to PHP
export function postDataToPhp(data) {
	let phoneEditErr = document.querySelector(".editsForm .phone .phoneEditErr");
	let nameEditErr = document.querySelector(".editsForm .name .nameEditErr");
	let addressEditErr = document.querySelector(".editsForm .address .addressEditErr");
	let xhr = new XMLHttpRequest();
	xhr.addEventListener("readystatechange", () => {
		if (xhr.readyState == 4 && xhr.status == 200) {
			if (xhr.responseText == "verifyPhone") {
				window.location.href = "./verifications.php?type=verifyphone";
			} else if (xhr.responseText == "verifyEmail") {
				window.location.href = "./verifications.php?type=editEmail";
			} else if (xhr.responseText == "success") {
				window.location.href = "./profile";
			} else {
				let error = document.querySelector(".editsForm form .err");
				error.style.display = "block";
				error.textContent = xhr.responseText;
			}
		}
	});
	xhr.open("POST", "ajax/edits.php");
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send(data);
}
