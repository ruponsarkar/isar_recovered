import React from 'react'

import Card from '@mui/material/Card';
import { Divider } from '@mui/material';

const RefundPolicy = () => {
    return (
        <>
            <Card>
                <div className="row">
                    <div className="col-12 p-4">
                        <h3>CANCELLATION/REFUND POLICY</h3>
                    </div>
                </div>
            </Card>
            <Divider />

            <Card>
                <div className="row">
                    <div className="col-12 p-4">
                        <p>

                            The Cancellation/Refund Policy of the journal is designed to address various scenarios and maintain fairness while considering the financial and administrative processes involved in manuscript handling. Here are the key points of the policy:
                        </p>
                        <p>
                            <ul>
                                <li>


                                <strong>	Article Processing Charges (APCs): </strong> Authors agree to pay APCs upon submission. Once an article is accepted for publication, these charges become due.
                                </li>
                                <li>

                                <strong>	Withdrawal within 7 Days: </strong> Authors have the prerogative to withdraw their submitted article within 7 days of initial submission without incurring any charges.
                                </li>
                                <li>

                                <strong>	Refund for Non-Review Stage: </strong> A full refund is provided if the paper does not reach the reviewing stage or if it is not accepted for publication.
                                </li>
                                <li>

                                <strong>	No Refund after Publication: </strong> Once a paper is published, no refunds or cancellations are possible. This is due to the nature of the service provided and the costs incurred in the publication process.
                                </li>
                                <li>

                                <strong>	No Refund for Withdrawals: </strong> Authors do not receive refunds or cancellations of debts if they voluntarily withdraw their paper from consideration or decline to revise and resubmit it.
                                </li>
                                <li>

                                <strong>	No Refund for Copyright Infringement Claims: </strong> Refunds or cancellations are not available if a paper is removed due to copyright infringement claims.
                                </li>
                                <li>

                                <strong>	Refund Process: </strong> Refunds are processed through the online mode within 20-30 working days.
                                </li>
                                <li>

                                <strong>	Wrong or Extra Deposits: </strong> If an author mistakenly deposits the wrong amount or extra money, it will be returned within 03 weeks if the author informs the journal within 02 days of the deposit.
                                </li>
                                <li>

                                <strong>	Cancellation After Two Working Days: </strong> If an author withdraws their paper or declines to revise and resubmit it after two working days, refunds or cancellations are strictly governed by the policy.
                                </li>
                                <li>

                                    <strong>	No Refund after Publication Decision: </strong> After the final submission or publication decision, fees are non-refundable based on the Terms and Conditions accepted during the author declaration form.
                                </li>

                            </ul>
                        </p>
                        <p>

                            This policy balances the author's rights to make decisions about their submissions with the practical considerations involved in manuscript processing and publication. It provides clear guidelines for when refunds are applicable and when they are not, ensuring transparency and fairness in the process.
                        </p>



                    </div>

                </div>
            </Card>
        </>
    )
}

export default RefundPolicy;