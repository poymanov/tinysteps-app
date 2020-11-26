import React, {Fragment} from "react";
import {connect} from "react-redux";
import {currentUserSelector} from "../../store/selectors";
import UserTypes from "../../types/users";
import {Link} from "react-router-dom";
import {AppRoute} from "../../constants/const";
import AlertsList from "../alerts-list/alerts-list";

const ProfileCommon = ({currentUser}) => {
    return (
        <Fragment>
            <AlertsList />
            <div>
                <Link to={AppRoute.PROFILE_CHANGE_NAME} className="btn btn-info mt-2">Изменить имя</Link>
            </div>
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
        </Fragment>
    );
}

ProfileCommon.propTypes = {
    currentUser: UserTypes.default,
}

const mapStateToProps = (state) => ({
    currentUser: currentUserSelector(state)
});

export default connect(mapStateToProps, null)(ProfileCommon);
