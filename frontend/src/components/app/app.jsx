import React from "react";
import {Route, Router as BrowserRouter, Switch} from "react-router-dom";
import Layout from "../layout/layout";
import Main from "../main/main";
import browserHistory from "../../services/browser-history";
import Goal from "../goal/goal";
import Teacher from "../teacher/teacher";
import TeacherRequest from "../teacher-request/teacher-request";
import {AppRoute} from "../../constants/const";
import Registration from "../registration/registration";

function App() {
    return (
        <BrowserRouter history={browserHistory}>
            <Switch>
                <Layout>
                    <Route exact path={AppRoute.ROOT}><Main/></Route>
                    <Route exact path={AppRoute.REGISTRATION}><Registration/></Route>
                    <Route exact path={AppRoute.GOALS + `/:alias`} render={({match}) => <Goal alias={match.params.alias}/>} />
                    <Route exact path={AppRoute.TEACHERS + `/:alias`} render={({match}) => <Teacher alias={match.params.alias}/>} />
                    <Route exact path={AppRoute.REQUEST}><TeacherRequest/></Route>
                </Layout>
            </Switch>
        </BrowserRouter>
    );
}

export default App;
