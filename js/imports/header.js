let menuBtn = document.querySelector(".menu__btn");
let coverScreen = document.querySelector(".cover__screen");
let phoneMenu = document.querySelector(".phone__menu");
let menuClose = document.querySelector(".phone__menu__close");

let active = false;

export default function header() {
	// Menu Toggle
	if (menuBtn && phoneMenu && coverScreen) {
		menuBtn.addEventListener("click", () => {
			phoneMenu.style.animation = "cover_slideIn 0.3s ease-in 0.3s forwards";
			coverScreen.style.animation = "cover_slideIn 0.3s ease-in forwards";
			active = true;
		});

		menuClose.addEventListener("click", () => {
			phoneMenu.style.animation = "cover_slideOut 0.3s ease-in forwards";
			setTimeout(() => {
				coverScreen.style.animation = "cover_slideOut 0.5s ease-in  forwards";
			}, 300);
			active = false;
		});
	}
}

export function closeMenuOnResize() {
	window.addEventListener("resize", () => {
		if (phoneMenu) {
			let innerWidth = window.innerWidth;
			if (innerWidth > 1025) {
				phoneMenu.style.animation = "none";
				coverScreen.style.animation = "none";
				phoneMenu.style.marginLeft = "0";
				coverScreen.style.marginLeft = "0";
			}
			if (innerWidth < 1025 && !active) {
				phoneMenu.style.animation = "none";
				coverScreen.style.animation = "none";
				phoneMenu.style.marginLeft = "-100%";
				coverScreen.style.marginLeft = "-100%";
			}
			if (innerWidth < 1025 && active) {
				phoneMenu.style.animation = "none";
				coverScreen.style.animation = "none";
				phoneMenu.style.marginLeft = "0";
				coverScreen.style.marginLeft = "0";
			}
		}
	});
}
export function notificationsOpen() {
	let open = document.querySelector(".notification");
	if (open) {
		open.addEventListener("click", () => {
			let notifications = document.querySelector("#notifications");
			notifications.style.animation = "slide_notifications_in 0.5s forwards";
		});
	}
}
header();
notificationsOpen();
closeMenuOnResize();
