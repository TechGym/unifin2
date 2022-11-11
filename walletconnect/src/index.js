import React from "react";
import ReactDOM from "react-dom/client";
import "./css/output.css";
import App from "./App";
import "react-app-polyfill/ie11";
import "core-js/features/array/find";
import "core-js/features/array/includes";
import "core-js/features/number/is-nan";
import { AppProvider } from "./context";

window.Buffer = window.Buffer || require("buffer").Buffer;

const root = ReactDOM.createRoot(document.getElementById("root"));
root.render(
	<AppProvider>
		<App />
	</AppProvider>
);
