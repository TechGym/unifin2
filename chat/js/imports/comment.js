let commentEnd = false;
let startComment = 0;
export function displayComments(socket) {
	let commentSection = document.querySelector("aside#message");
	let commentArrows = document.querySelectorAll(".chatItem .comments .arrow");
	commentArrows.forEach((arrow) => {
		arrow.addEventListener("click", (e) => {
			let data = JSON.stringify({
				type: "fetchComments",
				id: e.currentTarget.id,
			});
			socket.send(data);
			commentSection.style.display = "block";
		});
	});
}

export function closeComments() {
	let commentSection = document.querySelector("aside#message");
	let closeBtn = document.querySelector("aside#message .close");
	closeBtn.addEventListener("click", () => {
		commentSection.style.display = "none";
	});
}

export function createCommentSection(data, socket) {
	let aside = document.querySelector("aside#message");
	let commentSectionContent = document.querySelector("aside#message .content");
	function createName(e) {
		return e.firstname === userData().firstname ? "Me" : e.firstname;
	}

	let inner = `
		<div class="baseMessage" data-id="${data.baseMessage.messageId}">
			<div class="author">
				<div class="user">${createName(data.baseMessage)}</div>
				<div class="country">
					<img src="${data.baseMessage.countryFlag}" alt="Country Flag">
				</div>
				<div class="moderator">
				</div>
			</div>

			<div class="message"> ${data.baseMessage.content}</div>
			<div class="timestamp">${data.baseMessage.time}</div>
		</div>
	`;
	if (data.comments) {
		inner += `
		<div class="comments">
			<h3>Comments</h3>
		`;
		data.comments.forEach((e) => {
			inner += `
					<div class="chatItem">
						<div class="author">
							<div class="user">${createName(e)}</div>
							<div class="country">
								<img src="${e.countryFlag}" alt="Country Flag">
							</div>
							<div class="moderator">
							</div>
						</div>
						<div class="message"> ${e.content}</div>
						<div class="timestamp">${e.time}</div>
					</div>
		`;
		});
		inner += `
			</div>
		`;
	} else {
		inner += `
		<div class="noComments">
			<p>There are  no comments on this message </p>
		</div>
		`;
	}
	commentSectionContent.innerHTML = inner;
	if (data.comments && data.comments.count == 150) {
		fetchMoreComments(socket);
	} else {
		commentEnd = true;
	}
	sendReply(socket);
	aside.scrollTop = aside.scrollHeight;
}

export function createMoreComments(data) {
	let commentSectionContentComments = document.querySelector(
		"aside#message .content .comments"
	);
	function createName(e) {
		return e.firstname === userData().firstname ? "Me" : e.firstname;
	}

	let inner = "";
	if (data.comments) {
		data.comments.forEach((e) => {
			inner += `
					<div class="chatItem">
						<div class="author">
							<div class="user">${createName(e)}</div>
							<div class="country">
								<img src="${e.countryFlag}" alt="Country Flag">
							</div>
							<div class="moderator">
							</div>
						</div>
						<div class="message"> ${e.content}</div>
						<div class="timestamp">${e.time}</div>
					</div>
		`;
		});
	}
	if (data.comments.count !== 150 && !data.comments) {
		commentEnd = true;
	}
	commentSectionContentComments.innerHTML += inner;
}

export function fetchMoreComments(socket) {
	let commentSection = document.querySelector("aside#message");
	let message_id = commentSection.querySelector(".baseMessage").getAttribute("data-id");
	commentSection.addEventListener("scroll", () => {
		let checkScroll =
			commentSection.scrollHeight - commentSection.scrollTop ==
			commentSection.clientHeight;
		if (checkScroll && !commentEnd) {
			startComment += 250;
			let data = JSON.stringify({
				type: "fetchMoreComments",
				start: startComment,
				message_id,
			});
			console.log(data);
			socket.send(data);
		}
	});
}

export function sendReply(socket) {
	let commentSection = document.querySelector("aside#message");
	let sendReplyBtn = commentSection.querySelector(".replyMessage button");
	let textarea = commentSection.querySelector("#reply");

	if (sendReplyBtn) {
		sendReplyBtn.addEventListener("click", (e) => {
			let baseMessageId = commentSection
				.querySelector(".baseMessage")
				.getAttribute("data-id");
			e.preventDefault();
			let value = textarea.value;
			let data = userData();
			data.type = "reply";
			data.baseMessageId = baseMessageId;

			if (value != "") {
				let sentData = JSON.stringify({
					message: value,
					...data,
				});
				socket.send(sentData);
				textarea.value = "";
			}
		});
	}
}

export function displayReplyMessage(data) {
	let Aside = document.querySelector("aside#message");
	let Comments = document.querySelector("aside#message .content .comments");
	let Content = document.querySelector("aside#message .content");
	let noComments = document.querySelector("aside#message .content .noComments");

	function createName(e) {
		return e.firstname === userData().firstname ? "Me" : e.firstname;
	}

	let inner = "";
	inner = `
		<div class="chatItem">
			<div class="author">
				<div class="user">${createName(data)}</div>
				<div class="country">
					<img src="${data.countryFlag}" alt="Country Flag">
				</div>
				<div class="moderator">
				</div>
			</div>
			<div class="message"> ${data.message}</div>
			<div class="timestamp">${data.timestamp}</div>
		</div>
	`;
	if (Comments) {
		Comments.innerHTML += inner;
	} else {
		noComments.style.display = "none";
		inner = `<div class='comments'> <h3> Comments </h3>` + inner + `</div>`;
		Content.innerHTML += inner;
	}
	if (data.from == "Me") {
		Aside.scrollTop = Aside.scrollHeight;
	}

	// Changing comment description of the main message
	let message = document.querySelector(
		`.messageArea article[data-id='${data.baseMessageId}']`
	);
	let comment = message.querySelector(".comments .comment");
	comment.innerHTML = `<span>${data.commentDesc}</span>`;
}
