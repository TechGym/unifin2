import { subscription } from "./imports/footer.js";
import header, { notificationsOpen } from "./imports/header.js";
import logout from "./imports/menu.js";
import { notificationsClose, markReadNotification } from "./imports/notifications.js";

// let connect = document.querySelector("#connect");
// if (connect) {
// 	connect.addEventListener("click", () => {
// 		let xhr = new XMLHttpRequest();
// 		xhr.addEventListener("readystatechange", () => {
// 			if (xhr.status == 200 && xhr.readyState == 4) {
// 				// let response = JSON.parse();
// 				if (xhr.responseText == "success") {
// 					window.location.href = "./";
// 				}
// 			}
// 		});
// 		xhr.open("POST", "ajax/member.php");
// 		xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
// 		xhr.send(`setMembership=true`);
// 	});
// }
