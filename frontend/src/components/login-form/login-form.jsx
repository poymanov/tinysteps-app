import React, {PureComponent} from "react";
import {connect} from "react-redux";
import PropTypes from "prop-types";
import {loginValidationErrorsSelector} from "../../store/selectors";
import ErrorsTypes from "../../types/errors";
import FormErrorAlert from "../form-error-alert/form-error-alert";
import {flushLoginErrors} from "../../store/action";
import {login} from "../../store/api-actions";

class LoginForm extends PureComponent {
    constructor(props) {
        super(props);

        this.state = {
            fields: {
                email: ``,
                password: ``,
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
        this.props.flushLoginErrors();
    }

    handleSubmit(evt) {
        evt.preventDefault();
        this.props.login(this.state.fields);
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
                    <h1 className="h3 card-title mb-2">Войти</h1>
                </div>
                <hr/>
                <div className="card-body mx-3">
                    {errorsBlock}
                    <div>
                        <label htmlFor="email" className="mb-1 mt-2"><b>Email:</b></label>
                        <input id="email" className={`form-control ` + (validationErrors && validationErrors.errors && validationErrors.errors.email ? `is-invalid` : ``)} type="email" required name="email" onChange={this.handleFieldChange}/>
                        {validationErrors && validationErrors.errors && validationErrors.errors.email ? <div className="invalid-feedback">{validationErrors.errors.email}</div> : ``}
                    </div>

                    <div>
                        <label htmlFor="password" className="mb-1 mt-2"><b>Пароль:</b></label>
                        <input id="password" className={`form-control ` + (validationErrors && validationErrors.errors && validationErrors.errors.password ? `is-invalid` : ``)} type="password" required name="password" onChange={this.handleFieldChange}/>
                        {validationErrors && validationErrors.errors && validationErrors.errors.password ? <div className="invalid-feedback">{validationErrors.errors.password}</div> : ``}
                    </div>

                    <div className="d-flex">
                        <button type="submit" className="flex-grow-1 btn btn-primary mt-4 mb-2">Войти</button>
                    </div>
                </div>
            </form>
        );
    }
}

LoginForm.propTypes = {
    login: PropTypes.func.isRequired,
    flushLoginErrors: PropTypes.func.isRequired,
    validationErrors: ErrorsTypes.default,
};

const mapStateToProps = (state) => ({
    validationErrors: loginValidationErrorsSelector(state)
});

const mapDispatchToProps = (dispatch) => ({
    login(fields) {
        dispatch(login(fields));
    },
    flushLoginErrors() {
        dispatch(flushLoginErrors());
    }
});

export default connect(mapStateToProps, mapDispatchToProps)(LoginForm);
