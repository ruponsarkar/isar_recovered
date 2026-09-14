import React, { useState, useEffect } from "react";
import MUIDataTable from "mui-datatables";

import TableRow from '@mui/material/TableRow';
import TableCell from '@mui/material/TableCell';

// import FirstPage from "../form/addEventForm";

import AddEventForm from "../form/addEventForm";


import Skeleton from 'react-loading-skeleton'
import 'react-loading-skeleton/dist/skeleton.css'


import api from "../../../API/api";

import Card from '@mui/material/Card';
import CardActions from '@mui/material/CardActions';
import CardContent from '@mui/material/CardContent';
import Button from '@mui/material/Button';
import Typography from '@mui/material/Typography';


import Dialog from '@mui/material/Dialog';
import DialogActions from '@mui/material/DialogActions';
import DialogContent from '@mui/material/DialogContent';
import DialogTitle from '@mui/material/DialogTitle';
import Divider from '@mui/material/Divider';

import EditIcon from '@mui/icons-material/Edit';

import ImageList from '@mui/material/ImageList';
import ImageListItem from '@mui/material/ImageListItem';
import ImageListItemBar from '@mui/material/ImageListItemBar';
import ListSubheader from '@mui/material/ListSubheader';
import IconButton from '@mui/material/IconButton';
import InfoIcon from '@mui/icons-material/Info';




