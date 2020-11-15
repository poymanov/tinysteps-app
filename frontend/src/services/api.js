import axios from "axios";

const BACKEND_URL = process.env.REACT_APP_BACKEND_URL;
const REQUEST_TIMEOUT = process.env.REACT_APP_REQUEST_TIMEOUT;

export const createAPI = () => {
    const api = axios.create({
        baseURL: BACKEND_URL,
        timeout: REQUEST_TIMEOUT,
    });

    const onSuccess = (response) => response;

    const onFail = (err) => {
        throw err;
    };

    api.interceptors.response.use(onSuccess, onFail);

    return api;
};
