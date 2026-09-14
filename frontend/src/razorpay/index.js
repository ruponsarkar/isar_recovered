import React, { useState } from "react";
import axios from "axios";
import { Card } from "@mui/material";

const RazorpayCheckout = ({ currency }) => {
  // const api = 'http://127.0.0.1:8000/api/';
  const api = process.env.REACT_APP_MAIN_API;
  const [amount, setAmount] = useState("");

  const [paymentDetails, setPaymentDetails] = useState({
    amount: "",
    currency: currency || "INR",
    name: "",
    email: "",
    contact: "",
  });

  const handlePayment = async () => {
    // console.log("==> ", paymentDetails);
    // return;
    try {
      const { data } = await axios.post(api + "create-order", {
        amount: paymentDetails.amount,
        currency: paymentDetails.currency,
      });

      const options = {
        key: data.key,
        amount: data.amount,
        currency: paymentDetails.currency,
        name: "ISAR Publisher",
        description: "",
        order_id: data.order_id,
        handler: async (response) => {
          const res = await axios.post(api + "payment-success", {
            response,
            paymentDetails,
          });
          console.log("response1 ", response);
          console.log("res ", res);
          alert("Payment successful!");
        },
        prefill: {
          name: paymentDetails.name,
          email: paymentDetails.email,
          contact: paymentDetails.contact,
        },
        theme: {
          color: "#3399cc",
        },
      };

      const rzp = new window.Razorpay(options);
      rzp.open();
    } catch (err) {
      console.error(err);
    }
  };

  return (
    <div className="p-3">
      <Card>
        <div className=" p-3 col-12">
          <div className="row align-items-center">
            <div className="col-md-12 pt-3">
              <input
                className="form-control"
                placeholder="Your name"
                type="text"
                name="name"
                id=""
                onChange={(e) =>
                  setPaymentDetails({
                    ...paymentDetails,
                    [e.target.name]: e.target.value,
                  })
                }
              />
            </div>
            <div className="col-md-12 pt-3">
              <input
                className="form-control"
                placeholder="email address"
                type="text"
                name="email"
                id=""
                onChange={(e) =>
                  setPaymentDetails({
                    ...paymentDetails,
                    [e.target.name]: e.target.value,
                  })
                }
              />
            </div>
            <div className="col-md-12 pt-3">
              <input
                className="form-control"
                placeholder="Contact no"
                type="text"
                name="contact"
                id=""
                onChange={(e) =>
                  setPaymentDetails({
                    ...paymentDetails,
                    [e.target.name]: e.target.value,
                  })
                }
              />
            </div>

            <div className="col-3 pt-3">
              <select
                className="form-control"
                name="currency"
                value={paymentDetails.currency}
                id=""
                onChange={(e) =>
                  setPaymentDetails({
                    ...paymentDetails,
                    [e.target.name]: e.target.value,
                  })
                }
              >
                <option value="INR">INR</option>
                <option value="USD">USD</option>
              </select>
            </div>

            <div className="col-9 pt-3">
              <input
                type="number"
                name="amount"
                placeholder="Enter amount"
                className="form-control"
                value={paymentDetails.amount}
                // onChange={(e) => setAmount(e.target.value)}
                onChange={(e) =>
                  setPaymentDetails({
                    ...paymentDetails,
                    [e.target.name]: e.target.value,
                  })
                }
              />
            </div>

            <div className="col-12 pt-3">
              <button
                className="btn btn-success btn-sm"
                onClick={handlePayment}
              >
                Pay Now with Razorpay
              </button>
            </div>
          </div>
        </div>
      </Card>
    </div>
  );
};

export default RazorpayCheckout;
