import {combineReducers} from "redux";
import {goals} from "./goals/goals";
import {teachers} from "./teachers/teachers";
import {registration} from "./registration/registration";
import {alerts} from "./alerts/alerts";

export const NameSpace = {
    GOALS: `GOALS`,
    TEACHERS: `TEACHERS`,
    REGISTRATION: `REGISTRATION`,
    ALERTS: `ALERTS`
};

export default combineReducers({
    [NameSpace.GOALS]: goals,
    [NameSpace.TEACHERS]: teachers,
    [NameSpace.REGISTRATION]: registration,
    [NameSpace.ALERTS]: alerts
});
