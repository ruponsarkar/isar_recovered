import React, { useEffect, useState } from "react";
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';

const AddEventForm = (props) => {




    return (
        <>
            <ToastContainer />
            <section id="eventForm" className="eventForm">
                <div className="container">
                    <div className="row">
                        <div className="col-md-12">
                            <div className="php-email-form">

                                <div className="row">
                                    <div className="col-md-12 form-group">
                                        <label>Event Name</label><br />
                                        <input type="text" className="form-control" name="event" value={props.value.event} onChange={props.data} id="name" placeholder="Name" />
                                    </div>
                                </div>


                                <div className="row">

                                    <div className="col-md-4 form-group">
                                        <label>Select a date</label> <br />
                                        <input type="date" id="date1" name="from" value={props.value.from} onChange={props.data} placeholder="To" />
                                    </div>

                                    <div className="col-md-4 form-group">
                                        <label>Select a time</label> <br />
                                        <input type="time" id="appt" name="time" value={props.value.time} onChange={props.data} />
                                    </div>


                                    <div className="col-md-4 form-group">
                                        <label>Enter Your Location</label> <br />
                                        <input type="text" className="form-control" name="location" value={props.value.location} onChange={props.data} id="location" placeholder="Location" />
                                    </div>
                                </div>

                                <div className="form-group">
                                    <label>Enter Your Description</label><br />
                                    <textarea className="form-control" name="description" value={props.value.description} onChange={props.data} rows="5" placeholder="Description" ></textarea>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </>
    )
}

export default AddEventForm;