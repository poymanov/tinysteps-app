import PropTypes from "prop-types";

const ErrorsTypes = {
    default: PropTypes.shape({
        message: PropTypes.string,
        errors: PropTypes.shape({})
    })
};

export default ErrorsTypes;
