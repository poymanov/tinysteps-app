import {ActionType} from "../../action";
import {buildGoals} from "../../../services/goals";

const initialState = {
    goals: []
};

const goals = (state = initialState, action) => {
    switch (action.type) {
        case ActionType.LOAD_GOALS:
            return {...state, ...{goals: buildGoals(action.payload)}};
        default:
            return state;
    }
};

export {goals};
