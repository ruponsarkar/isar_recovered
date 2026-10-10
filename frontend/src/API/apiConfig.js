import axios from 'axios';

const API = axios.create({
    baseURL: process.env.REACT_APP_MAIN_API || 'http://127.0.0.1:8000/api/',
    headers: { 'Content-Type': 'application/json' }
});

API.interceptors.request.use(config => {
    try {
        const token = JSON.parse(sessionStorage.getItem('token'));
        if (typeof token === 'string' && token) config.headers.Authorization = `Bearer ${token}`;
    } catch (_) {
        // Invalid or unavailable session storage should not prevent a request.
    }
    return config;
});

export default API;
