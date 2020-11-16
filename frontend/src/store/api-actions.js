import {loadGoal, loadGoals, loadTeachers, loadTeachersByGoal} from "./action";
import {APIRoute} from "../constants/const";

export const fetchGoals = () => (dispatch, _getState, api) => (
    api.get(APIRoute.GOALS_ACTIVE)
        .then(({data}) => dispatch(loadGoals(data)))
);

export const fetchGoal = (alias) => (dispatch, _getState, api) => (
    api.get(APIRoute.GOAL + `/${alias}`)
        .then(({data}) => dispatch(loadGoal(data)))
);

export const fetchTeachers = () => (dispatch, _getState, api) => (
    api.get(APIRoute.TEACHERS_ACTIVE)
        .then(({data}) => dispatch(loadTeachers(data)))
);

export const fetchTeachersByGoal = (goalId) => (dispatch, _getState, api) => (
    api.get(APIRoute.TEACHERS_ACTIVE_BY_GOAL + `/${goalId}`)
        .then(({data}) => dispatch(loadTeachersByGoal(data)))
);
