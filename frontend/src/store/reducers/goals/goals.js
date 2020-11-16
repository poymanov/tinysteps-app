import {ActionType} from "../../action";
import {buildGoal, buildGoals} from "../../../services/goals";

const initialState = {
    goals: [],
    currentGoal: null
};

const goals = (state = initialState, action) => {
    switch (action.type) {
        case ActionType.LOAD_GOALS:
            return {...state, ...{goals: buildGoals(action.payload)}};
        case ActionType.LOAD_GOAL:
            return {...state, ...{currentGoal: buildGoal(action.payload)}};
        case ActionType.FLUSH_GOAL:
            return {...state, ...{currentGoal: null}};
        default:
            return state;
    }
};

export {goals};
