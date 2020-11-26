import React, {Fragment} from "react";
import PropTypes from "prop-types";
import {connect} from "react-redux";
import {currentUserSelector} from "../../store/selectors";
import UserTypes from "../../types/users";
import Spinner from "../spinner/spinner";
import {Link} from "react-router-dom";
import {AppRoute} from "../../constants/const";

const Profile = ({currentUser, active, children}) => {
    if (!currentUser) {
        return <Spinner description="Загрузка профиля пользователя" />
    }

    return (
        <Fragment>
            <nav className="mb-2">
                <div className="nav nav-tabs" id="nav-tab">
                    <Link to={AppRoute.PROFILE_COMMON} className={`nav-item nav-link ` + (active === `common` ? `active` : '')}>Основное</Link>
                </div>
            </nav>
            <div className="tab-content" id="nav-tabContent">
                {children}
            </div>
        </Fragment>
    );
}

Profile.propTypes = {
    currentUser: UserTypes.default,
    active: PropTypes.string.isRequired,
    children: PropTypes.oneOfType([
        PropTypes.arrayOf(PropTypes.node),
        PropTypes.node
    ]).isRequired
}

const mapStateToProps = (state) => ({
    currentUser: currentUserSelector(state)
});

export default connect(mapStateToProps, null)(Profile);
