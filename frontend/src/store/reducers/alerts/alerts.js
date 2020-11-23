import {ActionType} from "../../action";

const initialState = {
    alerts: [],
};

const alerts = (state = initialState, action) => {
    switch (action.type) {
        case ActionType.LOAD_ALERT:
            const alert = action.payload;
            const alerts = state.alerts;
            alerts.push({
                type: alert.type,
                message: alert.message
            });
            return {alerts};
        case ActionType.FLUSH_ALERTS:
            return {alerts: []};
        default:
            return state
    }
};

export {alerts}
