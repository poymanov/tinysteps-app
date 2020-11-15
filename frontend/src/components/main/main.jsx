import React, {Fragment, PureComponent} from "react";
import {connect} from "react-redux";
import GoalsList from "../goals-list/goals-list";
import TeachersList from "../teachers-list/teachers-list";
import PromoTeacherRequest from "../promo-teacher-request/promo-teacher-request";
import PropTypes from "prop-types";
import GoalTypes from "../../types/goals";
import {goalsSelector} from "../../store/selectors";
import {fetchGoals} from "../../store/api-actions";

class Main extends PureComponent {
    componentDidMount() {
        this.props.fetchGoals();
    }

    render() {
        const {goals} = this.props;

        let goalsList = null;

        if (goals.length > 0) {
            goalsList = <GoalsList goals={goals} />;
        }

        return (
            <Fragment>
                <h1 className="h1 text-center mx-auto mt-4 py-5">
                    <strong>Найдите идеального <br/>репетитора английского, <br/>занимайтесь онлайн</strong>
                </h1>
                {goalsList}
                <h2 className="h5 text-center mb-5">Свободны прямо сейчас</h2>
                <TeachersList/>
                <PromoTeacherRequest/>
            </Fragment>
        );
    }
}

Main.propTypes = {
    fetchGoals: PropTypes.func.isRequired,
    goals: GoalTypes.list
};

const mapStateToProps = (state) => ({
    goals: goalsSelector(state),
});

const mapDispatchToProps = (dispatch) => ({
    fetchGoals() {
        dispatch(fetchGoals());
    },
});

export default connect(mapStateToProps, mapDispatchToProps)(Main);
