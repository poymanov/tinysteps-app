import React, {PureComponent} from "react";
import {connect} from "react-redux";
import {Route, Router as BrowserRouter, Switch} from "react-router-dom";
import PropTypes from "prop-types";
import Layout from "../layout/layout";
import Main from "../main/main";
import browserHistory from "../../services/browser-history";
import Goal from "../goal/goal";
import Teacher from "../teacher/teacher";
import TeacherRequest from "../teacher-request/teacher-request";
import {AppRoute} from "../../constants/const";
import Registration from "../registration/registration";
import ConfirmProfile from "../confirm-profile/confirm-profile";
import Login from "../login/login";
import {fetchProfile} from "../../store/api-actions";
import GuestRoute from "../guest-route/guest-route";

class App extends PureComponent {
    componentDidMount() {
        this.props.fetchProfile();
    }

    render() {
        return (
            <BrowserRouter history={browserHistory}>
                <Switch>
                    <Layout>
                        <Route exact path={AppRoute.ROOT}><Main/></Route>
                        <GuestRoute path={AppRoute.REGISTRATION} exact component={Registration} />
                        <Route exact path={AppRoute.GOALS + `/:alias`} render={({match}) => <Goal alias={match.params.alias}/>} />
                        <Route exact path={AppRoute.TEACHERS + `/:alias`} render={({match}) => <Teacher alias={match.params.alias}/>} />
                        <Route exact path={AppRoute.REQUEST}><TeacherRequest/></Route>
                        <GuestRoute exact path={AppRoute.CONFIRM_PROFILE} render={({location}) => {
                            const params = new URLSearchParams(location.search);
                            const token = params.get('token');

                            return <ConfirmProfile token={token} />;
                        }} />
                        <GuestRoute path={AppRoute.LOGIN} exact component={Login} />
                    </Layout>
                </Switch>
            </BrowserRouter>
        );
    }
}

App.propTypes = {
    fetchProfile: PropTypes.func.isRequired
};

const mapDispatchToProps = {
    fetchProfile
};

export default connect(null, mapDispatchToProps)(App);
