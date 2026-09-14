import React from 'react'
import SendIcon from '@mui/icons-material/Send';
import { useNavigate } from "react-router-dom";

const ManuBtn = () => {
    const navigate = useNavigate();
    return (
        <>
            <div className='container'>

                <div className='text-end '>

                    <button className='manuBtn px-3' onClick={() => navigate('/manuscript')} style={{ borderRadius: '20px' }}>Submit Manuscript <SendIcon /> </button>
                </div>
            </div>
        </>
    )
}

export default ManuBtn;