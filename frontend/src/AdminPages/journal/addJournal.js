import React, { useState, useEffect } from 'react'
import AddJournalForm from '../form/addJournalForm';
import axios from 'axios';
import Card from '@mui/material/Card';

import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import LinearProgress from '@mui/material/LinearProgress';
import { useParams } from 'react-router-dom';
import api from '../../API/api';

const AddJournal = () => {

    const params = useParams();

    const [value, setValue] = useState({
        j_id: '',
        title: '',
        short: '',
        abbr: '',
        issn: '',
        frequency: '',
        language: '',
        countryOfOrigin: '',
        publisher: 'ISAR Publisher',
        googleScholar: '',
        cover: '',

    });




    const [aimAndScope, setAimAndScope] = useState();
    const [cover, setCover] = useState();
    const [load, setLoad] = useState(false);

    const [loadAim, setLoadAim] = useState();

    useEffect(()=>{

        if(params.slug){
            console.log(params.slug);
            setLoad(true);
            api.journalDetailsForUpdate(params.slug)
            .then((res)=>{
                setLoad(false);
                console.log(res);
                setValue(res.data.journal);
                setAimAndScope(res.data.journal.aim_and_scope)
                setLoadAim('1')
                console.log("=aim>",aimAndScope);
            })
            .catch((err)=>{ 
                console.log(err.response);
            })

        }
        else{
            console.log("no params");
        }
    }, [params]);



    const onchangeInput = (e) => {
        setValue({
            ...value,
            [e.target.name]: e.target.value
        })

    }
    const handleAimAndScope = (e) => {
        setAimAndScope(e);

    }

    const handleCoverImg = (e) => {
        console.log(e.target.files[0]);
        setCover(e.target.files[0])

    }

    const handleSubmit = () => {
        setLoad(true)

        const api = process.env.REACT_APP_MAIN_API;
        
        const url = api + "postJournal";

        const formData = new FormData();

        formData.append('id', value.j_id);
        formData.append('title', value.title);
        formData.append('short', value.short);
        formData.append('abbr', value.abbr);
        formData.append('issn', value.issn);
        formData.append('frequency', value.frequency);
        formData.append('language', value.language);
        formData.append('countryOfOrigin', value.countryOfOrigin);
        formData.append('publisher', value.publisher);
        formData.append('googleScholar', value.googleScholar);

        formData.append('aimAndScope', aimAndScope);
        formData.append('cover', cover);
        axios.post(url, formData)
            .then((res) => {
                console.log("added", res);
                setLoad(false)

                if (res.status === 200) {

                    toast.success('Successfully Added', {
                        position: "top-right",
                        autoClose: 5000,
                        hideProgressBar: false,
                        closeOnClick: true,
                        pauseOnHover: true,
                        draggable: true,
                        progress: undefined,
                        theme: "colored",
                    });
                }
                else {
                    toast.error('Something went wrong !', {
                        position: "top-right",
                        autoClose: 5000,
                        hideProgressBar: false,
                        closeOnClick: true,
                        pauseOnHover: true,
                        draggable: true,
                        progress: undefined,
                        theme: "colored",
                    });
                }
            })
            .catch((err) => {
                setLoad(false)
                console.log(err.response);
                toast.error('Something went wrong !', {
                    position: "top-right",
                    autoClose: 5000,
                    hideProgressBar: false,
                    closeOnClick: true,
                    pauseOnHover: true,
                    draggable: true,
                    progress: undefined,
                    theme: "colored",
                });
            })


    }






    return (
        <>
        {load && 
        <LinearProgress />
        }
            <ToastContainer />
            <Card>
                <AddJournalForm value={value} onchange={onchangeInput} handleAimAndScope={handleAimAndScope} aimAndScope={aimAndScope} handleCoverImg={handleCoverImg} loadAim={loadAim}/>
                <div className='text-center'>

                    <button className='btn btn-success mb-3' onClick={handleSubmit} disabled={ load ? 'disabled' : ''}>Submit Journal</button>
                </div>
            </Card>
        </>
    )
}

export default AddJournal;