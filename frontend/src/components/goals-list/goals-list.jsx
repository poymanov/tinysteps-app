import React from "react";
import GoalItem from "../goal-item/goal-item";
import {goals} from "../../mocks/goals";

function GoalsList() {
    return (
        <div className="text-center mb-5">
            <div className="btn-group mx-auto mb-0" role="group" aria-label="Basic example">
                {goals.map((goal) => <GoalItem key={goal.id} goal={goal}/>)}
            </div>
        </div>
    );
}

export default GoalsList;
