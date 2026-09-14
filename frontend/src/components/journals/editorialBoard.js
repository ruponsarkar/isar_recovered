import React from 'react'
import Card from '@mui/material/Card';
import CardActions from '@mui/material/CardActions';
import Button from '@mui/material/Button';

const EditorialBoard = (props) => {
    return (
        <>
            <div className='container'>
                <Card>
                    <div className='col-12'>
                        <Button variant='contained' disabled fullWidth>Chief Editor</Button>

                    </div>
                    <div className='col-12 p-2'>
                        <Card>
                            {props.editors.map((el) => (
                                <div className='row justify-content-center'>

                                    <div className='col-md-12'>
                                        {el.type === 'chief' &&
                                            <Card>
                                                <div className="row">
                                                    <div className="col-md-3">
                                                        <div className='m-2'>
                                                            <img src={`${process.env.REACT_APP_BACKEND}assets/editors/${el.image}`} className="img-circle border p-1" style={{ borderRadius: '5%', height: '120px', width: '120px' }} />
                                                        </div>
                                                    </div>

                                                    {/* <div className="col-md-8 d-flex align-items-center flex-wrap"> */}
                                                    <div className="col-md-9 align-items-center flex-wrap">
                                                        <div className=' my-2 font-small'>
                                                            <strong>{el.name}</strong>
                                                            <div>Editor-in-Chief</div>
                                                        </div>
                                                        <div className='my-2'>
                                                            <div className='' style={{ fontSize: 'small' }}>
                                                                {el.details}
                                                            </div>
                                                        </div>
                                                        <div className='my-2'>
                                                            <div className='' style={{ fontSize: 'small' }}>
                                                                Profile Link-  <a href={el.profile}>{el.profile}</a>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>

                                                {el.biography !== 'undefined' &&
                                                    <>
                                                        {el.biography !== 'null' &&
                                                            <>
                                                                {el.biography &&
                                                                    <div className='m-2'>
                                                                        <div className='left-line ' style={{ fontSize: 'small' }}> <strong> &nbsp; Biography </strong> </div>
                                                                        <div className='m-2' style={{ fontSize: 'small' }}>
                                                                            {el.biography}
                                                                        </div>
                                                                    </div>
                                                                }
                                                            </>
                                                        }
                                                    </>
                                                }

                                                {el.interest !== 'undefined' &&
                                                    <>
                                                        {el.interest !== 'null' &&
                                                            <>
                                                                {el.interest &&
                                                                    <div className='m-2 pt-2'>
                                                                        <div className='left-line' style={{ fontSize: 'small' }}> <strong> &nbsp; Research Interest </strong> </div>
                                                                        <div className='m-2' style={{ fontSize: 'small' }}>
                                                                            {el.interest}
                                                                        </div>
                                                                    </div>
                                                                }
                                                            </>
                                                        }
                                                    </>
                                                }


                                            </Card>
                                        }

                                    </div>
                                </div>
                            )
                            )}
                        </Card>


                    </div>



                </Card>
                <Card>
                    <div className='col-12'>
                        <Button variant='contained' disabled fullWidth>Editorial Board member</Button>

                    </div>
                    <div className='col-12 p-2'>
                        <Card>

                            <div className='row justify-content-center'>
                                {props.editors.map((el) => (


                                    <>
                                        {el.type === 'ass' &&
                                            <div className='col-md-12 mb-2'  >
                                                <Card>
                                                    <div className="row">
                                                        <div className="col-md-3">
                                                            <div className='m-2'>
                                                                <img src={`${process.env.REACT_APP_BACKEND}assets/editors/${el.image}`} className="img-circle border p-1" style={{ borderRadius: '5%', height: '120px', width: '120px' }} />
                                                            </div>
                                                        </div>

                                                        {/* <div className="col-md-6 d-flex align-items-center flex-wrap"> */}
                                                        <div className="col-md-9 align-items-center flex-wrap">
                                                            <div className=' my-2 font-small'>
                                                                <strong>{el.name}</strong>
                                                                <div>Editor</div>
                                                            </div>
                                                            <div className='my-2'>
                                                                <div className='' style={{ fontSize: 'small' }}>
                                                                    {el.details}
                                                                </div>
                                                                <div className='my-2'>
                                                                    <div className='' style={{ fontSize: 'small' }}>
                                                                        Profile Link-  <a href={el.profile}>{el.profile}</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    {el.biography !== 'undefined' &&
                                                        <>
                                                            {el.biography !== 'null' &&
                                                                <>
                                                                    {el.biography &&

                                                                        <div className='m-2'>
                                                                            <div className='left-line ' style={{ fontSize: 'small' }}> <strong> &nbsp; Biography </strong> </div>
                                                                            <div className='m-2' style={{ fontSize: 'small' }}>
                                                                                {el.biography}
                                                                            </div>
                                                                        </div>
                                                                    }
                                                                </>
                                                            }
                                                        </>
                                                    }


                                                    {el.interest !== 'undefined' &&
                                                        <>
                                                            {el.interest !== 'null' &&
                                                                <>
                                                                    {el.interest &&
                                                                        <div className='m-2 pt-2'>
                                                                            <div className='left-line' style={{ fontSize: 'small' }}> <strong> &nbsp; Research Interest </strong> </div>
                                                                            <div className='m-2' style={{ fontSize: 'small' }}>
                                                                                {el.interest}
                                                                            </div>
                                                                        </div>
                                                                    }
                                                                </>
                                                            }
                                                        </>
                                                    }



                                                </Card>
                                            </div>
                                        }
                                    </>


                                ))}




                                {/* <div className='col-md-3 mb-2'>
                                    <Card>
                                        <div className='text-center'>

                                            <img src='/assets/editors/1.jpg' className="img-circle" style={{ borderRadius: '50%', height: '150px', width: '150px' }} />
                                        </div>
                                        <div className='text-center my-2'>
                                            <strong>The Chief Editor Name</strong>
                                        </div>
                                        <div className='m-2'>
                                            <div>
                                                Associate Prof. in Contrastive Studies in Dep. of Linguistics & Literary Studies, Basrah & Arab Gulf Studies, University of Basrah
                                            </div>
                                        </div>

                                        <CardActions>
                                            <Button size="small">Share</Button>
                                            <Button size="small">Learn More</Button>
                                        </CardActions>
                                    </Card>

                                </div>
                                <div className='col-md-3 mb-2'>
                                    <Card>
                                        <div className='text-center'>

                                            <img src='/assets/editors/1.jpg' className="img-circle" style={{ borderRadius: '50%', height: '150px', width: '150px' }} />
                                        </div>
                                        <div className='text-center my-2'>
                                            <strong>The Chief Editor Name</strong>
                                        </div>
                                        <div className='m-2'>
                                            <div>
                                                Associate Prof. in Contrastive Studies in Dep. of Linguistics & Literary Studies, Basrah & Arab Gulf Studies, University of Basrah
                                            </div>
                                        </div>

                                        <CardActions>
                                            <Button size="small">Share</Button>
                                            <Button size="small">Learn More</Button>
                                        </CardActions>
                                    </Card>

                                </div>
                                <div className='col-md-3 mb-2'>
                                    <Card>
                                        <div className='text-center'>

                                            <img src='/assets/editors/1.jpg' className="img-circle" style={{ borderRadius: '50%', height: '150px', width: '150px' }} />
                                        </div>
                                        <div className='text-center my-2'>
                                            <strong>The Chief Editor Name</strong>
                                        </div>
                                        <div className='m-2'>
                                            <div>
                                                Associate Prof. in Contrastive Studies in Dep. of Linguistics & Literary Studies, Basrah & Arab Gulf Studies, University of Basrah
                                            </div>
                                        </div>

                                        <CardActions>
                                            <Button size="small">Share</Button>
                                            <Button size="small">Learn More</Button>
                                        </CardActions>
                                    </Card>

                                </div>
                                <div className='col-md-3 mb-2'>
                                    <Card>
                                        <div className='text-center'>

                                            <img src='/assets/editors/1.jpg' className="img-circle" style={{ borderRadius: '50%', height: '150px', width: '150px' }} />
                                        </div>
                                        <div className='text-center my-2'>
                                            <strong>The Chief Editor Name</strong>
                                        </div>
                                        <div className='m-2'>
                                            <div>
                                                Associate Prof. in Contrastive Studies in Dep. of Linguistics & Literary Studies, Basrah & Arab Gulf Studies, University of Basrah
                                            </div>
                                        </div>

                                        <CardActions>
                                            <Button size="small">Share</Button>
                                            <Button size="small">Learn More</Button>
                                        </CardActions>
                                    </Card>

                                </div>
                                <div className='col-md-3 mb-2'>
                                    <Card>
                                        <div className='text-center'>

                                            <img src='/assets/editors/1.jpg' className="img-circle" style={{ borderRadius: '50%', height: '150px', width: '150px' }} />
                                        </div>
                                        <div className='text-center my-2'>
                                            <strong>The Chief Editor Name</strong>
                                        </div>
                                        <div className='m-2'>
                                            <div>
                                                Associate Prof. in Contrastive Studies in Dep. of Linguistics & Literary Studies, Basrah & Arab Gulf Studies, University of Basrah
                                            </div>
                                        </div>

                                        <CardActions>
                                            <Button size="small">Share</Button>
                                            <Button size="small">Learn More</Button>
                                        </CardActions>
                                    </Card>

                                </div>
                                <div className='col-md-3 mb-2'>
                                    <Card>
                                        <div className='text-center'>

                                            <img src='/assets/editors/1.jpg' className="img-circle" style={{ borderRadius: '50%', height: '150px', width: '150px' }} />
                                        </div>
                                        <div className='text-center my-2'>
                                            <strong>The Chief Editor Name</strong>
                                        </div>
                                        <div className='m-2'>
                                            <div>
                                                Associate Prof. in Contrastive Studies in Dep. of Linguistics & Literary Studies, Basrah & Arab Gulf Studies, University of Basrah
                                            </div>
                                        </div>

                                        <CardActions>
                                            <Button size="small">Share</Button>
                                            <Button size="small">Learn More</Button>
                                        </CardActions>
                                    </Card>

                                </div>
                                <div className='col-md-3 mb-2'>
                                    <Card>
                                        <div className='text-center'>

                                            <img src='/assets/editors/1.jpg' className="img-circle" style={{ borderRadius: '50%', height: '150px', width: '150px' }} />
                                        </div>
                                        <div className='text-center my-2'>
                                            <strong>The Chief Editor Name</strong>
                                        </div>
                                        <div className='m-2'>
                                            <div>
                                                Associate Prof. in Contrastive Studies in Dep. of Linguistics & Literary Studies, Basrah & Arab Gulf Studies, University of Basrah
                                            </div>
                                        </div>

                                        <CardActions>
                                            <Button size="small">Share</Button>
                                            <Button size="small">Learn More</Button>
                                        </CardActions>
                                    </Card>

                                </div> */}


                            </div>
                        </Card>


                    </div>



                </Card>
            </div>
        </>
    )
}

export default EditorialBoard