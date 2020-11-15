import {NameSpace} from "./reducers/root-reducer";

export const goalsSelector = (state) => {
    return state[NameSpace.GOALS].goals;
};

export const teachersSelector = (state) => {
    return state[NameSpace.TEACHERS].teachers;
};
