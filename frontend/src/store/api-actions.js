import {loadGoals, loadTeachers} from "./action";
import {APIRoute} from "../constants/const";

export const fetchGoals = () => (dispatch, _getState, api) => (
    api.get(APIRoute.GOALS_ACTIVE)
        .then(({data}) => dispatch(loadGoals(data)))
);

export const fetchTeachers = () => (dispatch, _getState, api) => (
    api.get(APIRoute.TEACHERS_ACTIVE)
        .then(({data}) => dispatch(loadTeachers(data)))
);
