import React from 'react'

import Box from '@mui/material/Box';
import BottomNavigation from '@mui/material/BottomNavigation';
import BottomNavigationAction from '@mui/material/BottomNavigationAction';
import RestoreIcon from '@mui/icons-material/Restore';
import FavoriteIcon from '@mui/icons-material/Favorite';
import LocationOnIcon from '@mui/icons-material/LocationOn';
import Paper from '@mui/material/Paper';
import Button from '@mui/material/Button';

import Badge from '@mui/material/Badge';
import { styled } from '@mui/material/styles';
import IconButton from '@mui/material/IconButton';
import ShoppingCartIcon from '@mui/icons-material/ShoppingCart';

import Hidden from '@mui/material/Hidden';
import { useNavigate } from "react-router-dom";
import { useSelector } from 'react-redux';

const StyledBadge = styled(Badge)(({ theme }) => ({
    '& .MuiBadge-badge': {
      right: -3,
      top: 13,
      border: `2px solid ${theme.palette.background.paper}`,
      padding: '0 4px',
    },
  }));

const BottomBar = () => {

    const navigate = useNavigate();
    const state = useSelector((state) => state.handleCart);


    const [value, setValue] = React.useState(0);

    const handleChange = (newValue) => {
        console.log(newValue);
        if (newValue == 0) {
            navigate('/')
        }
        if (newValue == 1) {
            navigate('/cart')
        }
    }

    return (<>
        <Hidden
        //  smUp={true}
        >
            <Paper sx={{ position: 'fixed', bottom: 0, left: 0, right: 0 }} elevation={3}>
                <Box>
                    <BottomNavigation
                        showLabels
                        value={value}
                        onChange={(event, newValue) => {
                            setValue(newValue);
                            // console.log(newValue);
                            handleChange(newValue)
                        }}
                    >
                        <BottomNavigationAction label="Home" icon={<RestoreIcon />} />
                        <BottomNavigationAction label="Cart" icon={<FavoriteIcon />} />
                        <BottomNavigationAction label="Nearby" icon={<LocationOnIcon />} />
                        <BottomNavigationAction label="Cart" icon={<IconButton>
                            <StyledBadge badgeContent={state.length} color="secondary">
                                <ShoppingCartIcon />
                            </StyledBadge>
                        </IconButton>} />
                        

                    </BottomNavigation>
                </Box>
            </Paper>
        </Hidden>
    </>)
}

export default BottomBar;