const ViewEvents = () => {

    const [events, setEvents] = useState([]);
    const [loading, setLoading] = useState(true);
    const [loadingDialog, setLoadingDialog] = useState(true);
    const [openForm, setOpenForm] = React.useState(false);
    const [imgDialog, setImgDialog] = React.useState(false);
    const [img, setImg] = useState([]);
    const [formData, setFormdata] = useState({
        id: '',
        event: '',
        from: '',
        to: '',
        time: '',
        location: '',
        description: '',
        img: []

    });

    const handleInput = (e) => {
        console.log(e.target.value);
        setFormdata({
            ...formData,
            [e.target.name]: e.target.value
        })
    }






    const handleClickOpen = (id) => {
        console.log(id);
        setLoadingDialog(true);
        getEventData(id);



        setOpenForm(true);
    };

    const getEventData = (id) => {
        api.getEventData(id)
            .then((res) => {
                console.log("Event==>", res);
                setFormdata({
                    id: res.data.event.id,
                    event: res.data.event.event,
                    from: res.data.event.date,
                    to: '',
                    time: res.data.event.time,
                    location: res.data.event.location,
                    description: res.data.event.description,
                    img: []
                })

                setLoadingDialog(false);

            })
            .catch((err) => {
                console.log(err);
            })
    }

    const handleImgDialog = (id) => {

        getEventImg(id);


    };

    const getEventImg = (id) => {
        api.getEventImg(id).then((res) => {
            console.log(res);

            if (res.data.status === 200) {
                setImg(res.data.img)
                setImgDialog(true);
            }
        })
            .catch((err) => {
                console.log(err);
            })

    }

    const handleImgDialogClose = () => {
        setImgDialog(false);
    };

    const handleFormClose = () => {
        setOpenForm(false);

    };



    useEffect(() => {
        getEvents();

    }, []);


    const getEvents = () => {
        api.getEvents()
            .then((res) => {
                console.log(res);
                setEvents(res.data.events);
                setLoading(false);
            })
            .catch((err) => {
                console.log(err);
            })
    }

    const handleUpdate = (id) => {

        api.updateEvent(formData)
            .then((res) => {
                console.log("update response", res);

            })
            .catch((err) => {
                console.log(err);
            })
    }

    const skeltonloading = () => {
        return (
            <>
                <div className="border p-4">
                    <div className="pb-2">
                        <Skeleton inline={true} width={150} height={30} />
                    </div>

                    <div className="pb-2">
                        <Skeleton height={30} />
                    </div>
                    <div>
                        <Skeleton height={25} count={10} />
                    </div>

                </div>
            </>
        )

    }

    const imgSection = () => {
        return (
            <>
                {/* <div className="row">
                    {img.map(el => (
                        <>
                            <div className="col-md-3 text-center">

                                <img src={`${process.env.REACT_APP_BACKEND}events/${(el.img)}`} alt={el.img} width="200" />
                                <button className="btn btn-outline-success position-absolate"><EditIcon /></button>
                            </div>

                        </>


                    ))}
                </div> */}




                <ImageList >
                    <ImageListItem key="Subheader" cols={2} gap={8}>
                        <ListSubheader component="div">December</ListSubheader>
                    </ImageListItem>
                    {img.map((el) => (
                        <ImageListItem sx={{ width: 500, height: 250, margin: "auto" }}
                        cols={4} key={el.id}>
                            <img
                                src={`${process.env.REACT_APP_BACKEND}events/${(el.img)}`}
                                srcSet={`${process.env.REACT_APP_BACKEND}events/${(el.img)}`}
                                alt={el.img}
                                loading="lazy"
                            />
                            <ImageListItemBar
                                title={el.img}
                                subtitle={el.img}
                                actionIcon={
                                    <IconButton
                                        sx={{ color: 'rgba(255, 255, 255, 0.54)' }}
                                        aria-label={`info about ${el.img}`}
                                    >
                                        <InfoIcon />
                                    </IconButton>
                                }
                            />
                        </ImageListItem>
                    ))}
                </ImageList>



            </>
        )
    }


    const columns = [

        {
            name: "event",
            label: "Event",
            options: {
                filter: true,
                sort: true,
            }
        },
        {
            name: "location",
            label: "Location",
            options: {
                filter: true,
                sort: true,
            }
        },
        {
            name: "date",
            label: "Date",
            options: {
                filter: true,
                sort: false,
            }
        },
        {
            name: "time",
            label: "time",
            options: {
                filter: true,
                sort: false,
            }
        },
        {
            name: "description",
            label: "Description",
            options: {
                display: false,
                filter: false,
                sort: false,
            }
        },
        {
            name: "id",
            label: "id",
            options: {
                filter: false,
                sort: false,
                display: false,
            }
        },
    ];


    const options = {
        rowsPerPage: 10,
        filterType: '',
        selectableRows: false,
        responsive: 'standard',

        expandableRows: true,
        expandableRowsHeader: false,
        expandableRowsOnClick: true,

        renderExpandableRow: (rowData, rowMeta) => {
            const colSpan = rowData.length + 1;

            return (
                <>
                    <TableRow>
                        <TableCell colSpan={colSpan}>
                            <Card style={{ marginTop: '-15px' }}>
                                <CardContent>
                                    <Typography gutterBottom variant="h6" component="div">
                                        {rowData[0]} :
                                    </Typography>
                                    <Typography variant="body2" color="text.secondary">
                                        <div className='my-3'>
                                            {rowData[4]}

                                        </div>
                                    </Typography>
                                </CardContent>
                                <CardActions>

                                    <div className="col-12 text-center">
                                        <Divider style={{ width: '100%', margin: '5px' }} />
                                        <span className="p-2">
                                            <Button color="success" size="small" variant="outlined" onClick={() => handleClickOpen(rowData[5])}>
                                                Update Content
                                            </Button>
                                        </span>

                                        <span className="p-2">
                                            <Button color="success" size="small" variant="outlined" onClick={() => handleImgDialog(rowData[5])}>
                                                Update Image
                                            </Button>
                                        </span>

                                        <span className="p-2">
                                            <Button color="error" size="small" variant="outlined" onClick={handleClickOpen}>
                                                Delete
                                            </Button>
                                        </span>
                                    </div>

                                </CardActions>
                            </Card>



                            <Dialog
                                fullWidth
                                maxWidth="lg"
                                open={openForm} onClose={handleFormClose}>
                                <DialogTitle> <span className="dotted"> Update</span></DialogTitle>
                                <DialogContent>
                                    {loadingDialog ? skeltonloading() :
                                        <>
                                            <AddEventForm data={handleInput} value={formData} />
                                        </>
                                    }

                                </DialogContent>
                                <DialogActions>
                                    <Button variant="contained" onClick={() => handleUpdate(formData.id)}>Update</Button>
                                    <Button onClick={handleFormClose}>Cancel</Button>
                                </DialogActions>
                            </Dialog>






                            <Dialog
                                fullWidth
                                maxWidth="lg"
                                open={imgDialog} onClose={handleImgDialogClose}>
                                <DialogTitle> <span className="dotted"> Update</span></DialogTitle>
                                <DialogContent>

                                    {imgSection()}
                                    {/* <h1>Okkk</h1> */}




                                </DialogContent>
                                <DialogActions>
                                    <Button onClick={handleImgDialogClose}>Cancel</Button>
                                </DialogActions>
                            </Dialog>


                        </TableCell>
                    </TableRow>
                </>
            );
        },
        onRowExpansionChange: (curExpanded, allExpanded, rowsExpanded) =>
            console.log(curExpanded, allExpanded, rowsExpanded),

    };





    return (
        <>
            <div className="row py-4">
                {loading ? skeltonloading() :
                    <MUIDataTable
                        title={"Employee List"}
                        data={events}
                        columns={columns}
                        options={options}
                    />}
            </div>
        </>
    )


}

export default ViewEvents;