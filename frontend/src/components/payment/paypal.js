import Button from "react-bootstrap/Button";
import Col from "react-bootstrap/Col";
import Form from "react-bootstrap/Form";
import Row from "react-bootstrap/Row";
// import "./Checkout.css";
import React, { useEffect, useState } from "react";
import { PayPalScriptProvider, PayPalButtons } from "@paypal/react-paypal-js";
// import { useEffect } from "react";
// import { addOrder } from "../Actions/orderAction";
// import { useDispatch, useSelector } from "react-redux";

function Checkout(props) {
    const [onlinePay, setOnlinePay] = useState(true);

    const [amount, setAmount] = useState();

    const handleAmount = (e) => {
        setAmount(e.target.value);
    }
    useEffect(() => {
        paya()
    }, [amount])

    const paya = () => {
        return (
            <PayPalScriptProvider options={{ "client-id": "AQG2uGbCFA6Pki1lcdQjXgNYLGq8X2AczH5kyh5qE5YwVCnc7JHbBkAOJaJtYv1X9A-EODzy-c2hujwn" }}>
                <PayPalButtons
                    createOrder={(data, actions) => {
                        return actions.order.create({
                            purchase_units: [
                                {
                                    amount: {
                                        value: amount,
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
        <Form className="fourma">
            

            <div className='p-2'>
                <select name="" onChange={handleAmount} className='form-control' id="">
                    <option value="30">$30 USD</option>
                    {/* <option value="">-Select-</option>
                    <option value="20">$20 USD</option>
                    <option value="30">$30 USD</option>
                    <option value="50">$50 USD</option>
                    <option value="75">$75 USD</option>
                    <option value="100">$100 USD</option>
                    <option value="200">$200 USD</option> */}
                </select>
            </div>

            {amount &&
            paya()
            }

        </Form>
    );
}

export default Checkout;