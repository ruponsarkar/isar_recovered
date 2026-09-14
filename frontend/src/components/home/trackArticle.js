import React from 'react'

import Card from '@mui/material/Card';
import TextField from '@mui/material/TextField';

const TractArticle = () => {
    return (
        <>
            <div className='pt-2'>
                <Card>
                    <div className='bg-primary p-2 text-white botton-line'>
                        Track Your Article
                    </div>

                    <div className="input-group p-3 text-center">
                        <div className="form-outline w-100">
                            <input type="search" id="form1" className="form-control" placeholder='Enter Manuscript ID'/>
                            <button className='btn btn-secondary btn-sm m-2'>Search</button>
                        </div>
                    </div>

                </Card>
            </div>
        </>
    )
}

export default TractArticle;