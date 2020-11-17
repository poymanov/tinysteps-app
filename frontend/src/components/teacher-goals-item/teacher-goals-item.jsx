import React from "react";
import {Link} from "react-router-dom";
import GoalTypes from "../../types/goals";
import {AppRoute} from "../../constants/const";

function TeacherGoalsItem(props) {
    const {goal} = props;

    return (
        <Link to={AppRoute.GOALS + `/${goal.alias}`}><span className="badge badge-secondary mr-2">{goal.name}</span></Link>
    );
}

TeacherGoalsItem.propTypes = {
    goal: GoalTypes.item.isRequired
};

export default TeacherGoalsItem;
