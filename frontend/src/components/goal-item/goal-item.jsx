import React from "react";
import GoalTypes from "../../types/goals";

function GoalItem(props) {
    const {goal} = props;

    return (
        <a key={goal.id} href="#" className="btn btn-outline-secondary">{goal.icon} {goal.title}</a>
    );
}

GoalItem.propTypes = {
    goal: GoalTypes.item,
};

export default GoalItem;
