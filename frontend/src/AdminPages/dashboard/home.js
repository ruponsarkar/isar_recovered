import React, { useState, useEffect } from 'react'
import { Card } from '@mui/material';
import MenuBookIcon from '@mui/icons-material/MenuBook';
import GroupIcon from '@mui/icons-material/Group';
import CalendarTodayIcon from '@mui/icons-material/CalendarToday';
import CalendarMonthIcon from '@mui/icons-material/CalendarMonth';
import ContentPasteIcon from '@mui/icons-material/ContentPaste';
import DifferenceIcon from '@mui/icons-material/Difference';
import AttributionIcon from '@mui/icons-material/Attribution';
import api from '../../API/api';
import { Divider } from '@mui/material';
import LinearProgress from '@mui/material/LinearProgress';


const AdminDash = () => {
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
        <>{load ? <LinearProgress /> :
            <Card>
                <div className="text-center m-3">
                    <h3>Dashboard</h3>
                </div>

                <div className="container p-3">
                    <div className="row">
                        <div className="col-md-3 mt-3">
                            <Card>
                                <div className="m-2 pb-3">
                                    <div className='m-2 text-center'>
                                        <MenuBookIcon fontSize='large' color='error' />
                                    </div>
                                    <Divider />
                                    <div className="p-3">
                                        <div className="float-start">
                                            <strong>  Total Journal</strong>
                                        </div>
                                        <div className="float-end">
                                            <strong>  {count.tot_journal}</strong>
                                        </div>
                                    </div>

                                </div>
                            </Card>
                        </div>
                        <div className="col-md-3 mt-3">
                            <Card>
                                <div className="m-2 pb-3">
                                    <div className='m-2 text-center'>
                                        <GroupIcon fontSize='large' color='success' />
                                    </div>
                                    <Divider />
                                    <div className="p-3">
                                        <div className="float-start">
                                            <strong>  Total Editors</strong>
                                        </div>
                                        <div className="float-end">
                                            <strong>  {count.tot_editors}</strong>
                                        </div>
                                    </div>

                                </div>
                            </Card>
                        </div>
                        <div className="col-md-3 mt-3">
                            <Card>
                                <div className="m-2 pb-3">
                                    <div className='m-2 text-center'>
                                        <CalendarTodayIcon fontSize='large' color='warning' />
                                    </div>
                                    <Divider />
                                    <div className="p-3">
                                        <div className="float-start">
                                            <strong>  Total Volume</strong>
                                        </div>
                                        <div className="float-end">
                                            <strong>  {count.tot_volume}</strong>
                                        </div>
                                    </div>

                                </div>
                            </Card>
                        </div>
                        <div className="col-md-3 mt-3">
                            <Card>
                                <div className="m-2 pb-3">
                                    <div className='m-2 text-center'>
                                        <CalendarMonthIcon fontSize='large' color='info' />
                                    </div>
                                    <Divider />
                                    <div className="p-3">
                                        <div className="float-start">
                                            <strong>  Total Issues</strong>
                                        </div>
                                        <div className="float-end">
                                            <strong>  {count.tot_issue}</strong>
                                        </div>
                                    </div>

                                </div>
                            </Card>
                        </div>
                        <div className="col-md-3 mt-3">
                            <Card>
                                <div className="m-2 pb-3">
                                    <div className='m-2 text-center'>
                                        <ContentPasteIcon fontSize='large' color='info' />
                                    </div>
                                    <Divider />
                                    <div className="p-3">
                                        <div className="float-start">
                                            <strong>  Total Article</strong>
                                        </div>
                                        <div className="float-end">
                                            <strong>  {count.tot_article}</strong>
                                        </div>
                                    </div>

                                </div>
                            </Card>
                        </div>
                        <div className="col-md-3 mt-3">
                            <Card>
                                <div className="m-2 pb-3">
                                    <div className='m-2 text-center'>
                                        <DifferenceIcon fontSize='large' color='error' />
                                    </div>
                                    <Divider />
                                    <div className="p-3">
                                        <div className="float-start">
                                            <strong>  Manuscript Received</strong>
                                        </div>
                                        <div className="float-end">
                                            <strong>  {count.tot_manuscript}</strong>
                                        </div>
                                    </div>

                                </div>
                            </Card>
                        </div>
                        <div className="col-md-3 mt-3">
                            <Card>
                                <div className="m-2 pb-3">
                                    <div className='m-2 text-center'>
                                        <AttributionIcon fontSize='large' color='success' />
                                    </div>
                                    <Divider />
                                    <div className="p-3">
                                        <div className="float-start">
                                            <strong>  Reviewer Request</strong>
                                        </div>
                                        <div className="float-end">
                                            <strong>  {count.tot_reviewer}</strong>
                                        </div>
                                    </div>

                                </div>
                            </Card>
                        </div>
                        <div className="col-md-3 mt-3">
                            <Card>
                                <div className="m-2 pb-3">
                                    <div className='m-2 text-center'>
                                        <AttributionIcon fontSize='large' color='warning' />
                                    </div>
                                    <Divider />
                                    <div className="p-3">
                                        <div className="float-start">
                                            <strong>  Editor Request</strong>
                                        </div>
                                        <div className="float-end">
                                            <strong>  {count.tot_editors_req}</strong>
                                        </div>
                                    </div>

                                </div>
                            </Card>
                        </div>
                    </div>
                </div>
            </Card>
        }
        </>
    )
}

export default AdminDash;