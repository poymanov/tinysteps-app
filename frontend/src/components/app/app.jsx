import React from "react";
import {Route, Router as BrowserRouter, Switch} from "react-router-dom";
import Layout from "../layout/layout";
import Main from "../main/main";
import browserHistory from "../../services/browser-history";
import Goals from "../goals/goals";
import Teacher from "../teacher/teacher";
import TeacherRequest from "../teacher-request/teacher-request";
import {AppRoute} from "../../constants/const";

function App() {
    return (
        <BrowserRouter history={browserHistory}>
            <Switch>
                <Layout>
                    <Route exact path={AppRoute.ROOT}><Main/></Route>
                    <Route exact path={AppRoute.GOALS + `/:alias`}><Goals/></Route>
                    <Route exact path={AppRoute.TEACHERS + `/:alias`}><Teacher/></Route>
                    <Route exact path={AppRoute.REQUEST}><TeacherRequest/></Route>
                </Layout>
            </Switch>
        </BrowserRouter>
    );
}

export default App;
