import React, { useContext, useEffect, useState } from 'react'
import Card from '@mui/material/Card';
import Divider from '@mui/material/Divider';
import { JournalContext } from '../Helper/Contex';
import axios from 'axios';
import LinearProgress from '@mui/material/LinearProgress';
import Swal from 'sweetalert2';

import SendIcon from '@mui/icons-material/Send';

const Manuscript = () => {
    const { journals, setJournals } = useContext(JournalContext)

    const [formData, setFormData] = useState({});
    const [file, setFile] = useState();
    const [load, setLoad] = useState(false);
    const [submitted, setSubmitted] = useState(false)

    const handleChange = (e) => {
        setFormData({
            ...formData,
            [e.target.name]: e.target.value
        })
        console.log(formData);
    }

    const handleFile = (e) => {
        setFile(e.target.files[0])
    }

    const handleSubmit = () => {
        console.log(formData);
        console.log(file);

        if(!formData.mode){
            Swal.fire({
                title: "Warning",
                text: "Process need to select ",
                icon: 'warning',
            });
            return;
        }
        if(!formData.type){
            Swal.fire({
                title: "Warning",
                text: "Article Type need to select ",
                icon: 'warning',
            });
            return;
        }
        if(!formData.journal){
            Swal.fire({
                title: "Warning",
                text: "Journal need to select ",
                icon: 'warning',
            });
            return;
        }
        if(!formData.author){
            Swal.fire({
                title: "Warning",
                text: "Please enter Corresponding Author",
                icon: 'warning',
            });
            return;
        }
        if(!formData.country){
            Swal.fire({
                title: "Warning",
                text: "Please enter country",
                icon: 'warning',
            });
            return;
        }
        if(!formData.affiliation){
            Swal.fire({
                title: "Warning",
                text: "Please enter affiliation",
                icon: 'warning',
            });
            return;
        }
        if(!formData.email){
            Swal.fire({
                title: "Warning",
                text: "Please enter email",
                icon: 'warning',
            });
            return;
        }
        if(!formData.mobile){
            Swal.fire({
                title: "Warning",
                text: "Please enter mobile",
                icon: 'warning',
            });
            return;
        }
        if(!formData.manuscript){
            Swal.fire({
                title: "Warning",
                text: "Please enter Manuscript Title",
                icon: 'warning',
            });
            return;
        }

        setLoad(true);

        const api = process.env.REACT_APP_MAIN_API;
        const url = api + "postManuscript";

        const AllData = new FormData();
        AllData.append('mode', formData.mode);
        AllData.append('type', formData.type);
        AllData.append('journal', formData.journal);
        AllData.append('author', formData.author);
        AllData.append('country', formData.country);
        AllData.append('affiliation', formData.affiliation);
        AllData.append('email', formData.email);
        AllData.append('mobile', formData.mobile);
        AllData.append('manuscript', formData.manuscript);
        AllData.append('file', file);

        axios.post(url, AllData)
            .then((res) => {
                console.log(res);
                // setFormData({})
                setSubmitted(true)
                setLoad(false);

                Swal.fire({
                    title: "Your Request Submitted Successfully",
                    text: "Thank You, Will Get back you soon  ",
                    icon: 'success',
                });
                return;
            })
            .catch((err) => {
                setLoad(false);
                console.log(err.response);

                Swal.fire({
                    title: "Oppsss",
                    text: err.response.data.message,
                    icon: 'error',
                });
                return;
            })


    }

    return (
        <>

            <div className='container my-2'>
                <Card>

                    <div className="col m-4">

                        <div className="m-2">
                            <strong> You may also submit your paper through email to <span style={{ color: 'blue' }}>
                                isarpublishers@gmail.com
                            </span>
                            </strong>
                        </div>
                        <Divider />

                        <div className="row">
                            <div className="col-md-6 pt-3">
                                <label htmlFor="">Process</label>

                                <select name="mode" onChange={handleChange} id="" className='form-control'>
                                    <option value="">--Select--</option>
                                    <option value="First Track Process">First Track Process</option>
                                    <option value="Normal Process">Normal Process</option>
                                </select>

                            </div>
                            <div className="col-md-6 pt-3">
                                <label htmlFor="">Article Type</label>
                                <select name="type" onChange={handleChange} value={formData.type} id="" className='form-control'>
                                    <option value="">--Select--</option>
                                    <option value="Research article">Research article</option>
                                    <option value="Review article">Review article</option>
                                    <option value="Short Communication">Short Communication</option>
                                    <option value="Case Report">Case Report</option>
                                    <option value="Letter to editor">Letter to editor</option>
                                </select>
                            </div>
                            <div className="col-md-6 pt-3">
                                <label htmlFor="">Journal</label>
                                <select name="journal" onChange={handleChange} value={formData.journal} id="" className='form-control'>
                                    <option value="">--Select--</option>
                                    {journals && journals.map((el) => (
                                        <option value={el.j_name}>{el.j_name}</option>
                                    ))}

                                </select>
                            </div>


                            <div className="col-md-6 pt-3">
                                <label htmlFor="author">Corresponding Author</label>
                                <input type="text" name='author' onChange={handleChange} value={formData.author} className='form-control' placeholder='Full Name' />
                            </div>
                            <div className="col-md-6 pt-3">
                                <label htmlFor="country">Country</label>
                                <input type="text" name='country' onChange={handleChange} value={formData.country} className='form-control' placeholder='Enter Your Country name' />
                            </div>

                            <div className="col-md-6 pt-3">
                                <label htmlFor="">Affiliation</label>
                                <input type="text" name='affiliation' onChange={handleChange} value={formData.affiliation} className='form-control' placeholder='Author Affiliation' />
                            </div>

                            <div className="col-md-6 pt-3">
                                <label htmlFor="">Email</label>
                                <input type="text" name='email' onChange={handleChange} value={formData.email} className='form-control' placeholder='Your Email' />
                            </div>

                            <div className="col-md-6 pt-3">
                                <label htmlFor="">Phone</label>
                                <input type="number" name='mobile' onChange={handleChange} value={formData.mobile} className='form-control' placeholder='Your Phone Number' />
                            </div>

                            <div className="col-md-12 pt-3">
                                <label htmlFor="">Manuscript Title</label>
                                <input type="text" name='manuscript' onChange={handleChange} value={formData.manuscript} className='form-control' placeholder='Enter Manuscript Title' />
                            </div>

                            <div className="col-md-12 pt-3">
                                <label htmlFor="">Article File</label>
                                <input type="file" accept='.pdf,.docx' name='file' onChange={handleFile} className='form-control' />
                            </div>

                            <div className="col-md-12 pt-3 text-center">
                                <button onClick={handleSubmit} className='btn btn-success' disabled={ submitted? 'disabled': ''}>Submit Manuscript &nbsp; <SendIcon/> </button>
                            </div>


                        </div>
                    </div>
                </Card>

            </div>
            {load &&
                <LinearProgress />
            }
        </>
    )
}

export default Manuscript