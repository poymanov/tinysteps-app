import React from "react";
import GoalTypes from "../../types/goals";
import TeacherGoalsItem from "../teacher-goals-item/teacher-goals-item";

function TeacherGoalsList(props) {
    const {goals} = props;

    return (
        <p>
            {goals.map((goal) => <TeacherGoalsItem key={goal.id} goal={goal}/>)}
        </p>
    );
}

TeacherGoalsList.propTypes = {
    goals: GoalTypes.list.isRequired
};

export default TeacherGoalsList;
