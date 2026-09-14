import React, { useState, useEffect } from 'react';
import { DataGrid } from '@mui/x-data-grid';
import Box from '@mui/material/Box';
import Button from '@mui/material/Button';
import Typography from '@mui/material/Typography';
import Modal from '@mui/material/Modal';

import api from '../../API/api';
import { useNavigate } from 'react-router-dom';

import AddEditorForm from '../form/addEditorForm';
import axios from 'axios';
import Card from '@mui/material/Card';
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import LinearProgress from '@mui/material/LinearProgress';

import { useParams } from 'react-router-dom';


const style = {
    position: 'absolute',
    top: '50%',
    left: '50%',
    transform: 'translate(-50%, -50%)',
    width: '80%',
    bgcolor: 'background.paper',
    border: '2px solid #000',
    boxShadow: 24,
    p: 4,
};


const Editors = () => {

    const params = useParams();

    const [open, setOpen] = React.useState(false);
    const handleOpen = () => setOpen(true);
    const handleClose = () => setOpen(false);
    const [load, setLoad] = useState(false);
    const [checked, setChecked] = useState(false);

    const navigate = useNavigate();

    const [editors, setEditors] = useState();

    const [editorData, setEditorData] = useState({
        name: '',
        details: '',
        type: '',
        profile: '',
        interest: '',
        biography: '',
    })

    const [editorId, setEditorId] = useState();

    const [editorPhoto, setEditorPhoto] = useState();

    // for feature 
    const handleCheck = () => {
        setChecked(!checked)

        console.log(checked);
    }
    const handleEditorPhoto = (e) => {
        console.log(e.target.files[0]);
        setEditorPhoto(e.target.files[0]);
    }

    const handleAddInput = (e) => {
        console.log(e.target.value);
        setEditorData({
            ...editorData,
            [e.target.name]: e.target.value
        })
    }



    useEffect(() => {
        console.log(params.id);
        journalDetails();

    }, []);

    const journalDetails = () => {
        setLoad(true)
        api.journalDetails(params.id)
            .then((res) => {
                setLoad(false)
                console.log(res);
                setEditors(res.data.editors)
                setEditorId('');

            })
            .catch((err) => {
                console.log(err.response);
            })

    }

    const handleUpdate = (id) => {

        setEditorId('');

        api.getEditor(id)
            .then((res) => {
                console.log("==>", res);
                setEditorData({
                    name: res.data.editor.name,
                    details: res.data.editor.details,
                    type: res.data.editor.type,
                    profile: res.data.editor.profile,
                    interest: res.data.editor.interest,
                    biography: res.data.editor.biography,
                });
                if (res.data.editor.isFeature == 'true') {
                    setChecked(true)
                } else {
                    setChecked(false)
                }
                setEditorId(id);
                setOpen(true);

            })
            .catch((err) => {
                console.log(err);
            })
    }


    const handleSaveEditor = () => {
        console.log(editorData);
        // return ;

        const api = process.env.REACT_APP_MAIN_API;

        const url = api + "postEditor";

        const formData = new FormData();
        formData.append('name', editorData.name);
        formData.append('details', editorData.details);
        formData.append('type', editorData.type);
        formData.append('interest', editorData.interest);
        formData.append('biography', editorData.biography);
        formData.append('profile', editorData.profile);
        formData.append('cover', editorPhoto);
        formData.append('j_id', params.id);
        formData.append('editorId', editorId);
        formData.append('checked', checked);

        axios.post(url, formData)
            .then((res) => {
                console.log(res);
                journalDetails();
                if (res.status === 200) {
                    setEditorData({
                        name: '',
                        details: '',
                        interest: '',
                        type: '',
                        profile: ''
                    });
                    handleClose();
                    toast.success(res.data.message, {
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
                console.log(err.response);
            })

    }



    const addEditor = () => {
        return (
            <>
                <div>
                    <div className="my-4">

                        <Button variant='contained' onClick={handleOpen}>+ Add New Editor</Button>
                    </div>


                    <Modal
                        open={open}
                        onClose={handleClose}
                        aria-labelledby="modal-modal-title"
                        aria-describedby="modal-modal-description"
                    >
                        <Box sx={style}>
                            <Typography id="modal-modal-title" variant="h6" component="h2">
                                {/* Text in a modal */}
                            </Typography>
                            <Typography id="modal-modal-description" sx={{ mt: 2 }}>

                                <AddEditorForm handleAddInput={handleAddInput} editorData={editorData} editorPhoto={editorPhoto} handleEditorPhoto={handleEditorPhoto} handleCheck={handleCheck} checked={checked} />
                                <div className="col-12 m-2 text-center">
                                    <button className='btn btn-success' onClick={handleSaveEditor} disabled={load ? 'disabled' : ''}>Save</button>
                                </div>

                            </Typography>
                        </Box>
                    </Modal>
                </div>
            </>
        )
    }



    const viewEditors = () => {

        const columns = [
            { field: 'name', headerName: 'Name', width: 200 },
            { field: 'details', headerName: 'Details', width: 250 },
            { field: 'type', headerName: 'Type', width: 100 },
            {
                field: 'image', headerName: 'photo', width: 150,
                renderCell: (field) => (
                    <><img src={`${process.env.REACT_APP_BACKEND}assets/editors/${field.row.image}`} alt={field.row.img} width={100} />
                    </>
                ),
            },
            {
                field: 'isFeature', headerName: 'Top Editor', width: 100,
                renderCell: (field) => (
                    <>
                    {field.row.isFeature == 'true' ? <span className='text-success'> <strong> Yes</strong></span>: <span className='text-danger'> <strong> No</strong></span>

                    }
                    </>
                ),
            },
            {
                field: 'id', headerName: 'Action', width: 100,
                renderCell: (field) => (
                    <>
                        <button className='btn btn-info btn-sm' onClick={() => handleUpdate(field.row.id)}>View</button>
                        &nbsp;
                    </>
                ),
            },

        ];

        return (
            <div style={{ height: 800, width: '100%' }}>
                {editors &&

                    <DataGrid
                        rows={editors}
                        columns={columns}
                        getRowId={(row) => row.id}
                        pageSize={5}
                        rowsPerPageOptions={[8]}
                        checkboxSelection
                    />
                }
            </div>
        );
    }



    return (

        <>
            {load &&
                <LinearProgress />
            }
            <Card>
                <Card>
                    <div className='m-2 text-center'>
                        <h3>  All Editors</h3>

                    </div>
                </Card>
                <div className="m-2">
                    <ToastContainer />
                    {addEditor()}

                    {viewEditors()}
                </div>
            </Card>
        </>

    );
}

export default Editors;