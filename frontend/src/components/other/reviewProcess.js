import React from 'react'
import Card from '@mui/material/Card';
import { Divider } from '@mui/material';
import SendIcon from '@mui/icons-material/Send';
import { useNavigate } from "react-router-dom";
import Paper from '@mui/material';
import PeerReview from '../../PublicPages/peerReview';

const ReviewProcess = () => {
    const navigate = useNavigate();
    return (
        <>
        <PeerReview />


            {/* <Card>
                <div className='p-4'>
                    <h3>Review Process</h3>
                </div>
            </Card>
            <Divider />
            <Card>
                <div className='m-2 text-center'>
                    <img src='./assets/reviewProcess.png' style={{ width: '100%' }} />
                </div>
                <Divider color="success"/>
                <div className='text-center m-3'>
                    <button className='btn btn-warning btn-sm px-3' onClick={() => navigate('/manuscript')} style={{ borderRadius: '20px' }}>Submit Manuscript <SendIcon /> </button>
                </div>
            </Card> */}
        </>
    )
}

export default ReviewProcess;