export const ActionType = {
    LOAD_GOALS: `LOAD_GOALS`,
    LOAD_GOALS_BY_TEACHER: `LOAD_GOALS_BY_TEACHER`,
    LOAD_TEACHERS: `LOAD_TEACHERS`,
    LOAD_TEACHERS_BY_GOAL: `LOAD_TEACHERS_BY_GOAL`,
    LOAD_TEACHER: `LOAD_TEACHER`,
    LOAD_GOAL: `LOAD_GOAL`,
    FLUSH_GOAL: `FLUSH_GOAL`,
    FLUSH_GOALS_BY_TEACHER: `FLUSH_GOALS_BY_TEACHER`,
    FLUSH_TEACHER: `FLUSH_TEACHER`,
    REDIRECT_TO_ROUTE: `REDIRECT_TO_ROUTE`,
    FLUSH_TEACHERS_BY_GOAL: `FLUSH_TEACHERS_BY_GOAL`
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

export const flushTeachersByGoal = () => ({
    type: ActionType.FLUSH_TEACHERS_BY_GOAL,
});

export const flushGoalsByTeacher = () => ({
    type: ActionType.FLUSH_GOALS_BY_TEACHER,
});

export const flushTeacher = () => ({
    type: ActionType.FLUSH_TEACHER,
});

export const redirectToRoute = (url) => ({
    type: ActionType.REDIRECT_TO_ROUTE,
    payload: url,
});
