export function verifyEmail(email) {
	return /^[a-zA-Z][\w\.-]+[a-zA-Z0-9]@[\w.-]+\.[a-zA-Z0-9]+$/.test(email);
}

export function verifyName(name) {
	name = name.trim();
	return /^[a-zA-Z\s-_\']+$/.test(name);
}

export function checkEmptyFields(obj) {
	let values = Array.from(Object.values(obj));
	return !values.some((e) => e === "");
}

function padDate(date) {
	let arr = date.split("-");
	arr = arr.map((e) => {
		let elem = +e;
		return e < 10 ? "0" + elem : elem;
	});
	return arr.join("-");
}
export function verifyDOB(dob) {
	dob = padDate(dob);
	let DOB = Math.floor(new Date(dob).getTime() / 1000);

	let currentTime = Math.floor(new Date().getTime() / 1000),
		diff = currentTime - DOB;
	let twelveYears = 3600 * 24 * (365 * 9 + 366 * 3);

	let test = /^[0-9]{2}-[0-9]{2}-[0-9]{4}$/.test(dob);
	if (test) {
		let month = +dob.substring(0, 2);
		let day = +dob.substring(3, 5);

		if (month > 12) {
			return "Please enter a valid month of birth. Format MM-DD-YYYY";
		}
		if (day > 31) {
			return "Please enter a valid day of birth. Format MM-DD-YYYY";
		}
		if (diff < twelveYears) {
			return "User must be at least 12 years of age ";
		}
		return true;
	}

	return "Please enter a valid date of birth. Format MM-DD-YYY";
}

export function displayError(error_element, text) {
	error_element.style.display = "block";
	error_element.textContent = text;
}
export function verifyEstimate(estimate, error) {
	if (estimate != 0 && estimate.includes(".")) {
		if (!/^[0-9]+\.[0-9]{2}$/.test(estimate.trim())) {
			error.style.display = "block";
			error.textContent = "Invalid estimate format";
			return false;
		}
	} else {
		if (!/^[0-9]+$/.test(estimate.trim())) {
			error.style.display = "block";
			error.textContent = "Invalid estimate format";
			return false;
		}
	}
	return true;
}
export function validateAmount(estimate) {
	if (estimate != 0 && estimate.includes(".")) {
		if (!/^[0-9]+\.[0-9]{2}$/.test(estimate.trim())) {
			return false;
		}
	} else {
		if (!/^[0-9]+$/.test(estimate.trim())) {
			return false;
		}
	}
	return true;
}

export function clearError(inputs, error_element) {
	inputs.forEach((e) => {
		e.addEventListener("focus", () => [(error_element.style.display = "none")]);
	});
}
// console.log(verifyDOB("09-30-2010"));
