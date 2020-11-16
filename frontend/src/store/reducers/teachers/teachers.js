import {ActionType} from "../../action";
import {buildTeachers} from "../../../services/teachers";

const initialState = {
    teachers: [],
    teachersByGoal: []
};

const teachers = (state = initialState, action) => {
    switch (action.type) {
        case ActionType.LOAD_TEACHERS:
            return {...state, ...{teachers: buildTeachers(action.payload)}};
        case ActionType.LOAD_TEACHERS_BY_GOAL:
            return {...state, ...{teachersByGoal: buildTeachers(action.payload)}};
        case ActionType.FLUSH_TEACHERS_BY_GOAL:
            return {...state, ...{teachersByGoal: []}};
        default:
            return state;
    }
};

export {teachers};
