import React, {Fragment, PureComponent} from "react";
import PropTypes from "prop-types";
import {alertsSelector} from "../../store/selectors";
import AlertsTypes from "../../types/alerts";
import {connect} from "react-redux";
import AlertsItem from "../alerts-item/alerts-item";
import {flushAlerts} from "../../store/action";

class AlertsList extends PureComponent {
    componentWillUnmount() {
        this.props.flushAlerts();
    }

    render() {
        const {alerts} = this.props;

        if (alerts.length === 0) {
            return null;
        }

        return (
            <Fragment>
                {alerts.map((alert) => <AlertsItem key={alert.type + alert.message} alert={alert} />)}
            </Fragment>
        );
    }
}

AlertsList.propTypes = {
    alerts: AlertsTypes.defaultList,
    flushAlerts: PropTypes.func.isRequired
};

const mapStateToProps = (state) => ({
    alerts: alertsSelector(state)
});

const mapDispatchToProps = {
    flushAlerts
};

export default connect(mapStateToProps, mapDispatchToProps)(AlertsList);
