import React from "react";
import {Route, Router as BrowserRouter, Switch} from "react-router-dom";
import Layout from "../layout/layout";
import Main from "../main/main";
import browserHistory from "../../services/browser-history";
import Goals from "../goals/goals";
import Teacher from "../teacher/teacher";
import TeacherRequest from "../teacher-request/teacher-request";

function App() {
    return (
        <BrowserRouter history={browserHistory}>
            <Switch>
                <Layout>
                    <Route exact path="/"><Main/></Route>
                    <Route exact path="/goals/:alias"><Goals/></Route>
                    <Route exact path="/teachers/:alias"><Teacher/></Route>
                    <Route exact path="/request"><TeacherRequest/></Route>
                </Layout>
            </Switch>
        </BrowserRouter>
    );
}

export default App;
