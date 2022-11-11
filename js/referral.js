import header, { notificationsOpen, closeMenuOnResize } from "./imports/header.js";
import logout from "./imports/menu.js";
import { notificationsClose, markReadNotification } from "./imports/notifications.js";
// Copy to clipboard
(function () {
	let link = document.querySelector(".link");
	let clipboard = document.querySelector(".clipboard");
	let icon = clipboard.querySelector("i");

	async function copyText() {
		navigator.clipboard.writeText(link.innerText);

		// Changing icon to tick
		icon.classList.remove("fa-clipboard");
		icon.classList.remove("fa-regular");
		icon.classList.add("fa-solid");
		icon.classList.add("fa-check");

		setTimeout(() => {
			icon.classList.remove("fa-solid");
			icon.classList.remove("fa-check");
			icon.classList.add("fa-clipboard");
			icon.classList.add("fa-regular");
		}, 2000);
	}
	link.addEventListener("click", copyText);

	clipboard.addEventListener("click", copyText);
})();
