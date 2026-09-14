import React from 'react'
import RightSide from '../components/common/rightSide';
import AboutUs from '../components/other/aboutUs';


const AboutUsPage = () => {
    return (
        <>
            <div className='container mt-2'>
                <div className='row'>
                    {/* <div className='col-md-4'>
                        <RightSide />
                    </div> */}


                    <div className='col-md-3 d-none d-lg-block'>
                        <RightSide />
                    </div>
                    <div className='col-md-8'>
                        <AboutUs />
                    </div>

                    <div className='col-md-3 d-block d-sm-none'>
                        <RightSide />
                    </div>

                </div>
            </div>

        </>
    )
}

export default AboutUsPage;