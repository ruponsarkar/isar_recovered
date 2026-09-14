import React, { useState, useEffect } from 'react';
import api from '../../API/api';
import { DataGrid } from '@mui/x-data-grid';
import Box from '@mui/material/Box';
import Button from '@mui/material/Button';
import Typography from '@mui/material/Typography';
import Modal from '@mui/material/Modal';

import { useParams } from 'react-router-dom';
import { useNavigate } from 'react-router-dom';
import AddVolumeForm from '../form/addVolumeForm';
import Card from '@mui/material/Card';
import LinearProgress from '@mui/material/LinearProgress';
import EditIcon from '@mui/icons-material/Edit';

const style = {
    position: 'absolute',
    top: '50%',
    left: '50%',
    transform: 'translate(-50%, -50%)',
    width: 400,
    bgcolor: 'background.paper',
    border: '2px solid #000',
    boxShadow: 24,
    p: 4,
};


const AddVolume = () => {
    const navigate = useNavigate();
    const params = useParams();

    const [volume, setVolume] = useState();
    const [journal, setJournal] = useState();

    const [open, setOpen] = React.useState(false);
    const [openEdit, setOpenEdit] = React.useState(false);
    const handleOpen = () => setOpen(true);
    const [load, setLoad] = useState(false);
    

    const [volumeData, setVolumeData] = useState({
        name: '',
        year: ''
    });

    const handleClose = () => {
        setOpen(false);
        setVolumeData({
            name: '',
            year: ''
        })
    }

    useEffect(() => {
        getVolume()
    }, []);


    const getVolume = () => {
        setLoad(true)
        api.getVolume(params.id)
            .then((res) => {
                console.log("da==>>", res);
                setVolume(res.data.volume);
                setJournal(res.data.journal)
                setLoad(false)
            })
            .catch((err) => {
                console.log(err.response);
            })
    }

    const editVolume=(data)=>{
        console.log("editVolume ", data);
        setOpen(true)
        setVolumeData({
            id: data.id,
            name: data.name,
            year: data.year
        });

    }

    const columns = [
        { field: 'name', headerName: 'Title', width: 300 },
        { field: 'year', headerName: 'Year', width: 300 },
        {
            field: 'id', headerName: 'Action', width: 300,
            renderCell: (field) => (
                <>
                    &nbsp;
                    <button className='btn btn-success btn-sm' onClick={() => navigate(`/admin/AddIssue/${field.row.id}`)}>+ Add Issue</button>
                    <button className='btn btn-warning btn-sm mx-2' onClick={() => editVolume(field.row)}>
                    <EditIcon fontSize='sm'/> 
                    </button>
                    &nbsp;
                </>
            ),
        },

    ];


    const handleAddInput = (e) => {

        setVolumeData({
            ...volumeData,
            [e.target.name]: e.target.value
        })
    }

    const handleYear=(e)=>{
        var volName = 'Volume';
        var no = e.target.value - 2022;
        // console.log(no);
        setVolumeData({ name : volName + ' '+ no, year: e.target.value})

        if(e.target.value == 0){
            setVolumeData({ name : '', year: ''})
        }
    }

    const saveVolume = () => {
        console.log(volumeData);
        // return;
        api.addVolume(params.id, volumeData)
            .then((res) => {
                console.log(res);
                getVolume();
                handleClose();
            })
            .catch((err) => {
                console.log(err.response);
            })
    }

    const addEditor = () => {
        return (
            <>
                <div>
                    <div className="m-2">

                        <Button variant='contained' onClick={handleOpen}>+ Add New Volume</Button>
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

                                <div className='row'>
                                    <div className="form-group">
                                        <div className="col-12 mb-3">
                                            <label htmlFor=""> Year </label>
                                            <select name='year' onChange={handleYear} value={volumeData.year} className='form-control'>
                                                <option value="0">-Select-</option>
                                                <option value="2023">2023</option>
                                                <option value="2024">2024</option>
                                                <option value="2025">2025</option>
                                                <option value="2026">2026</option>
                                                <option value="2027">2027</option>
                                                <option value="2028">2028</option>
                                            </select>
                                        </div>
                                        <div className="col-12 mb-3">
                                            <label htmlFor=""> Name </label>
                                            <input type="text" name='name' onChange={handleAddInput} value={volumeData.name} className='form-control' placeholder=''/>
                                        </div>
                                        
                                    </div>
                                </div>

                                <div className="col-12 m-2 text-center">
                                    <button className='btn btn-success' onClick={saveVolume}>Save</button>
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
                {volume &&
                    <div style={{ height: 500, width: '100%' }}>
                        <DataGrid
                            rows={volume}
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
            <Card>
                <Card>
                    <div className='m-2 text-center'>
                        <h3> {journal && journal.j_name}</h3>

                    </div>
                </Card>


                {addEditor()}

                {volumeTable()}
            </Card>


        </>
    )
}

export default AddVolume;