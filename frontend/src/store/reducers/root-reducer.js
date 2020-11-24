import {combineReducers} from "redux";
import {goals} from "./goals/goals";
import {teachers} from "./teachers/teachers";
import {registration} from "./registration/registration";
import {alerts} from "./alerts/alerts";
import {login} from "./login/login";
import {users} from "./users/users";

export const NameSpace = {
    GOALS: `GOALS`,
    TEACHERS: `TEACHERS`,
    REGISTRATION: `REGISTRATION`,
    LOGIN: `LOGIN`,
    ALERTS: `ALERTS`,
    USERS: `USERS`
};

export default combineReducers({
    [NameSpace.GOALS]: goals,
    [NameSpace.TEACHERS]: teachers,
    [NameSpace.REGISTRATION]: registration,
    [NameSpace.LOGIN]: login,
    [NameSpace.ALERTS]: alerts,
    [NameSpace.USERS]: users
});
