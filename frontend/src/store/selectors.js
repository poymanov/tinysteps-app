import {NameSpace} from "./reducers/root-reducer";

export const goalsSelector = (state) => {
    return state[NameSpace.GOALS].goals;
};

export const goalSelector = (state) => {
    return state[NameSpace.GOALS].currentGoal;
};

export const teachersSelector = (state) => {
    return state[NameSpace.TEACHERS].teachers;
};

export const teacherSelector = (state) => {
    return state[NameSpace.TEACHERS].currentTeacher;
};

export const teachersByGoalSelector = (state) => {
    return state[NameSpace.TEACHERS].teachersByGoal;
};

export const goalsByTeacherSelector = (state) => {
    return state[NameSpace.GOALS].currentTeacherGoals;
};
