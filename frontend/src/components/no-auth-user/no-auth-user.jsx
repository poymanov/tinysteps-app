import React, {Fragment} from "react";
import {Link} from "react-router-dom";
import {AppRoute} from "../../constants/const";

function NoAuthUser() {
    return (
        <Fragment>
            <span className="navbar-text d-sm-none d-lg-block">
                <Link to={AppRoute.REGISTRATION} className="nav-link">Регистрация</Link>
            </span>
            <span className="navbar-text d-sm-none d-lg-block">
                <Link to={AppRoute.LOGIN} className="nav-link">Войти</Link>
            </span>
        </Fragment>
    );
}

export default NoAuthUser;
