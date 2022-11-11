const successCallback = async (e) => {
	let form = document.querySelector("#recaptchaForm");
	// Validate form Data
	let postData = `token=${e}`;
	let xhr = new XMLHttpRequest();
	xhr.addEventListener("readystatechange", () => {
		if (xhr.status === 200 && xhr.readyState === 4) {
			let response = xhr.responseText;
			if (response === "success") {
				setTimeout(() => {
					window.location.href = "./register";
				});
			} else {
				let error = form.querySelector(".error");
				error.style.display = "block";
				error.textContent = response;
			}
		}
	});
	xhr.open("POST", "ajax/recaptcha.php");
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.send(postData);
};

function onLoadFunction() {
	grecaptcha.render("recaptchaBtn", {
		sitekey: "6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI",
		callback: successCallback,
	});
}
