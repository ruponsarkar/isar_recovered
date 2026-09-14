import { useCallback, useEffect } from "react";
import useRazorpay from "react-razorpay";

export default function Payment() {
    const [Razorpay, isLoaded] = useRazorpay();

    const handlePayment = useCallback(() => {
        // const order = await createOrder(params);

        const options = {
            key: 'rzp_test_hYSCuPaVZYSZou',
            currency: "INR",
            amount: 20 * 100,
            name: "Rupon",
            description: "This is a test Payment",
            image: 'https://pageuptechnologies.com/assets/img/logo.jpg',

            handler: function (response) {
                console.log(response);
                alert(response.razorpay_payment_id)
                alert("Success");
               
            },
            prefill: {
                name: "Rupon",

            },
            // notes: {
            //     address: "Razorpay Corporate Office",
            // },
            // theme: {
            //     color: "#3399cc",
            // },
        };

        const rzpay = new Razorpay(options);
        rzpay.open();
    }, [Razorpay]);

    

    // useEffect(() => {
    //     if (isLoaded) {
    //         handlePayment();
    //     }
    // }, [isLoaded, handlePayment])


















    return (
        <div className="App">
            <button onClick={handlePayment}>Click</button>
        </div>
    );
}