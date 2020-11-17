import PropTypes from "prop-types";

const goal = PropTypes.shape({
    id: PropTypes.string.isRequired,
    name: PropTypes.string.isRequired,
    alias: PropTypes.string.isRequired,
    sort: PropTypes.number.isRequired,
    status: PropTypes.number.isRequired,
    icon: PropTypes.string,
    createdAt: PropTypes.string.isRequired,
});

const GoalTypes = {
    item: goal,
    list: PropTypes.arrayOf(goal.isRequired)
};

export default GoalTypes;
