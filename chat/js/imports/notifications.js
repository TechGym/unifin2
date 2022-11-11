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
				window.location.href = "../" + link;
			}
		}
	});
	xhr.open("POST", "../ajax/notifications.php");
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send(data);
}

export async function pushNotifications() {
	// Display only when user is not in current tab
	let showNotification = document.visibilityState !== "hidden";
	if (showNotification) {
		if (typeof Notification !== "undefined") {
			// Checking if user has granted permission

			if (Notification.permission !== "granted") {
				let consent = await Notification.requestPermission();
			}
			let consent = await Notification.requestPermission();

			var title = "UNIFIN.cc",
				icon = "http://localhost/unifin/images/logo.png";
			var body = "Chat body here ";
			var notification = new Notification(title, { body, icon });
		}
	}
}
// pushNotifications();
