import React, {Fragment} from "react";
import PromoTeacherRequest from "../promo-teacher-request/promo-teacher-request";

function Goals() {
    return (
        <Fragment>
            <h1 className="h1 text-center w-50 mx-auto mt-1 py-5 mb-4">
                <strong><br/>Преподаватели<br /> для переезда</strong>
            </h1>
            {/*<TeachersList />*/}
            <PromoTeacherRequest />
        </Fragment>
    );
}

export default Goals;
