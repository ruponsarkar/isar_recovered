import React, { useState, useEffect } from 'react'
import Card from '@mui/material/Card';
import MenuBookIcon from '@mui/icons-material/MenuBook';
import LibraryBooksIcon from '@mui/icons-material/LibraryBooks';
import DownloadIcon from '@mui/icons-material/Download';
import VisibilityIcon from '@mui/icons-material/Visibility';

import CountUp from 'react-countup';
import VisibilitySensor from 'react-visibility-sensor';

import api from '../../API/api'


const Counter = () => {

    const [count, setCount] = useState({});
    const [load, setLoad] = useState(false);

    useEffect(() => {
        countSection();
    }, []);

    const countSection = () => {
        setLoad(true);
        api.countSection()
            .then((res) => {
                setLoad(false);
                console.log(res);
                setCount(res.data);
            })
            .catch((err) => {
                setLoad(false);
                console.log(err.response);
            })


    }

    return (
        <>

            <Card>
             



                {/* *********** */}
                <div className="counter-up">
                    <div className="content">
                        <div className="box">
                            <div className="icon"><LibraryBooksIcon fontSize="large"/></div>
                            <div className="counter">{count.tot_article && count.tot_article}</div>
                            <div className="text">Total Articles</div>
                           
                        </div>
                        <div className="box">
                            <div className="icon"><MenuBookIcon fontSize="large"/></div>
                            <div className="counter">{count.tot_journal && count.tot_journal }</div>
                            <div className="text">Journals</div>
                        </div>
                        <div className="box">
                            <div className="icon"><DownloadIcon fontSize="large"/></div>
                            <div className="counter">{count.tot_dowloads && count.tot_dowloads }</div>
                            <div className="text">Total Downloads</div>
                        </div>
                        <div className="box">
                            <div className="icon"><LibraryBooksIcon fontSize="large"/></div>
                            <div className="counter">{count.visitor && count.visitor }</div>
                            <div className="text">Visitors</div>
                        </div>
                    </div>
                </div>

            </Card>
        </>
    )
}

export default Counter;