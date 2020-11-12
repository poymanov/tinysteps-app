import React, {Fragment} from "react";
import GoalsList from "../goals-list/goals-list";
import TeachersList from "../teachers-list/teachers-list";
import PromoTeacherRequest from "../promo-teacher-request/promo-teacher-request";

function Main() {
    return (
        <Fragment>
            <h1 className="h1 text-center mx-auto mt-4 py-5">
                <strong>Найдите идеального <br />репетитора английского, <br />занимайтесь онлайн</strong>
            </h1>
            <GoalsList />
            <TeachersList />
            <PromoTeacherRequest />
        </Fragment>
    );
}

export default Main;
