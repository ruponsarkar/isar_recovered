import React from 'react'
import RightSide from '../components/common/rightSide';

import Card from '@mui/material/Card';
import { Divider } from '@mui/material';

const PublicationFees = () => {
    return (
        <>
            <div className='container mt-2'>
                <div className='row'>
                    <div className='col-md-3 d-none d-lg-block'>
                        <RightSide />
                    </div>
                    <div className='col-md-8'>


                        <Card>
                            <div className="row">
                                <div className="col-12 p-4">
                                    <h4>Fast Track Publication Fee</h4>
                                </div>
                            </div>
                        </Card>
                        <Divider />

                        <Card>
                            <div className="row">
                                <div className="col-12 p-4">

                                    <h5>Fast Track Publication Fee</h5>

                                    <p>
                                    For any journal, we offer a fast-track publication option to expedite the publishing process. With this option, your article can be published within just 2 days of acceptance.
                                    </p>

                                    <li>
                                        <strong> India: </strong> Rs. 6000 INR
                                    </li>
                                    <li>
                                        <strong> Foreign: </strong> USD 150$
                                    </li>

                                    <p>
                                    We understand that there may be situations where rapid publication is essential, and we provide this service to accommodate the needs of our authors. Please note that this fee is in addition to any standard publication fees that may apply.
                                    </p>


                                    <h5>Normal Process</h5>

                                    <table class="table">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th scope="col" className='text-center'>Journal Name</th>
                                                <th scope="col">Indian(INR)</th>
                                                <th scope="col">Foreign(USD)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>ISAR Journal of Arts, Humanities and Social Sciences</td>
                                                <td className='text-center'>4000</td>
                                                <td className='text-center'>100$</td>
                                            </tr>
                                            <tr>
                                                <td>ISAR Journal of Multidisciplinary Research and Studies</td>
                                                <td className='text-center'>4000</td>
                                                <td className='text-center'>100$</td>
                                            </tr>
                                            <tr>
                                                <td>ISAR Journal of Medical and Pharmaceutical Sciences</td>
                                                <td className='text-center'>4000</td>
                                                <td className='text-center'>100$</td>
                                            </tr>
                                            <tr>
                                                <td>ISAR Journal of Economics and Business Management</td>
                                                <td className='text-center'>4000</td>
                                                <td className='text-center'>100$</td>
                                            </tr>
                                            <tr>
                                                <td>ISAR Journal of Science and Technology</td>
                                                <td className='text-center'>3000</td>
                                                <td className='text-center'>75$</td>
                                            </tr>
                                            <tr>
                                                <td>ISAR Journal of Agriculture and Biology</td>
                                                <td className='text-center'>3000</td>
                                                <td className='text-center'>75$</td>
                                            </tr>
                                        </tbody>
                                    </table>




                                </div>

                            </div>
                        </Card>








                    </div>

                    <div className='col-md-3 d-block d-sm-none'>
                        <RightSide />
                    </div>

                </div>
            </div>
        </>
    )
}

export default PublicationFees;