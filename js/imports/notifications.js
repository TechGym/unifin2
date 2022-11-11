export function notificationsClose() {
	let close = document.querySelector(".closeNotification");
	if (close) {
		close.addEventListener("click", () => {
			let notifications = document.querySelector("#notifications");
			notifications.style.animation = "slide_notifications_out 0.5s forwards";
		});
	}
}

export function markReadNotification() {
	let notices = document.querySelectorAll("#notifications .notice");

	notices.forEach((notice) => {
		notice.addEventListener("click", (e) => {
			e.preventDefault();
			let data = `markRead=true&id=${notice.id}&type=${notice.getAttribute(
				"data-type"
			)}`;
			let link = notice.querySelector("a");
			link = link.getAttribute("href");
			postToPhp(data, link);
		});
	});
}
notificationsClose();
markReadNotification();

function postToPhp(data, link) {
	let xhr = new XMLHttpRequest();
	xhr.addEventListener("readystatechange", () => {
		if (xhr.readyState == 4 && xhr.status == 200) {
			if (xhr.responseText == "success") {
				window.location.href = link;
			}
		}
	});
	xhr.open("POST", "ajax/notifications.php");
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send(data);
}
