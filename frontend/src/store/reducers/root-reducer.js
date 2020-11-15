import {combineReducers} from "redux";
import {goals} from "./goals/goals";
import {teachers} from "./teachers/teachers";

export const NameSpace = {
    GOALS: `GOALS`,
    TEACHERS: `TEACHERS`
};

export default combineReducers({
    [NameSpace.GOALS]: goals,
    [NameSpace.TEACHERS]: teachers
});
