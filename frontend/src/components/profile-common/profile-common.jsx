import React from "react";
import {connect} from "react-redux";
import {currentUserSelector} from "../../store/selectors";
import UserTypes from "../../types/users";

const ProfileCommon = ({currentUser}) => {
    return (
        <table className="table table-borderless">
            <tbody>
            <tr>
                <td><b>Фамилия</b></td>
                <td>{currentUser.name.last}</td>
            </tr>
            <tr>
                <td><b>Имя</b></td>
                <td>{currentUser.name.first}</td>
            </tr>
            <tr>
                <td><b>Email</b></td>
                <td>{currentUser.email}</td>
            </tr>
            </tbody>
        </table>
    );
}

ProfileCommon.propTypes = {
    currentUser: UserTypes.default,
}

const mapStateToProps = (state) => ({
    currentUser: currentUserSelector(state)
});

export default connect(mapStateToProps, null)(ProfileCommon);
