import React, { useEffect, useState } from "react";
import Paypal from "./paypal";

import Card from "@mui/material/Card";
import { Divider } from "@mui/material";
import { Button } from "@mui/material";
import { useNavigate, useParams } from "react-router-dom";
import { ToastContainer, toast } from "react-toastify";

import RazorpayCheckout from "../../razorpay";

const Payment = () => {
  const navigation = useNavigate();

  const [amount, setAmount] = useState("");

  const [method, setMethod] = useState("paypal");

  const handleAmount = (e) => {
    setAmount(e.target.value);
  };

  const makePayment = () => {
    if (!amount) {
      toast.error("Please Select amount !", {
        position: "top-right",
        autoClose: 5000,
        hideProgressBar: false,
        closeOnClick: true,
        pauseOnHover: true,
        draggable: true,
        progress: undefined,
        theme: "colored",
      });
      return;
    }
    navigation("/PaymentDetails", { state: amount });
  };
  return (
    <>
      <ToastContainer />
      <Card>
        <div className="row">
          <div className="col-12 p-4">
            <h3> PAYMENTS </h3>
          </div>
        </div>
      </Card>
      <Divider />

      <Card>
        <div className="row">
          <div className="col-12 p-4">
            <div className="row">
              <div className="col-md-6 border p-2">
                <div>
                  <h3>
                    <strong>Foreign Authors</strong>
                  </h3>
                  <p className="p-2">
                    Foreign authors can conveniently pay their processing fees
                    online in USD using methods such as PayPal, Credit Card, or
                    Debit Card.
                  </p>
                </div>

                <div>
                  <strong>Choose Payment method: </strong>

                  <div>
                    <label htmlFor="paypal">Paypal : </label>{" "}
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input
                      type="radio"
                      value={"paypal"}
                      name="method"
                      defaultChecked={method === "paypal" ? true : false}
                      onChange={(e) => setMethod(e.target.value)}
                      id="paypal"
                    />
                  </div>
                  <div>
                    <label htmlFor="razorpay">Razorpay : </label>&nbsp;&nbsp;
                    <input
                      type="radio"
                      value={"razorpay"}
                      name="method"
                      defaultChecked={method === "razorpay" ? true : false}
                      onChange={(e) => setMethod(e.target.value)}
                      id="razorpay"
                    />
                  </div>
                </div>

                {method === "razorpay" && (
                  <div>
                    <RazorpayCheckout currency="USD" />
                  </div>
                )}

                {method === "paypal" && (
                  <div>
                    <div className="p-2">
                      <select
                        name=""
                        onChange={handleAmount}
                        className="form-control"
                        id=""
                      >
                        <option value="">-Select-</option>
                        <option value="20">$20 USD</option>
                        <option value="30">$30 USD</option>
                        <option value="50">$50 USD</option>
                        <option value="75">$75 USD</option>
                        <option value="100">$100 USD</option>
                        <option value="150">$150 USD</option>
                        <option value="200">$200 USD</option>
                      </select>
                    </div>

                    <div className="p-2">
                      <Button
                        variant="contained"
                        color="warning"
                        fullWidth
                        onClick={makePayment}
                      >
                        {" "}
                        Pay Now With Paypal
                      </Button>
                      {/* <Paypal amount={amount} /> */}
                    </div>
                  </div>
                )}
                <div className="p-2">
                  <p>To make a payment, please follow these steps:</p>
                  <div>1. Select the required Payment Option and Fee.</div>
                  <div>
                    2. Click "Pay now" and proceed to the required payment
                    option.
                  </div>
                  <div>3. Check your payable amount and taxes</div>
                  <div>
                    4. Click on PayPal Button and make payment. If you're using
                    a Credit Card or Debit Card click on "Debit or Credit Card"
                  </div>
                  <div>
                    This process will guide you through the payment and checkout
                    procedure for your transaction.
                  </div>
                </div>
              </div>
              <div className="col-md-6 border p-2">
                <div>
                  <h3>
                    <strong>Indian Authors</strong>
                  </h3>
                  <p>Pay online in INR (PayUmoney, UPI, GPay)</p>
                </div>

                <div>
                  <RazorpayCheckout currency="INR" />

                  <div className="p-2">
                    <p>To make a payment, please follow these steps:</p>
                    <div>1. Fill the required Payment Option and Fee.</div>
                    <div>
                      2. Click "Pay now" and proceed to the required payment
                      option.
                    </div>
                    <div>3. Check your payable amount and taxes</div>
                    <div>
                      4. Click on Pay now and make payment. If you're using a
                      Credit Card or Debit Card click on "Debit or Credit Card"
                    </div>
                    <div>
                      This process will guide you through the payment and
                      checkout procedure for your transaction.
                    </div>
                  </div>
                </div>
                {/* <img src="/assets/qr.jpg" alt="" className='img-fluid' /> */}
              </div>
            </div>
          </div>
        </div>
      </Card>
      <Card>
        <div className="row">
          <div className="col-12 p-4">
            <div className="row">
              {/* <div className="col-md-12 border p-4">
                <div>
                  <strong>
                    All publication applications and related procedures
                    originating from Turkey must be conducted through our
                    authorized representative: Ayaktan Gelen Sağlık Eğitim
                    Danışmanlık Hizmetleri Limited Company.
                  </strong>
                </div>
              </div> */}

              {/* <div className="col-md-12 border p-4" >

                                <div>
                                    Account Number: &nbsp;
                                    <strong>
                                        39840337701
                                    </strong>
                                </div>
                                <div>
                                    IFSC Code:	&nbsp;
                                    <strong>
                                        SBIN0008751
                                    </strong>
                                </div>
                                <div>
                                    Name of Account:&nbsp;
                                    <strong>
                                        AMIRUL HUSSAIN MOJUMDER
                                    </strong>
                                </div>
                                <div>
                                    Bank:	&nbsp;
                                    <strong>
                                        State Bank of India
                                    </strong>
                                </div>
                                <div>
                                    Branch: 	&nbsp;
                                    <strong>
                                        Murajhar
                                    </strong>
                                </div>
                                <div>
                                    Branch Code:	&nbsp;
                                    <strong>
                                        08751
                                    </strong>
                                </div>

                            </div> */}
            </div>
          </div>
        </div>
      </Card>
    </>
  );
};

export default Payment;
