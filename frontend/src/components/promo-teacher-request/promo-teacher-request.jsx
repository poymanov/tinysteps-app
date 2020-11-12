import React, {Fragment} from "react";
import {Link} from "react-router-dom";

function PromoTeacherRequest() {
    return (
        <Fragment>
            <h2 className="text-center mt-5 mb-3">Не нашли своего репетитора?</h2>
            <p className="text-center mb-4">Расскажите, кто вам нужен и мы подберем его сами</p>
            <div className="text-center pb-5">
                <Link to="/" className="btn btn-primary">Заказать подбор</Link>
            </div>
        </Fragment>
    );
}

export default PromoTeacherRequest;
