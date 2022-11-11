import header, { notificationsOpen } from "./imports/header.js";
import logout from "./imports/menu.js";
import { notificationsClose, markReadNotification } from "./imports/notifications.js";
import { increaseTextArea } from "./imports/textarea.js";
import { verifyEstimate } from "./imports/verifications.js";

let proposalControls = document.querySelector("#proposalControls");
let rejectReason_div = document.querySelector(".rejectReason");

let approve = document.querySelector("#proposalControls #approve");
let reject = document.querySelector("#proposalControls #reject");

let select_field = document.querySelector(".rejectReason select");

let confirmReject = document.querySelector(".rejectReason #confirmReject");
let cancelReject = document.querySelector(".rejectReason  #cancelReject");

// Getting the proposal id from string
const urlParams = new URLSearchParams(window.location.search);
const proposalId = +urlParams.get("id");
// The AJAX Call

function postData(data) {
	let xhr = new XMLHttpRequest();
	xhr.addEventListener("readystatechange", () => {
		if (xhr.readyState == 4 && xhr.status == 200) {
			if (xhr.responseText == "success") {
				window.location.href = "./proposals";
			} else {
				let error = form.querySelector(".err");
				error.style.display = "block";
				error.textContent = xhr.responseText;
			}
		}
	});
	xhr.open("POST", "ajax/proposals.php");
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send(`${data}`);
}
// Reject buttton displays the reject reason div where moderator needs to selct a reason from the dropdown
if (reject) {
	reject.addEventListener("click", () => {
		rejectReason_div.style.display = "block";
		proposalControls.style.display = "none";
	});
}
// Appprove sends postMessage to ajax/proposals.php to modify the proposal

if (approve) {
	approve.addEventListener("click", (e) => {
		let string = `approve=true&proposalId=${proposalId}`;
		postData(string);
	});
}
// Tests to see if the rejection reason field is not empty
// Sends postMessage to ajax/proposals.php to modify the proposal

if (confirmReject) {
	confirmReject.addEventListener("click", (e) => {
		e.preventDefault();
		let code = document.querySelector("#rejectionReason").value;
		// Validating code
		if (!/^[0-9]{1}$/.test(code)) {
			let error = rejectReason_div.querySelector(".err");
			error.style.display = "block";
			error.textContent = "Please select a valid option";
			return;
		}
		let string = `reject=true&proposalId=${proposalId}&code=${code}`;
		postData(string);
	});
}
// Cancels rejection and redirects to the proposal page
if (cancelReject) {
	cancelReject.addEventListener("click", (e) => {
		e.preventDefault();
		window.location.href = `./proposals?id=${proposalId}`;
	});
}
// Clearing the rejectReason error on select focus
if (select_field) {
	select_field.addEventListener("focus", () => {
		let error = rejectReason_div.querySelector(".err");
		error.style.display = "none";
	});
}

// Redirect on page error(No such proposal or moderated proposal)
let errorMessage = document.querySelector(".error");
if (errorMessage) {
	setTimeout(() => {
		window.location.href = "./proposals";
	}, 2000);
}

// CREATING A NEW PROPOSAL AND EDITING A PROPOSAL
/*
1. Both edit proposals and create new proposal have the same syntax it is the form name that differs and form data
if form is not new proposal
tThen form is edit proposal
*/
let form = document.querySelector("#newProposal");
if (!form) {
	form = document.querySelector("#editProposal");
}

if (form) {
	let cancel = form.querySelector(".cancel");
	let submit = form.querySelector(".submit");
	let error = form.querySelector(".err");

	// Increasing the text area on input
	let textarea = form.querySelector("textarea[name='message']");
	increaseTextArea(textarea);

	// Redirect to proposals page if user cancels proposal creation
	if (cancel) {
		cancel.addEventListener("click", (e) => {
			e.preventDefault();
			window.location.href = "./proposals";
		});
	}
	// Validating input fields and sending over new Proposal to ajax/proposals.php to be inserted
	if (submit) {
		submit.addEventListener("click", (e) => {
			e.preventDefault();
			let formData = new FormData(form);
			let heading = formData.get("heading");
			let description = formData.get("message");
			let estimate = formData.get("estimate") || 0;
			let estimateAnnual = formData.get("estimateAnnual") || 0;
			let tags = formData.get("tags");
			if (!heading || !description || !tags) {
				error.style.display = "block";
				error.textContent = "Please fill in all fields";
				return;
			}

			// validating estimate
			let verifications = verifyEstimate(estimate, error);
			let annualVerifications = verifyEstimate(estimateAnnual, error);
			if (!verifications || !annualVerifications) {
				return;
			}

			// Heading should not be more than 100 characters long
			// Posting data
			heading = heading.replace(/&/gi, "and");
			description = description.replace(/&/gi, "and");

			let string = `create=true&heading=${heading}&description=${description}&estimate=${estimate}&estimateAnnual=${estimateAnnual}&tags=${tags}`;
			postData(string);
		});

		// Clearing errors on focus
		let textareas = form.querySelectorAll("textarea");
		textareas.forEach((e) => {
			e.addEventListener("click", () => {
				error.style.display = "none";
			});
		});
	}
}

// Switching tabs

let switch_elems = document.querySelectorAll(".switch .switch_elem");

let tabs = document.querySelectorAll("#proposals .tab ");

switch_elems.forEach((e, index) => {
	e.addEventListener("click", () => {
		setActiveClass(e);
		switchTabs(index);
	});
});
function setActiveClass(element) {
	switch_elems.forEach((e) => {
		e.classList.remove("active");
	});
	element.classList.add("active");
}

function switchTabs(index) {
	if (index == 1) {
		tabs[0].style.display = "none";
	} else {
		tabs[1].style.display = "none";
	}
	if (index === 0) {
		tabs[index].style.display = "grid";
	} else {
		tabs[index].style.display = "block";
	}

	tabs[index].style.animation = "slide_proposal_container_in 1s forwards";
}

// SEE MORE and MORE VOTES  FUNCTIONALITIES
let skip = 10;
let seeMore = document.querySelector("main #proposals .content .seeMore");
let moreVotes = document.querySelector("main #proposals .content .moreVotes");
function seeMorePosts(string) {
	let xhr = new XMLHttpRequest();
	xhr.addEventListener("readystatechange", () => {
		if (xhr.status == 200 && xhr.readyState == 4) {
			let response = JSON.parse(xhr.responseText);

			response.forEach((e) => {
				createProposal(e);
			});
			if (response.length < 10) {
				if (seeMore) {
					seeMore.style.display = "none";
				}
				if (moreVotes) {
					moreVotes.style.display = "none";
				}
			}
		}
	});
	xhr.open("POST", "ajax/proposals.php");
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send(`${string}=true&skip=${skip}`);
}

if (seeMore) {
	seeMore.addEventListener("click", () => {
		seeMorePosts("viewMoreModeratedProposals");
		skip += 10;
	});
}
if (moreVotes) {
	moreVotes.addEventListener("click", () => {
		seeMorePosts("viewMoreFinishedVotes");
		skip += 10;
	});
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
												<a href="${proposal.link}" class="view_proposal">View ${proposal["type"]}</a>
										</div>
									</div>`;
	proposalsContainer.innerHTML += newProposal;
}
