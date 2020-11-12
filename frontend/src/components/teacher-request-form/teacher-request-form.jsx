import React, {PureComponent} from "react";

class TeacherRequestForm extends PureComponent {
    constructor(props) {
        super(props);

        this.state = {
            goal: `travel`,
            time: `5-7`
        };

        this.handleSubmit = this.handleSubmit.bind(this);
        this.handleFieldChange = this.handleFieldChange.bind(this);
    }

    handleSubmit(evt) {
        evt.preventDefault();
    }

    handleFieldChange(evt) {
        const {name, value} = evt.target;
        this.setState({[name]: value});
    }

    render() {
        const {goal, time} = this.state;
        return (
            <form className="card mb-5" onSubmit={this.handleSubmit}>
                <div className="card-body text-center pt-5">
                    <h1 className="h3 card-title mt-4 mb-2">Подбор преподавателя</h1>
                    <p className="px-5">Напишите, чего вам нужно и&nbsp;мы&nbsp;подберем отличных&nbsp;ребят</p>
                </div>
                <hr/>
                <div className="card-body mx-3">
                    <div className="row">
                        <div className="col">
                            <p>Какая цель занятий?</p>
                            <div className="form-check ">
                                <input type="radio" className="form-check-input" name="goal" value="travel" id="goal1" checked={goal === `travel`} onChange={this.handleFieldChange}/>
                                <label className="form-check-label" htmlFor="goal1">Для путешествий</label>
                            </div>
                            <div className="form-check ">
                                <input type="radio" className="form-check-input" name="goal" value="study" id="goal2" checked={goal === `study`} onChange={this.handleFieldChange}/>
                                <label className="form-check-label" htmlFor="goal2">Для школы</label>
                            </div>
                            <div className="form-check ">
                                <input type="radio" className="form-check-input" name="goal" value="work" id="goal3" checked={goal === `work`} onChange={this.handleFieldChange}/>
                                <label className="form-check-label" htmlFor="goal3">Для работы</label>
                            </div>
                            <div className="form-check ">
                                <input type="radio" className="form-check-input" name="goal" value="relocate" id="goal4" checked={goal === `relocate`} onChange={this.handleFieldChange}/>
                                <label className="form-check-label" htmlFor="goal4">Для переезда</label>
                            </div>
                        </div>
                        <div className="col">
                            <p>Сколько времени есть?</p>
                            <div className="form-check">
                                <input type="radio" className="form-check-input" name="time" value="1-2" id="time1" checked={time === `1-2`} onChange={this.handleFieldChange}/>
                                <label className="form-check-label" htmlFor="time1">1-2 часа в&nbsp;неделю</label>
                            </div>
                            <div className="form-check">
                                <input type="radio" className="form-check-input" name="time" value="3-5" id="time2" checked={time === `3-5`} onChange={this.handleFieldChange}/>
                                <label className="form-check-label" htmlFor="time2">3-5 часов в&nbsp;неделю</label>
                            </div>
                            <div className="form-check">
                                <input type="radio" className="form-check-input" name="time" value="5-7" id="time3" checked={time === `5-7`} onChange={this.handleFieldChange}/>
                                <label className="form-check-label" htmlFor="time3">5-7 часов в&nbsp;неделю</label>
                            </div>
                            <div className="form-check">
                                <input type="radio" className="form-check-input" name="time" value="7-10" id="time4" checked={time === `7-10`} onChange={this.handleFieldChange}/>
                                <label className="form-check-label" htmlFor="time4">7-10 часов в&nbsp;неделю</label>
                            </div>
                        </div>
                    </div>
                </div>
                <hr/>
                <div className="card-body mx-3">
                    <label className="mb-1 mt-2">Вас зовут</label>
                    <input className="form-control" type="text" placeholder="Ваше имя" required name="client_name" onChange={this.handleFieldChange}/>

                    <label className="mb-1 mt-2">Ваш телефон</label>
                    <input className="form-control" type="tel" placeholder="9261234567" required name="client_phone" onChange={this.handleFieldChange}/>
                    <button type="submit" className="btn btn-primary mt-4 mb-2">Найдите мне преподавателя</button>
                </div>
            </form>
        );
    }
}

export default TeacherRequestForm;
