import React, { useState, useEffect } from 'react';
import { DataGrid } from '@mui/x-data-grid';
import api from '../../API/api';
import { useNavigate } from 'react-router-dom';
import Card from '@mui/material/Card';
import LinearProgress from '@mui/material/LinearProgress';


const ViewJournal = () => {
    const navigate = useNavigate();

    const [journalData, setJournalData] = useState([]);
    const [load, setLoad] = useState(false);

    useEffect(() => {
        getJournals();

    }, []);


    const getJournals = () => {
        setLoad(true)
        api.getJournals()
            .then((res) => {
                console.log(res);
                setJournalData(res.data.journals);
                setLoad(false)
            })
            .catch((err) => {
                console.log(err);
                setLoad(false)
            })
    }




    const columns = [
        { field: 'j_name', headerName: 'Journal', width: 300 },
        { field: 'issn', headerName: 'ISSN', width: 150 },
        // { field: 'country_of_origin', headerName: 'Country Of Origin Year', width: 130 },
        {
            field: 'photo', headerName: 'photo', width: 120, renderCell: (field) => (
                <>
                    <img src={`${process.env.REACT_APP_BACKEND}assets/journal/${field.row.photo}`} alt='Journal' width={100} />
                </>
            ),
        },

        {
            field: 'j_id', headerName: 'Action', width: 700,
            renderCell: (field) => (
                <>
                    
                    <button className="btn btn-primary btn-sm me-2" onClick={() => navigate(`/admin/journal-pages/${field.row.slug}`)}>Pages &amp; navigation</button>
                    <button  onClick={()=> navigate(`/admin/addjournal/${field.row.slug}`)} className='btn btn-warning btn-sm'>Edit</button>
                    &nbsp;
                    <button className='btn btn-success btn-sm' onClick={() => navigate(`/admin/editors/${field.row.slug}`)}>+ Editors</button>
                    &nbsp;
                    <button className='btn btn-info btn-sm' onClick={() => navigate(`/admin/indexing/${field.row.slug}`)}>+ Indexing</button>
                    &nbsp;
                    <button className='btn btn-secondary btn-sm' onClick={() => navigate(`/admin/ImpactFactor/${field.row.slug}`)}>+ Impact Factor</button>
                    &nbsp;
                    <button className='btn btn-info btn-sm' onClick={() => navigate(`/admin/certificate/${field.row.slug}`)}>+ Certificate</button>
                    &nbsp;
                </>
            ),
        },

    ];


    return (
        <>
        {load && 
        <LinearProgress />
        }
       
        <Card>
            <Card>
                <div className='m-2 text-center'>
                    <h3>  All Journals</h3>

                </div>
            </Card>
            <div style={{ height: 500, width: '100%' }}>
                <DataGrid
                    rows={journalData}
                    columns={columns}
                    getRowId={(row) => row.j_id}
                    pageSize={5}
                    rowsPerPageOptions={[8]}
                    checkboxSelection
                />
            </div>
        </Card>
        </>
    );
}

export default ViewJournal