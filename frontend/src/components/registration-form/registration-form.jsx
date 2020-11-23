import React, {PureComponent} from "react";
import {connect} from "react-redux";
import {registration} from "../../store/api-actions";
import PropTypes from "prop-types";
import {registrationValidationErrorsSelector} from "../../store/selectors";
import ErrorsTypes from "../../types/errors";
import FormErrorAlert from "../form-error-alert/form-error-alert";
import {flushRegistrationErrors} from "../../store/action";

class RegistrationForm extends PureComponent {
    constructor(props) {
        super(props);

        this.state = {
            fields: {
                first_name: ``,
                last_name: ``,
                email: ``,
                password: ``,
                password_confirmation: ``
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
        this.props.flushRegistrationErrors();
    }

    handleSubmit(evt) {
        evt.preventDefault();
        this.props.registration(this.state.fields);
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
                    <h1 className="h3 card-title mb-2">Регистрация</h1>
                </div>
                <hr/>
                <div className="card-body mx-3">
                    {errorsBlock}

                    <div className="custom-control pl-0">
                        <label htmlFor="first_name" className="mb-1 mt-2"><b>Имя:</b></label>
                        <input id="first_name" className={`form-control ` + (validationErrors && validationErrors.errors.first_name ? `is-invalid` : ``)} type="text" required name="first_name" onChange={this.handleFieldChange}/>
                        {validationErrors && validationErrors.errors.first_name ? <div className="invalid-feedback">{validationErrors.errors.first_name}</div> : ``}
                    </div>

                    <div>
                        <label htmlFor="last_name" className="mb-1 mt-2"><b>Фамилия:</b></label>
                        <input id="last_name" className={`form-control ` + (validationErrors && validationErrors.errors.last_name ? `is-invalid` : ``)} type="text" required name="last_name" onChange={this.handleFieldChange}/>
                        {validationErrors && validationErrors.errors.last_name ? <div className="invalid-feedback">{validationErrors.errors.last_name}</div> : ``}
                    </div>

                    <div>
                        <label htmlFor="email" className="mb-1 mt-2"><b>Email:</b></label>
                        <input id="email" className={`form-control ` + (validationErrors && validationErrors.errors.email ? `is-invalid` : ``)} type="email" required name="email" onChange={this.handleFieldChange}/>
                        {validationErrors && validationErrors.errors.email ? <div className="invalid-feedback">{validationErrors.errors.email}</div> : ``}
                    </div>

                    <div>
                        <label htmlFor="password" className="mb-1 mt-2"><b>Пароль:</b></label>
                        <input id="password" className={`form-control ` + (validationErrors && validationErrors.errors.password ? `is-invalid` : ``)} type="password" required name="password" onChange={this.handleFieldChange}/>
                        {validationErrors && validationErrors.errors.password ? <div className="invalid-feedback">{validationErrors.errors.password}</div> : ``}
                    </div>

                    <div>
                        <label htmlFor="password_confirmation" className="mb-1 mt-2"><b>Подтверждение пароля</b></label>
                        <input id="password_confirmation" className={`form-control ` + (validationErrors && validationErrors.errors && validationErrors.errors.password_confirmation ? `is-invalid` : ``)} type="password" required name="password_confirmation" onChange={this.handleFieldChange}/>
                        {validationErrors && validationErrors.errors.password_confirmation ? <div className="invalid-feedback">{validationErrors.errors.password_confirmation}</div> : ``}
                    </div>

                    <div className="d-flex">
                        <button type="submit" className="flex-grow-1 btn btn-primary mt-4 mb-2">Зарегистрироваться</button>
                    </div>
                </div>
            </form>
        );
    }
}

RegistrationForm.propTypes = {
    registration: PropTypes.func.isRequired,
    flushRegistrationErrors: PropTypes.func.isRequired,
    validationErrors: ErrorsTypes.default,
};

const mapStateToProps = (state) => ({
    validationErrors: registrationValidationErrorsSelector(state)
});

const mapDispatchToProps = (dispatch) => ({
    registration(fields) {
        dispatch(registration(fields));
    },
    flushRegistrationErrors() {
        dispatch(flushRegistrationErrors());
    }
});

export default connect(mapStateToProps, mapDispatchToProps)(RegistrationForm);
