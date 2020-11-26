import {flushCurrentUser, loadAlert, loadChangeNameErrors, loadGoal, loadGoals, loadGoalsByTeacher, loadLoginErrors, loadProfile, loadRegistrationErrors, loadTeacher, loadTeachers, loadTeachersByGoal, redirectToRoute} from "./action";
import {ALERTS, APIRoute, AppRoute, HttpCode} from "../constants/const";
import {oauthPasswordGrantTypeData} from "../services/api";

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

export const login = (fields) => (dispatch, _getState, api) => {
    const formData = new FormData();
    formData.append('grant_type', oauthPasswordGrantTypeData.grantType);
    formData.append('client_id', oauthPasswordGrantTypeData.clientId);
    formData.append('client_secret', oauthPasswordGrantTypeData.clientSecret);
    formData.append('username', fields.email);
    formData.append('password', fields.password);

    api.post(APIRoute.TOKEN, formData)
        .then((response) => {
            if (response.status === HttpCode.SUCCESS) {
                const accessToken = response.data.access_token;
                localStorage.setItem('accessToken', accessToken);

                api.get(APIRoute.PROFILE, {headers: {'Authorization': `Bearer ${accessToken}`}}).then((response) => {
                    if (response.status === HttpCode.SUCCESS) {
                        dispatch(loadProfile(response.data))
                    }
                })

                dispatch(redirectToRoute(AppRoute.ROOT));
            }
        })
        .catch((e) => {
            if (e.response.data.error === `invalid_grant`) {
                dispatch(loadLoginErrors({message: `Ошибка авторизации`}));
            } else {
                dispatch(loadLoginErrors(e.response.data));
            }
        })
};

export const fetchProfile = () => (dispatch, _getState, api) => (
    api.get(APIRoute.PROFILE).then((response) => {
        if (response.status === HttpCode.SUCCESS) {
            dispatch(loadProfile(response.data))
        }
    }).catch((e) => dispatch(flushCurrentUser()))
);

export const changeName = (fields) => (dispatch, _getState, api) => (
    api.patch(APIRoute.CHANGE_NAME, fields)
        .then((response) => {
            if (response.status === HttpCode.SUCCESS) {
                dispatch(fetchProfile());
                dispatch(loadAlert(ALERTS.SUCCESS_CHANGE_NAME));
                dispatch(redirectToRoute(AppRoute.PROFILE_COMMON));
            }
        })
        .catch((e) => {dispatch(loadChangeNameErrors(e.response.data))})
);
