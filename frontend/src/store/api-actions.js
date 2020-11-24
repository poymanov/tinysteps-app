import {loadAlert, loadGoal, loadGoals, loadGoalsByTeacher, loadRegistrationErrors, loadTeacher, loadTeachers, loadTeachersByGoal, redirectToRoute} from "./action";
import {ALERTS, APIRoute, AppRoute, HttpCode} from "../constants/const";

export const fetchGoals = () => (dispatch, _getState, api) => (
    api.get(APIRoute.GOALS_ACTIVE)
        .then(({data}) => dispatch(loadGoals(data)))
);

export const fetchGoalsByTeacher = (id) => (dispatch, _getState, api) => (
    api.get(APIRoute.GOALS_BY_TEACHER + `/${id}`)
        .then(({data}) => dispatch(loadGoalsByTeacher(data)))
);

export const fetchGoal = (alias) => (dispatch, _getState, api) => (
    api.get(APIRoute.GOAL + `/${alias}`)
        .then(({data}) => dispatch(loadGoal(data)))
);

export const fetchTeachers = () => (dispatch, _getState, api) => (
    api.get(APIRoute.TEACHERS_ACTIVE)
        .then(({data}) => dispatch(loadTeachers(data)))
);

export const fetchTeacher = (alias) => (dispatch, _getState, api) => (
    api.get(APIRoute.TEACHER + `/${alias}`)
        .then(({data}) => dispatch(loadTeacher(data)))
);

export const fetchTeachersByGoal = (goalId) => (dispatch, _getState, api) => (
    api.get(APIRoute.TEACHERS_ACTIVE_BY_GOAL + `/${goalId}`)
        .then(({data}) => dispatch(loadTeachersByGoal(data)))
);

export const registration = (fields) => (dispatch, _getState, api) => (
    api.post(APIRoute.REGISTRATION, fields)
        .then((response) => {
            if (response.status === HttpCode.CREATED) {
                dispatch(loadAlert(ALERTS.SUCCESS_REGISTRATION));
                dispatch(redirectToRoute(AppRoute.ROOT));
            }
        })
        .catch((e) => {dispatch(loadRegistrationErrors(e.response.data))})
);

export const confirmProfile = (token) => (dispatch, _getState, api) => (
    api.get(APIRoute.REGISTRATION + `/${token}`)
        .then((response) => {
            if (response.status === HttpCode.SUCCESS) {
                dispatch(loadAlert(ALERTS.SUCCESS_CONFIRM_PROFILE));
                dispatch(redirectToRoute(AppRoute.ROOT));
            }
        })
        .catch((e) => {
            dispatch(loadAlert({type: `danger`, message: e.response.data.message}));
            dispatch(redirectToRoute(AppRoute.ROOT));
        })
);
