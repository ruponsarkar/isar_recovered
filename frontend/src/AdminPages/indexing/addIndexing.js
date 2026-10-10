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
    width: '50%',
    bgcolor: 'background.paper',
    border: '2px solid #000',
    boxShadow: 24,
    p: 4,
};


const AddIndexingPage = () => {

    const params = useParams();

    const [open, setOpen] = React.useState(false);
    const [open2, setOpen2] = React.useState(false);
    const handleOpen = () => { setLink(''); setImg(undefined); setIsShow(0); setOpen(true); };
    const handleClose = () => setOpen(false);
    const handleCloseEdit = () => setOpen2(false);
    const [load, setLoad] = useState(false);

    const navigate = useNavigate();

    const [indexing, setIndexing] = useState();

    const [journalId, setJournalId] = useState();

    const [editedIndix, setEditedIndex] = useState({

    });

    const [link, setLink] = useState();
    const [isShow, setIsShow] = useState(0);

    const [img, setImg] = useState();

    const [editedImg, setEditedImg] = useState('');




    const handleAddInput = (e) => {
        console.log(e.target.value);
        setLink(e.target.value);
    }
    const handleAddIsShow = (event) => {
        setIsShow(event.target.checked ? 1 : 0);
    }

    const handleEditInput = (event) => {
        const { name, value, checked } = event.target;
        setEditedIndex(previous => ({
            ...previous,
            [name === 'isShowOnHome' ? 'isShow' : name]: name === 'isShowOnHome' ? (checked ? 1 : 0) : value,
        }));
    }

    const handleEditImg = (e) => {
        setEditedImg(e.target.files[0])
    }


    const handleImg = (e) => {
        console.log(e.target.files[0]);
        setImg(e.target.files[0]);

    }

    const handleEdit = (data) => {
        console.log(data);
        setEditedImg('');
        setEditedIndex(data);
        setOpen2(true);

    }


    useEffect(() => {
        console.log(params.id);
        getIndexingById();

    }, []);

    const getIndexingById = () => {
        setLoad(true)
        api.getIndexingById(params.id)
            .then((res) => {
                setLoad(false)
                console.log(res);
                setIndexing(res.data.indexing);
                setJournalId(res.data.journal.j_id)

            })
            .catch((err) => {
                console.log(err.response);
            })

    }



    const handleSave = () => {
        console.log(isShow);

        const api = process.env.REACT_APP_MAIN_API;

        const url = api + "postIndexing";

        const formData = new FormData();
        formData.append('link', link);
        formData.append('img', img);
        formData.append('j_id', journalId);
        formData.append('isShow', isShow);

        axios.post(url, formData)
            .then((res) => {
                console.log(res);
                getIndexingById();
                if (res.status === 200) {
                    handleClose();
                    setLink('')


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
                toast.error(err.response.data.message, {
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

    const handleUpdate = () => {
        console.log(editedIndix);
        console.log(editedImg);


        const api = process.env.REACT_APP_MAIN_API;

        const url = api + "postIndexing";

        const formData = new FormData();
        formData.append('link', editedIndix.link);
        formData.append('img', editedImg);
        formData.append('j_id', journalId);
        formData.append('isShow', Number(editedIndix.isShow) === 1 ? 1 : 0);
        formData.append('id', editedIndix.id);

        axios.post(url, formData)
            .then((res) => {
                console.log(res);
                getIndexingById();
                if (res.status === 200) {
                    handleCloseEdit();
                    setLink('')


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
                toast.error(err.response.data.message, {
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



    const addIndexing = () => {
        return (
            <>
                <div>
                    <div className="my-4">
                        <Button variant='contained' onClick={handleOpen}>
                            + Add New Indexing
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
                                            <label htmlFor="">Indexing Link</label>
                                            <input type="text" name='link' onChange={handleAddInput} value={link} className='form-control' />
                                        </div>

                                        <br />
                                        <div className="col-12">
                                            <label htmlFor="">Image</label>
                                            <input type="file" name='file' onChange={handleImg} className='form-control' />
                                        </div>
                                        <br />
                                        <div className="form-check">
                                            <label className="form-check-label">
                                                <input className="form-check-input" type="checkbox" name="isShowOnHome" value="" checked={isShow === 1} onChange={handleAddIsShow}
                                                    id="indexing-show-on-home" />
                                                Show on home
                                            </label>
                                            <small className="d-block text-muted">Display in the home sidebar. This entry still appears on its journal page when unchecked.</small>
                                        </div>
                                    </div>

                                </div>

                                <div className="col-12 m-2 text-center">
                                    <button className='btn btn-success' onClick={handleSave} disabled={load ? 'disabled' : ''}>Save</button>
                                </div>

                            </Typography>
                        </Box>
                    </Modal>








                    {/* edit modal  */}
                    <Modal
                        open={open2}
                        onClose={handleCloseEdit}
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
                                            <label htmlFor="">Indexing Link</label>
                                            <input type="text" name='link' onChange={handleEditInput} value={editedIndix.link} className='form-control' />
                                        </div>

                                        <br />
                                        <div className="col-12">
                                            <label htmlFor="">Image</label>
                                            <input type="file" name='file' onChange={handleEditImg} className='form-control' />
                                        </div>
                                        <br />
                                        <div className="form-check">
                                            <label className="form-check-label">
                                                <input className="form-check-input" type="checkbox" name="isShowOnHome" value="" checked={editedIndix.isShow == 1} onChange={handleEditInput}
                                                    id="edit-indexing-show-on-home" />
                                                Show on home
                                            </label>
                                            <small className="d-block text-muted">Display in the home sidebar. This entry still appears on its journal page when unchecked.</small>
                                        </div>
                                    </div>

                                </div>

                                <div className="col-12 m-2 text-center">
                                    <button className='btn btn-success' onClick={handleUpdate} disabled={load ? 'disabled' : ''}>Update</button>
                                </div>

                            </Typography>
                        </Box>
                    </Modal>


                </div>
            </>
        )
    }



    const [deletingId, setDeletingId] = useState(null);
    const handleDelete = async (row) => {
      if (deletingId !== null || !window.confirm('Delete this Indexing entry?')) return;
      setDeletingId(row.id);
      try {
        const response = await api.deleteIndexing(row.id, journalId);
        setIndexing(current => current.filter(item => item.id !== row.id));
        toast.success(response.data.message);
      } catch (error) {
        toast.error(error.response?.data?.message || 'Unable to delete this entry. Please try again.');
      } finally {
        setDeletingId(null);
      }
    };

    const viewIndexing = () => {

        const columns = [

            {
                field: 'img', headerName: 'Indexing', width: 300,
                renderCell: (field) => (
                    <>
                        <img src={`${process.env.REACT_APP_BACKEND}assets/indexing/${field.row.img}`} alt={field.row.img} height={50} />
                    </>
                ),
            },
            { field: 'link', headerName: 'Link', width: 300 },
            { field: 'isShow', headerName: 'Show on home', width: 150, valueFormatter: ({ value }) => Number(value) === 1 ? 'Yes' : 'No' },
            {
                field: 'id', headerName: 'Edit', width: 300,
                renderCell: (field) => (
                    <>
                        {/* <img src={`${process.env.REACT_APP_BACKEND}assets/indexing/${field.row.img}`} alt={field.row.img} height={50} /> */}
                        <button className='btn btn-info btn-sm' onClick={() => handleEdit(field.row)}>Edit</button>
                    </>
                ),
            },
            // {
            //     field: 'id', headerName: 'Action', width: 100,
            //     renderCell: (field) => (
            //         <>
            //             <button className='btn btn-info btn-sm' onClick={() => handleEdit(field.row.id)}>View</button>
            //             &nbsp;
            //         </>
            //     ),
            // },

      {
        field: 'delete', headerName: 'Delete', width: 120, sortable: false, filterable: false,
        renderCell: ({ row }) => (
          <button type="button" className="btn btn-danger btn-sm" disabled={deletingId !== null || !journalId}
            onClick={(event) => { event.stopPropagation(); handleDelete(row); }}>
            {deletingId === row.id ? 'Deleting…' : 'Delete'}
          </button>
        ),
      },
        ];

        return (
            <div style={{ height: 800, width: '100%' }}>
                {indexing &&

                    <DataGrid
                        rows={indexing}
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
                        <h3>All Indexing</h3>
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

export default AddIndexingPage;