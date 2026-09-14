import React from 'react';
import { MDBFooter, MDBContainer, MDBRow, MDBCol, MDBIcon } from 'mdb-react-ui-kit';
import FacebookRoundedIcon from '@mui/icons-material/FacebookRounded';
import TwitterIcon from '@mui/icons-material/Twitter';
import InstagramIcon from '@mui/icons-material/Instagram';
import LinkedInIcon from '@mui/icons-material/LinkedIn';
import { Link } from 'react-router-dom';
export default function Footer() {
  return (
    // <MDBFooter bgColor='dark' className='text-center text-lg-start text-muted'>
    <MDBFooter bgColor='dark' className='text-center text-white '>
      <section className='d-flex justify-content-center justify-content-lg-between p-4 border-bottom'>
        <div className='me-5 d-none d-lg-block'>
          <span>Get connected with us on social networks:</span>
        </div>

        <div>
          <a href='https://www.facebook.com/profile.php?id=61552592232822&mibextid=ZbWKwL' target='_blank' className='me-4 text-reset'>
            <FacebookRoundedIcon />
          </a>
          <a href='https://x.com/ISAR_Publisher?t=W-2w_kZ1mjWcaPF8IDlocA&s=09' className='me-4 text-reset'>
            <TwitterIcon />
          </a>
          <a href='https://www.instagram.com/isarpublisher?igsh=YnNhOTQwYjM5MnNp' className='me-4 text-reset'>
            <InstagramIcon />
          </a>
          <a href='https://in.linkedin.com/in/isar-publisher-345816355' className='me-4 text-reset'>
            <LinkedInIcon />
          </a>
        </div>
      </section>

      <section className=''>
        <MDBContainer className='text-center text-md-start mt-5'>
          <MDBRow className='mt-3'>

            <MDBCol md="2" lg="2" xl="2" className='mx-auto mb-4'>
              <h6 className='text-uppercase fw-bold mb-4'>Useful links</h6>
              <p>
                <a href='/AllJournals' className='text-reset'>
                  Journal
                </a>
              </p>
              <p>
                <a href='/refundpolicy' className='text-reset'>
                  CANCELLATION/REFUND POLICY
                </a>
              </p>
              <p>
                <a href='/CopyrightPolicyPage' className='text-reset'>
                Copyright Policy
                </a>
              </p>
              <p>
                <a href='/author-instruction' className='text-reset'>
                  Authors Guidlines
                </a>
              </p>
              <p>
                <a href='#!' className='text-reset'>
                  Contact
                </a>
              </p>
            </MDBCol>

            <MDBCol md="5" lg="4" xl="3" className='mx-auto mb-4'>
              <h6 className='text-uppercase fw-bold mb-4'>
                <MDBIcon icon="gem" className="me-3" />
                Open Access License
              </h6>
              {/* <img src="/assets/openaccess.png" />
              <p>
                This work is licensed under a Creative Commons Attribution-NonCommercial 4.0 International License.
              </p> */}


              <a rel="license" href="http://creativecommons.org/licenses/by-nc/4.0/">
                <img alt="Creative Commons License" style={{ borderWidth: '0' }} src="https://i.creativecommons.org/l/by-nc/4.0/88x31.png" />
              </a>
              <br />
              This work is licensed under a <a rel="license" href="http://creativecommons.org/licenses/by-nc/4.0/">Creative Commons Attribution-NonCommercial 4.0 International License</a>


            </MDBCol>
            <MDBCol md="5" lg="3" xl="3" className='mx-auto mb-md-0 mb-4'>
              <h6 className='text-uppercase fw-bold mb-4'>Contact</h6>
              <p>
                <MDBIcon icon="home" className="me-2" />
                Podumoni, Murajhar, Hojai, Assam, 782439 (India)
                {/* Ghaniarpar, Ambari, Hojai, Assam, 782445 (India) */}
              </p>
              <p>
                <MDBIcon icon="envelope" className="me-3" />
                contact@isarpublisher.com
              </p>
              {/* <p>
                <MDBIcon icon="phone" className="me-3" /> +91 9085848287
              </p> */}
              <p>
                <MDBIcon icon="phone" className="me-3" /> +91 8638994354
              </p>
              {/* <p>
                <MDBIcon icon="phone" className="me-3" /> +91 8474849463
              </p> */}
            </MDBCol>
          </MDBRow>
        </MDBContainer>
      </section>

      <div className='text-center p-3' style={{ backgroundColor: 'rgba(0, 0, 0, 0.05)' }}>

        <strong>  © Copyright {new Date().getFullYear()} ISAR Publisher. All Rights Reserved </strong>
      </div>
      <div className='text-center p-1' style={{ backgroundColor: 'rgba(0, 0, 0, 0.1)' }}>
        {/* © 2023 Copyright: */}
        Developed by : &nbsp;
        <a className='text-reset fw-bold' href='https://pageuptechnologies.com'>
          pageuptechnologies.com
        </a>
      </div>
    </MDBFooter>
  );
}