import React, { useState, useEffect } from 'react';
import { DataGrid } from '@mui/x-data-grid';
import api from '../../API/api';
import { useNavigate } from 'react-router-dom';

import Card from '@mui/material/Card';
import LinearProgress from '@mui/material/LinearProgress';

const Volume = () => {
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
                setLoad(false)
                console.log(res);
                setJournalData(res.data.journals);
            })
            .catch((err) => {
                console.log(err);
            })
    }




    const columns = [
        { field: 'j_name', headerName: 'Journal', width: 600 },
        {
            field: 'j_id', headerName: 'Action', width: 300,
            renderCell: (field) => (
                <>
                    &nbsp;
                    <button className='btn btn-success btn-sm' onClick={() => navigate(`/admin/volume/${field.row.j_id}`)}>Volumes</button>
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

export default Volume