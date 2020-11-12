import React from "react";
import {Link} from "react-router-dom";

function Header() {
    return (
        <header className="container mt-3">
            <nav className="navbar navbar-expand-lg navbar-light bg-light">
                <Link to="/" className="navbar-brand">TINYSTEPS</Link>
                <button className="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span className="navbar-toggler-icon" />
                </button>
                <div className="collapse navbar-collapse" id="navbarNav">
                    <ul className="navbar-nav">
                        <li className="nav-item active">
                            <Link to="/" className="nav-link">Все репетиторы</Link>
                        </li>
                        <li className="nav-item">
                            <Link to="/request" className="nav-link">Заявка на подбор</Link>
                        </li>
                    </ul>
                </div>
                <span className="navbar-text d-sm-none d-lg-block">☺️</span>
            </nav>
        </header>
    );
}

export default Header;
