import header, { notificationsOpen } from "./imports/header.js";
import logout from "./imports/menu.js";
import { notificationsClose, markReadNotification } from "./imports/notifications.js";

/*
1. Script is shared by the reportcompromised.pph  and contact.php pages
2. Clear errors that may be on the form after validations 
3. Validate form inputs for contact page
4. Validate form inputs for reportcompromised page
5. Post data to ajax/verifications.php
6. Cancelling forms
*/

// Clear errors on a form
const clearErr = (elements, err) => {
	elements.forEach((e) => {
		e.addEventListener("focus", () => {
			err.style.display = "none";
			err.textContent = "";
		});
	});
};

// Processing and validating contact form
const contact = document.querySelector("#contact form");

if (contact) {
	const contactErr = contact.querySelector(".err");
	const contactTextareas = contact.querySelectorAll("textarea");
	const contactInputs = contact.querySelectorAll("input");

	// if the user focuses on a textarea or an input field rrror should be cleared
	clearErr(contactInputs, contactErr);
	clearErr(contactTextareas, contactErr);

	contact.addEventListener("submit", (e) => {
		e.preventDefault();
		// Getting form data
		const formData = new FormData(contact);
		const firstName = formData.get("fname");
		const lastName = formData.get("lname");
		const email = formData.get("email");
		const organization = formData.get("organization");
		const message = formData.get("message");

		// Validating inputs
		if (!firstName || !lastName || !email || !message) {
			contactErr.style.display = "block";
			contactErr.textContent = "Please fill in all fields";
			return;
		}
		if (!organization) {
			contactErr.style.display = "block";
			contactErr.textContent = "Please set default organization to personal";
			return;
		}
		if (!/^[A-Za-z\s-]+$/.test(firstName) || !/^[A-Za-z\s-]+$/.test(lastName)) {
			contactErr.style.display = "block";
			contactErr.textContent = "Please use valid name formats";
			return;
		}
		if (!/^[A-Za-z][\w-.]+[\w]@[\w]+\.[\w]{2,}$/.test(email)) {
			contactErr.style.display = "block";
			contactErr.textContent = "Please use a valid email address";
			return;
		}

		// Send data to php
		let data = `contact=true&message=${message}&firstname=${firstName}&lastname=${lastName}&email=${email}&organization=${organization}`;
		postData(data);
	});
}

// Processing report form
const report = document.querySelector("#report form");

if (report) {
	const reportErr = report.querySelector(".err");
	const reportTextareas = report.querySelectorAll("textarea");
	const reportInputs = report.querySelectorAll("input");

	// Clearing error message on input focus
	clearErr(reportTextareas, reportErr);

	report.addEventListener("submit", (e) => {
		e.preventDefault();
		// Getting data from the form
		const formData = new FormData(report);
		const heading = formData.get("heading");
		const message = formData.get("message");

		// Validating inputs

		if (!heading || !message) {
			reportErr.style.display = "block";
			reportErr.textContent = "Please fill in all fields";
			return;
		}
		if (heading.length > 255) {
			reportErr.style.display = "block";
			reportErr.textContent = "Heading should be less than 255 characters long";
			return;
		}
		// Send data to php
		let data = `report=true&heading=${heading}&message=${message}`;
		postData(data);
	});
}

// Posting data to php and acting based on response
/*
1. Post data to php
2. listen to result 
 if contact : successfully sent a contact message hence redirect to home
 if report : successfully sent a report hence redirect to profile
  But if there was an error. Check the particular page and display error message
*/
const postData = (data) => {
	let xhr = new XMLHttpRequest();
	xhr.addEventListener("readystatechange", () => {
		if (xhr.status == 200 && xhr.readyState == 4) {
			if (xhr.responseText == "contact") {
				window.location.href = "./home";
			} else if (xhr.responseText == "report") {
				window.location.href = "./profile";
			} else {
				const err = document.querySelector(".err");
				err.style.display = "block";
				err.textContent = xhr.responseText;
			}
		}
	});
	xhr.open("POST", "ajax/contact.php");
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send(`${data}`);
};

// Cancelling forms
const cancel = document.querySelector("form button[class='cancel'");
cancel.addEventListener("click", (e) => {
	e.preventDefault();
	window.location.href = "./";
});
