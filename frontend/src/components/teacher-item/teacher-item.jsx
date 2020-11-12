import React from "react";
import TeacherTypes from "../../types/teachers";

function TeacherItem(props) {
    const {teacher} = props;

    return (
        <div className="card mb-4">
            <div className="card-body">
                <div className="row">
                    <div className="col-3"><img src={teacher.photo} className="img-fluid" alt={teacher.name} /></div>
                    <div className="col-9">
                        <p className="float-right">Рейтинг: {teacher.rating} Ставка: {teacher.price} / час</p>
                        <h2 className="h4">{teacher.name}</h2>
                        <p>{teacher.description}</p>
                        <a href="#" className="btn btn-outline-primary btn-sm mr-3 mb-2">Показать информацию и расписание</a>
                    </div>
                </div>
            </div>
        </div>
    );
}

TeacherItem.propTypes = {
    teacher: TeacherTypes.item,
};

export default TeacherItem;
