import {NameSpace} from "./reducers/root-reducer";

export const goalsSelector = (state) => {
    return state[NameSpace.GOALS].goals;
};
