
import axios from 'axios';
// import AuthUser from './token';
// 
// const {token} = AuthUser();

const API = axios.create({
    // baseURL : "http://127.0.0.1:8000/api/", //local link
    baseURL : "https://isarpublisher.com/backend/public/api/", //test link
    // baseURL :  process.env.REACT_APP_MAIN_API, //server link
   
    headers :{
        "Content-Type": "application/json",
        // "Authorization" : `Bearer ${token}`
    }
});


export default API;
