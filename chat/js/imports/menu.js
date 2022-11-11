//  Logout functionality
export default function logout() {
	let logoutBtn = document.querySelector(".phone__menu nav .logout");
	if (logoutBtn) {
		let postString = `logout=true`;
		logoutBtn.addEventListener("click", () => {
			postToPhp(postString);
		});
	}
}
logout();

function postToPhp(string) {
	let xhr = new XMLHttpRequest();
	xhr.addEventListener("readystatechange", () => {
		if (xhr.readyState == 4 && xhr.status == 200) {
			if (xhr.responseText == "success") {
				document.cookie = "logUser=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
				document.cookie = "PHPSESSID=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
				window.location.href = "./";
			}
		}
	});
	xhr.open("POST", "../ajax/logout.php");
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send(`${string}`);
}
// Screen resize
export function menuResize() {
	window.addEventListener("resize", () => {
		let phoneMenu = document.querySelector(".phone__menu");
		let coverScreen = document.querySelector(".cover__screen");
		let style = getComputedStyle(phoneMenu);
		let innerWidth = parseInt(window.innerWidth);
		let marginLeft = -parseInt(style.marginLeft);
		if (innerWidth >= 1025 && marginLeft == 0) {
			phoneMenu.style.animation = "cover_slideOut 0.3s ease-in forwards";
			setTimeout(() => {
				coverScreen.style.animation = "cover_slideOut 0.5s ease-in  forwards";
			}, 300);
		}
	});
}
