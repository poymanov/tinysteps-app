import React from "react";
import PropTypes from "prop-types";
import {isAuthSelector} from "../../store/selectors";
import {Redirect, Route} from "react-router-dom";
import {AppRoute} from "../../constants/const";
import {connect} from "react-redux";

const GuestRoute = ({isAuth, exact, path, render, component: Component}) => {
    return (
        <Route
            path={path}
            exact={exact}
            render={(routeProps) => {
                if (isAuth) {
                    return <Redirect to={AppRoute.ROOT}/>;
                } else {
                    if (render) {
                        return render(routeProps);
                    } else {
                        return <Component />;
                    }
                }
            }}
        />
    );
}

GuestRoute.propTypes = {
    isAuth: PropTypes.bool.isRequired,
    exact: PropTypes.bool.isRequired,
    path: PropTypes.string.isRequired,
    render: PropTypes.func,
    component: PropTypes.func
};

const mapStateToProps = (state) => ({
    isAuth: isAuthSelector(state),
});

export default connect(mapStateToProps, null)(GuestRoute);
