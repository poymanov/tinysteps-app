import PropTypes from "prop-types";

const user = PropTypes.shape({
    id: PropTypes.string.isRequired,
    email: PropTypes.string.isRequired,
    name: PropTypes.shape({
        first: PropTypes.string.isRequired,
        last: PropTypes.string.isRequired,
        full: PropTypes.string.isRequired,
    }).isRequired,
    status: PropTypes.string.isRequired,
    role: PropTypes.string.isRequired
});

const UserTypes = {
    item: user.isRequired
};

export default UserTypes;
