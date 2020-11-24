import React from "react";
import UserTypes from "../../types/users";
import {currentUserSelector} from "../../store/selectors";
import {connect} from "react-redux";

function AuthUser({currentUser}) {
    return (
        <span className="navbar-text d-sm-none d-lg-block">{currentUser.name.full}</span>
    );
}

AuthUser.propTypes = {
    currentUser: UserTypes.item
}

const mapStateToProps = (state) => ({
    currentUser: currentUserSelector(state)
});

export default connect(mapStateToProps, null)(AuthUser);
