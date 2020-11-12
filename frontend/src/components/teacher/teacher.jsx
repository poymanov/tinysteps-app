import React from "react";
import {Link} from "react-router-dom";

function Teacher() {
    return (
        <div className="card my-4 mx-auto">
            <div className="card-body m-2 m-md-4">
                <div className="row">
                    <div className="col-5">
                        <img src="https://via.placeholder.com/300.png" className="img-fluid" alt="" />
                    </div>
                    <div className="col-7">
                        <h1 className="h2">Morris Simmmons</h1>
                        <p>
                            <Link to="/"><span className="badge badge-secondary mr-2">Для путешествий</span></Link>
                            <Link to="/"><span className="badge badge-secondary mr-2">Для переезда</span></Link>
                            <Link to="/"><span className="badge badge-secondary mr-2">Для учёбы</span></Link>
                        </p>
                        <p>Рейтинг: 4.2 Ставка: 900 / час</p>
                        <p>Репетитор американского английского языка. Структурированная система обучения. Всем привет! Я предпочитаю называть себя «тренером» английского языка. Мои занятия похожи на тренировки</p>
                    </div>
                </div>
            </div>
        </div>
    );
}

export default Teacher;
