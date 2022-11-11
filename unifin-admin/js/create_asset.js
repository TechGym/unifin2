// Clear  error text on input focus
(function () {
	let inputs = document.querySelectorAll("#create_asset input");
	let err = document.querySelector("#create_asset .err");
	if (err) {
		inputs.forEach((e) => {
			e.addEventListener("focus", () => {
				err.style.display = "none";
			});
		});
	}
})();

// Create asset form validation and submission
(function () {
	// Exitting on creating asset
	let exit = document.querySelector("#create_asset .exit");
	exit.addEventListener("click", (e) => {
		e.preventDefault();
		let answer = confirm(
			"You give a consent that you want to create the file, exit and create its information file later"
		);
		if (answer) {
			validateCreateForm("exit");
		}
	});
})();

(function () {
	// Proceeding after  creating asset
	let exit = document.querySelector("#create_asset .proceed");
	exit.addEventListener("click", (e) => {
		e.preventDefault();
		validateCreateForm("proceed");
	});
})();

function validateCreateForm(type) {
	let form = document.querySelector("#create_asset");
	let formData = new FormData(form);
	let asset_name = formData.get("assetname");
	let asset_limit = formData.get("limit");
	let asset_domain = formData.get("domain");
	let seedPhrase1 = formData.get("seedphrase1");
	let seedPhrase2 = formData.get("seedphrase2");

	let object = {
		asset_name,
		asset_limit,
		asset_domain,
		seedPhrase1,
		seedPhrase2,
	};

	if (checkEmptyFields(object) !== true) {
		displayError(checkEmptyFields(object));
		return;
	}

	if (validateName(asset_name) !== true) {
		displayError(validateName(asset_name));
		return;
	}

	if (validateLimit(asset_limit) !== true) {
		displayError(validateLimit(asset_limit));
		return;
	}
	if (validateSeedPhrase(seedPhrase1) !== true) {
		displayError(validateSeedPhrase(seedPhrase1));
		return;
	}
	if (validateSeedPhrase(seedPhrase2) !== true) {
		displayError(validateSeedPhrase(seedPhrase2));
		return;
	}
	// Submitting Form
	let dataString = `type=${type}&asset_name=${asset_name}&asset_limit=${asset_limit}&asset_domain=${asset_domain}&seedphrase1=${seedPhrase1}&seedphrase2=${seedPhrase2} `;
	submitCreateForm(dataString);
}

function displayError(text) {
	let err = document.querySelector("#create_asset .err");
	err.textContent = text;
	err.style.display = "block";
}
function validateName(name) {
	if (/^[a-zA-Z]+$/.test(name)) {
		if (name.length > 4 && name.length <= 10) {
			return true;
		} else {
			return "Asset name length should be greater than 4 and less than 10";
		}
	} else {
		return "Please enter a valid asset name";
	}
}

function checkEmptyFields(object) {
	let values = Array.from(Object.values(object));
	let emptyFields = values.some((e) => e == "");
	if (!emptyFields) {
		return true;
	} else {
		return "Please fill in all credentials";
	}
}

function validateLimit(limit) {
	if (/^[0-9]+$/.test(limit)) {
		return true;
	} else {
		return "Please enter a valid asset token amount";
	}
}

function validateSeedPhrase(seed) {
	if (seed.length == 56) {
		return true;
	} else {
		return "Please use valid seed phrase formats";
	}
}

function submitCreateForm(data) {
	let xhr = new XMLHttpRequest();

	function displaySucess(text, path) {
		let success = document.querySelector("main div#success");
		success.textContent = text;
		success.style.display = "flex";

		setTimeout(() => {
			window.location.href = path;
		}, 1000);
	}
	xhr.addEventListener("readystatechange", () => {
		if (xhr.status === 200 && xhr.readyState === 4) {
			let response = xhr.responseText;
			if (response === "successfully created, exiting ...") {
				displaySucess("Asset successfully created, exiting...", "./");
			} else if (response === "successfully created, proceeding ...") {
				displaySucess(
					"Asset successfully created, proceeding...",
					"./asset_info?category=new"
				);
			} else {
				if (response.length > 100) {
					response =
						"An error occurred when creating asset. Please correct information and try again";
				}
				displayError(response);
			}
		}
	});
	xhr.open("POST", "./ajax/create_asset.ajax.php");
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send(data);
}
