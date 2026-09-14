import React from 'react'
import RightSide from '../components/common/rightSide';
import RefundPolicy from '../components/other/refundpolicy';


const RefundPolicyPage = () => {
    return (
        <>
            <div className='container mt-2'>
                <div className='row'>
                    <div className='col-md-3 d-none d-lg-block'>
                        <RightSide />
                    </div>
                    <div className='col-md-8'>
                        <RefundPolicy />
                    </div>

                    <div className='col-md-3 d-block d-sm-none'>
                        <RightSide />
                    </div>

                </div>
            </div>
        </>
    )
}

export default RefundPolicyPage;