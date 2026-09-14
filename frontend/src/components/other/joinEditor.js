import React, {useState} from 'react'
import JoinForm from '../form/joinForm'

import axios from 'axios';
import Swal from 'sweetalert2';

const JoinEditor = () => {

    const [formData, setFormdata] = useState({
        name: '',
        email: '',
        mobile: '',
        country: '',
        specialization: '',
        affiliation: '',
        publication: '',
        photo: '',
        cv: ''
    });

    const [load, setLoad] = useState(false);
    const [cv, setCv] = useState();
    const [photo, setPhoto] = useState();

    const handleChange =(e)=>{
        setFormdata({
            ...formData,
            [e.target.name] : e.target.value
        })
    }

    const handlePhoto=(e)=>{
        console.log(e.target.files[0]);
        setPhoto(e.target.files[0]);
    }
    const handleCV=(e)=>{
        console.log(e.target.files[0]);
        setCv(e.target.files[0])
    }

    const handleSubmit=()=>{
        console.log(formData);
        postData();
    }

    // *******************nxt api call*****************

    const postData=()=>{
        setLoad(true);
        const api = process.env.REACT_APP_MAIN_API;
        const url = api + "postEditorData";
        const AllData = new FormData();
        AllData.append('name', formData.name)
        AllData.append('email', formData.email)
        AllData.append('mobile', formData.mobile)
        AllData.append('country', formData.country)
        AllData.append('specialization', formData.specialization)
        AllData.append('affiliation', formData.affiliation)
        AllData.append('publication', formData.publication)
        AllData.append('cv', cv)
        AllData.append('photo', photo)

        axios.post(url, AllData)
        .then((res)=>{
            setLoad(false);
            console.log(res);
            Swal.fire({
                title: "Your Request Submitted Successfully",
                text: "Thank You, Will Get back you soon  ",
                icon: 'success',
            });
            return;
        })
        .catch((err)=>{
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
            <JoinForm heading="Join As Editor" formData={formData} handleChange={handleChange} handleSubmit={handleSubmit} handlePhoto={handlePhoto} handleCV={handleCV} load={load}/>
        </>
    )
}

export default JoinEditor