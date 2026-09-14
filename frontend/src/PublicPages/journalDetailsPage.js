import React from 'react'
import JournalDetails from '../components/journals/journalDetails';
import RightSide from '../components/common/rightSide';
import LeftSide from '../components/common/leftSide';

const JournalDetailsPage = () => {
    return (
        <>

            <div className='container-fluid mt-2'>
                <div className='row'>
                    <div className='col-md-3 d-none d-lg-block'>
                        <RightSide />
                    </div>
                    <div className='col-md-6'>
                        <JournalDetails />
                    </div>
                    <div className='col-md-3'>
                        <LeftSide />
                    </div>
                    <div className='col-md-3 d-block d-sm-none'>
                        <RightSide />
                    </div>

                </div>
            </div>


        </>
    )
}

export default JournalDetailsPage;