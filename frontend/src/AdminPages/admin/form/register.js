import React, { useState, useEffect } from 'react'
import api from '../../../API/api';


const Register = () => {

    const [formData, setForm] = useState({
        user: "",
        email: "",
        password: ""
    }
    );

    const handleInputChange = (e) => {
        setForm({
            ...formData,
            [e.target.name]: e.target.value
        })
    }

    const handleSubmit = () => {

        api.register(formData).then((res) => {
            console.log("Register=>", res);
        })
            .catch((err) => {
                console.log(err);
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

                        <h2>Register</h2>
                        <p>Don't have an account? <a href="#">Creat Your Account</a> it takes less than a minute</p>

                        <div className="inputs">
                            <input type="text" name="user" value={formData.user} onChange={handleInputChange} placeholder="user name" />
                            <br />
                            <input type="email" name="email" value={formData.email} onChange={handleInputChange} placeholder="Email Id" />
                            <br />
                            <input type="password" name="password" value={formData.password} onChange={handleInputChange} placeholder="password" />
                        </div>

                        <br /><br />

                        <div className="remember-me--forget-password">
                            <label>
                                <input type="checkbox" name="item" />
                                <span className="text-checkbox">Remember me</span>
                            </label>
                            <p>forget password?</p>
                        </div>

                        <br />
                        <button onClick={handleSubmit}>Register</button>

                    </div>

                </div>

            </section>
        </>
    )
}


export default Register;