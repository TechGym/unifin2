import { createContext, useContext } from "react";
import { useState, useEffect } from "react";
import axios from "axios";
import SignClient from "@walletconnect/sign-client";

const AppContext = createContext();

const AppProvider = ({ children }) => {
	const [session, setSession] = useState(localStorage.getItem("sessionId") || "");

	const [sessionExpiry, setSessionExpiry] = useState(true);

	useEffect(() => {
		(function () {
			if (session !== "") {
				let parsed = JSON.parse(session),
					expiry = parsed?.expiry + "000";
				// Get difference and check expiry
				let now = new Date().getTime(),
					expTime = new Date(+expiry),
					diff = expTime - now;
				if (diff > 0) {
					setSessionExpiry(false);
				} else {
					setSessionExpiry(true);
					localStorage.removeItem("sessionId");
				}
			}
		})();
	}, [session, sessionExpiry]);

	async function postData(data) {
		console.log("Posting data...");
		let result = await axios.post("http://localhost/unifin/ajax/payment.php", {
			data: data,
		});

		console.log(result.data);
		if (result.data === "success") {
			console.log("Payment successful");
			setTimeout(() => {
				window.location = "../dashboard";
			}, 1000);
		} else {
			console.log("An error occurred");
		}
	}

	async function startStellarConnection() {
		const projectId = "6f07574f473f744b4437fef79a60aae2";
		const metadata = {
			name: "UNIFIN ",
			description: "Unifin.cc is the first DAO",
			url: "https://unifin.cc",
			icons: ["https://unifin.cc/backdoor/images/logo.png"],
		};

		try {
			let connection = await SignClient.init({
				projectId,
				metadata,
			});
			return connection;
		} catch (e) {
			console.log(e);
			return "An error occurred";
		}
	}

	return (
		<AppContext.Provider
			value={{
				session,
				setSession,
				sessionExpiry,
				setSessionExpiry,
				startStellarConnection,
				postData,
			}}
		>
			{children}
		</AppContext.Provider>
	);
};

const useGlobalContext = () => {
	return useContext(AppContext);
};
export { useGlobalContext, AppProvider };
