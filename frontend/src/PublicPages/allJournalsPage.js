import React from 'react'
import AllJournals from '../components/journals/alljournals'
import RightSide from '../components/common/rightSide'
import { Paper } from '@mui/material'

const AllJournalsPage = () => {
    return (
        <>
            <div className="container mt-2">
                <div className="row">
                    {/* <div className="col-md-4">
                        <RightSide />
                    </div> */}
                    <div className="col-md-12">
                        <Paper>
                            <AllJournals />
                        </Paper>
                    </div>

                </div>

            </div>
        </>
    )
}

export default AllJournalsPage