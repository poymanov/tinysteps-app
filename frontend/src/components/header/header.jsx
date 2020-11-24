import React from "react";
import {Link} from "react-router-dom";
import {AppRoute} from "../../constants/const";
import {connect} from "react-redux";
import PropTypes from "prop-types";
import {isAuthSelector} from "../../store/selectors";
import NoAuthUser from "../no-auth-user/no-auth-user";
import AuthUser from "../auth-user/auth-user";

function Header({isAuth}) {
    let profile = null;

    if (isAuth) {
        profile = <AuthUser />
    } else {
        profile = <NoAuthUser />
    }

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
                            <Link to={AppRoute.ROOT} className="nav-link">Все репетиторы</Link>
                        </li>
                        <li className="nav-item">
                            <Link to={AppRoute.REQUEST} className="nav-link">Заявка на подбор</Link>
                        </li>
                    </ul>
                </div>
                {profile}
            </nav>
        </header>
    );
}

Header.propTypes = {
    isAuth: PropTypes.bool.isRequired
};

const mapStateToProps = (state) => ({
    isAuth: isAuthSelector(state),
});

export default connect(mapStateToProps, null)(Header);
