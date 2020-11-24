import React, {Fragment, PureComponent} from "react";
import PropTypes from "prop-types";
import {connect} from "react-redux";
import {confirmProfile} from "../../store/api-actions";

class ConfirmProfile extends PureComponent {
    componentDidMount() {
        this.props.confirmProfile(this.props.token);
    }

    componentDidUpdate(prevProps, prevState, snapshot) {
        this.props.confirmProfile(this.props.token);
    }

    render() {
        return <Fragment>
            <h1 className="h1 text-center mx-auto mt-4 py-5">
                <strong>Подтверждение регистрации</strong>
            </h1>
            <div className="text-center">
                <div className="spinner-border" style={{width: `150px`, height: `150px`}} role="status">
                    <span className="sr-only">Профиль находится в процессе подтверждения...</span>
                </div>
            </div>
        </Fragment>;
    }
}

ConfirmProfile.propTypes = {
    token: PropTypes.string.isRequired,
    confirmProfile: PropTypes.func.isRequired,
};

const mapDispatchToProps = {
    confirmProfile
};

export default connect(null, mapDispatchToProps)(ConfirmProfile);
