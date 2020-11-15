export const ActionType = {
    LOAD_GOALS: `LOAD_GOALS`,
    LOAD_TEACHERS: `LOAD_TEACHERS`,
    REDIRECT_TO_ROUTE: `REDIRECT_TO_ROUTE`
};

export const loadGoals = (goals) => ({
    type: ActionType.LOAD_GOALS,
    payload: goals,
});

export const loadTeachers = (teachers) => ({
    type: ActionType.LOAD_TEACHERS,
    payload: teachers,
});

export const redirectToRoute = (url) => ({
    type: ActionType.REDIRECT_TO_ROUTE,
    payload: url,
});
