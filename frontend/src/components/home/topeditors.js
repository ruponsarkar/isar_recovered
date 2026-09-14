import React, { useEffect } from 'react'
import Card from '@mui/material/Card';

import { Swiper, SwiperSlide } from 'swiper/react';
import { Navigation, Pagination, Scrollbar, Autoplay } from 'swiper';
import 'swiper/css/autoplay';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import 'swiper/css/scrollbar';
import CardActions from '@mui/material/CardActions';

const TopEditors = (props) => {

    useEffect(() => {
        console.log(props.topEditors);
    })
    return (
        <>





            {props.topEditors &&
                <div className='pt-2'>
                    <Card>
                        <div className='bg-primary p-2 text-white botton-line'>
                            TOP EDITORS
                        </div>

                        <div className='m-4'>
                            <Swiper
                                slidesPerView={2}
                                loop={true}
                                modules={[Autoplay, Scrollbar, Pagination]}
                                //autoplay={false}
                                spaceBetween={20}
                                autoplay={{
                                    delay: 3000,
                                    disableOnInteraction: false,
                                }}
                                // navigation
                                speed={800}
                                // pagination={{ clickable: true }}
                            >

                                {props.topEditors.map((editor) => (
                                    <SwiperSlide>
                                        <div className="hero">
                                            <div className="card bg-dark text-white border-0">
                                                <img src={`${process.env.REACT_APP_BACKEND}assets/editors/${editor.image}`} className="card-img" alt="Banner"  style={{height: '155px'}}/>

                                            </div>

                                        </div>
                                    </SwiperSlide>

                                ))}

                            </Swiper>
                        </div>
                    </Card>
                </div>
            }

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
                                            <div className='col-6 text-center p-3'>
                                                <a href={indexing.link} target='_blank'>
                                                    <img src={`${process.env.REACT_APP_BACKEND}assets/indexing/${indexing.img}`}className='col-12' />
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
    )
}


export default TopEditors;