import header, { notificationsOpen } from "./imports/header.js";
import logout from "./imports/menu.js";
import { subscription } from "./imports/footer.js";
import { notificationsClose, markReadNotification } from "./imports/notifications.js";

let approve = document.querySelector("#voteControls #approve");
let reject = document.querySelector("#voteControls #reject");

if (approve) {
	const urlParams = new URLSearchParams(window.location.search);
	const voteId = +urlParams.get("id");
	approve.addEventListener("click", (e) => {
		let string = `approve=true&voteId=${voteId}`;
		postData(string, voteId);
	});
}
if (reject) {
	const urlParams = new URLSearchParams(window.location.search);
	const voteId = +urlParams.get("id");
	reject.addEventListener("click", (e) => {
		let string = `reject=true&voteId=${voteId}`;
		postData(string, voteId);
	});
}

function postData(data, voteId) {
	let xhr = new XMLHttpRequest();
	xhr.addEventListener("readystatechange", () => {
		if (xhr.readyState == 4 && xhr.status == 200) {
			if (xhr.responseText == "success") {
				window.location.href = `./votes`;
			}
		}
	});
	xhr.open("POST", "ajax/votes.php");
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send(`${data}`);
}

// TABS SWITCH IS IN PROPOSALS.JS
//  SeeMore functionality is in proposal.js using the an event listener on moreVotes

// Countdown functionality
let proposals = document.querySelectorAll("#proposals .pendingProposals .proposal");

function formatNumber(number) {
	if (number < 10) {
		return `0${number}`;
	}
	return number;
}

// Formatting time
function formatNumberToTime(remainingTime) {
	let minutes = Math.floor(remainingTime / 60) % 60;
	let hours = Math.floor(remainingTime / (60 * 60)) % 24;
	let days = Math.floor(remainingTime / (60 * 60 * 24));
	let seconds = Math.floor(remainingTime) % 60;

	let time = `${formatNumber(days)}:${formatNumber(hours)}:${formatNumber(minutes)}`;

	return time;
}

proposals.forEach((e) => {
	let remainingTime = +e.getAttribute("data-time");
	let timeHolder = e.querySelector(".proposal_heading .time span");

	let countdown = setInterval(() => {
		timeHolder.textContent = formatNumberToTime(remainingTime);

		// If any of the proposals time elapses ,
		// countdown should be removed and page refreshed
		if (remainingTime == 0) {
			// Send ajax to finalize vote.
			// Maybe later
			e.style.display = "none";
			window.location.reload();
		}

		if (remainingTime <= 300) {
			timeHolder.style.color = "red";
		}
		remainingTime -= 1;
	}, 1000);
});
