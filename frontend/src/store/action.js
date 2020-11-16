export const ActionType = {
    LOAD_GOALS: `LOAD_GOALS`,
    LOAD_TEACHERS: `LOAD_TEACHERS`,
    LOAD_TEACHERS_BY_GOAL: `LOAD_TEACHERS_BY_GOAL`,
    LOAD_GOAL: `LOAD_GOAL`,
    FLUSH_GOAL: `FLUSH_GOAL`,
    REDIRECT_TO_ROUTE: `REDIRECT_TO_ROUTE`,
    FLUSH_TEACHERS_BY_GOAL: `FLUSH_TEACHERS_BY_GOAL`
};

export const loadGoals = (goals) => ({
    type: ActionType.LOAD_GOALS,
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

export const loadTeachersByGoal = (teachers) => ({
    type: ActionType.LOAD_TEACHERS_BY_GOAL,
    payload: teachers,
});

export const flushTeachersByGoal = () => ({
    type: ActionType.FLUSH_TEACHERS_BY_GOAL,
});

export const redirectToRoute = (url) => ({
    type: ActionType.REDIRECT_TO_ROUTE,
    payload: url,
});
