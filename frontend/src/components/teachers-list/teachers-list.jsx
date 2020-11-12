import React, {Fragment} from "react";
import TeacherItem from "../teacher-item/teacher-item";
import {teachers} from "../../mocks/teachers";

function TeachersList() {
    return (
        <Fragment>
            <div className="row">
                <div className="col-12 col-lg-10 offset-lg-1 m-auto">
                    {teachers.map((teacher) => <TeacherItem key={teacher.id} teacher={teacher}/>)}
                </div>
            </div>
        </Fragment>
    );
}

export default TeachersList;
