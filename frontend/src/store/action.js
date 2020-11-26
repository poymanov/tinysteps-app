export const ActionType = {
    LOAD_GOALS: `LOAD_GOALS`,
    LOAD_GOALS_BY_TEACHER: `LOAD_GOALS_BY_TEACHER`,
    LOAD_TEACHERS: `LOAD_TEACHERS`,
    LOAD_TEACHERS_BY_GOAL: `LOAD_TEACHERS_BY_GOAL`,
    LOAD_TEACHER: `LOAD_TEACHER`,
    LOAD_GOAL: `LOAD_GOAL`,
    LOAD_REGISTRATION_ERRORS: `LOAD_REGISTRATION_ERRORS`,
    LOAD_LOGIN_ERRORS: `LOAD_LOGIN_ERRORS`,
    LOAD_CHANGE_NAME_ERRORS: `LOAD_CHANGE_NAME_ERRORS`,
    LOAD_ALERT: `LOAD_ALERT`,
    LOAD_PROFILE: `LOAD_PROFILE`,
    FLUSH_GOAL: `FLUSH_GOAL`,
    FLUSH_GOALS_BY_TEACHER: `FLUSH_GOALS_BY_TEACHER`,
    FLUSH_TEACHER: `FLUSH_TEACHER`,
    FLUSH_REGISTRATION_ERRORS: `FLUSH_REGISTRATION_ERRORS`,
    FLUSH_LOGIN_ERRORS: `FLUSH_LOGIN_ERRORS`,
    FLUSH_CHANGE_NAME_ERRORS: `FLUSH_CHANGE_NAME_ERRORS`,
    FLUSH_ALERTS: `FLUSH_ALERTS`,
    FLUSH_TEACHERS_BY_GOAL: `FLUSH_TEACHERS_BY_GOAL`,
    FLUSH_CURRENT_USER: `FLUSH_CURRENT_USER`,
    REDIRECT_TO_ROUTE: `REDIRECT_TO_ROUTE`,
};

export const loadGoals = (goals) => ({
    type: ActionType.LOAD_GOALS,
    payload: goals,
});

export const loadGoalsByTeacher = (goals) => ({
    type: ActionType.LOAD_GOALS_BY_TEACHER,
    payload: goals,
});

export const loadGoal = (goal) => ({
    type: ActionType.LOAD_GOAL,
    payload: goal,
});

export const flushGoal = () => ({
    type: ActionType.FLUSH_GOAL,
});

export const loadTeachers = (teachers) => ({
    type: ActionType.LOAD_TEACHERS,
    payload: teachers,
});

export const loadTeacher = (teacher) => ({
    type: ActionType.LOAD_TEACHER,
    payload: teacher,
});

export const loadTeachersByGoal = (teachers) => ({
    type: ActionType.LOAD_TEACHERS_BY_GOAL,
    payload: teachers,
});

export const loadRegistrationErrors = (errors) => ({
    type: ActionType.LOAD_REGISTRATION_ERRORS,
    payload: errors,
});

export const loadLoginErrors = (errors) => ({
    type: ActionType.LOAD_LOGIN_ERRORS,
    payload: errors,
});

export const loadChangeNameErrors = (errors) => ({
    type: ActionType.LOAD_CHANGE_NAME_ERRORS,
    payload: errors,
});

export const loadAlert = (alert) => ({
    type: ActionType.LOAD_ALERT,
    payload: alert
});

export const loadProfile = (profile) => ({
    type: ActionType.LOAD_PROFILE,
    payload: profile
});

export const flushTeachersByGoal = () => ({
    type: ActionType.FLUSH_TEACHERS_BY_GOAL,
});

export const flushGoalsByTeacher = () => ({
    type: ActionType.FLUSH_GOALS_BY_TEACHER,
});

export const flushTeacher = () => ({
    type: ActionType.FLUSH_TEACHER,
});

export const flushRegistrationErrors = () => ({
    type: ActionType.FLUSH_REGISTRATION_ERRORS,
});

export const flushLoginErrors = () => ({
    type: ActionType.FLUSH_LOGIN_ERRORS,
});

export const flushChangeNameErrors = () => ({
    type: ActionType.FLUSH_CHANGE_NAME_ERRORS,
});

export const flushAlerts = () => ({
    type: ActionType.FLUSH_ALERTS,
});

export const flushCurrentUser = () => ({
    type: ActionType.FLUSH_CURRENT_USER,
});

export const redirectToRoute = (url) => ({
    type: ActionType.REDIRECT_TO_ROUTE,
    payload: url,
});
