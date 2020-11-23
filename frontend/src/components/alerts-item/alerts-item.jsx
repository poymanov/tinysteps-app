import React from "react";
import AlertsTypes from "../../types/alerts";

function AlertsItem(props) {
    const {alert} = props;

    return (
        <div className={`alert alert-` + alert.type}>{alert.message}</div>
    );
}

AlertsItem.propTypes = {
    alert: AlertsTypes.default
};

export default AlertsItem;
