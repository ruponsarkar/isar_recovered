import React from 'react'

import Box from '@mui/material/Box';
import Skeleton from '@mui/material/Skeleton';

const LoadingSkelton=()=>{
    return (
        <div className='container'>
            <Box sx={{ width: '100%' }}>
                <Skeleton />
                <Skeleton animation="wave" />
                <Skeleton animation={false} />
            </Box>
            <div className='row'>
                <div className='col-6'>

                    <Skeleton variant="rectangular" width={'100%'} height={218} />
                </div>
                <div className='col-6'>
                    <Skeleton variant="rectangular" width={'100%'} height={218} />

                </div>

            </div>


            <Box sx={{ pt: 0.5 }}>
                <Skeleton />
                <Skeleton width="100%" />
            </Box>

        </div>
    )

}


export default LoadingSkelton;