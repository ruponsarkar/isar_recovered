import { useState, useEffect } from "react";
import {
    PayPalScriptProvider,
    BraintreePayPalButtons,
    usePayPalScriptReducer
} from "@paypal/react-paypal-js";

// This values are the props in the UI
const style = { "label": "paypal", "layout": "vertical" };





export default function App(props) {
    const [clientToken, setClientToken] = useState(null);

    useEffect(() => {
        (async () => {
            const response = await (
                await fetch(
                    "https://react-paypal-js-storybook.fly.dev/api/braintree/generate-client-token",
                    { method: "POST" }
                )
            ).json();
            setClientToken(response?.client_token || response?.clientToken);
        })();
    }, []);



    // Custom component to wrap the PayPalButtons and handle currency changes
    const ButtonWrapper = ({ currency }) => {
        // const amount = 20;
        // usePayPalScriptReducer can be use only inside children of PayPalScriptProviders
        // This is the main reason to wrap the PayPalButtons in a new component
        const [{ options, isPending }, dispatch] = usePayPalScriptReducer();
        console.log("props", props);
        useEffect(() => {
            dispatch({
                type: "resetOptions",
                value: {
                    ...options,
                    currency: currency,
                },
            });
        }, [currency]);

        return (<BraintreePayPalButtons
            style={style}
            disabled={false}
            fundingSource="" // Available values are: ["paypal", "card", "credit", "paylater", "venmo"]
            forceReRender={[style, props.amount]}
            createOrder={function (data, actions) {
                return actions.braintree
                    .createPayment({
                        flow: "checkout",
                        amount: props.amount, // Here change the amount if needed
                        currency: "USD", // Here change the currency if needed
                        intent: "capture",
                        enableShippingAddress: true,
                        shippingAddressEditable: false,
                        shippingAddressOverride: {
                            recipientName: "Scruff McGruff",
                            line1: "1234 Main St.",
                            line2: "Unit 1",
                            city: "Chicago",
                            countryCode: "US",
                            postalCode: "60652",
                            state: "IL",
                            phone: "123.456.7890",
                        },
                    })
                    .then((orderId) => {
                        // Your code here after create the order
                        console.log("orderId", orderId);
                        return orderId;
                    });
            }}
            onApprove={function (data, actions) {
                return actions.braintree
                    .tokenizePayment(data)
                    .then((payload) => {
                        // Your code here after capture the order
                        console.log(JSON.stringify(payload));
                        console.log("actions==>>", actions);
                        console.log("data==>>", data);
                        console.log("ord==>>", payload);
                    });
            }


            }
        />);
    };





    return (
        <>
            {clientToken ? (
                <div style={{ maxWidth: "750px", minHeight: "200px" }}>
                    <PayPalScriptProvider
                        options={{
                            clientId: "AXwaeBE66zpATAxrFK_JcSrjLPwWcZ3QodW2yN3mtNhhSq0yjPYSXSfDAusHdhaWO-yZSOxKkp7p6aFp",
                            // clientId: "AQm2wrJi5l6e_C2pGjpayzCAdl0mnFMVSvJnJuE21pLVisxVwDiXV6pEfV_sgwbOiOAX-ldvUfB9-zk9",
                            components: "buttons",
                            // dataUserIdToken: "your-tokenization-key-here",
                            dataClientToken: clientToken,
                            intent: "capture",
                            vault: false,
                        }}
                    >
                        <ButtonWrapper currency={"USD"} />
                    </PayPalScriptProvider>
                </div>
            ) : (
                <h1>Loading token...</h1>
            )}
        </>
    );
}