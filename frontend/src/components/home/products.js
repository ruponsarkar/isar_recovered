import React, { useEffect, useState } from 'react';

import axios from "axios";
import InfiniteScroll from "react-infinite-scroll-component";

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
import { useNavigate } from "react-router-dom";
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







    return (
        <>
            <div className='container mt-4'>
                <Box sx={{ bgcolor: '#f9fafa' }} >
                    <div className="row">
                        <div className="col-12">
                            <h2 className="m-2">Latest Products</h2>
                            <hr />
                        </div>
                    </div>
                    <div className='row'>

                        <InfiniteScroll
                            dataLength={products.length}
                            next={fetchMoreData}
                            hasMore={products.length < totalCount}
                            loader={<h4>Loading...</h4>}
                            endMessage={
                                <p style={{ textAlign: 'center' }}>
                                    <b>Yay! You have seen it all</b>
                                </p>
                            }
                        >
                            <div className='row m-1'>
                                {products && products.length > 0 && products.map((product) => {
                                    return (

                                        <>
                                            {/* <Grid key={product.id}></Grid> */}
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
                                                        {/* <Link to={`/products/${product.id}`} className="btn btn-outline-dark">Buy Now</Link> */}
                                                        {/* <Button size="small" variant='outlined' endIcon={<SendIcon/>}>View</Button> */}
                                                    </CardActions>
                                                </Card>
                                            </Grid>


                                            {/* <div className="col-md-3 mb-4">
                                            <div className="card h-100 text-center p-4" key={product.id}>
                                                <img src={product.images[0]} className="card-img-top" alt={product.title} height='250px' />
                                                <div className="card-body">
                                                    <h5 className="card-title mb-0">{product.title.substring(0, 12)}...</h5>
                                                    <p className="card-text lead fw-bold"> ${product.price}</p>
                                                    <p className="card-text lead fw-bold"> {product.category}</p>
                                                    <NavLink to={`/products/${product.id}`} className="btn btn-outline-dark">Buy Now</NavLink>
                                                </div>
                                            </div>
                                        </div> */}
                                        </>






                                    )
                                })}
                            </div>
                        </InfiniteScroll>

                    </div>
                </Box>
            </div>
        </>
    )

}

export default Products;