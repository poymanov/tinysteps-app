import axios from "axios";

const BACKEND_URL = process.env.REACT_APP_BACKEND_URL;
const REQUEST_TIMEOUT = process.env.REACT_APP_REQUEST_TIMEOUT;
const ACCESS_TOKEN = localStorage.getItem('accessToken');

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

    if (ACCESS_TOKEN) {
        api.defaults.headers.common = {'Authorization': `Bearer ${ACCESS_TOKEN}`}
    }

    return api;
};

export const oauthPasswordGrantTypeData = {
    grantType: process.env.REACT_APP_BACKEND_OAUTH_GRANT_TYPE_PASSWORD,
    clientId: process.env.REACT_APP_BACKEND_OAUTH_CLIENT_ID,
    clientSecret: process.env.REACT_APP_BACKEND_OAUTH_CLIENT_SECRET
};
