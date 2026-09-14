import React from 'react'

import AuthorInstruction from '../components/other/authorInstruction';
import RightSide from '../components/common/rightSide';

const AuthorInstructionPage = () => {
    return (
        <>
            <div className="container mt-2">
                <div className='row'>
                    <div className='col-md-3 d-none d-lg-block'>
                        <RightSide />
                    </div>
                    <div className='col-md-8'>
                        <AuthorInstruction />
                    </div>
                    <div className='col-md-3 d-block d-sm-none'>
                        <RightSide />
                    </div>

                </div>
            </div>

        </>
    )
}

export default AuthorInstructionPage;