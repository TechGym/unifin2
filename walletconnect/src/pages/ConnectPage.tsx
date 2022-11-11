import React, { useState, useEffect } from "react";
import StellarSdk from "stellar-sdk";

import QRCodeModal from "@walletconnect/qrcode-modal";
import SignClient from "@walletconnect/sign-client";
import { useGlobalContext } from "../context";
import Payment from "../components/Payment";

let client = SignClient;
// let horizon_api_url: "https://horizon-testnet.stellar.org/";

interface INamespace {
	stellar: {
		chains: string[];
		accounts: string[];
		methods: string[];
		events: string[];
	};
}

const STELLAR_METHODS = {
	SIGN_AND_SUBMIT: "stellar_signAndSubmitXDR",
	SIGN: "stellar_signXDR",
};

const ConnectPage = () => {
	const [visibility, setVisibility] = useState("hide");
	let [keyError, setKeyError] = useState(false);
	let [secretKey, setSecretKey] = useState("");
	const [agreedTca, setAgreedTca] = useState(false);
	const [paymentType, setPaymentType] = useState("");
	const [keyPair, setkeyPair] = useState("");
	let { setSession, setSessionExpiry, startStellarConnection, session } =
		useGlobalContext();
	function repeatOften() {
		requestAnimationFrame(repeatOften);
	}
	useEffect(() => {
		session ? setPaymentType("walletconnect") : setPaymentType("");
	}, [session]);
	requestAnimationFrame(repeatOften);
	const namespaces: INamespace = {
		stellar: {
			chains: ["stellar:testnet"],
			methods: [STELLAR_METHODS.SIGN],
			events: ["accountsChanged"],
			accounts: [],
		},
	};
	async function approved(res: any) {
		let response: any = await res.approval();
		// Setting  localStorage
		localStorage.setItem("sessionId", JSON.stringify(response));
		QRCodeModal.close();
		setSession(JSON.stringify(response));
		setSessionExpiry(false);
		setPaymentType("walletconnect");
	}

	async function connect(connection: any) {
		if (!client) {
			return;
		}
		let previousPairings = connection?.pairing?.getAll({ active: true })?.reverse();
		let params: any = {
			...(!previousPairings?.length && { pairings: previousPairings }),
			requiredNamespaces: namespaces,
		};

		try {
			//
			let res = await connection.connect(params);
			if (res?.uri && res?.approval) {
				QRCodeModal.open(res.uri, () => {
					console.log("QR Code modal closed");
				});
				console.log("Connecting...");
				approved(res);
			}
		} catch (e) {
			console.log(e);
		}
	}
	async function connectStellar() {
		try {
			let connection = await startStellarConnection();
			if (connection) {
				connect(connection);
			}
		} catch (e) {
			console.log(e);
		}
	}

	function changeTca() {
		setAgreedTca((prev: boolean) => !prev);
	}
	function proceedPayment(e) {
		e.preventDefault();
		// Validate secret key
		if (!/^[0-9A-Za-z]{56}$/.test(secretKey)) {
			setKeyError(true);
			return;
		}

		// Test private key by getting public key
		try {
			let keyPair = StellarSdk.Keypair.fromSecret(secretKey);

			setKeyError(false);
			setkeyPair(keyPair);
			localStorage.removeItem("sessionId");
			setPaymentType("privateKey");
		} catch (e) {
			setKeyError(true);
			return;
		}
	}
	function changeVisibility(e) {
		e.preventDefault();
		setVisibility(visibility === "hide" ? "show" : "hide");
	}
	function storeValue(e) {
		let value = e.target.value;
		setSecretKey(value);
	}
	function setFocus(e) {
		let parent = e.target.parentNode;
		parent.style.outline = "1px solid black";
	}
	function setBlur(e) {
		let parent = e.target.parentNode;
		parent.style.outline = "none";
	}
	return (
		<>
			<section className="w-[100%]  h-[60vh] text-black bg-white flex justify-between items-start">
				<section className="w-[70%] h-[100%]  p-[20px] ">
					<h3 className="text-[24px] mb-[10px]"> Access your account</h3>
					<p className="text-[14px] opacity-40 bold">
						Log in with your secret key to continue payment.
					</p>
					<form action="" method="POST" className="w-[95%] h-[70%] mt-[30px]">
						<label htmlFor="secretKey" className="text-[12px] bold block">
							Enter Secret key
						</label>
						<div className="content w-full h-[50px] flex justify-between items-center mt-[10px] pr-[20px] border-[1.5px] border-black">
							<input
								type={visibility === "show" ? "text" : "password"}
								name="secretKey"
								className="block w-[90%] h-[px]  focus:outline-none  px-[10px] text-[12px] tracking-[2px]"
								placeholder="SXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX"
								onFocus={setFocus}
								onBlur={setBlur}
								onChange={storeValue}
								value={secretKey}
							/>
							<button
								className="w-auto px-[5px] py-[4px]"
								onClick={changeVisibility}
							>
								<i
									className={`fa-solid ${
										visibility === "hide" ? "fa-eye" : "fa-eye-slash"
									}`}
								></i>
							</button>
						</div>
						{keyError && (
							<p className="text-[11px] text-red-500 mt-[3px] font-600">
								Please enter a valid secret key
							</p>
						)}
						<div className="tca bg-[#120d331a] flex justify-start items-center mt-[15px] p-[13px]">
							<input
								type="checkbox"
								name="tca"
								id="tca"
								className="block mr-3"
								onChange={changeTca}
							/>
							<label htmlFor="tca" className="text-[12px] ">
								I accept the Terms of Use, understand the risks associated
								with cryptocurrencies, and know that UNIFIN does issue or
								endorse only the ePHI asset on the Stellar network.
							</label>
						</div>
						<button
							className={`connect mt-[20px] text-[15px] ${
								agreedTca ? "bg-[#120d33] " : "bg-[#120d3380]"
							} w-[250px] h-[60px] text-white`}
							disabled={!agreedTca}
							onClick={proceedPayment}
						>
							Continue To Payment
						</button>
					</form>
				</section>
				<aside className="w-[30%] h-[100%] bg-[#120d331a]  p-[20px] ">
					<div className="warning flex justify-start items-center">
						<i className="fa-solid fa-triangle-exclamation text-red-500 mr-[10px]"></i>
						<p className="text-red-500 text-[13px]">
							Secret Phrase not found
						</p>
					</div>
					<p className="w-full my-[7px] text-[14px] bold">Check the URL</p>
					<p className="text-[13px] text-black opacity-50">
						Make sure you are on the correct website.
					</p>
					<div className="website  w-[80%] h-[30px] my-[7px] px-[10px] flex justify-start items-center  border-[1.5px] border-[#00000033] rounded-[15px] ">
						<div className="icon mr-[10px] -mt-[5px]">
							<i className="fa-solid text-[#00af50] text-[13px]  fa-lock "></i>
						</div>
						<p className="text-[13px] block ">
							<span className="text-[#00af50]">https://</span>unifin.cc
						</p>
					</div>
					<h3 className="w-full text-[14px] mt-[20px]">
						Keep your secret key secure
					</h3>
					<p className="w-full text-[12px] text-black opacity-50 mt-5 leading-[25px] ">
						UNIFIN does not save your secret key. It is stored on your browser
						and will be deleted once the page is refreshed or exited.
					</p>
				</aside>
			</section>
			<section className="w-[100%]  h-[30vh] mt-[40px] text-black bg-white flex justify-between items-start">
				<section className="w-[70%] h-[100%]   p-[20px] ">
					<h3 className="text-[20px] mb-[5px]"> Connect your wallet</h3>

					<p className="text-[14px] opacity-40 bold">
						Log in with wallet connect
					</p>
					<button
						className="connect mt-[20px] text-[15px] border-[1.5px] border-[#120d3380] w-[250px] h-[60px]  px-[20px] flex justify-center items-center"
						onClick={connectStellar}
					>
						<img
							src="./walletconnect.svg"
							alt="Wallet Connect"
							className="w-[30px] h-[30px] block"
						/>
						<p className="ml-[10px] font-bold">WalletConnect</p>
					</button>
				</section>
			</section>
			{(session || keyPair) && (
				<Payment paymentType={paymentType} secretKey={secretKey} />
			)}
		</>
	);
};

// SBLSOR6MMF4DRGGVMQLTTBSSMOUVKRYB4MN43MRTFRH67MCDXLZJWRL3;
//
// soda forward soon hazard economy aware festival keep asthma pulse tiny core furnace vapor nose say mountain achieve actor arm grass poem winner chase

export default ConnectPage;
