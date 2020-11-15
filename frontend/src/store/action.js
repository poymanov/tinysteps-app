export const ActionType = {
    LOAD_GOALS: `LOAD_GOALS`,
    REDIRECT_TO_ROUTE: `REDIRECT_TO_ROUTE`
};

export const loadGoals = (goals) => ({
    type: ActionType.LOAD_GOALS,
    payload: goals,
});

export const redirectToRoute = (url) => ({
    type: ActionType.REDIRECT_TO_ROUTE,
    payload: url,
});
