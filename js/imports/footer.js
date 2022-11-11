// Subscriptions

export function subscription() {
  let subscription_form = document.querySelector(
    "footer .footer__newsletter form"
  );
  if (subscription_form) {
    let subscription_err = subscription_form.querySelector(".err");

    subscription_form.addEventListener("submit", (e) => {
      e.preventDefault();
      let formdata = new FormData(subscription_form);
      let email = formdata.get("email");
      if (!/^[A-Za-z][\w-\.]+[\w]@[\w-\.]+\.[\w]{2,3}$/.test(email)) {
        subscription_err.style.display = "block";
        subscription_err.textContent = "Please enter a valid email address ";
        return;
      } else {
        // Post to php
        let xhr = new XMLHttpRequest();
        xhr.addEventListener("readystatechange", () => {
          if (xhr.readyState == 4 && xhr.status == 200) {
            if (xhr.responseText == "success") {
              window.location.href = "./home";
            } else if (xhr.responseText == "email registered") {
              subscription_err.textContent = "Email already registered ";
              subscription_err.style.display = "block";
            }
          }
        });
        xhr.open("POST", "ajax/subscription.php");
        xhr.setRequestHeader(
          "Content-Type",
          "application/x-www-form-urlencoded"
        );
        xhr.send(`subscribed=true&email=${email}`);
      }
    });

    let email_input = subscription_form.querySelector("input[name='email']");
    email_input.addEventListener("focus", () => {
      subscription_err.style.display = "none";
    });
  }
}
subscription();
