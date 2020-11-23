import React from "react";
import PropTypes from "prop-types";

function FormErrorAlert(props) {
    return (
        <div className="d-flex alert alert-danger justify-content-center">{props.message}</div>
    );
}

FormErrorAlert.propTypes = {
    message: PropTypes.string.isRequired
};

export default FormErrorAlert;
