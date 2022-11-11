export function increaseTextArea(textarea) {
	textarea.addEventListener("input", () => {
		let style = getComputedStyle(textarea);
		let height = parseInt(style.height);
		let scrollHeight = textarea.scrollHeight;

		if (scrollHeight > height) {
			scrollHeight = scrollHeight;
			textarea.style.height = scrollHeight + "px";
		}

		// if()
	});
}
