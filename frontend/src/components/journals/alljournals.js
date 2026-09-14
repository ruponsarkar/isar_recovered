import React, { useEffect, useState } from 'react'
import Card from '@mui/material/Card';
import CardActions from '@mui/material/CardActions';
import Button from '@mui/material/Button';
import Divider from '@mui/material/Divider';

import { useNavigate, Outlet } from 'react-router-dom';

import api from '../../API/api';

import LoadingSkelton from '../common/skelton';
import LinearProgress from '@mui/material/LinearProgress';



const journal = () => {



    return (
        <>
            <Card>
                <div className="row">
                    <div className="col-6 d-flex flex-column justify-content-center">
                        <img src="/assets/journals/j1.jpg" className="card-img" alt="Banner" />
                    </div>

                    <div className="col-6 d-flex flex-column justify-content-center p-2">

                        <span className='text-success'> <strong> ISAR Journal of Arts, Humanities and Social Sciences</strong> </span>

                        <div> <strong> Abbr. Title:</strong> Res J Edu Lin Cul</div>
                        <div> <strong> ISSN(Online):</strong>  2945-4174 </div>
                        <div> <strong> Publisher:</strong> Embar Publishers </div>
                        <div> <strong> Chief Editor: </strong> Dr. Murad Hameed Al-Abullah </div>
                        <div> <strong> Language: </strong> English </div>
                        <div className='text-right pt-2'>
                            <button className='btn btn-info btn-sm'>Read More</button>
                        </div>
                    </div>
                </div>
            </Card>
        </>
    )
}


const AllJournals = () => {
    const navigator = useNavigate();
    const [journals, setJournals] = useState([]);

    const [load, setLoad] = useState(false);

    useEffect(() => {
        getJournals();
    }, []);



    const getJournals = () => {
        setLoad(true)

        api.getJournals()
            .then((res) => {
                console.log(res.data);
                setJournals(res.data.journals);
                setLoad(false);
            })
            .catch((err) => {
                console.log(err.response);
                setLoad(false);
            })

    }




    return (
        <>
            {load ?
                <>
                    <LinearProgress />

                    <LoadingSkelton />

                </> :
                <div className="container">
                    <div className="row">

                        {journals.map((journal) => (
                            <div className="col-md-6 p-2">
                                <Card>
                                    <div className="row">
                                        <div className="col-5 d-flex flex-column justify-content-center">
                                            <img src={`${process.env.REACT_APP_BACKEND}assets/journal/${journal.photo}`} className="card-img" alt="Banner" />
                                        </div>

                                        <div className="col-7 d-flex flex-column justify-content-center">
                                            <div className='m-2'>

                                                <span className='text-success font-small'> <strong>{journal.j_name}</strong> </span>

                                                <div className='font-small'> <strong> Abbr. Title:</strong> {journal.abbr_title}</div>
                                                <div className='font-small'> <strong> ISSN(Online):</strong>  {journal.issn} </div>
                                                <div className='font-small'> <strong> Publisher:</strong> ISAR Publisher </div>
                                                {/* <div className='font-small'> <strong> Chief Editor: </strong> Dr. Murad Hameed Al-Abullah </div> */}
                                                <div className='font-small'> <strong> Language: </strong> English </div>
                                                <div className='text-right pt-2 '>
                                                    {/* <button className='btn btn-info btn-sm' onClick={() => navigator(`/journal/${journal.j_name.replace(/ /g, '-')}`)}>Read More</button> */}
                                                    <button className='btn btn-info btn-sm ' onClick={() => navigator(`/journal/${journal.slug}`)}>Read More</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </Card>
                            </div>

                        ))

                        }


                    </div>
                </div>
            }


        </>
    )
}

export default AllJournals;