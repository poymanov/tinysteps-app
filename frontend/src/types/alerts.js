import PropTypes from "prop-types";

const item = PropTypes.shape({
    type: PropTypes.string.isRequired,
    message: PropTypes.string.isRequired,
});

const AlertsTypes = {
    default: item,
    defaultList: PropTypes.arrayOf(item)
};

export default AlertsTypes;
