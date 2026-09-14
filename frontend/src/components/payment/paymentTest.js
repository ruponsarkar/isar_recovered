import Form from "react-bootstrap/Form";
import React, { useEffect, useState } from "react";
import { PayPalScriptProvider, PayPalButtons } from "@paypal/react-paypal-js";
import { useNavigate, useParams, useLocation } from "react-router-dom"
import Card from '@mui/material/Card';

function Checkout() {
    const location = useLocation();
    console.log("patment amount", location.state);

    useEffect(() => {
        setAmount(location.state);
        if (location.state == 20) {
            setTax(2)
            setTotal(parseFloat(location.state) + parseFloat(2))
        }
        if (location.state == 30) {
            setTax(3)
            setTotal(parseFloat(location.state) + parseFloat(3))
        }
        if (location.state == 50) {
            setTax(5)
            setTotal(parseFloat(location.state) + parseFloat(5))
        }
        if (location.state == 75) {
            setTax(7.5)
            setTotal(parseFloat(location.state) + parseFloat(7.5))
        }
        if (location.state == 100) {
            setTax(10)
            setTotal(parseFloat(location.state) + parseFloat(10))
        }
        if (location.state == 150) {
            setTax(15)
            setTotal(parseFloat(location.state) + parseFloat(15))
        }
        if (location.state == 200) {
            setTax(20)
            setTotal(parseFloat(location.state) + parseFloat(20))
        }

    }, [location.state])

    const [amount, setAmount] = useState();
    const [tax, setTax] = useState();
    const [total, setTotal] = useState();


    const paya = () => {
        return (
            <PayPalScriptProvider options={{ "client-id": "AQG2uGbCFA6Pki1lcdQjXgNYLGq8X2AczH5kyh5qE5YwVCnc7JHbBkAOJaJtYv1X9A-EODzy-c2hujwn" }}>
                <PayPalButtons
                    createOrder={(data, actions) => {
                        return actions.order.create({
                            purchase_units: [
                                {
                                    amount: {
                                        value: total,
                                    },
                                },
                            ],
                        });
                    }}
                    onApprove={(data, actions) => {
                        return actions.order.capture().then((details) => {
                            const name = details.payer.name.given_name;
                            alert(`Transaction completed by ${name}`);
                        });
                    }}
                />
            </PayPalScriptProvider>
        )
    }

    return (
        <>
            <Card>
                <div className="row d-flex justify-content-center">
                    {/* <Form className=" m-5 p-5"> */}
                    <div className="col-md-3 my-5 p-5" style={{ borderStyle:'double'}}>

                        <div className="row pb-5">



                            <div className="col-6">
                                <strong>  Proccessing Fees:</strong>
                            </div>
                            <div className="col-6 text-end px-3">
                                <strong> ${amount} USD </strong>
                            </div>

                            <div className="col-6">
                                <strong>  Tax:</strong>
                            </div>
                            <div className="col-6 text-end px-3">
                                <strong> ${tax} USD </strong>
                            </div>
                            <hr  className="m-0 p-0"/>
                            <div className="col-6">
                                <strong>  Total:</strong>
                            </div>
                            <div className="col-6 text-end px-3">
                                <strong> ${total} USD </strong>
                            </div>

                        </div>


                        {total &&
                            paya()
                        }
                        {/* </Form> */}
                    </div>
                </div>
            </Card>
        </>
    );
}

export default Checkout;