import {ActionType} from "../../action";
import {buildTeacher, buildTeachers} from "../../../services/teachers";

const initialState = {
    teachers: [],
    teachersByGoal: [],
    currentTeacher: null
};

const teachers = (state = initialState, action) => {
    switch (action.type) {
        case ActionType.LOAD_TEACHERS:
            return {...state, ...{teachers: buildTeachers(action.payload)}};
        case ActionType.LOAD_TEACHERS_BY_GOAL:
            return {...state, ...{teachersByGoal: buildTeachers(action.payload)}};
        case ActionType.FLUSH_TEACHERS_BY_GOAL:
            return {...state, ...{teachersByGoal: []}};
        case ActionType.LOAD_TEACHER:
            return {...state, ...{currentTeacher: buildTeacher(action.payload)}};
        case ActionType.FLUSH_TEACHER:
            return {...state, ...{currentTeacher: null}};
        default:
            return state;
    }
};

export {teachers};
