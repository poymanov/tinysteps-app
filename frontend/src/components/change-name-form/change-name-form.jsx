import React, {PureComponent} from "react";
import {connect} from "react-redux";
import PropTypes from "prop-types";
import {changeNameValidationErrorsSelector, currentUserSelector} from "../../store/selectors";
import ErrorsTypes from "../../types/errors";
import FormErrorAlert from "../form-error-alert/form-error-alert";
import {flushChangeNameErrors} from "../../store/action";
import {changeName} from "../../store/api-actions";
import UserTypes from "../../types/users";

class ChangeNameForm extends PureComponent {
    constructor(props) {
        super(props);

        this.state = {
            fields: {
                last: props.currentUser.name.last,
                first: props.currentUser.name.first,
            },
            validationErrors: null
        };

        this.handleSubmit = this.handleSubmit.bind(this);
        this.handleFieldChange = this.handleFieldChange.bind(this);
    }

    componentDidUpdate(prevProps, prevState, snapshot) {
        const {validationErrors} = this.props;
        if ((validationErrors && !prevProps.validationErrors) ||
            (validationErrors && prevProps.validationErrors &&
                validationErrors.message !== prevProps.validationErrors.message &&
                validationErrors.errors !== prevProps.validationErrors.errors)) {
            this.setState({validationErrors});
        }
    }

    componentWillUnmount() {
        this.props.flushErrors();
    }

    handleSubmit(evt) {
        evt.preventDefault();
        this.props.changeName(this.state.fields);
    }

    handleFieldChange(evt) {
        const {name, value} = evt.target;
        const {validationErrors} = this.state

        this.setState(state => ({
            fields: {
                ...state.fields,
                [name]: value
            }
        }))

        if (validationErrors && validationErrors.errors && name in validationErrors.errors) {
            const errors = validationErrors.errors;
            delete errors[name];

            if (Object.keys(errors).length === 0) {
                this.setState({validationErrors: null});
            } else {
                this.setState({
                    validationErrors: {
                        message: validationErrors.message,
                        errors
                    }
                });
            }
        }
    }

    render() {
        const {validationErrors} = this.state;

        let errorsBlock = null;

        if (validationErrors) {
            errorsBlock = <FormErrorAlert message={validationErrors.message || validationErrors.error.message}/>;
        }

        return (
            <form className="card mb-5" onSubmit={this.handleSubmit}>
                <div className="card-body text-center pt-5">
                    <h1 className="h3 card-title mb-2">Изменить имя</h1>
                </div>
                <hr/>
                <div className="card-body mx-3">
                    {errorsBlock}
                    <div>
                        <label htmlFor="last" className="mb-1 mt-2"><b>Фамилия:</b></label>
                        <input id="last" className={`form-control ` + (validationErrors && validationErrors.errors && validationErrors.errors.email ? `is-invalid` : ``)} type="text" value={this.state.fields.last} required name="last" onChange={this.handleFieldChange}/>
                        {validationErrors && validationErrors.errors && validationErrors.errors.last ? <div className="invalid-feedback">{validationErrors.errors.last}</div> : ``}
                    </div>

                    <div>
                        <label htmlFor="first" className="mb-1 mt-2"><b>Имя:</b></label>
                        <input id="first" className={`form-control ` + (validationErrors && validationErrors.errors && validationErrors.errors.first ? `is-invalid` : ``)} type="text" value={this.state.fields.first} required name="first" onChange={this.handleFieldChange}/>
                        {validationErrors && validationErrors.errors && validationErrors.errors.first ? <div className="invalid-feedback">{validationErrors.errors.first}</div> : ``}
                    </div>

                    <div className="d-flex">
                        <button type="submit" className="flex-grow-1 btn btn-primary mt-4 mb-2">Сохранить</button>
                    </div>
                </div>
            </form>
        );
    }
}

ChangeNameForm.propTypes = {
    currentUser: UserTypes.default,
    changeName: PropTypes.func.isRequired,
    flushErrors: PropTypes.func.isRequired,
    validationErrors: ErrorsTypes.default,
};

const mapStateToProps = (state) => ({
    currentUser: currentUserSelector(state),
    validationErrors: changeNameValidationErrorsSelector(state)
});

const mapDispatchToProps = (dispatch) => ({
    changeName(fields) {
        dispatch(changeName(fields));
    },
    flushErrors() {
        dispatch(flushChangeNameErrors());
    }
});

export default connect(mapStateToProps, mapDispatchToProps)(ChangeNameForm);
