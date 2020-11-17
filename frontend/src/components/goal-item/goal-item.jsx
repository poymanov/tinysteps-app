import React from "react";
import GoalTypes from "../../types/goals";
import {Link} from "react-router-dom";

function GoalItem(props) {
    const {goal} = props;

    return (
        <Link to={`/goals/${goal.alias}`} className="btn btn-outline-secondary">{goal.icon} {goal.name}</Link>
    );
}

GoalItem.propTypes = {
    goal: GoalTypes.item,
};

export default GoalItem;
