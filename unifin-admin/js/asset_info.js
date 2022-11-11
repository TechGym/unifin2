let data = {};
let isAnchored = false;
function displayError(step, text) {
	let err = document.querySelector(`.step${step} form .err`);
	if (err) {
		err.textContent = text;
		err.style.display = "block";
	}
}

function displayForm(step) {
	let steps = document.querySelectorAll(".step");
	steps.forEach((e) => {
		e.style.display = "none";
	});
	let step_to_display = document.querySelector(`.step${step}`);
	step_to_display.style.display = "block";
}
function clearErr(step) {
	let err = document.querySelector(`.step${step} form .err`);
	let inputs = document.querySelectorAll(
		`.step${step} form input , .step${step} form textarea `
	);
	inputs.forEach((e) => {
		e.addEventListener("focus", () => {
			if (err) {
				err.style.display = "none";
			}
		});
	});
}

function checkEmptyFields(object) {
	let values = Array.from(Object.values(object));
	return values.some((e) => e == "");
}

function validateName(name) {
	return /^[a-zA-Z0-9\s-_']+$/.test(name);
}

function validateLink(urlString) {
	try {
		return Boolean(new URL(urlString));
	} catch (e) {
		return false;
	}
}

function storeEntries(obj, entries) {
	for (let entry of entries) {
		if (entry[1] !== "") {
			obj[entry[0]] = entry[1];
		}
	}
}

function validateOptionalFields(value, callback) {
	if (value != "") {
		return callback(value);
	}
	return true;
}

function validateEmail(email) {
	return /^[a-zA-Z][\w\.-]+[a-zA-Z0-9]@[\w.-]+\.[a-zA-Z0-9]+$/.test(email);
}
function displayPrevForm(step, button) {
	if (button) {
		button.addEventListener("click", (e) => {
			e.preventDefault();
			displayForm(step);
		});
	}
}

function transformToUppercase(entries) {
	// Transforming form names to uppercase
	entries = Array.from(entries);
	entries = entries.map((e) => {
		return [e[0].toUpperCase(), e[1]];
	});
	return entries;
}

// Processing and validating step 1
(function () {
	let form = document.querySelector(".step1 form");
	if (form) {
		// Form submit
		form.addEventListener("submit", (e) => {
			e.preventDefault();
			let formData = new FormData(form);
			let public_keys = formData.get("public_keys");
			let type = formData.get("option");

			if (!type || !public_keys) {
				displayError("1", "Please fill in all credentials");
				return;
			}
			// Check public keys
			public_keys = public_keys.replace(/^[\r\n]$/gm, "");
			let keys = public_keys.split(",");
			console.log(keys);
			keys = keys.map((e) => {
				return e.trim();
			});
			let matched_keys = keys.every((e) => {
				return e.trim().length == 56;
			});
			if (!matched_keys) {
				displayError("1", "Please enter valid public key formats");
				return;
			}

			// Proceeding to step2
			data.ACCOUNTS = keys;
			displayForm("2");
		});

		// Clear errors
		clearErr("1");
	}
})();

// Processing and validating step 2
(function () {
	let submit = document.querySelector(".step2 form input[type='submit']");
	let form = document.querySelector(".step2 form");
	let prev = document.querySelector(".step2 form button");
	if (submit) {
		submit.addEventListener("click", (e) => {
			e.preventDefault();
			let formData = new FormData(form);
			let org_name = formData.get("org_name");
			let org_dba = formData.get("org_dba");
			let org_url = formData.get("org_url");
			let org_logo = formData.get("org_logo");
			let org_address = formData.get("org_physical_address");
			let official_email = formData.get("org_official_email");
			let support_email = formData.get("org_support_email");
			let org_twitter = formData.get("org_twitter");
			let org_github = formData.get("org_github");
			let org_description = formData.get("org_description");
			let required_obj = {
				org_name,
				org_url,
				org_logo,
				official_email,
				org_description,
			};

			if (checkEmptyFields(required_obj)) {
				displayError("2", "Please fill in all required credentials");
				return;
			}

			// Validate Required Fields
			if (!validateName(org_name)) {
				displayError("2", "Please enter a valid Organization name");
				return;
			}
			if (!validateLink(org_url)) {
				displayError("2", "Please enter a valid Organization URL");
				return;
			}
			if (!validateLink(org_logo)) {
				displayError("2", "Please enter a valid Organization Logo link");
				return;
			}
			if (!validateEmail(official_email)) {
				displayError("2", "Please enter a valid  email address");
				return;
			}

			// Validating optional fields
			if (!validateOptionalFields(org_dba, validateName)) {
				displayError("2", "Please enter a valid DBA");
				return;
			}

			if (!validateOptionalFields(support_email, validateEmail)) {
				displayError("2", "Please enter a valid  support email address");
				return;
			}
			if (!validateOptionalFields(org_twitter, validateLink)) {
				displayError("2", "Please enter a valid  twitter account link");
				return;
			}
			if (!validateOptionalFields(org_github, validateLink)) {
				displayError("2", "Please enter a valid  github account link");
				return;
			}
			let entries = formData.entries();

			// Transforming form input names to uppercase
			entries = transformToUppercase(entries);
			let DOCUMENTATION = {};
			// Storing data as a property of the data object
			storeEntries(DOCUMENTATION, entries);
			data.DOCUMENTATION = DOCUMENTATION;
			displayForm("3");
		});
		clearErr("2");
	}
	// Displaying previous form by clicking on prev button
	displayPrevForm("1", prev);
})();

// Processing and validating step 3
(function () {
	let submit = document.querySelector(".step3 form input[type='submit']");
	let form = document.querySelector(".step3 form");
	let prev = document.querySelector(".step3 form button");
	if (submit) {
		submit.addEventListener("click", (e) => {
			e.preventDefault();
			let formData = new FormData(form);
			let name = formData.get("name");
			let email = formData.get("email");
			let github = formData.get("github");
			let twitter = formData.get("twitter");
			let keybase = formData.get("keybase");

			if (checkEmptyFields({ name, email })) {
				displayError("3", "Please fill in all required credentials");
				return;
			}

			// Validating required fields
			if (!validateName(name)) {
				displayError("3", "Please enter a valid name");
				return;
			}
			if (!validateEmail(email)) {
				displayError("3", "Please enter a valid email address");
				return;
			}

			// Validating optional fields
			if (!validateOptionalFields(github, validateLink)) {
				displayError("3", "Please enter a valid github account link");
				return;
			}
			if (!validateOptionalFields(twitter, validateLink)) {
				displayError("3", "Please enter a valid twitter account link");
				return;
			}
			if (!validateOptionalFields(keybase, validateLink)) {
				displayError("3", "Please enter a valid keybase account link");
				return;
			}

			let entries = formData.entries(),
				PRINCIPALS = {};
			// Storing data as a property of the data object
			storeEntries(PRINCIPALS, entries);
			data.PRINCIPALS = PRINCIPALS;
			displayForm("4");
		});
		clearErr("3");
	}
	displayPrevForm("2", prev);
})();

// Processing and validating step 4
(function () {
	let submit = document.querySelector(".step4 form input[type='submit']");
	let form = document.querySelector(".step4 form");
	let prev = document.querySelector(".step4 form button");

	if (submit) {
		submit.addEventListener("click", (e) => {
			e.preventDefault();
			let formData = new FormData(form);
			let assetCode = formData.get("code");
			let issuer = formData.get("issuer");
			let image = formData.get("image");
			let desc = formData.get("desc");
			let conditions = formData.get("conditions");
			let required_obj = {
				assetCode,
				issuer,
				desc,
				image,
			};

			if (isAnchored) {
				let anchor_asset_type = formData.get("anchor_asset_type");
				let anchor_asset = formData.get("anchor_asset");
				let redemption_instructions = formData.get("redemption_instructions");
				let attestation_of_reserve = formData.get("attestation_of_reserve");
				required_obj.anchor_asset_type = anchor_asset_type;
				required_obj.anchor_asset = anchor_asset;
				required_obj.redemption_instructions = redemption_instructions;
				required_obj.attestation_of_reserve = attestation_of_reserve;
			}

			if (checkEmptyFields(required_obj)) {
				displayError("4", "Please fill in all required credentials");
				return;
			}

			// Validating required fields
			if (issuer.trim().length !== 56) {
				displayError("4", "Please enter a valid issuer public key");
				return;
			}
			if (assetCode.length < 4 || assetCode.length > 9) {
				displayError(
					"4",
					"Asset code length should be greater than 4 and less than 10"
				);
				return;
			}
			if (!validateLink(image)) {
				displayError("4", "Please enter a valid image URL");
				return;
			}
			if (isAnchored) {
				if (!validateLink(attestation_of_reserve)) {
					displayError(
						"4",
						"Please enter a valid link to attestation of reserve"
					);
					return;
				}
			}

			let entries = formData.entries(),
				CURRENCIES = {};
			// Storing data as a property of the data object
			storeEntries(CURRENCIES, entries);
			data.CURRENCIES = CURRENCIES;

			console.log(data);
			postToPhp(data);
		});
		clearErr("4");
	}
	displayPrevForm("3", prev);
})();

// Displaying advanced form for anchored assets
(function () {
	let radio_btns = document.querySelectorAll(
		".step1 form .token_type input[name='option'][type='radio'] "
	);
	let anchored_asset_form = document.querySelector(".step4 form .anchored_asset_form");
	if (radio_btns) {
		radio_btns.forEach((radio_btn) => {
			radio_btn.addEventListener("change", () => {
				let asset_type = radio_btn.value;
				if (asset_type === "anchored") {
					anchored_asset_form.style.display = "block";
					isAnchored = true;
				} else {
					anchored_asset_form.style.display = "none";
					isAnchored = false;
				}
			});
		});
	}
})();

function postToPhp(data) {
	$.ajax({
		url: "./ajax/asset_info.ajax.php",
		method: "POST",
		data: data,
		success: function (data) {
			data = JSON.parse(data);

			if (data.success) {
				window.location.href = `./asset_info?type=preview&id=${data.link}`;
			} else if (data == "done") {
				let editStart = document.querySelector("#editStart");
				editStart.style.display = "none";
			} else if (data.deleted) {
				window.location.href = "../admin";
			} else {
				displayError("4", "An error occured during creation of toml file");
				return;
			}
		},
	});
}

function checkClick(e, decided) {
	let file = document.querySelector(".download a");
	if (!decided) {
		e.preventDefault();
		let decision = confirm(
			"Thank you for using UNIFIN.cc. \nDownloading file deletes file from our system and redirects you to the homepage.\nProceeding based on your decision..."
		);
		if (decision) {
			decided = true;
		}
	}
	return decided;
}
// Download process
(function () {
	let download = document.querySelector(".download");

	if (download) {
		let decided = false;
		download.addEventListener("click", (e) => {
			let decision = checkClick(e, false);
			if (decision) {
				let hiddenLink = document.querySelector("#preview .hiddenFileLink");
				hiddenLink.click();
			}
			// FInding file name
			let Params = new URLSearchParams(window.location.search);
			let name = Params.get("id");
			postToPhp(`delete=true&filename=${name}`);
		});
	}
})();
