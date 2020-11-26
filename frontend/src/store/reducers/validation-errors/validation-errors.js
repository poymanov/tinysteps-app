import {ActionType} from "../../action";

const initialState = {
    changeName: null,
};

const validationErrors = (state = initialState, action) => {
    switch (action.type) {
        case ActionType.LOAD_CHANGE_NAME_ERRORS:
            return {...state, ...{changeName: action.payload}};
        case ActionType.FLUSH_LOGIN_ERRORS:
            return {...state, ...{changeName: null}};
        default:
            return state;
    }
};

export {validationErrors};
