import React, { useState } from 'react'
import api from '../../API/api';
import AuthUser from '../../API/token';

import Stack from '@mui/material/Stack';
import CircularProgress from '@mui/material/CircularProgress';
import Alert from '@mui/material/Alert';
import Card from '@mui/material/Card';

const Login = () => {
    const { setToken } = AuthUser();

    const [email, setEmail] = useState();
    const [password, setPassword] = useState();

    const [loading, setLoading] = useState(false);
    const [error, setError] = useState(false);

    const submit = () => {
        setLoading(true);

        api.login(email, password)
            .then((res) => {
                console.log("Login success==>", res);
                setToken(res.data.user, res.data.access_token)
                setLoading(false);
            })
            .catch((err) => {
                console.log("login error==>", err.response);
                setLoading(false);
                setError(true);
            })
    }

    const Loading = () => {
        return (
            <Stack sx={{ color: 'grey.500' }} style={{ 'alignItems': 'center' }}>
                <CircularProgress color="inherit" />
            </Stack>
        );
    }

    return (
        <>
        <Card>
            <section className='bg-light'>
                <div className="container">
                    <div>
                        <div className="row">
                            <div className="col-lg-7">
                                <div className="card1 pb-5">
                                    <div className="row px-3 justify-content-center mt-5 mb-5">
                                        <img src="/assets/login.png" className="img-fluid" />
                                    </div>
                                </div>
                            </div>

                            <div className="col-lg-5">
                                <div className='box-form card p-4 mt-4'>
                                    <div className="right">
                                        <div className="text-center py-3 d-flex">
                                            {/* <label className="mb-0 mr-4 mt-2 px-3">Sign in with  </label>

                                            <a className="facebook text-center mx-1" href='#'>
                                                <i className="bx bxl-facebook"></i>
                                            </a>
                                            <a className="twitter text-center mx-1" href='#'>
                                                <i className="bx bxl-instagram"></i>
                                            </a>
                                            <a className="linkedin text-center mx-1" href='#'>
                                                <i className="bx bxl-linkedin"></i>
                                            </a> */}
                                        </div>
                                        <div className="row px-3 mb-4">
                                            <div className="line"></div>
                                            <small className="or text-center mx-2">Admin Login</small>
                                            <div className="line"></div>
                                        </div>

                                        {/* <form> */}

                                        <div className="inputs">
                                            <label>Email Address</label>
                                            <input type="email" value={email} className='form-control' onChange={e => setEmail(e.target.value)} placeholder="enter a valid email address" />
                                            <br /><br />
                                            <label>Password</label>
                                            <input type="password" value={password} className='form-control' onChange={e => setPassword(e.target.value)} placeholder="enter password" />
                                        </div>

                                        <br /><br />

                                        <div className="remember-me--forget-password d-flex">
                                            <label>
                                                <input type="checkbox" name="item" checked />
                                                <span className="text-checkbox">Remember me</span>
                                            </label>
                                            <p><a href='#'>forget password?</a></p>
                                        </div>

                                        <br />
                                        {error && <Alert severity="error">Something went wrong !</Alert>
                                        }


                                        {loading ? <Loading /> :
                                            <button className='btn btn-success' onClick={submit}>Login</button>
                                        }


                                        <br /><br />

                                        <div className="row mb-4 px-3">
                                            <label className="font-weight-bold">Don't have an account ?
                                                &nbsp;<a className="text-danger" href='/register'>Register</a></label>
                                        </div>
                                        {/* </form> */}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            </Card>
        </>
    )
}


export default Login;