import React, { useEffect, useState, useMemo, useReducer } from 'react'
// import Navbar from '../components/common/nav'
import Hero from '../components/home/hero'
import LatestArticle from '../components/home/latestArticle'
import OpenAccess from '../components/home/openAcces'
import Counter from '../components/home/counter'

import api from '../API/api'
import LoadingSkelton from '../components/common/skelton'
import LinearProgress from '@mui/material/LinearProgress';

import RightSide from '../components/common/rightSide'


const Homepage = () => {

    const [journals, setJournals] = useState();
    const [latest, setLatest] = useState();
    const [load, setLoad] = useState(false);

    useEffect(() => {
        getJournals()
    }, []);

    const getJournals = () => {
        setLoad(true)
        api.getJournals()
            .then((res => {
                setLoad(false);
                console.log("==>", res);
                setJournals(res.data.journals);
                setLatest(res.data.latest_artricle)
            }))
            .catch((err) => {
                console.log(err.response);
            })
    }


    return (
        <>
            {load ?
                <>
                    <LinearProgress />

                    <LoadingSkelton />

                </> :
                <div>

                    <div className='container mt-2'>
                        <div className='row'>
                            <div className='col-md-3 d-none d-lg-block'>
                                <RightSide />
                            </div>
                            <div className='col-md-9'>
                                <Hero journals={journals} />
                                <OpenAccess />
                                <LatestArticle latest={latest} />
                            </div>
                            <div className='col-md-3 d-block d-sm-none'>
                                <RightSide />
                            </div>

                        </div>
                    </div>
                    <div className='py-2'>
                        <Counter />
                    </div>
                </div>
            }

        </>
    )
}

export default Homepage;