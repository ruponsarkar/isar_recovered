import React, { useState, useEffect } from 'react';
import api from '../../API/api';
import { DataGrid } from '@mui/x-data-grid';
import Box from '@mui/material/Box';
import Button from '@mui/material/Button';
import Typography from '@mui/material/Typography';
import Modal from '@mui/material/Modal';

import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';

import { useParams } from 'react-router-dom';
import { useNavigate } from 'react-router-dom';
import AddArticleForm from '../form/addArticleForm';
import axios from 'axios';
import Card from '@mui/material/Card';
import LinearProgress from '@mui/material/LinearProgress';
import EditIcon from '@mui/icons-material/Edit';
import DeleteIcon from '@mui/icons-material/Delete';
import Swal from 'sweetalert2'


const style = {
    position: 'absolute',
    top: '50%',
    left: '50%',
    transform: 'translate(-50%, -50%)',
    width: 800,
    maxHeight: '90vh',        // 👈 important
    overflowY: 'auto',        // 👈 enable vertical scroll
    bgcolor: 'background.paper',
    borderRadius: 2,
    boxShadow: 24,
    p: 4,
};



const Article = () => {
    const navigate = useNavigate();
    const params = useParams();

    const [article, setArticle] = useState();
    const [load, setLoad] = useState(false);
    const [journal, setJournal] = useState();

    const [open, setOpen] = React.useState(false);
    const handleOpen = () => setOpen(true);

    const handleClose = () => {
        setArticleData({
            ...articleData,
            'id': ''
        })
        setOpen(false)
    };



    const [articleData, setArticleData] = useState({
        id: '',
        name: '',
        aname: '',
        designation: '',
        doi: '',
        page: '',
        abstract: '',
        googleScholar: '',
        published_date: '',
        keywords: '',

    })
    const [articleFile, setArticleFile] = useState();

    useEffect(() => {
        getArticle()
    }, []);


    const getArticle = () => {
        setLoad(true)
        api.getArticle(params.id)
            .then((res) => {
                setLoad(false)
                console.log(res);
                setArticle(res.data.article);
                setJournal(res.data.getJournal);
            })
            .catch((err) => {
                console.log(err.response);
            })
    }

    const getArticleById = (id) => {
        console.log(id);
        setArticleData(id);
        setOpen(true);
    }

    const handleDelete=(article)=>{
        console.log("iff", article.id);
        // Swal.fire 
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
                
                api.deleteArticle(article.id).then((res)=>{
                    console.log("log delete article ", res);
                    getArticle();

                    Swal.fire({
                        title: "Deleted!",
                        text: "Your file has been deleted.",
                        icon: "success"
                      });

                })
                .catch((err)=>{
                    console.log("error : ", err);
                })

              
            }
          });

    }

    const columns = [
        { field: 'name', headerName: 'Title', width: 300 },
        { field: 'aname', headerName: 'Author', width: 300 },
        { field: 'file', headerName: 'Article', width: 100 },
        {
            field: 'id', headerName: 'Action', width: 100,
            renderCell: (field) => (
                <>
                    &nbsp;
                    <button className='btn btn-success btn-sm' onClick={() => getArticleById(field.row)} ><EditIcon fontSize='sm'/> </button>
                    <button className='btn btn-danger btn-sm mx-2' onClick={() => handleDelete(field.row)} ><DeleteIcon fontSize='sm'/> </button>
                    &nbsp;


                    {/* <ArticleDialog id={field.id} /> */}
                </>
            ),
        },

    ];


    const handleAddInput = (e) => {
        setArticleData({
            ...articleData,
            [e.target.name]: e.target.value
        });

    }

    const handleFile = (e) => {
        setArticleFile(e.target.files[0]);
    }


    const saveArticle = () => {
        // console.log(articleFile);
        // console.log(articleData);
        // return;

        setLoad(true)
        const api = process.env.REACT_APP_MAIN_API;
        const url = api + "postArticle";

        const formData = new FormData();

        formData.append('id', articleData.id);
        formData.append('name', articleData.name);
        formData.append('aname', articleData.aname);
        formData.append('designation', articleData.designation);
        formData.append('doi', articleData.doi);
        formData.append('page', articleData.page);
        formData.append('abstract', articleData.abstract);
        formData.append('googleScholar', articleData.googleScholar);
        formData.append('published_date', articleData.published_date);
        formData.append('keywords', articleData.keywords);
        formData.append('file', articleFile);
        formData.append('i_id', params.id);

        axios.post(url, formData)
            .then((res) => {
                console.log(res);
                getArticle();
                handleClose();
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

    const handleOpenArticle = () => {
        handleOpen();
        setArticleData({});
    }


    const addEditor = () => {
        return (
            <>
                <div>
                    <div className="m-2">

                    {articleData.id} <Button variant='contained' onClick={handleOpenArticle}>+ Add New Article </Button>
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

                                <AddArticleForm articleData={articleData} handleAddInput={handleAddInput} handleFile={handleFile} />

                                <div className="col-12 m-2 text-center">
                                    <button className='btn btn-success' onClick={saveArticle} disabled={load ? 'disabled' : ''}>Save Article</button>
                                </div>

                            </Typography>
                        </Box>
                    </Modal>
                </div>
            </>
        )
    }


    const volumeTable = () => {
        return (
            <>
                {article &&
                    <div style={{ height: 650, width: '100%' }}>
                        <DataGrid
                            rows={article}
                            columns={columns}
                            getRowId={(row) => row.id}
                            pageSize={5}
                            rowsPerPageOptions={[8]}
                            checkboxSelection
                        />
                    </div>
                }

            </>
        )
    }




    return (
        <>
            {load &&
                <LinearProgress />
            }
            <ToastContainer />
            <Card>
                <Card>
                    <div className='m-2 text-center'>
                        <h3>  All Article</h3>
                        <h5>{journal && journal.j_name}</h5>

                    </div>
                </Card>

                {addEditor()}

                {volumeTable()}
            </Card>

        </>
    )
}

export default Article;