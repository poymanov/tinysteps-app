import PropTypes from "prop-types";

const goal = PropTypes.shape({
    id: PropTypes.number.isRequired,
    alias: PropTypes.string.isRequired,
    icon: PropTypes.string.isRequired,
    title: PropTypes.string.isRequired,
});

const GoalTypes = {
    item: goal
};

export default GoalTypes;
