import React, { useState, useEffect } from "react";
// import SignClient from "@walletconnect/sign-client";
import StellarSdk from "stellar-sdk";
import { useGlobalContext } from "../context";

const Payment = ({ paymentType, secretKey }) => {
	let { session, startStellarConnection, postData } = useGlobalContext();
	const [rate, setRate] = useState(0);
	const [agreePayment, setAgreePayment] = useState(false);
	const STELLAR_METHODS = {
		SIGN_AND_SUBMIT: "stellar_signAndSubmitXDR",
		SIGN: "stellar_signXDR",
	};
	// UseEffect to get rate

	useEffect(() => {
		// let sdk = new StellarSdk.Server("https://horizon-testnet.stellar.org");
		setRate(10);
	}, []);

	async function signTx(server, tx) {
		if (!session) {
			console.log("no session");
			return;
		}
		if (typeof session === "string") {
			session = JSON.parse(session);
		}

		let params = {
			topic: session.topic,
			chainId: "stellar:testnet",
			request: {
				method: STELLAR_METHODS.SIGN,
				params: {
					xdr: tx.toEnvelope().toXDR("base64"),
				},
			},
		};

		let connection = await startStellarConnection();
		try {
			console.log("Waiting for user's response...");
			let response = await connection.request(params);
			console.log("Building transaction...");
			submitTransaction(server, response);
			// try {
			// } catch (e) {
			// 	console.log(e);
			// }
		} catch (e) {
			console.log(e);
		}
	}

	async function submitTransaction(server, res) {
		try {
			const deserializedTx = new StellarSdk.Transaction(
				res.signedXDR,
				StellarSdk.Networks.TESTNET
			);
			console.log("Sumitting...");
			const transactionResult = await server.submitTransaction(deserializedTx);
			postData(transactionResult);
		} catch (err) {
			console.log(err);
		}
	}

	function createTransaction() {
		// let parsed = JSON.parse(session);
		let publicKey = "";
		if (paymentType === "walletconnect") {
			let parsed = JSON.parse(session);
			let accounts = parsed.namespaces.stellar.accounts;
			accounts = accounts[0].split(":");
			publicKey = accounts[accounts.length - 1];
		} else {
			const privateKey = secretKey;
			const keyPair = StellarSdk.Keypair.fromSecret(privateKey);
			publicKey = keyPair.publicKey();
		}

		const server = new StellarSdk.Server("https://horizon-testnet.stellar.org");

		(async function main() {
			const account = await server.loadAccount(publicKey);

			/*
		    Right now, we have one function that fetches the base fee.
		    In the future, we'll have functions that are smarter about suggesting fees,
		    e.g.: `fetchCheapFee`, `fetchAverageFee`, `fetchPriorityFee`, etc.
		*/
			const fee = await server.fetchBaseFee();

			const transaction = new StellarSdk.TransactionBuilder(account, {
				fee: fee,
				networkPassphrase: "Test SDF Network ; September 2015",
			})
				.addOperation(
					// this operation funds the new account with XLM
					StellarSdk.Operation.payment({
						destination:
							"GBXGIK5FQNTIZEJBSCD7ME353TIYEKFU6BZMSLVYTACXX4NCIAS6EPPY",
						asset: StellarSdk.Asset.native(),
						amount: "" + rate,
					})
				)
				.setTimeout(0)
				.build();

			if (paymentType === "walletconnect") {
				try {
					signTx(server, transaction);
				} catch (err) {
					console.error(err);
				}
			} else if (paymentType === "privateKey") {
				transaction.sign(StellarSdk.Keypair.fromSecret(secretKey));
				const transactionResult = await server.submitTransaction(transaction);
				postData(transactionResult);
				try {
				} catch (e) {
					// sign the transaction
					console.log(e);
				}
			}
		})();
	}

	function changeAgreePayment() {
		setAgreePayment((prev) => !prev);
	}

	function proceedPayment(e) {
		e.preventDefault();
		createTransaction();
	}
	return (
		<section className="w-full h-full bg-slate-700 absolute top-0 left-0 flex justify-center items-start z-4">
			<div className="content w-[50%] h-[400px] bg-white rounded-[5px] p-[20px] mt-[10vh]">
				<h3 className="text-[24px]"> Confirm Payment:</h3>
				<div className="price mt-[10px] font-bold ">
					<span className="text-[20px]  block mb-[3px] opacity-[1]">
						Rate :
					</span>
					<p className="opacity-[0.4]">
						$25 = <span className="">{rate} XLM</span>
					</p>
				</div>
				<form action="">
					<div className="agree w-full flex items-center justify-start mt-[20px]">
						<input
							type="checkbox"
							name="agree"
							id="agreePayment"
							className="mr-[10px]"
							onChange={changeAgreePayment}
						/>
						<label htmlFor="agreePayment">
							You agree that acceptojvkcm Lorem ipsum dolor sit amet
							consectetur adipisicing elit. Explicabo tempore unde
							quibusdam.
						</label>
					</div>

					<button
						className={`connect mt-[20px] text-[15px] ${
							agreePayment ? "bg-[#120d33] " : "bg-[#120d3380]"
						} w-[250px] h-[60px] text-white`}
						disabled={!agreePayment}
						onClick={proceedPayment}
					>
						Confirm Payment
					</button>
				</form>
			</div>
		</section>
	);
};

export default Payment;
