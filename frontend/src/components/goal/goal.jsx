import React, {Fragment, PureComponent} from "react";
import PromoTeacherRequest from "../promo-teacher-request/promo-teacher-request";
import PropTypes from "prop-types";
import GoalTypes from "../../types/goals";
import TeacherTypes from "../../types/teachers";
import {goalSelector, teachersByGoalSelector} from "../../store/selectors";
import {fetchGoal, fetchTeachersByGoal} from "../../store/api-actions";
import {connect} from "react-redux";
import TeachersList from "../teachers-list/teachers-list";
import {flushGoal, flushTeachersByGoal} from "../../store/action";

class Goal extends PureComponent {
    componentDidMount() {
        this.props.fetchGoal(this.props.alias);
    }

    componentDidUpdate(prevProps, prevState, snapshot) {
        const {goal} = this.props;
        if ((goal && !prevProps.goal) || (goal && prevProps.goal && goal.id !== prevProps.goal.id)) {
            this.props.fetchTeachersByGoal(goal.id);
        }
    }

    componentWillUnmount() {
        this.props.flushTeachersByGoal();
        this.props.flushGoal();
    }

    render() {
        const {goal, teachers} = this.props;

        let goalTitle = null;

        if (goal) {
            goalTitle = <h1 className="h1 text-center w-50 mx-auto mt-1 py-5 mb-4">
                <strong>{goal.icon}<br/>Преподаватели<br/>{goal.name.toLowerCase()}</strong>
            </h1>;
        }

        let teachersList = null;

        if (teachers.length > 0) {
            teachersList = <TeachersList teachers={teachers}/>;
        }

        return (
            <Fragment>
                {goalTitle}
                {teachersList}
                <PromoTeacherRequest/>
            </Fragment>
        );
    }
}

Goal.propTypes = {
    fetchGoal: PropTypes.func.isRequired,
    fetchTeachersByGoal: PropTypes.func.isRequired,
    flushTeachersByGoal: PropTypes.func.isRequired,
    flushGoal: PropTypes.func.isRequired,
    goal: GoalTypes.item,
    teachers: TeacherTypes.list,
    alias: PropTypes.string.isRequired
};

const mapStateToProps = (state) => ({
    goal: goalSelector(state),
    teachers: teachersByGoalSelector(state)
});

const mapDispatchToProps = (dispatch) => ({
    fetchGoal(alias) {
        dispatch(fetchGoal(alias));
    },
    fetchTeachersByGoal(goalId) {
        dispatch(fetchTeachersByGoal(goalId));
    },
    flushTeachersByGoal() {
        dispatch(flushTeachersByGoal());
    },
    flushGoal() {
        dispatch(flushGoal());
    }
});

export default connect(mapStateToProps, mapDispatchToProps)(Goal);
