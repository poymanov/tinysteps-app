import React, {PureComponent} from "react";
import {connect} from "react-redux";
import PropTypes from "prop-types";
import {fetchGoalsByTeacher, fetchTeacher} from "../../store/api-actions";
import {goalsByTeacherSelector, teacherSelector} from "../../store/selectors";
import TeacherTypes from "../../types/teachers";
import GoalTypes from "../../types/goals";
import TeacherGoalsList from "../teacher-goals-list/teacher-goals-list";
import {flushGoalsByTeacher, flushTeacher} from "../../store/action";

class Teacher extends PureComponent {
    componentDidMount() {
        this.props.fetchTeacher(this.props.alias);
    }
    
    componentDidUpdate(prevProps, prevState, snapshot) {
        const {teacher} = this.props;
        if ((teacher && !prevProps.teacher) || (teacher && prevProps.teacher && teacher.id !== prevProps.teacher.id)) {
            this.props.fetchGoalsByTeacher(teacher.id);
        }
    }

    componentWillUnmount() {
        this.props.flushGoalsByTeacher();
        this.props.flushTeacher();
    }

    render() {
        const {teacher, goals} = this.props;

        if (!teacher) {
            return null;
        }

        let teacherGoalsList = null;

        if (goals && goals.length > 0) {
            teacherGoalsList = <TeacherGoalsList goals={goals} />
        }

        return (
            <div className="card my-4 mx-auto">
                <div className="card-body m-2 m-md-4">
                    <div className="row">
                        <div className="col-5">
                            <img src="https://via.placeholder.com/300.png" className="img-fluid" alt={teacher.name.full} />
                        </div>
                        <div className="col-7">
                            <h1 className="h2">{teacher.name.full}</h1>
                            {teacherGoalsList}
                            <p>Рейтинг: {teacher.rating} Ставка: {teacher.price} / час</p>
                            <p>{teacher.description}</p>
                        </div>
                    </div>
                </div>
            </div>
        );
    };
}

Teacher.propTypes = {
    fetchTeacher: PropTypes.func.isRequired,
    fetchGoalsByTeacher: PropTypes.func.isRequired,
    flushGoalsByTeacher: PropTypes.func.isRequired,
    flushTeacher: PropTypes.func.isRequired,
    alias: PropTypes.string.isRequired,
    teacher: TeacherTypes.item,
    goals: GoalTypes.list
};

const mapStateToProps = (state) => ({
    teacher: teacherSelector(state),
    goals: goalsByTeacherSelector(state)
});

const mapDispatchToProps = (dispatch) => ({
    fetchTeacher(alias) {
        dispatch(fetchTeacher(alias))
    },
    fetchGoalsByTeacher(id) {
        dispatch(fetchGoalsByTeacher(id));
    },
    flushGoalsByTeacher() {
        dispatch(flushGoalsByTeacher())
    },
    flushTeacher() {
        dispatch(flushTeacher())
    }
});

export default connect(mapStateToProps, mapDispatchToProps)(Teacher);
