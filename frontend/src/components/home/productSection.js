import React, { useEffect, useState } from 'react';

import axios from "axios";
import InfiniteScroll from "react-infinite-scroll-component";
import { Swiper, SwiperSlide } from 'swiper/react';
import { Navigation, Pagination, Scrollbar, Autoplay } from 'swiper';
import 'swiper/css/autoplay';
import 'swiper/css';

import Box from '@mui/material/Box';
import Card from '@mui/material/Card';
import CardActions from '@mui/material/CardActions';
import CardContent from '@mui/material/CardContent';
import CardMedia from '@mui/material/CardMedia';
import Button from '@mui/material/Button';
import Typography from '@mui/material/Typography';
import Grid from '@mui/material/Grid';
import SendIcon from '@mui/icons-material/Send';
import AddShoppingCartIcon from '@mui/icons-material/AddShoppingCart';
import { Link, useNavigate } from "react-router-dom";
import { useDispatch } from "react-redux";
import { addCart } from "../../redux/action";



const Products = () => {

    const navigate = useNavigate();
    const PAGE_LIMIT = 10;
    const [pageNo, setPageNo] = useState(0);

    const totalCount = 99;
    const apiPath = "https://dummyjson.com/products";

    const [products, setProducts] = useState([]);


    const getProductList = () => {

        const queryParam = "?skip=" + pageNo + "&limit=" + PAGE_LIMIT
        const finalUrl = apiPath + queryParam;

        axios.get(finalUrl)
            .then((res) => {
                const apiRes = res?.data.products
                const mergeData = [...products, ...apiRes];
                console.log(apiRes);
                setProducts(mergeData);
            })
            .catch((err) => {
                console.log("error==>", err);
            })

        setPageNo(pageNo + PAGE_LIMIT)

    }

    useEffect(() => {
        getProductList()
    }, []);


    const fetchMoreData = () => {
        getProductList()
    }



    const dispatch = useDispatch();

    const addProduct = (product) => {
        dispatch(addCart(product));
    }


    const LatestProducts = () => {
        return (
            <>
                <Box sx={{ bgcolor: '#f9fafa' }} >
                    <div className="">
                        <div className="col-12">
                            <h3 className="m-2 text-center">Latest Products</h3>
                            <hr className='p-0 m-0'/>
                        </div>
                    </div>
                    <div className='py-3'>
                        <Swiper slidesPerView={4}
                            loop={true}
                            modules={[Autoplay, Scrollbar, Pagination]}
                            //autoplay={false}
                            autoplay={{
                                delay: 3000,
                                disableOnInteraction: false,
                            }}
                            speed={500}
                            pagination={{ clickable: true }}
                        >


                            <div className='row'>
                                {products && products.length > 0 && products.slice(0, 8).map((product) => {
                                    return (

                                        <>
                                            <SwiperSlide>
                                                <Grid container

                                                    item
                                                    direction="row"
                                                    justifyContent="center"
                                                    alignItems="center"
                                                    spacing={0.5}
                                                    p={1}
                                                >
                                                    <Card elevation={3} className='bg-light'>

                                                        <CardMedia
                                                            component="img"
                                                            alt="green iguana"
                                                            height="140"
                                                            onClick={() => navigate(`/products/${product.id}`)}
                                                            image={product.images[0]}
                                                        />

                                                    </Card>
                                                </Grid>
                                            </SwiperSlide>
                                        </>
                                    )
                                })}
                            </div>

                        </Swiper>
                    </div>
                </Box>
            </>
        )
    }





    return (
        <>

            <div className=''>
                {LatestProducts()}
            </div>
            <div className='container mt-4'>
                <Box sx={{ bgcolor: '#f9fafa' }} >
                    <div className="row">
                        <div className="col-12">
                            <h2 className="m-2">Electronics</h2>
                            <hr />
                        </div>
                    </div>

                    <div className='row p-3'>
                        {products && products.length > 0 && products.slice(0, 8).map((product) => {
                            return (

                                <>
                                    <Grid container

                                        item
                                        direction="row"
                                        justifyContent="center"
                                        alignItems="center"
                                        spacing={0.5} xs={6} md={3}
                                        p={1}
                                    >
                                        <Card elevation={3} className='bg-light'>

                                            <CardMedia
                                                component="img"
                                                alt="green iguana"
                                                height="140"
                                                onClick={() => navigate(`/products/${product.id}`)}
                                                image={product.images[0]}
                                            />
                                            <CardContent className='p-2' onClick={() => navigate(`/products/${product.id}`)}>
                                                <Typography gutterBottom component="div" >
                                                    {product.title}
                                                </Typography>
                                                <Typography variant="body2" color="text.secondary">
                                                    Lizards are a widespread group...
                                                </Typography>
                                                <Typography variant="body2" color="text.secondary" >
                                                    <span style={{ textDecoration: 'line-through' }}> Rs {product.price + 150}  </span>  <span style={{ fontWeight: 'bold', color: 'black' }}> Rs.{product.price}</span>
                                                </Typography>
                                            </CardContent>
                                            <CardActions>
                                                <Button size="small" variant='contained' color='info' onClick={() => addProduct(product)} fullWidth><AddShoppingCartIcon /> Add to Card </Button>

                                            </CardActions>
                                        </Card>
                                    </Grid>
                                </>
                            )
                        })}
                    </div>
                    <div className='text-end p-3'>
                        <Link to="products">View More</Link>
                    </div>
                </Box>

            </div>
            <div className='container mt-4'>
                <Box sx={{ bgcolor: '#ffffff' }} >
                    <div className="row">
                        <div className="col-12">
                            <h2 className="m-2">Gadgets</h2>
                            <hr />
                        </div>
                    </div>

                    <div className='row p-3'>
                        {products && products.length > 0 && products.slice(0, 4).map((product) => {
                            return (

                                <>
                                    <Grid container

                                        item
                                        direction="row"
                                        justifyContent="center"
                                        alignItems="center"
                                        spacing={0.5} xs={6} md={3}
                                        p={1}
                                    >
                                        <Card elevation={3} className='bg-light'>

                                            <CardMedia
                                                component="img"
                                                alt="green iguana"
                                                height="140"
                                                onClick={() => navigate(`/products/${product.id}`)}
                                                image={product.images[0]}
                                            />
                                            <CardContent className='p-2' onClick={() => navigate(`/products/${product.id}`)}>
                                                <Typography gutterBottom component="div" >
                                                    {product.title}
                                                </Typography>
                                                <Typography variant="body2" color="text.secondary">
                                                    Lizards are a widespread group...
                                                </Typography>
                                                <Typography variant="body2" color="text.secondary" >
                                                    <span style={{ textDecoration: 'line-through' }}> Rs {product.price + 150}  </span>  <span style={{ fontWeight: 'bold', color: 'black' }}> Rs.{product.price}</span>
                                                </Typography>
                                            </CardContent>
                                            <CardActions>
                                                <Button size="small" variant='contained' color='info' onClick={() => addProduct(product)} fullWidth><AddShoppingCartIcon /> Add to Card </Button>

                                            </CardActions>
                                        </Card>
                                    </Grid>
                                </>
                            )
                        })}
                    </div>

                    <div className='text-end p-3'>
                        <Link to="products">View More</Link>
                    </div>
                </Box>

            </div>
        </>
    )

}

export default Products;