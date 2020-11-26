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

export const registrationValidationErrorsSelector = (state) => {
    return state[NameSpace.REGISTRATION].validationErrors;
};

export const loginValidationErrorsSelector = (state) => {
    return state[NameSpace.LOGIN].validationErrors;
};

export const alertsSelector = (state) => {
    return state[NameSpace.ALERTS].alerts;
}

export const isAuthSelector = (state) => {
    return state[NameSpace.USERS].currentUser !== null;
};

export const currentUserSelector = (state) => {
    return state[NameSpace.USERS].currentUser;
}

export const changeNameValidationErrorsSelector = (state) => {
    return state[NameSpace.VALIDATION_ERRORS].changeName;
};
