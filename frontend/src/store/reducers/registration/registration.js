import {ActionType} from "../../action";

const initialState = {
    validationErrors: null,
};

const registration = (state = initialState, action) => {
    switch (action.type) {
        case ActionType.LOAD_REGISTRATION_ERRORS:
            return {...state, ...{validationErrors: action.payload}};
        case ActionType.FLUSH_REGISTRATION_ERRORS:
            return {...state, ...{validationErrors: null}};
        default:
            return state;
    }
};

export {registration};
