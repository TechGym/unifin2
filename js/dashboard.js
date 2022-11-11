import header, { notificationsOpen } from "./imports/header.js";
import logout from "./imports/menu.js";
import { notificationsClose, markReadNotification } from "./imports/notifications.js";

// Setting the active button on scroll

function clearActive(index) {
	let indicators = document.querySelectorAll(".indicators .indicator");
	indicators.forEach((e, index) => {
		if (e.classList.contains("active")) {
			e.classList.remove("active");
		}
	});
	indicators[index].classList.add("active");
}

function setBreakPoints(firstBreak) {
	let arr = [];
	for (let i = 1; i <= 5; i++) {
		let data = {
			index: i,
			break: +firstBreak * i,
		};
		arr.push(data);
	}
	return arr;
}

function setScrollPoints(firstScroll) {
	let arr = [];
	for (let i = 0; i < 5; i++) {
		arr.push(+firstScroll * i);
	}
	return arr;
}
(function () {
	let heroModal = document.querySelector(".hero__modal");
	let pageWidth = Math.floor(heroModal.scrollWidth / 5);
	let breakPointsData = setBreakPoints(pageWidth);
	heroModal.addEventListener("scroll", () => {
		let scroll = heroModal.scrollLeft;
		let filt = breakPointsData.filter((e, index) => +e.break > scroll);
		// Checking the position of the modal whether it is past 50% or not
		let first = filt[0],
			second = filt[1];
		let active = first;
		if (second) {
			let diff_between_first_second = second.break - first.break;
			let diff_between_scroll_second = first.break - scroll;

			if (
				diff_between_scroll_second < Math.floor(diff_between_first_second * 0.3)
			) {
				active = second;
			}
		}

		clearActive(+active.index - 1);
	});
})();

// Automating scrolls on the modal
(function () {
	let heroModal = document.querySelector(".hero__modal");
	let index = 2;

	let pageWidth = Math.floor(heroModal.scrollWidth / 5);
	let scrollPoints = setScrollPoints(pageWidth);
	setInterval(() => {
		heroModal.scrollLeft = scrollPoints[index - 1];
		index = index === 5 ? 0 : index;
		index++;
	}, 3000);
})();

// Automating scrolls on the notification section (hero section);
(function () {
	let notification = document.querySelector(".notifications .content");
	let notification_items = notification.querySelectorAll(".notification_item");
	let length = notification_items.length;
	let start = 1;

	setInterval(() => {
		notification_items[start].scrollIntoView({
			behavior: "smooth",
			block: "nearest",
			inline: "nearest",
		});
		start === length - 1 ? (start = 0) : start++;
	}, 5000);
})();
