import React, { useEffect, useState } from 'react'
import Card from '@mui/material/Card';
import MUIDataTable from "mui-datatables";

import SimCardDownloadSharpIcon from '@mui/icons-material/SimCardDownloadSharp';

import api from '../../API/api';

import LinearProgress from '@mui/material/LinearProgress';

const AllEditorRequest = () => {

    const [data, setData] = useState([]);
    const [load, setLoad] = useState(false);
    useEffect(() => {
        getData()
    }, []);

    const getData = () => {
        setLoad(true);
        api.getEditorRequest()
            .then((res) => {
                console.log(res);
                setLoad(false);
                setData(res.data.reviewers)
            })
            .catch((err) => {
                console.log(err.response);
                setLoad(false);
            })
    }

    const columns = [
        {
            name: "name",
            label: "Informations",
            options: {
                setCellHeaderProps: value => ({ style: { textDecoration: 'underline' } }),
                filter: false,
                customBodyRender: (value, tableMeta, updateValue, rowData) => (
                    <>
                        <div className='text-success'>Name: <strong> {tableMeta.rowData[0]}</strong></div>
                        <div>Email:  <strong> {tableMeta.rowData[1]}</strong></div>
                        <div>Phone: <strong> {tableMeta.rowData[2]}</strong></div>

                    </>
                )
            }
        },
        {
            name: "email",
            label: "Email",
            options: {
                display: false,
                filter: true,
                sort: false,
            }
        },
        {
            name: "mobile",
            label: "Mobile",
            options: {
                display: false,
                filter: true,
                sort: false,
            }
        },
        {
            name: "specialization",
            label: "Specialization",
            options: {
                setCellHeaderProps: value => ({ style: { textDecoration: 'underline' } }),
                filter: true,
                sort: false,
            }
        },

        {
            name: "affiliation",
            label: 'Affiliation',
            options: {
                setCellHeaderProps: value => ({ style: { textDecoration: 'underline' } }),
                filter: false,
                customBodyRender: (value, tableMeta, updateValue, rowData) => (
                    <>
                        <div className=''>
                            <strong> {tableMeta.rowData[4]} </strong>
                        </div>
                    </>
                )
            }
        },
        {
            name: "cv",
            label: 'Download CV',
            options: {
                setCellHeaderProps: value => ({ style: { textDecoration: 'underline' } }),
                filter: false,
                customBodyRender: (value, tableMeta, updateValue, rowData) => (
                    <>
                        <div className=''>
                            <a href={`${process.env.REACT_APP_BACKEND}assets/editorsRequest/cv/${tableMeta.rowData[5]}`} target='_blank' className='btn btn-warning btn-sm'>
                                <SimCardDownloadSharpIcon />
                            </a>
                        </div>
                    </>
                )
            }
        },


        {
            name: "photo",
            label: "Photo",
            options: {
                viewColumns: false,
                filter: false,
                sort: false,
                customBodyRender: (value, tableMeta, updateValue, rowData) => (
                    <>
                        <div className=''>

                            <img src={`${process.env.REACT_APP_BACKEND}assets/editorsRequest/photo/${tableMeta.rowData[6]}`} height={100} />
                        </div>
                    </>
                )
            }
        },

        {
            name: "date",
            label: "Date",
            options: {
                viewColumns: false,
                filter: false,
                sort: false,
                customBodyRender: (value, tableMeta, updateValue, rowData) => (
                    <>
                        <div className=''>

                            {
                                new Date(tableMeta.rowData[7]).getDate() + ','+
                                new Date(tableMeta.rowData[7]).toLocaleString("default", {
                                    month: "long",
                                }) +
                                    "-" +
                                    new Date(tableMeta.rowData[7]).getFullYear()
                                    
                                    
                            }
                        </div>
                    </>
                )

            }
        },
    ];

    // new Date(month).toLocaleString("default", {
    //     month: "long",
    //   }) +
    //   "-" +
    //   new Date(month).getFullYear()

    const options = {
        filterType: 'checkbox',
        selectableRows: false

    };


    return (
        <>
            {load ?
                <LinearProgress /> :
                <Card>
                    <MUIDataTable
                        title={"Editors Request"}
                        data={data}
                        columns={columns}
                        options={options}
                    />
                </Card>
            }
        </>
    )
}

export default AllEditorRequest;