import React from "react";
import "../css/includes.css";
import Logo from "../images/logo.png";
const Header = () => {
	return (
		<div className="header_container">
			<header>
				<div className="header__logo">
					<img src={Logo} alt="Logo" />
					<h1>UNIFIN</h1>
				</div>
				<nav className="desktop">
					<a href="../home" className="active home">
						Home
					</a>
					<a href="../swap" className="swap">
						Swap
					</a>
					<a href="../whitepaper" className="whitePaper">
						White paper
					</a>
					<a href="../faq" className="faq">
						Faq
					</a>
					<a href="../contact" className="contact">
						Contact
					</a>
				</nav>
				<div className="header__right">
					<button className="menu__btn phone">
						<i className="fa-solid fa-bars "></i>
					</button>
					<p className="desktop whitepaper"> White paper</p>
				</div>
			</header>
		</div>
	);
};

export default Header;
