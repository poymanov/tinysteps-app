import {ActionType} from "../../action";
import {buildTeachers} from "../../../services/teachers";

const initialState = {
    teachers: []
};

const teachers = (state = initialState, action) => {
    switch (action.type) {
        case ActionType.LOAD_TEACHERS:
            return {...state, ...{teachers: buildTeachers(action.payload)}};
        default:
            return state;
    }
};

export {teachers};
