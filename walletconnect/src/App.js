// import Payment from "./components/Payment.tsx";
import Header from "./components/Header";
import ConnectPage from "./pages/ConnectPage.tsx";
import React from "react";
function App() {
	return (
		<main className="w-full h-[100vh]  lg:mt-[90px] lg:max-w-[1050px] mx-auto ">
			<Header />
			<ConnectPage />
		</main>
	);
}

export default App;
