import React from "react";
import Container from '@mui/material/Container';
import Box from '@mui/material/Box';
import AddEvents from "../event/addEvents";

const EventForm = () => {
    return (
        <>
            <Container  maxWidth="100%" style={{'background':'#fbfbfb'}}>
                <Box style={{'padding':'30px'}}>
                    <AddEvents />
                </Box>
            </Container>
        </>
    )
}

export default EventForm;