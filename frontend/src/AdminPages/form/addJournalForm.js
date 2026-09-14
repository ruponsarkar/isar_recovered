import React, { useEffect, useState, useRef, useCallback, useMemo } from "react";

import JoditEditor from 'jodit-react';

import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';

const AddJournalForm = (props) => {

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
                value={props.aimAndScope}
                onChange={props.handleAimAndScope}
            />
        ), [props.loadAim])
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
                                    <div className="col-md-9 form-group p-3">
                                        <label>Journal Title</label><br />
                                        <input type="text" className="form-control" name="title" value={props.value.title} onChange={props.onchange} id="name" />
                                    </div>
                                    <div className="col-md-3 form-group p-3">
                                        <label>Short Name</label><br />
                                        <input type="text" className="form-control" name="short" value={props.value.short} onChange={props.onchange} id="name" />
                                    </div>
                                </div>


                                <div className="row">

                                    <div className="col-md-4 form-group p-3">
                                        <label>Abbr Title</label> <br />
                                        <input type="text" className="form-control" name="abbr" value={props.value.abbr} onChange={props.onchange} id="name" />
                                    </div>

                                    <div className="col-md-4 form-group p-3">
                                        <label>ISSN</label> <br />
                                        <input type="text" className="form-control" name="issn" value={props.value.issn} onChange={props.onchange} id="name" />
                                    </div>


                                    <div className="col-md-4 form-group p-3">
                                        <label>Frequency</label> <br />
                                        <input type="text" className="form-control" name="frequency" value={props.value.frequency} onChange={props.onchange} id="location" />
                                    </div>


                                    <div className="col-md-4 form-group p-3">
                                        <label>Language</label> <br />
                                        <input type="text" className="form-control" name="language" value={props.value.language} onChange={props.onchange} id="name" />
                                    </div>

                                    <div className="col-md-4 form-group p-3">
                                        <label>Country Of Origin</label> <br />
                                        <input type="text" className="form-control" name="countryOfOrigin" value={props.value.countryOfOrigin} onChange={props.onchange} id="name" />
                                    </div>

                                    <div className="col-md-4 form-group p-3">
                                        <label>Publishers</label> <br />
                                        <input type="text" className="form-control" name="googleScholar" value={props.value.publisher} onChange={props.onchange} id="name" />
                                    </div>


                                </div>

                                <div className="col-md-12 form-group p-3">
                                    <label>Cover Image</label> <br />
                                    <input type="file" className="form-control" name="cover" id="imgInp" onChange={props.handleCoverImg} />
                                </div>
                                {/* <div className="col-md-12 form-group p-3">
                                    <label>Preview</label> <br />
                                    <div className="card" style={{ height: "180px", alignItems: "center" }}>
                                        <img id="blah" src={props.coverpreview} alt="Cover Image Preview" style={{ height: "180px", width: "fit-content" }} />

                                    </div>

                                </div> */}

                                <div className="form-group p-3">
                                    <label>Aim And Scope</label><br />
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

export default AddJournalForm;