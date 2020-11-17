import {ActionType} from "../../action";
import {buildGoal, buildGoals} from "../../../services/goals";

const initialState = {
    goals: [],
    currentGoal: null,
    currentTeacherGoals: null
};

const goals = (state = initialState, action) => {
    switch (action.type) {
        case ActionType.LOAD_GOALS:
            return {...state, ...{goals: buildGoals(action.payload)}};
        case ActionType.LOAD_GOALS_BY_TEACHER:
            return {...state, ...{currentTeacherGoals: buildGoals(action.payload)}};
        case ActionType.LOAD_GOAL:
            return {...state, ...{currentGoal: buildGoal(action.payload)}};
        case ActionType.FLUSH_GOAL:
            return {...state, ...{currentGoal: null}};
        case ActionType.FLUSH_GOALS_BY_TEACHER:
            return {...state, ...{currentTeacherGoals: null}};
        default:
            return state;
    }
};

export {goals};
