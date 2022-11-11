import header, { notificationsOpen, closeMenuOnResize } from "./imports/header.js";
import logout from "./imports/menu.js";
import { notificationsClose, markReadNotification } from "./imports/notifications.js";

import {
	createCountries,
	displayCountrySuggestions,
	modifyLaterAdd,
	registerASelectedCountry,
	phoneFormValidation,
	emailFormValidation,
	addressFormValidation,
	nameFormValidation,
	dobFormValidation,
	cancelForm,
	setPhoneCode,
	clearErrors,
	fetchCountryCode,
} from "./imports/edits.js";

/*
1. Page validates and process four forms 
email , address , phone and name 
*/

let form = document.querySelector(".editsForm .container div form");
/*
1. If form is the phone form, create the countries and place them in the country holder
Functions used 
- createACountry
2. Displaying the countries field by clicking on the countryName div
3. Register a selected country
4. if form is the phoneForm set country code , validate phone form and post to PHP
5. if form is the addressForm , 
  check for country selected and display the laterAdd div if applicable
  Validat input fields
  Post to php differently based on whether the country has a state or not
*/
if (form) {
	if (form.id == "phoneForm" || form.id == "addressForm") {
		createCountries();
		displayCountrySuggestions();
		registerASelectedCountry();
		if (form.id == "phoneForm") {
			// Page load
			let currentCountry = document.querySelector(
				".editsForm .phone .countryName input"
			);
			let code = fetchCountryCode(currentCountry.value);
			setPhoneCode(code);
			phoneFormValidation();
			// Phone will be updated after verification. See verification.php
		} else if (form.id == "addressForm") {
			// Modify later add for page start
			modifyLaterAdd();
			addressFormValidation();
		}
	} else {
		if (form.id == "emailForm") {
			emailFormValidation();
		} else if (form.id == "nameForm") {
			nameFormValidation();
		} else {
			dobFormValidation();
		}
	}
	clearErrors();
	cancelForm();
}

// PROPOSALS
// SEE MORE FUNCTIONALITIES
let skip = 4;
let seeMore = document.querySelector("main #proposals .content .seeMore");

// seeMore functionality
if (seeMore) {
	seeMore.addEventListener("click", (e) => {
		e.preventDefault();
		seeMorePosts();
		skip += 4;
	});
}
function setRemoveProposal(proposal) {
	if (proposal.status == "completed" || proposal.status == "disapproved") {
		return `<button class="removeProposal" id=${proposal.id}>Remove Proposal</button> `;
	}
	return " ";
}
function postToPHP(string) {
	let xhr = new XMLHttpRequest();
	xhr.addEventListener("readystatechange", () => {
		if (xhr.status == 200 && xhr.readyState == 4) {
			if (xhr.responseText == "proposalRemoved") {
				window.location.reload();
			}
			let response = JSON.parse(xhr.responseText);
			// console.log(response);
			response.forEach((e) => {
				createProposal(e);
			});
			if (response.length < 4) {
				seeMore.style.display = "none";
			}
		}
	});
	xhr.open("POST", "ajax/proposals.php");
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send(`${string}`);
}

function seeMorePosts() {
	let postString = `viewMore=true&skip=${skip}`;
	// console.log(postString);
	postToPHP(postString);
}

function createProposal(proposal) {
	let proposalsContainer = document.querySelector(
		"main #proposals .content .proposals_container"
	);
	let newProposal = `<div class="content_elem">
										<div class="status ">
												<div class="status_container ${proposal.status}">
														<i class="fa-solid "></i>
												</div>
										</div>
										<div class="proposal">
												<div class="proposal_heading">${proposal.heading}</div>
												<div class="proposal_desc">${proposal.proposal_desc}</div>
												 <div class="controls">
												<a href="${proposal.link}" class="view_proposal">View Proposal</a>
												${setRemoveProposal(proposal)}
												</div>
										</div>
									</div>`;
	proposalsContainer.innerHTML += newProposal;
}

// Removing disapproved votes
let removableProposals = document.querySelectorAll(
	"#proposals .content  .controls button"
);

removableProposals.forEach((e) => {
	e.addEventListener("click", () => {
		removeRemovableProposal(+e.id);
	});
});
function removeRemovableProposal(proposal_id) {
	let postString = `removeProposal=true&proposal_id=${proposal_id}`;
	postToPHP(postString);
}
