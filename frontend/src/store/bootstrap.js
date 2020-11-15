import {createAPI} from "../services/api";
import {applyMiddleware, createStore} from "redux";
import rootReducer from "../store/reducers/root-reducer"
import {composeWithDevTools} from "redux-devtools-extension";
import thunk from "redux-thunk";
import {redirect} from "./middlewares/redirect";

export const initStore = () => {
    const api = createAPI();

    return createStore(
        rootReducer,
        composeWithDevTools(
            applyMiddleware(thunk.withExtraArgument(api)),
            applyMiddleware(redirect)
        )
    );
};
