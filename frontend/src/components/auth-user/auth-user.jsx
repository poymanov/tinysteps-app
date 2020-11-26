import React, {Fragment} from "react";
import UserTypes from "../../types/users";
import {currentUserSelector} from "../../store/selectors";
import {connect} from "react-redux";
import {flushCurrentUser} from "../../store/action";
import PropTypes from "prop-types";
import {Link} from "react-router-dom";
import {AppRoute} from "../../constants/const";

function AuthUser({currentUser, flushCurrentUser}) {
    return (
        <Fragment>
            <span className="navbar-text d-sm-none d-lg-block">
                <Link to={AppRoute.PROFILE_COMMON} className="nav-link"><b>{currentUser.name.full}</b></Link>
            </span>
            <span className="navbar-text d-sm-none d-lg-block">
                <Link to="/" className="nav-link" onClick={flushCurrentUser}>Выход</Link>
            </span>
        </Fragment>
    );
}

AuthUser.propTypes = {
    currentUser: UserTypes.item,
    flushCurrentUser: PropTypes.func.isRequired
}

const mapStateToProps = (state) => ({
    currentUser: currentUserSelector(state)
});

const mapDispatchToProps = {
    flushCurrentUser
};

export default connect(mapStateToProps, mapDispatchToProps)(AuthUser);
