import React, { useEffect, useState } from 'react'
import Card from '@mui/material/Card';
import MUIDataTable from "mui-datatables";

import SimCardDownloadSharpIcon from '@mui/icons-material/SimCardDownloadSharp';

import api from '../../API/api';

import LinearProgress from '@mui/material/LinearProgress';

const AllManuscript = () => {

    const [manuscript, setManuscript] = useState([]);
    const [load, setLoad] = useState(false);
    useEffect(() => {
        getManuscript()
    }, []);

    const getManuscript = () => {
        setLoad(true);
        api.getManuscript()
            .then((res) => {
                console.log(res);
                setLoad(false);
                setManuscript(res.data.manuscript)
            })
            .catch((err) => {
                console.log(err.response);
                setLoad(false);
            })
    }

    const columns = [
        {
            name: "author",
            label: "Author Information",
            options: {
                setCellHeaderProps: value => ({ style: { textDecoration: 'underline' } }),
                filter: false,
                customBodyRender: (value, tableMeta, updateValue, rowData) => (
                    <>
                        <div>Name: <strong> {tableMeta.rowData[0]}</strong></div>
                        <div>Email: {tableMeta.rowData[7]}</div>
                        <div>Phone: {tableMeta.rowData[6]}</div>
                        <div>Affiliation: {tableMeta.rowData[8]}</div>
                        <div>Country: {tableMeta.rowData[10]}</div>

                    </>
                )
            }
        },
        {
            name: "mode",
            label: "Mode",
            options: {
                setCellHeaderProps: value => ({ style: { textDecoration: 'underline' } }),
                filter: true,
                sort: false,
            }
        },
        {
            name: "type",
            label: "Type",
            options: {
                setCellHeaderProps: value => ({ style: { textDecoration: 'underline' } }),
                filter: true,
                sort: false,
            }
        },
        {
            name: "journal",
            label: "Journal",
            options: {
                setCellHeaderProps: value => ({ style: { textDecoration: 'underline' } }),
                filter: true,
                sort: false,
            }
        },

        {
            name: "manuscript",
            label: 'Manuscript Name',
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
            name: "file",
            label: 'Download',
            options: {
                setCellHeaderProps: value => ({ style: { textDecoration: 'underline' } }),
                filter: false,
                customBodyRender: (value, tableMeta, updateValue, rowData) => (
                    <>
                        <div className=''>
                            <a href={`${process.env.REACT_APP_BACKEND}assets/manuscript/${tableMeta.rowData[5]}`} target='_blank' className='btn btn-warning btn-sm'>
                                <SimCardDownloadSharpIcon />
                            </a>
                        </div>
                    </>
                )
            }
        },


        {
            name: "mobile",
            label: "Mobile",
            options: {
                display: false,
                viewColumns: false,
                filter: false,
                sort: false,
            }
        },
        {
            name: "email",
            label: "Email",
            options: {
                display: false,
                viewColumns: false,
                filter: false,
                sort: false,
            }
        },
        {
            name: "affiliation",
            label: "Affiliation",
            options: {
                display: false,
                viewColumns: false,
                filter: false,
                sort: false,
            }
        },
        {
            name: "date",
            label: "Date",
            options: { 
                viewColumns: false,
                filter: false,
                sort: false,
            }
        },
        {
            name: "country",
            label: "Country",
            options: {
                display: false,
                viewColumns: false,
                filter: false,
                sort: false,
            }
        },
    ];


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
                        title={"Manuscript List"}
                        data={manuscript}
                        columns={columns}
                        options={options}
                    />
                </Card>
            }
        </>
    )
}

export default AllManuscript;