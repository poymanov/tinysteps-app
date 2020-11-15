import React from 'react';
import ReactDOM from 'react-dom';
import {Provider} from "react-redux";
import App from "./components/app/app";
import {initStore} from "./store/bootstrap";

ReactDOM.render(
    <Provider store={initStore()}>
        <App />
    </Provider>,
  document.getElementById('root')
);
