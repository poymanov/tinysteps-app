import {ActionType} from "../../action";

const initialState = {
    validationErrors: null,
};

const login = (state = initialState, action) => {
    switch (action.type) {
        case ActionType.LOAD_LOGIN_ERRORS:
            return {...state, ...{validationErrors: action.payload}};
        case ActionType.FLUSH_LOGIN_ERRORS:
            return {...state, ...{validationErrors: null}};
        default:
            return state;
    }
};

export {login};
