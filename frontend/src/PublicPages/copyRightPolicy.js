import React from 'react'
import RightSide from '../components/common/rightSide';
import { Paper } from '@mui/material';

const CopyrightPolicyPage = () => {
    return (
        <>
            <div className='container mt-2'>
                <div className='row'>
                    <div className='col-md-3 d-none d-lg-block'>
                        <RightSide />
                    </div>
                    <div className='col-md-8'>

                        <Paper>
                            <div className="p-5">
                                <div className="col-12">
                                    <h3>COPYRIGHT POLICY</h3>
                                </div>
                                <div className='py-2'>
                                    All articles published in ISAR Publisher are protected under copyright laws. The copyrights for each article remain with the authors. Authors grant ISAR Publisher the right to first publication of their work, which includes the exclusive right to distribute and disseminate the article as part of the journal’s academic activities. The publisher assumes no responsibility for any subsequent uses of the work by third parties once the article has been published.

                                </div>

                                <div className='py-2'>
                                    All articles are published under the <a href="https://creativecommons.org/licenses/by-nc/4.0/"> Creative Commons Attribution-NonCommercial 4.0 International License (CC-BY-NC 4.0)</a>, a license that allows others to share, remix, adapt, and build upon the work non-commercially, provided the original work is properly cited. This license enables others to use and distribute the work for non-commercial purposes, while ensuring proper attribution to the author.
                                </div>

                                <div className='py-2'>
                                    Authors retain the rights to reuse, republish, archive, and distribute their articles after publication. This includes sharing the work on personal websites, institutional repositories, or any other academic platform. Furthermore, authors permit others to remix, adapt, and build upon the work, as long as the use remains non-commercial and proper attribution is maintained.
                                </div>

                                <div className="py-2">
                                    By publishing with ISAR Publisher, authors agree to these terms, which help ensure the broad academic dissemination of their work while maintaining their copyright. This policy supports the free and open sharing of research in accordance with the CC-BY-NC 4.0 license.
                                </div>

                                <div className="py-2">
                                    For complete guidance on the CC-BY-NC 4.0 license, please refer to the <a href="https://creativecommons.org/licenses/by-nc/4.0/"> license terms </a>.
                                </div>

                            </div>
                        </Paper>
                    </div>

                    <div className='col-md-3 d-block d-sm-none'>
                        <RightSide />
                    </div>

                </div>
            </div>
        </>
    )
}

export default CopyrightPolicyPage;