import * as React from 'react';
import Card from '@mui/material/Card';
import CardActions from '@mui/material/CardActions';
import Button from '@mui/material/Button';
import Divider from '@mui/material/Divider';
import { useNavigate } from 'react-router-dom';


export default function BasicCard(props) {
    const navigate = useNavigate();
    return (
        <>

            {/* <Card >
                <div className='bg-primary p-2 text-white botton-line'>
                    APPLICATIONS
                </div>
                <CardActions>
                    <Button size="small" onClick={() => navigate('/joinReviewer')}>join as Reviewer</Button>
                </CardActions>
                <CardActions>
                    <Button size="small" onClick={() => navigate('/joinEditor')}>Join as Editor</Button>
                </CardActions>
            </Card> */}
            <div className=''>
                <Card>
                    <div className='bg-primary p-2 text-white botton-line'>
                        JOURNALS
                    </div>
                    {props.journals && props.journals.map((el) => (
                        <>
                            <CardActions>
                                <Button size="small" onClick={() => navigate(`/journal/${el.slug}`)}>{el.j_name}</Button>
                            </CardActions>
                            <Divider />
                        </>
                    ))}
                </Card>
            </div>
            {/* {props.indexings &&
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
                                            <div className='col-6 text-center p-1'>
                                                <a href={indexing.link} target='_blank'>
                                                    <img src={`${process.env.REACT_APP_BACKEND}assets/indexing/${indexing.img}`} width='150' />
                                                </a>
                                            </div>
                                        </>
                                    ))}

                                </div>

                            </div>
                        </CardActions>
                    </Card>
                </div>
            } */}

        </>
    );
}