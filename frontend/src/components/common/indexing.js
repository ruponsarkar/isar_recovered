import React from 'react'
import Card from '@mui/material/Card';
import CardActions from '@mui/material/CardActions';

const IndexingJournal = (props) => {
    console.log("innn", props);
    return (
        <>
            {props.indexings &&
                <div className='pt-2'>
                    <Card>
                        <div className='bg-primary p-2 text-white botton-line'>
                            INDEXING
                        </div>
                        <CardActions>
                            <div className='col-12'>
                                <div className='row'>
                                    {props.indexings.map((indexing) => (
                                        <>
                                            <div className='col-6 text-center p-3'>
                                                <a href={indexing.link} target='_blank'>
                                                    <img src={`${process.env.REACT_APP_BACKEND}assets/indexing/${indexing.img}`} className='col-12' />
                                                </a>
                                            </div>
                                        </>
                                    ))}

                                </div>

                            </div>
                        </CardActions>
                    </Card>
                </div>
            }
        </>
    )
}

export default IndexingJournal