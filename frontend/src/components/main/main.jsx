import React, {Fragment, PureComponent} from "react";
import {connect} from "react-redux";
import GoalsList from "../goals-list/goals-list";
import TeachersList from "../teachers-list/teachers-list";
import PromoTeacherRequest from "../promo-teacher-request/promo-teacher-request";
import PropTypes from "prop-types";
import GoalTypes from "../../types/goals";
import {goalsSelector, teachersSelector} from "../../store/selectors";
import {fetchGoals, fetchTeachers} from "../../store/api-actions";
import TeacherTypes from "../../types/teachers";
import AlertsList from "../alerts-list/alerts-list";

class Main extends PureComponent {
    componentDidMount() {
        this.props.fetchGoals();
        this.props.fetchTeachers();
    }

    render() {
        const {goals, teachers} = this.props;

        let goalsList = null;
        let teachersList = null;

        if (goals.length > 0) {
            goalsList = <GoalsList goals={goals} />;
        }

        if (teachers.length > 0) {
            teachersList = <TeachersList teachers={teachers} />;
        }

        return (
            <Fragment>
                <AlertsList />
                <h1 className="h1 text-center mx-auto mt-4 py-5">
                    <strong>Найдите идеального <br/>репетитора английского, <br/>занимайтесь онлайн</strong>
                </h1>
                {goalsList}
                <h2 className="h5 text-center mb-5">Свободны прямо сейчас</h2>
                {teachersList}
                <PromoTeacherRequest/>
            </Fragment>
        );
    }
}

Main.propTypes = {
    fetchGoals: PropTypes.func.isRequired,
    fetchTeachers: PropTypes.func.isRequired,
    goals: GoalTypes.list,
    teachers: TeacherTypes.list
};

const mapStateToProps = (state) => ({
    goals: goalsSelector(state),
    teachers: teachersSelector(state)
});

const mapDispatchToProps = (dispatch) => ({
    fetchGoals() {
        dispatch(fetchGoals());
    },
    fetchTeachers () {
        dispatch(fetchTeachers());
    }
});

export default connect(mapStateToProps, mapDispatchToProps)(Main);
