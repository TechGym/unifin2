import {
	displayComments,
	createCommentSection,
	closeComments,
	createMoreComments,
	displayReplyMessage,
} from "./imports/comment.js";
import header, { notificationsOpen, closeMenuOnResize } from "../../js/imports/header.js";
import logout from "./imports/menu.js";
import { displayError, validateAmount } from "../../js/imports/verifications.js";

// Automatic growth of message textarea
let socket = "";
let end = false;
let start = 0;
(function () {
	let element = document.querySelector("textarea#message");
	element.addEventListener("input", () => {
		element.style.height = "10px";
		element.style.height = element.scrollHeight + "px";
	});
})();

// Scroll to bottom on start
// Main has an overflow of scroll hence it is targetted and scrolled to the bottom
(function () {
	let scrollable = document.querySelector("main #container");
	scrollable.scrollTop = scrollable.scrollHeight;
})();

// // Starting up websocket
(function () {
	// Create a new WebSocket.
	socket = new WebSocket(`ws://localhost:8080/unifin/chat`);

	socket.onopen = function (e) {
		console.log("connection started");
	};
	socket.onmessage = function (e) {
		let data = JSON.parse(e.data);
		if (data.type && data.type === "newMessage") {
			createChat(data);
			return;
		}
		if (data.content && data.content === "ended") {
			createStartOfMessageNotice();
			end = true;
			return;
		}
		if (data.content && data.content === "more") {
			data = data.data;
			createMoreMessages(data, socket);
		}
		if (data.type && data.type === "fetchComments") {
			createCommentSection(data, socket);
		}
		if (data.type && data.type === "fetchMoreComments") {
			createMoreComments(data);
		}
		if (data.type && data.type === "reply") {
			displayReplyMessage(data);
		}
		if (data.type && data.type === "tip") {
			displayTipResponse(data);
		}
	};
})();

// Creating and submitting chat message
(function () {
	let form = document.querySelector("form");
	form.addEventListener("submit", (e) => {
		e.preventDefault();
		let message = document.querySelector("#message");
		let data = userData();
		data.type = "newMessage";

		if (message.value != "") {
			let sentData = JSON.stringify({
				message: message.value,
				...data,
			});
			socket.send(sentData);
			message.value = "";
		}
	});
})();

// // Getting more data when users scrolls to the top of the loaded messages
(function () {
	let chatArea = document.querySelector("main #container");
	chatArea.addEventListener("scroll", (e) => {
		if (!end) {
			let scrollPos = +chatArea.scrollTop;
			if (scrollPos == 0) {
				start += 250;
				let data = JSON.stringify({
					type: "fetchMore",
					start: start,
				});

				socket.send(data);
			}
		}
	});
})();

function preserveLineBreak(text) {
	return text.replace(/\n/gi, "<br />");
}
function createChat(data) {
	let container = document.querySelector("main #container");
	let messageArea = document.querySelector("#container #chatArea .messageArea");
	let textarea = document.querySelector("textarea#message");
	let inner = "";
	if (data.from == "Me") {
		inner = `
		<article class="chatItem sent" data-id='${data.messageId}'>
			<div class="author">
				<div class="user" data-user-id='${data.sender_id}'>Me</div>
				<div class="country">
					<img src="${data.countryFlag}" alt="Country Flag">
				</div>
				<div class="moderator">

				</div>
			</div>
			<div class="message"> ${preserveLineBreak(data.message)}</div>
			<div class="timestamp">${data.timestamp}</div>
			<div class="comments">
				<div class="comment">
					<div class="chat">
						<i class="fa-regular fa-comment"></i>
					</div>
					<p>Leave a comment</p>
				</div>
				<div class="arrow" id="${data.messageId}">
					<i class=" fa-solid fa-arrow-right" ></i>
				</div>
			</div>
    </article>`;
		messageArea.innerHTML += inner;
		container.scrollTop = container.scrollHeight;
		textarea.style.height = "40px";
	} else {
		inner = `
		<article class="chatItem received" data-id='${data.messageId}'>
			<div class="author">
				<div class="user" data-user-id='${data.sender_id}'>${data.firstname}</div>
				<div class="country">
					<img src="${data.countryFlag}" alt="Country Flag">
				</div>
				<div class="moderator"></div>
			</div>
			<div class="message"> ${preserveLineBreak(data.message)}</div>
			<div class="timestamp">${data.timestamp}</div>
			<div class="comments">
				<div class="comment">
					<div class="chat">
						<i class="fa-regular fa-comment"></i>
					</div>
					<p>Leave a comment</p>
				</div>
				<div class="arrow" id="${data.messageId}">
					<i class=" fa-solid fa-arrow-right" ></i>
				</div>
			</div>
		</article>
		`;
		messageArea.innerHTML += inner;
		pushNotifications();
	}
	displayComments(socket);
	displayTipPopUp();
}

