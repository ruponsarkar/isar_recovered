import React from "react";

import { Swiper, SwiperSlide } from 'swiper/react';
import { Navigation, Pagination, Scrollbar, Autoplay } from 'swiper';
import 'swiper/css/autoplay';
import 'swiper/css';

import Card from '@mui/material/Card';
import CardActions from '@mui/material/CardActions';
import Button from '@mui/material/Button';
import Divider from '@mui/material/Divider';
import { useNavigate } from "react-router-dom";

const Hero = (props) => {
    const navigate = useNavigate();
    return (
        <>
            <Card>
                <div className='bg-primary p-2 text-white botton-line'>
                    JOURNALS
                </div>
                <div className='m-4'>
                    <Swiper slidesPerView={4}
                        loop={false}
                        modules={[Autoplay, Scrollbar, Pagination]}
                        //autoplay={false}
                        spaceBetween={20}
                        autoplay={{
                            delay: 3000,
                            disableOnInteraction: false,
                        }}
                        speed={800}
                        pagination={{ clickable: true }}
                    >

                        {props.journals && props.journals.map((el) => (


                            <SwiperSlide key={el.j_id}>
                                <div className="hero">
                                    <div className="card bg-dark text-white border-0">
                                        <img src={`${process.env.REACT_APP_BACKEND}assets/journal/${el.photo}`} className="card-img" alt="Banner" onClick={() => navigate('AllJournals')} />
                                    </div>

                                </div>
                            </SwiperSlide>
                        ))}

                    </Swiper>

                </div>

            </Card>


            <div className='pt-2'>
                <Card>
                    <div className='bg-primary p-2 text-white botton-line'>
                        Mission and Vision
                    </div>
                    <CardActions>
                        <div className="m-2">
                            <div className="text-uppercase">
                                <strong>Our Mission</strong>
                            </div>
                            <p>
                                Our mission is to provide academicians, researchers, and scholars with up-to-date information on discoveries, developments, and ongoing activities in the fields of science and technology. We aim to make this information easily accessible, enabling individuals in these domains to stay informed effortlessly.
                            </p>

                            <div className="text-uppercase">
                                <strong>Our Vision</strong>
                            </div>
                            <p>
                                At ISAR Publisher, our vision is to uphold the highest standards of quality in the publication of research and review articles. We are deeply committed to the timely and effective dissemination of peer-reviewed, top-quality work. To achieve this, we have assembled a team of esteemed editorial members representing diverse disciplines and specializations. Their dedication ensures an efficient and constructive review process that aligns with international research publishing standards.
                            </p>
                            <p>

                                Through our mission and vision, we aspire to be a trusted source for the academic and research community, facilitating the exchange of knowledge and contributing to the advancement of science and technology.
                            </p>



                        </div>


                    </CardActions>
                    <Divider />
                </Card>
            </div>
            <div className='pt-2'>
                <Card>
                    <div className='bg-primary p-2 text-white botton-line'>
                        Aim and Objective
                    </div>
                    <CardActions>
                        <div className="m-2">
                            International Scientific and Academic Research Publisher is an international publisher committed to providing a ‘peer reviewed’ platform to outstanding researchers and scientists to exhibit their findings for the furtherance of society to provoke debate and provide an educational forum.
                            <br />
                            <br />
                            It's aim is to serve for the scholars and researchers all over the world by means of fast publishing and to get the widest exposure by providing free access. International Scientific and Academic Research Publishers team works with full dedication to improve the services and to maintain high quality standards.
                        </div>
                    </CardActions>
                    <Divider />
                </Card>
            </div>
        </>
    );
}

export default Hero;