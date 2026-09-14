import React from 'react'
import OpenAccess from '../components/other/openAccess'
import RightSide from '../components/common/rightSide'


const OpenAccessPage = () => {
    return (
        <>
            <div className="container mt-2">
                <div className='row'>
                    <div className='col-md-3 d-none d-lg-block'>
                        <RightSide />
                    </div>
                    <div className='col-md-8'>
                        <OpenAccess />
                    </div>
                    <div className='col-md-3 d-block d-sm-none'>
                        <RightSide />
                    </div>


                </div>
            </div>

        </>
    )
}

export default OpenAccessPage