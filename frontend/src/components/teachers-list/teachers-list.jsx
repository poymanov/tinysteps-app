import React, {Fragment} from "react";
import TeacherItem from "../teacher-item/teacher-item";
import TeacherTypes from "../../types/teachers";

function TeachersList(props) {
    const {teachers} = props;

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

TeachersList.propTypes = {
    teachers: TeacherTypes.list.isRequired
};

export default TeachersList;
