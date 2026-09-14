import React, {useState} from 'react'
import api from '../../../API/api';
import AuthUser from '../../../API/token';

const Login = () => {
    const {setToken} = AuthUser();

    const [email, setEmail] = useState();
    const [password, setPassword] = useState();

    const submit = ()=>{

        api.login(email, password)
        .then((res)=>{
            console.log("Login success==>",res);
            setToken(res.data.user,res.data.access_token)
        })
        .catch((err)=>{
            console.log("login error==>",err);
        })
    }



    return (
        <>
            <section>
                <div className="box-form">
                    <div className="left">
                        <div className="overlay">
                            <h1>Agora.</h1>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                Curabitur et est sed felis aliquet sollicitudin</p>
                            <span>
                                <p>login with social media</p>
                                <a href="#"><i className="fa fa-facebook" aria-hidden="true"></i></a>
                                <a href="#"><i className="fa fa-twitter" aria-hidden="true"></i> Login with Twitter</a>
                            </span>
                        </div>
                    </div>


                    <div className="right">
                        {/* <form> */}
                            <h2>Login</h2>
                            <p>Don't have an account? <a href="#">Creat Your Account</a> it takes less than a minute</p>

                            <div className="inputs">
                                <input type="email" value={email} onChange={e=> setEmail(e.target.value)} placeholder="email" />
                                <br />
                                <input type="password" value={password} onChange={e=> setPassword(e.target.value)} placeholder="password" />
                            </div>

                            <br /><br />

                            <div className="remember-me--forget-password">
                                <label>
                                    <input type="checkbox" name="item" checked />
                                    <span className="text-checkbox">Remember me</span>
                                </label>
                                <p>forget password?</p>
                            </div>

                            <br />
                            <button onClick={submit}>Login</button>
                        {/* </form> */}
                    </div>

                </div>

            </section>
        </>
    )
}


export default Login;