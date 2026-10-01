import React, { useState } from 'react'

import Button from 'react-bootstrap/Button';
import Container from 'react-bootstrap/Container';
import Form from 'react-bootstrap/Form';
import Nav from 'react-bootstrap/Nav';
import Navbar from 'react-bootstrap/Navbar';
import NavDropdown from 'react-bootstrap/NavDropdown';
import Offcanvas from 'react-bootstrap/Offcanvas';
import FacebookIcon from '@mui/icons-material/Facebook';
import InstagramIcon from '@mui/icons-material/Instagram';
import WhatsAppIcon from '@mui/icons-material/WhatsApp';
import SendIcon from '@mui/icons-material/Send';
import { NavLink, useNavigate } from "react-router-dom";

const Navup = () => {
  const navigate = useNavigate();

  const [expanded, setExpanded] = useState(false);


  return (
    <>
      <div className='bg-secondary p-1 text-white text-end '>
        <div className='mx-3'>
          <a href="https://wa.me/9085848287?text='Hii" style={{ color: 'white' }}>
            <WhatsAppIcon />
          </a>
          &nbsp;
          <a href="https://www.facebook.com/profile.php?id=61552592232822&mibextid=ZbWKwL" target='_blank' style={{ color: 'white' }}>
            <FacebookIcon />
          </a>
          &nbsp;
          <a href="" style={{ color: 'white' }}>
            <InstagramIcon />
          </a>
        </div>
      </div>
      <div className='p-1' style={{ backgroundImage: 'url(/assets/logobg.jpg)', backgroundSize: 'cover', backgroundPosition: 'center' }}>
        <div className='mx-1 d-flex align-items-center'>
          <img src='/assets/mainlogo.png' width="200" className='logo-img' alt='ISAR' />
          <h3 className='px-4 text-white' style={{ textShadow: 'rgb(21 78 179) 2px 2px' }}>
            <strong> International Scientific and Academic Research Publisher </strong>
          </h3>
        </div>
      </div>
      <Navbar expanded={expanded} sticky="top" bg="warning" expand="lg" className=" botton-line">
        <Container fluid className=''>
          <Navbar.Brand href="#" className='text-white'>
            {/* <img src='./logo.png' width="300" alt='ISAR'/> */}
            ISAR Publisher
          </Navbar.Brand>
          <Navbar.Toggle onClick={() => setExpanded(expanded ? false : "expanded")} aria-controls={`offcanvasNavbar-expand-lg`} />
          <Navbar.Offcanvas
            id={`offcanvasNavbar-expand-lg`}
            aria-labelledby={`offcanvasNavbarLabel-expand-lg`}
            placement="end"
          >
            <Offcanvas.Header closeButton onClick={() => setExpanded(false)}>
              <Offcanvas.Title id={`offcanvasNavbarLabel-expand-lg`}>
                ISAR Publisher
              </Offcanvas.Title>
            </Offcanvas.Header>
            <Offcanvas.Body>
              <Nav className="justify-content-end flex-grow-1 pe-3">
                <NavLink onClick={() => setExpanded(false)} className="nav-link scrollt" to="/">Home</NavLink>
                {/* <Nav.Link href="AllJournals">Journals</Nav.Link> */}
                <NavLink onClick={() => setExpanded(false)} className="nav-link scrollt" to="AllJournals">Journals</NavLink>

                <NavDropdown
                  title="For Authors"
                  id={`offcanvasNavbarDropdown-expand-lg`}
                >

                  <NavLink onClick={() => setExpanded(false)} className="nav-link scrollt font-small" to="/author-instruction">
                    Authors Guidlines
                  </NavLink>
                  {/* <NavDropdown.Divider /> */}
                  <NavLink onClick={() => setExpanded(false)} className="nav-link scrollt font-small" to="/joinAuthor">
                    Join Author
                  </NavLink>
                  <NavLink onClick={() => setExpanded(false)} className="nav-link scrollt font-small" to="open-access-policy">
                    Open Access Policy
                  </NavLink>
                  {/* <NavLink onClick={() => setExpanded(false)} className="nav-link scrollt font-small" to="/ProcessingFees">
                   Processing Fees
                  </NavLink> */}
                  <NavLink onClick={() => setExpanded(false)} className="nav-link scrollt font-small" to="/reviewProcess">
                    Review Process
                  </NavLink>
                  <NavLink onClick={() => setExpanded(false)} className="nav-link scrollt font-small" to="/representatives">
                  Representatives
                  </NavLink>
                </NavDropdown>
                <NavDropdown
                  title="For Reviewers"
                  id={`offcanvasNavbarDropdown-expand-lg`}
                >
                  <NavLink onClick={() => setExpanded(false)} className="nav-link scrollt font-small" to="/ReviewGuidlines">
                    Reviewer Guidlines
                  </NavLink>
                  <NavDropdown.Divider />
                  <NavLink onClick={() => setExpanded(false)} className="nav-link scrollt font-small" to="/joinReviewer">
                    Join Reviewer
                  </NavLink>
                </NavDropdown>
                <NavDropdown
                  title="For Editors"
                  id={`offcanvasNavbarDropdown-expand-lg`}
                >
                  <NavLink onClick={() => setExpanded(false)} className="nav-link scrollt font-small" to="/EditorGuidlines">
                    Editor Guidlines
                  </NavLink>
                  <NavDropdown.Divider />
                  <NavLink onClick={() => setExpanded(false)} className="nav-link scrollt font-small" to="/joinEditor">
                    Join Reviewer
                  </NavLink>
                </NavDropdown>


                <NavLink onClick={() => setExpanded(false)} className="nav-link scrollt" to="aboutUs">About Us</NavLink>
                <NavLink onClick={() => setExpanded(false)} className="nav-link scrollt" to="EthicsPolicies">Ethics & Policies</NavLink>
                <NavLink onClick={() => setExpanded(false)} className="nav-link scrollt" to="Payment">Payment</NavLink>
                <NavLink onClick={() => setExpanded(false)} className="nav-link scrollt" to="action">Contact Us</NavLink>


              </Nav>


              {/* <Form className="d-flex">
                  <Form.Control
                    type="search"
                    placeholder="Search"
                    className="me-2"
                    aria-label="Search"
                  />
                  <Button variant="outline-success">Search</Button>
                </Form> */}
              {/* <button className='btn btn-warning btn-sm px-3' onClick={() => navigate('/manuscript')} style={{ borderRadius: '20px' }}>Submit Manuscript <SendIcon /> </button> */}
            </Offcanvas.Body>
          </Navbar.Offcanvas>
        </Container>
      </Navbar>
    </>
  );
}

export default Navup;
