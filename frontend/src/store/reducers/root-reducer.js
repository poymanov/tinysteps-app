import {combineReducers} from "redux";
import {goals} from "./goals/goals";

export const NameSpace = {
    GOALS: `GOALS`,
};

export default combineReducers({
    [NameSpace.GOALS]: goals,
});