function createMoreMessages(data, socket) {
	let messageArea = document.querySelector("main #container");
	let inner = "";
	function createClassName(e) {
		return e.firstname === userData().firstname ? "sent" : "received";
	}
	function createName(e) {
		return e.firstname === userData().firstname ? "Me" : e.firstname;
	}
	data.forEach((e) => {
		inner = `
		<article class="chatItem ${createClassName(e)}">
      <div class="author">
        <div class="user">${createName(e)}</div>
					<div class="country">
						<img src="${e.countryFlag}" alt="Country Flag">
					</div>
					<div class="moderator"></div>
      </div>
			<div class="message"> ${preserveLineBreak(e.content)}</div>
			<div class="timestamp">${e.time}</div>
			<div class="comments">
			<div class="comment">
		`;
		if (!e.comments) {
			inner += `
			<div class="chat">
		    <i class="fa-regular fa-comment"></i>
      </div>
      <p>Leave a comment</p>`;
		} else {
			inner += `
			<span>${e.comments} </span>
			`;
		}
		inner += `
		 </div>
		  <div class="arrow" id="${e.messageId}">
		    <i class=" fa-solid fa-arrow-right" ></i>
		  </div>
		</div>
		</article>
		`;
		messageArea.innerHTML = inner + messageArea.innerHTML;
	});
	displayComments(socket);
	displayTipPopUp();
}

function createStartOfMessageNotice() {
	let messageArea = document.querySelector("main #container #chatArea");
	let inner = "";
	inner = `<div class="start"> Start of messages </div>`;
	messageArea.innerHTML = inner + messageArea.innerHTML;
	displayComments(socket);
	displayTipPopUp();
}

(function () {
	displayComments(socket);
	closeComments();
})();

// // Displaying tip pop up
function displayTipPopUp() {
	let tipPopUp = document.querySelector("aside#tip");
	let messages = document.getElementsByClassName("chatItem");
	for (let i = 0; i < messages.length; i++) {
		let authorName = messages[i].querySelector(".author .user");
		authorName.addEventListener("click", (e) => {
			tipPopUp.style.display = "flex";
			insertPopUpInfo(authorName.getAttribute("data-user-id"));
		});
	}
}

function insertPopUpInfo(user_id) {
	let tipPopUp = document.querySelector("aside#tip");
	let span = tipPopUp.querySelector("span");
	span.textContent = user_id;
}

function displayTipNotice(text, color) {
	let area = document.querySelector("aside#tip .displayNotice");
	area.textContent = text;
	area.style.display = "block";
	area.style.color = color;
}
function displayTipResponse(data) {
	let tipArea = document.querySelector("aside#tip");
	let area = document.querySelector("aside#tip .displayNotice");
	// Clear tip,pop up on success
	if (data.success) {
		displayTipNotice("Tip successfully sent", "green");

		setTimeout(() => {
			area.style.color = "red";
			area.style.display = "none";
			tipArea.style.display = "none";
		}, 1000);
	}
	// Show error message
	if (data.error) {
		displayTipNotice(data.error, "red");
	}
}

displayTipPopUp();

// Cancelling tip
(function () {
	let tipPopUp = document.querySelector("aside#tip");
	let tipPopUpButton = document.querySelector("aside#tip button");
	tipPopUpButton.addEventListener("click", (e) => {
		e.preventDefault();
		tipPopUp.style.display = "none";
	});
})();

// Submitting tip
(function () {
	let tipPopUp = document.querySelector("aside#tip");
	let inputs = document.querySelectorAll("aside#tip input");
	let tipPopUpSubmit = document.querySelector("aside#tip input[type='submit']");
	let displayNotice = tipPopUp.querySelector(".displayNotice");

	function responseDisplay(text, color) {
		displayNotice.style.display = "block";
		displayNotice.textContent = text;
		displayNotice.style.color = color;
	}
	tipPopUpSubmit.addEventListener("click", (e) => {
		e.preventDefault();
		let authorName = tipPopUp.querySelector("span");
		let author_id = authorName.textContent;
		// tipPopUp.style.display = "none";
		let formData = new FormData(tipPopUp.querySelector("form"));
		let tipAmount = formData.get("tipAmount");

		let validity = validateAmount(tipAmount);
		if (!validity) {
			responseDisplay("Please enter a valid amount");
			return;
		}
		// Check if sender_id is not equal to receiver_id

		let data = userData();
		if (author_id === data.sender_id) {
			// displayError
			responseDisplay("Sender is same as receiver ");
			return;
		}
		data.type = "newTip";
		let sentData = JSON.stringify({
			amount: tipAmount,
			receiver_id: author_id,
			...data,
		});
		socket.send(sentData);
	});

	// Clearing error on input focus
	inputs.forEach((e) => {
		e.addEventListener("focus", () => {
			displayNotice.style.display = "none";
		});
	});
})();
