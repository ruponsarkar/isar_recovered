import React, { useEffect, useState, useRef, useCallback, useMemo } from "react";

import JoditEditor from 'jodit-react';

import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';

const AddProductForm = (props) => {

    const editor = useRef(null);

    const config = {
        uploader: {
            insertImageAsBase64URI: true
        },
        height: 420,
        triggerChangeEvent: true,
        readonly: false,
    }

    const Jodit = () => {
        return useMemo(() => (
            <JoditEditor
                ref={editor}
                config={config}
                value={props.details}
                onChange={props.handleDetails}
            />
        ), [])
    }


    return (
        <>
            <ToastContainer />
            <section id="eventForm" className="eventForm bg-light p-4">
                <div className="container">
                    <div className="row">
                        <div className="col-md-12">
                            <div className="php-email-form">

                                <div className="row">
                                    <div className="col-md-12 form-group">
                                        <label>Journal Title</label><br />
                                        <input type="text" className="form-control" name="title" value={props.value.title} onChange={props.data} id="name" />
                                    </div>
                                </div>


                                <div className="row">

                                    <div className="col-md-4 form-group">
                                        <label>Brand</label> <br />
                                        <input type="text" className="form-control" name="brand" value={props.value.brand} onChange={props.data} id="name" />
                                    </div>

                                    <div className="col-md-4 form-group">
                                        <label>Category</label> <br />
                                        <select className="form-control" name="category" onChange={props.data}>
                                            <option>--select--</option>
                                            <option value="Headphone">Headphone</option>
                                            <option value="Charger">Charger</option>
                                        </select>
                                    </div>


                                    <div className="col-md-4 form-group">
                                        <label>Price</label> <br />
                                        <input type="number" className="form-control" name="price" value={props.value.price} onChange={props.data} id="location" />
                                    </div>


                                    <div className="col-md-4 form-group">
                                        <label>Discount Percentage</label> <br />
                                        <input type="number" className="form-control" name="discountPercentage" value={props.value.discountPercentage} onChange={props.data} id="name" />
                                    </div>

                                    <div className="col-md-4 form-group">
                                        <label>Stock</label> <br />
                                        <input type="number" className="form-control" name="stock" value={props.value.stock} onChange={props.data} id="name" />
                                    </div>

                                    <div className="col-md-4 form-group">
                                        <label>Rating</label> <br />
                                        <input type="text" className="form-control" name="rating" value={props.value.rating} onChange={props.data} id="name" />
                                    </div>


                                </div>

                                <div className="col-md-12 form-group">
                                    <label>Cover Image</label> <br />
                                    <input type="file" className="form-control" name="cover" id="imgInp" onChange={props.handleCoverImg} />
                                </div>
                                <div className="col-md-12 form-group">
                                    <label>Preview</label> <br />
                                    <div className="card" style={{ height: "180px", alignItems: "center" }}>
                                        <img id="blah" src={props.coverpreview} alt="Cover Image Preview" style={{ height: "180px", width: "fit-content" }} />

                                    </div>

                                </div>

                                <div className="form-group">
                                    <label>Enter Your Description</label><br />
                                    {Jodit()}
                                </div>





                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </>
    )
}

export default AddProductForm;