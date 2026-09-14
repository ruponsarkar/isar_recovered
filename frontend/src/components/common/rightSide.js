import React, { useContext, useEffect, useState } from 'react'
import BasicCard from '../home/card';
import TopEditors from '../home/topeditors';
import TractArticle from '../home/trackArticle';
import LoadingSkelton from './skelton';
import LinearProgress from '@mui/material/LinearProgress';

import { JournalContext } from '../../Helper/Contex';

const RightSide = () => {

    const {journals, indexings, topEditors} = useContext(JournalContext)



    const [load, setLoad] = useState(false);

   

    return (
        <>
            {load ?
                <>
                    <LinearProgress />

                    <LoadingSkelton />

                </> :
                <>
                    <BasicCard journals={journals} indexings={indexings}/>
                    <TopEditors topEditors={topEditors} indexings={indexings}/>
                    <TractArticle />
                </>
            }

        </>
    )
}

export default RightSide;