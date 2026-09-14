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
import Swal from 'sweetalert2'



const style = {
    position: 'absolute',
    top: '50%',
    left: '50%',
    transform: 'translate(-50%, -50%)',
    width: '50%',
    bgcolor: 'background.paper',
    border: '2px solid #000',
    boxShadow: 24,
    p: 4,
};


const ImpactFactorPage = () => {

    const params = useParams();

    const [open, setOpen] = React.useState(false);
    const [open2, setOpen2] = React.useState(false);
    const handleOpen = () => setOpen(true);
    const handleClose = () => setOpen(false);
    const handleCloseEdit = () => setOpen2(false);
    const [load, setLoad] = useState(false);

    const navigate = useNavigate();

    const [indexing, setIndexing] = useState();
    const [impact, setImpact] = useState();

    const [formdata, setFormdata] = useState({
        impact: '',
        link: '',
        j_id: '',
    })






    useEffect(() => {
        console.log(params.id);
        getIndexingById();
        getImpactFactor();

    }, []);

    const getIndexingById = () => {
        setLoad(true)
        api.getIndexingById(params.id)
            .then((res) => {
                setLoad(false)
                console.log(res);
                setIndexing(res.data.indexing);
                setFormdata({ ...formdata, j_id: res.data.journal.j_id })

            })
            .catch((err) => {
                console.log(err.response);
            })

    }

    const getImpactFactor = () => {
        api.getImpactFactor(params.id).then((res) => {
            console.log("impact", res);
            setImpact(res.data.impact);
            setFormdata({ ...formdata, j_id: res.data.journal.j_id })
        })
            .catch((err) => {
                console.log(err);
            })
    }

    const handleSave = () => {
        console.log(formdata);
        api.saveImpactFactor(formdata).then((res) => {
            if (res.status === 200) {
                handleClose();
                getImpactFactor();
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
                console.log(err);
                toast.error('Something went wrong from server !', {
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

    const handleDelete = (data) => {
        console.log(data.id);


        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {


                api.deleteImpactFactor(data.id).then((res) => {
                    console.log(res);
                    if (res.status === 200) {
                        handleClose();
                        getImpactFactor();
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

                        Swal.fire({
                            title: "Deleted!",
                            text: "Your file has been deleted.",
                            icon: "success"
                        });
                    }
                    else {
                        console.log("Something went wrong");
                    }
                })
                    .catch((err) => {
                        console.log(err);
                    })



            }
        });

    }




    const addIndexing = () => {
        return (
            <>
                <div>
                    <div className="my-4">
                        <Button variant='contained' onClick={handleOpen}>
                            + Add New Impact Factor
                        </Button>
                    </div>


                    {/* add indexing modal  */}
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

                                <div className='row'>
                                    <div className="form-group">

                                        <div className="col-12">
                                            <label htmlFor="">Impact Factor</label>
                                            <input type="text" name='impact' onChange={(e) => setFormdata({ ...formdata, impact: e.target.value })} value={formdata.impact} className='form-control' />
                                        </div>
                                        <br />
                                        <div className="col-12">
                                            <label htmlFor=""> Link</label>
                                            <input type="text" name='link' onChange={(e) => setFormdata({ ...formdata, link: e.target.value })} value={formdata.link} className='form-control' />
                                        </div>
                                        <br />


                                    </div>

                                </div>

                                <div className="col-12 m-2 text-center">
                                    <button className='btn btn-success' onClick={handleSave} disabled={load ? 'disabled' : ''}>Save</button>
                                </div>

                            </Typography>
                        </Box>
                    </Modal>


                </div>
            </>
        )
    }



    const viewIndexing = () => {

        const columns = [


            { field: 'impact', headerName: 'name', width: 300 },
            { field: 'link', headerName: 'Link', width: 300 },
            {
                field: 'id', headerName: 'Edit', width: 300,
                renderCell: (field) => (
                    <>
                        <button className='btn btn-info btn-sm' onClick={() => handleDelete(field.row)}>Delete</button>
                    </>
                ),
            },
        ];

        return (
            <div style={{ height: 800, width: '100%' }}>
                {impact &&

                    <DataGrid
                        rows={impact}
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
                        <h3>Impact Factor</h3>
                    </div>
                </Card>
                <div className="m-2">
                    <ToastContainer />
                    {addIndexing()}
                    {viewIndexing()}
                </div>
            </Card>
        </>

    );
}

export default ImpactFactorPage;