import React, { useState } from 'react'
import { Card } from '@mui/material'
import { Divider } from '@mui/material'
import SendIcon from '@mui/icons-material/Send';

const JoinForm = (props) => {


    return (
        <>
            <Card>
                <div className='p-4'>
                    <h3>{props.heading}</h3>
                </div>
            </Card>
            <Divider />

            <Card>
                <div className='container my-2'>
                    <Card>

                        <div className="col m-4">

                            <div className="m-5">
                                {/* <strong> 
                                You may also submit your paper through email to <span style={{ color: 'blue' }}>
                                isarpublishers@gmail.com
                            </span>
                            </strong> */}
                            </div>
                            <Divider />

                            <div className="row">

                                <div className="col-md-12 pt-3">
                                    <label htmlFor="author">Name</label>
                                    <input type="text" name='name' onChange={props.handleChange} value={props.formData.name} className='form-control' placeholder='Full Name' />
                                </div>

                                <div className="col-md-6 pt-3">
                                    <label htmlFor="">Email</label>
                                    <input type="text" name='email' onChange={props.handleChange} value={props.formData.email} className='form-control' placeholder='Your Email' />
                                </div>

                                <div className="col-md-6 pt-3">
                                    <label htmlFor="">Phone</label>
                                    <input type="text" name='mobile' onChange={props.handleChange} value={props.formData.mobile} className='form-control' placeholder='Your Phone Number' />
                                </div>

                                <div className="col-md-12 pt-3">
                                    <label htmlFor="">Area of Specialization</label>
                                    <input type="text" name='specialization' onChange={props.handleChange} value={props.formData.specialization} className='form-control' placeholder='Area of Specialization' />
                                </div>
                                <div className="col-md-12 pt-3">
                                    <label htmlFor="">Complete Affiliation</label>
                                    <textarea name="affiliation" className='form-control' onChange={props.handleChange} id="" rows="3">{props.formData.affiliation}</textarea>

                                </div>

                                <div className="col-md-6 pt-3">
                                    <label htmlFor="">Upload Passport Photo</label>
                                    <input type="file" accept='.jpg,.jpeg, .png' name='file' onChange={props.handlePhoto} className='form-control' />
                                </div>
                                <div className="col-md-6 pt-3">
                                    <label htmlFor="">Upload CV </label>
                                    <input type="file" accept='.pdf,.docx' name='file' onChange={props.handleCV} className='form-control' />
                                </div>

                                <div className="col-md-12 pt-3 mt-2 text-center">
                                    <button onClick={props.handleSubmit} className='btn btn-success' disabled={props.load ? 'disabled' : ''}> Submit &nbsp; <SendIcon /> </button>
                                </div>


                            </div>
                        </div>
                    </Card>

                </div>
            </Card>
        </>
    )
}

export default JoinForm