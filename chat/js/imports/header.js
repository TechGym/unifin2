let menuBtn = document.querySelector(".menu__btn");
let coverScreen = document.querySelector(".cover__screen");
let phoneMenu = document.querySelector(".phone__menu");
let menuClose = document.querySelector(".phone__menu__close");

export default function header() {
	// Menu Toggle
	if (menuBtn) {
		menuBtn.addEventListener("click", () => {
			phoneMenu.style.animation =
				"cover_slideIn 0.3s ease-in 0.3s forwards";
			coverScreen.style.animation = "cover_slideIn 0.3s ease-in forwards";
		});

		menuClose.addEventListener("click", () => {
			phoneMenu.style.animation = "cover_slideOut 0.3s ease-in forwards";
			setTimeout(() => {
				coverScreen.style.animation =
					"cover_slideOut 0.5s ease-in  forwards";
			}, 300);
		});
	}
}
export function notificationsOpen() {
	let open = document.querySelector(".notification");
	if (open) {
		open.addEventListener("click", () => {
			let notifications = document.querySelector("#notifications");

			notifications.style.animation =
				"slide_notifications_in 0.5s forwards";
		});
	}
}
header();
notificationsOpen();
