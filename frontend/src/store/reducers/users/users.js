import {ActionType} from "../../action";

const initialState = {
    currentUser: null,
};

const users = (state = initialState, action) => {
    switch (action.type) {
        case ActionType.LOAD_PROFILE:
            return {...state, ...{currentUser: action.payload}};
        case ActionType.FLUSH_CURRENT_USER:
            return {...state, ...{currentUser: null}};
        default:
            return state;
    }
};

export {users};
