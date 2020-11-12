import React from "react";

function Header() {
    return (
        <header className="container mt-3">
            <nav className="navbar navbar-expand-lg navbar-light bg-light">
                <a className="navbar-brand" href="#">TINYSTEPS</a>
                <button className="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span className="navbar-toggler-icon" />
                </button>
                <div className="collapse navbar-collapse" id="navbarNav">
                    <ul className="navbar-nav">
                        <li className="nav-item active">
                            <a className="nav-link" href="#">Все репетиторы</a>
                        </li>
                        <li className="nav-item">
                            <a className="nav-link" href="#">Заявка на подбор</a>
                        </li>
                    </ul>
                </div>
                <span className="navbar-text d-sm-none d-lg-block">☺️</span>
            </nav>
        </header>
    );
}

export default Header;
